<?php

namespace App\Http\Controllers;

use App\Models\CounterModel;
use App\Models\MenuItemModel;
use App\Models\ReservationModel;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;  // Added for DB operations
use Carbon\Carbon; // Added for date and time handling
use Illuminate\Foundation\Auth\User;
use PHPUnit\Framework\Constraint\Count;

class DashboardController extends Controller
{

    public function index()
    {
        try {
            // $sumPrice = MenuItemModel::where('is_active', 1)
            //     ->sum('price');

            $countReservation = ReservationModel::where('status', 'confirmed')
                ->count();

            $countMenu = MenuItemModel::where('is_active', 1)
                ->count();

            $countUser = UserModel::count();

            $countView = CounterModel::count();


            //วิวแยกตามเดือน
            // กําาหนดวันเริ่มต้น = ย้อนหลังไป 11 เดือนจากปัจจุบัน และปัดให้เป็นวันแรกของเดือนนั้น 
            $startDate = Carbon::now()->subMonths(11)->startOfMonth();
            // กําหนดวันสิ้นสุด = เดือนปัจจุบัน และปัดให้เป็นวันสุดท้ายของเดือน
            $endDate = Carbon::now()->endOfMonth();

            //raw sql
            $monthlyVisits = DB::table('counter')
                ->selectRaw('DATE_FORMAT(c_date, "%M-%Y") as ym, COUNT(*) as total')->whereBetween('c_date', [$startDate, $endDate])
                ->groupBy('ym')
                ->orderByRaw('DATE_FORMAT(c_date, "%Y-%m") DESC')
                ->get();
            // แปลงเป็น array สําหรับ Chart.js
            $label = $monthlyVisits->pluck('ym');
            $data = $monthlyVisits->pluck('total');
            // echo '<pre>';
            // print_r($monthlyVisits);
            // exit;

            return view('dashboard.index', compact(
                // 'sumPrice',
                'countReservation',
                'countMenu',
                'countUser',
                'countView',
                'label',
                'data'
            ));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            // return view('errors.404');
        }
    }
} //class
