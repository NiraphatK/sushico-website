<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\AdminModel;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{

    public function index()
    {

        return view('auth.login');
    }

    public function login(Request $request)
    {
        //vali msg
        $request->validateWithBag('login', [
            'phone'    => 'required|string|max:20',
            'password' => 'required|string|min:6',
        ], [
            'phone.required'    => 'กรุณากรอกเบอร์โทรศัพท์',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min'      => 'รหัสผ่านต้องอย่างน้อย :min ตัวอักษร',
        ]);

        //ตรวจสอบข้อมูลที่ส่งมา
        // ใช้ only() แทน validate() เพื่อไม่สร้าง error ใน default bag
        $credentials = $request->only([
            'phone',
            'password',
        ]);


        if (Auth::guard('user')->attempt([
            // ตรวจสอบค่า phone/password ที่ส่งมาจากฟอร์ม
            'phone' => $credentials['phone'],
            'password' => $credentials['password'],
        ])) {
            // ถ้า login สำเร็จ

            // เพื่อความปลอดภัย Laravel จะสร้าง session ใหม่
            // ป้องกัน session fixation attack
            $request->session()->regenerate();

            // เก็บค่า ของคนที่ login สำเร็จ ลงใน session
            // เพื่อเรียกใช้ใน view เช่น {{ session('') }}
            $user = Auth::guard('user')->user();

            session([
                'user_id'   => $user->user_id,
                'phone'     => $user->phone,
                'full_name' => $user->full_name,
                'role'      => $user->role,
            ]);


            // หลัง login สำเร็จ ส่งผู้ใช้ไปที่ /dashboard
            // หรือถ้าก่อนหน้านี้ผู้ใช้กดลิงก์ไปหน้าที่ต้อง login ก่อน
            // Laravel จะพา redirect ไปหน้าที่ intended แทน

            // redirect ตาม role
            if ($user->role === 'ADMIN' || $user->role === 'STAFF') {
                return redirect()->intended('/dashboard');
            } else { // CUSTOMER
                return redirect()->intended('/');
            }
        }


        return back()
            ->withErrors([
                'phone' => 'เบอร์โทรหรือรหัสผ่านไม่ถูกต้อง',
            ], 'login') // ส่ง error ไปยัง error bag ชื่อ login
            ->with('showLogin', true) // ให้ layout เปิด modal ให้อัตโนมัติ
            ->withInput($request->only('phone')); // คืนค่า phone ให้ฟิลด์เดิม
        // คืนค่าข้อมูล input (เช่น admin_username) กลับไปที่ฟอร์ม
        // ทำให้เวลาผู้ใช้กรอกผิด รหัสผ่านไม่ถูก แต่ username ยังโชว์อยู่
        // จะได้ไม่ต้องกรอกใหม่

    } //login

    public function logout(Request $request)
    {
        // ออกจากระบบผู้ใช้ปัจจุบัน 
        Auth::guard('user')->logout();
        // ล้าง session ทั้งหมดของผู้ใช้เพื่อความปลอดภัย
        $request->session()->invalidate();
        // สร้าง CSRF token ใหม่ ป้องกันการโจมตีแบบ CSRF 
        $request->session()->regenerateToken();
        // เปลี่ยนเส้นทางผู้ใช้ไปยังหน้าแรกหลัง logout
        return redirect('/');
    }
} //class
