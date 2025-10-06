<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class AuthController extends Controller
{

    public function index()
    {

        return view('auth.login');
    }

    public function register(Request $request)
    {
        $bag = 'register';

        // (เอาเฉพาะตัวเลข)
        $request->merge([
            'phone' => preg_replace('/\D+/', '', (string) $request->input('phone')),
        ]);

        $rules = [
            'full_name' => ['required', 'string', 'max:100'],
            'phone'     => ['required', 'string', 'regex:/^[0-9]{9,10}$/', 'unique:users,phone'],
            'email'     => ['nullable', 'email', 'max:120', 'unique:users,email'],
            'password'  => ['required', 'string', 'min:6', 'confirmed'], // ต้องมี field password_confirmation
            'terms'     => ['accepted'],
        ];

        $messages = [
            'full_name.required' => 'กรุณากรอกชื่อ-สกุล',
            'phone.required'     => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.regex'        => 'กรุณากรอกเบอร์โทรให้เป็นตัวเลข 9-10 หลัก',
            'phone.unique'       => 'เบอร์โทรนี้ถูกใช้ไปแล้ว',
            'email.email'        => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique'       => 'อีเมลนี้ถูกใช้ไปแล้ว',
            'password.required'  => 'กรุณากรอกรหัสผ่าน',
            'password.min'       => 'รหัสผ่านต้องอย่างน้อย :min ตัวอักษร',
            'password.confirmed' => 'รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน',
            'terms.accepted'     => 'กรุณายอมรับข้อตกลงการใช้งาน',
        ];

        // ใช้ Validator เพื่อส่ง error ไป bag 'register' และเปิด modal อัตโนมัติ
        $v = Validator::make($request->all(), $rules, $messages);
        if ($v->fails()) {
            return back()
                ->withErrors($v, $bag)
                ->with('showRegister', true)
                ->withInput();
        }

        // บันทึกลงฐานข้อมูล
        $user = new UserModel();
        $user->full_name     = $request->input('full_name');
        $user->phone         = $request->input('phone');
        $user->email         = $request->filled('email') ? $request->input('email') : null;
        $user->password_hash = bcrypt($request->input('password'));
        $user->role          = 'CUSTOMER'; // ค่าเริ่มต้น
        $user->is_active     = 1;
        $user->save();

        // ล็อกอินอัตโนมัติ
        Auth::guard('user')->loginUsingId($user->user_id);
        $request->session()->regenerate();

        session([
            'user_id'   => $user->user_id,
            'phone'     => $user->phone,
            'full_name' => $user->full_name,
            'role'      => $user->role,
        ]);

        // ส่งข้อความสำเร็จและ redirect ตาม role
        if ($user->role === 'ADMIN' || $user->role === 'STAFF') {
            return redirect()->intended('/dashboard')->with('register_message', 'สมัครสมาชิกสำเร็จ!');
        }

        Alert::toast('สมัครสมาชิกสำเร็จ', 'success')
            ->position('top-end')->timerProgressBar()->autoClose(2200);
        return redirect()->intended('/');
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
            'is_active' => 1,
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


            Alert::toast('เข้าสู่ระบบสำเร็จ', 'success')
                ->position('top-end')->timerProgressBar()->autoClose(2200);

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

        Alert::toast('ออกจากระบบแล้ว', 'info')
            ->position('top-end')->timerProgressBar()->autoClose(1800);
        // เปลี่ยนเส้นทางผู้ใช้ไปยังหน้าแรกหลัง logout
        return redirect('/');
    }
} //class
