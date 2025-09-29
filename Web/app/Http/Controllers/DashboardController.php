<?php

namespace App\Http\Controllers;

use App\Models\ReservationModel;
use App\Models\MenuItemModel;
use App\Models\UserModel;
use App\Models\CounterModel;
use App\Models\TableModel;
use App\Models\StoreSettingModel;
use Carbon\Carbon; // Added for date and time handling
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB; // Added for DB operations
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function __construct()
    {
        // ใช้ middleware 'auth:user' เพื่อบังคับให้ต้องล็อกอินในฐานะ admin/staff ก่อนใช้งาน controller นี้
        // ถ้าไม่ล็อกอินหรือไม้ได้ใช้ guard 'user' จะถูก redirect ไปหน้า login
        $this->middleware(['auth:user', 'role:ADMIN,STAFF']);
    }


    public function index()
    {
        try {
            // ===== Top KPIs =====
            [$countReservation, $countMenu, $countUser, $countView] = $this->getTopKpis();

            // ===== 12-month window =====
            [$months, $label, $start, $end] = $this->getMonthsWindow();

            // Visits (12m)
            $data = $this->getVisitsSeries($months, $start, $end);

            // Reservation status (ทั้งหมด)
            $reservationStatusData = $this->getReservationStatusData();

            // Reservations Trend (12m)
            [$reservationTrendLabel, $reservationTrendData] = $this->getReservationTrendSeries($months, $label, $start, $end);

            // Avg Party Size (12m)
            [$avgPartySizeLabel, $avgPartySizeData] = $this->getAvgPartySizeSeries($months, $label, $start, $end);

            // User Growth (12m)
            [$userGrowthLabel, $userGrowthData] = $this->getUserGrowthSeries($months, $label, $start, $end);

            // Peak Hours Heatmap (last 8 weeks)
            $heatmapSeries = $this->getPeakHoursHeatmap();

            // No-Show Rate (30d)
            $noShowRate = $this->getNoShowRate30d();

            // Table Utilization (7d)
            $utilRate = $this->getTableUtilization7d();

            return view('dashboard.index', compact(
                'countReservation',
                'countMenu',
                'countUser',
                'countView',
                'label',
                'data',
                'reservationStatusData',
                'avgPartySizeLabel',
                'avgPartySizeData',
                'userGrowthLabel',
                'userGrowthData',
                'heatmapSeries',
                'noShowRate',
                'utilRate',
                'reservationTrendLabel',
                'reservationTrendData'
            ));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            // return view('errors.404');
        }
    }
    private function getTopKpis(): array
    {
        $countReservation = ReservationModel::count();
        $countMenu        = MenuItemModel::where('is_active', 1)->count();
        $countUser        = UserModel::count();
        $countView        = CounterModel::count();

        return [$countReservation, $countMenu, $countUser, $countView];
    }
    private function getMonthsWindow(): array
    {
        $end   = Carbon::now()->endOfMonth();
        $start = (clone $end)->subMonths(11)->startOfMonth();

        $months = collect(range(0, 11))
            ->map(fn(int $i) => (clone $start)->addMonths($i));

        $label  = $months->map(fn(Carbon $m) => $m->format('M-Y'))->values();

        return [$months, $label, $start, $end];
    }
    private function getVisitsSeries(Collection $months, Carbon $start, Carbon $end): Collection
    {
        $visitsByYm = DB::table('counter')
            ->selectRaw('DATE_FORMAT(c_date, "%Y-%m") as ym, COUNT(*) as total')
            ->whereBetween('c_date', [$start, $end])
            ->groupBy('ym')
            ->pluck('total', 'ym');

        return $months->map(fn(Carbon $m) => (int) ($visitsByYm[$m->format('Y-m')] ?? 0))->values();
    }
    private function getReservationStatusData(): Collection
    {
        $wanted = ['CONFIRMED', 'SEATED', 'COMPLETED', 'CANCELLED', 'NO_SHOW'];

        $statusCounts = ReservationModel::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return collect($wanted)->map(fn(string $s) => (int) ($statusCounts[$s] ?? 0))->values();
    }
    private function getReservationTrendSeries(Collection $months, Collection $label, Carbon $start, Carbon $end): array
    {
        $resByYm = ReservationModel::selectRaw('DATE_FORMAT(start_at, "%Y-%m") as ym, COUNT(*) as total')
            ->whereBetween('start_at', [$start, $end])
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $data = $months->map(fn(Carbon $m) => (int) ($resByYm[$m->format('Y-m')] ?? 0))->values();

        return [$label, $data];
    }
    private function getAvgPartySizeSeries(Collection $months, Collection $label, Carbon $start, Carbon $end): array
    {
        $partyByYm = ReservationModel::selectRaw('DATE_FORMAT(start_at, "%Y-%m") as ym, AVG(party_size) as avg_size')
            ->whereBetween('start_at', [$start, $end])
            ->groupBy('ym')
            ->pluck('avg_size', 'ym');

        $data = $months->map(
            fn(Carbon $m) => round((float) ($partyByYm[$m->format('Y-m')] ?? 0), 1)
        )->values();

        return [$label, $data];
    }
    private function getUserGrowthSeries(Collection $months, Collection $label, Carbon $start, Carbon $end): array
    {
        $userByYm = UserModel::selectRaw('DATE_FORMAT(created_at, "%Y-%m") as ym, COUNT(*) as total')
            ->whereBetween('created_at', [$start, $end])
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $data = $months->map(fn(Carbon $m) => (int) ($userByYm[$m->format('Y-m')] ?? 0))->values();

        return [$label, $data];
    }
    private function getPeakHoursHeatmap(): Collection
    {
        $heatStart = Carbon::now()->subWeeks(8)->startOfWeek();
        $heatEnd   = Carbon::now()->endOfWeek();

        $heatRaw = ReservationModel::selectRaw('DAYOFWEEK(start_at) as dow, HOUR(start_at) as hh, COUNT(*) as c')
            ->whereBetween('start_at', [$heatStart, $heatEnd])
            ->groupBy('dow', 'hh')
            ->get();

        // map: 1=Sun ... 7=Sat → order Mon..Sun
        $order    = [2, 3, 4, 5, 6, 7, 1];
        $dowLabel = [1 => 'Sun', 2 => 'Mon', 3 => 'Tue', 4 => 'Wed', 5 => 'Thu', 6 => 'Fri', 7 => 'Sat'];

        $matrix = [];
        foreach ($heatRaw as $r) {
            $matrix[$r->dow][$r->hh] = (int) $r->c;
        }

        return collect($order)->map(function (int $dow) use ($matrix, $dowLabel) {
            $row = [];
            for ($h = 0; $h <= 23; $h++) {
                $row[] = ['x' => sprintf('%02d:00', $h), 'y' => (int) ($matrix[$dow][$h] ?? 0)];
            }
            return ['name' => $dowLabel[$dow], 'data' => $row];
        })->values();
    }

    private function getNoShowRate30d(): float
    {
        $nsStart = Carbon::now()->subDays(30)->startOfDay();
        $nsEnd   = Carbon::now()->endOfDay();

        $total30  = ReservationModel::whereBetween('start_at', [$nsStart, $nsEnd])->count();
        $noShow30 = ReservationModel::where('status', 'NO_SHOW')
            ->whereBetween('start_at', [$nsStart, $nsEnd])
            ->count();

        return $total30 > 0 ? round($noShow30 * 100 / $total30, 1) : 0.0;
    }

    private function getTableUtilization7d(): float
    {
        // total seats (เฉพาะ tables ที่ is_active = 1 ถ้ามีคอลัมน์นี้)
        $totalSeats = 0;
        try {
            $query = TableModel::query();
            if (Schema::hasColumn('tables', 'is_active')) {
                $query->where('is_active', 1);
            }
            $totalSeats = (int) $query->sum('capacity');
        } catch (\Throwable $e) {
            $totalSeats = 0;
        }

        // เปิดร้าน/วัน (นาที) — จาก StoreSettingModel ถ้ามี open_time/close_time; ไม่งั้น default 600 นาที (10 ชม.)
        $openMinutes = 600;
        try {
            $setting = StoreSettingModel::first();
            if ($setting && $setting->open_time && $setting->close_time) {
                $ot   = Carbon::parse($setting->open_time);
                $ct   = Carbon::parse($setting->close_time);
                $diff = $ot->diffInMinutes($ct, false);
                if ($diff > 0) {
                    $openMinutes = $diff;
                }
            }
        } catch (\Throwable $e) {
            // keep default
        }

        $days      = 7;
        $utilStart = Carbon::now()->subDays($days)->startOfDay();
        $utilEnd   = Carbon::now()->endOfDay();

        $seatMinReserved = (int) ReservationModel::whereBetween('start_at', [$utilStart, $utilEnd])
            ->whereIn('status', ['CONFIRMED', 'SEATED', 'COMPLETED'])
            ->selectRaw('COALESCE(SUM(party_size * TIMESTAMPDIFF(MINUTE, start_at, end_at)),0) as sm')
            ->value('sm');

        $theoretical = max($totalSeats, 1) * max($openMinutes, 60) * $days;

        return $theoretical > 0
            ? round(min(100, ($seatMinReserved / $theoretical) * 100), 1)
            : 0.0;
    }
} //class

// Helper function to check if a column exists in a table
if (!function_exists('schema_has_column')) {
    function schema_has_column(string $table, string $column): bool
    {
        try {
            return Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
