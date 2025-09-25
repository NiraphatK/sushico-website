<?php

namespace App\Http\Controllers;

use App\Models\CounterModel;
use App\Models\MenuItemModel;
use App\Models\ReservationModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;  // Added for DB operations
use Carbon\Carbon; // Added for date and time handling
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;

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
            // $sumPrice = MenuItemModel::where('is_active', 1)
            //     ->sum('price');

            $countReservation = ReservationModel::count();
            $countMenu = MenuItemModel::where('is_active', 1)->count();
            $countUser = UserModel::count();
            $countView = CounterModel::count();

            //วิวแยกตามเดือน
            // กําาหนดวันเริ่มต้น = ย้อนหลังไป 11 เดือนจากปัจจุบัน และปัดให้เป็นวันแรกของเดือนนั้น 
            // $startDate = Carbon::now()->subMonths(11)->startOfMonth();
            // กําหนดวันสิ้นสุด = เดือนปัจจุบัน และปัดให้เป็นวันสุดท้ายของเดือน
            // $endDate = Carbon::now()->endOfMonth();

            //raw sql
            $monthlyVisits = DB::table('counter')
                ->selectRaw('DATE_FORMAT(c_date, "%M-%Y") as ym, COUNT(*) as total')
                // ->whereBetween('c_date', [$startDate, $endDate])
                ->groupBy('ym')
                ->orderByRaw('DATE_FORMAT(c_date, "%Y-%m") DESC')
                ->limit(12)
                ->get();
            // แปลงเป็น array สําหรับ Chart.js
            $label = $monthlyVisits->pluck('ym');
            $data = $monthlyVisits->pluck('total');
            // echo '<pre>';
            // print_r($monthlyVisits);
            // exit;

            // Reservation by Status
            $reservationStatusData = [
                ReservationModel::where('status', 'CONFIRMED')->count(),
                ReservationModel::where('status', 'SEATED')->count(),
                ReservationModel::where('status', 'COMPLETED')->count(),
                ReservationModel::where('status', 'CANCELLED')->count(),
                ReservationModel::where('status', 'NO_SHOW')->count(),
            ];

            // Average Party Size per Month
            $avgPartySize = ReservationModel::selectRaw('DATE_FORMAT(start_at, "%M-%Y") as ym, AVG(party_size) as avg_size')
                ->groupBy('ym')
                ->orderByRaw('DATE_FORMAT(start_at, "%Y-%m") ASC')
                ->limit(12)
                ->get();
            $avgPartySizeLabel = $avgPartySize->pluck('ym');
            $avgPartySizeData = $avgPartySize->pluck('avg_size');


            // User Growth by Month
            $userGrowth = UserModel::selectRaw('DATE_FORMAT(created_at, "%M-%Y") as ym, COUNT(*) as total')
                ->groupBy('ym')
                ->orderByRaw('DATE_FORMAT(created_at, "%Y-%m") ASC')
                ->limit(12)
                ->get();
            $userGrowthLabel = $userGrowth->pluck('ym');
            $userGrowthData = $userGrowth->pluck('total');

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
                'userGrowthData'
            ));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            // return view('errors.404');
        }
    }
} //class
