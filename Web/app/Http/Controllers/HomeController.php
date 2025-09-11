<?php

namespace App\Http\Controllers;

use App\Models\MenuItemModel;
use Illuminate\Pagination\Paginator; //แบ่งหน้า
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        DB::table('counter')->insert([
            'c_date' => now(),
        ]);
        return view('home.index'); // หน้าแรก
    }

    public function about()
    {
        return view('home.about-us'); // เกี่ยวกับเรา
    }

    public function contact()
    {
        return view('home.contact-us'); // เกี่ยวกับเรา
    }

    public function menu()
    {
        Paginator::useBootstrap(); // ใช้ Bootstrap pagination
        $menu = MenuItemModel::where('is_active', 1) // แสดงเฉพาะเมนูที่ Active
            ->orderBy('menu_id', 'desc')
            ->paginate(8);
        //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
        return view('home.menu_index', compact('menu')); // เมนูอาหาร
    }

    public function reservation()
    {
        return view('home.reservation'); // จองโต๊ะ
    }
}
