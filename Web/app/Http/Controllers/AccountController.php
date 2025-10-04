<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:user']);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user('user');

        $request->merge([
            'phone' => preg_replace('/\D+/', '', (string) $request->input('phone')),
        ]);

        $data = $request->validateWithBag('account', [
            'full_name' => ['required', 'string', 'max:100'],
            'email'     => ['nullable', 'email', 'max:120', Rule::unique('users', 'email')->ignore($user->user_id, 'user_id')],
            'phone'     => ['required', 'string', 'max:20', Rule::unique('users', 'phone')->ignore($user->user_id, 'user_id')],
        ], [
            'full_name.required' => 'กรุณากรอกชื่อ-สกุล',
            'email.email'        => 'รูปแบบอีเมลไม่ถูกต้อง',
            'phone.required'     => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.unique'       => 'เบอร์โทรศัพท์นี้ถูกใช้งานแล้ว',
            'email.unique'       => 'อีเมลนี้ถูกใช้งานแล้ว',
        ]);

        $user->full_name = $data['full_name'];
        $user->email     = $data['email'] ?? null;
        $user->phone     = $data['phone'];
        $user->save();


        Alert::success('บันทึกแล้ว', 'อัปเดตข้อมูลบัญชีเรียบร้อย');

        return redirect()->back()
            ->with('ok', 'อัปเดตข้อมูลบัญชีเรียบร้อย')
            ->withErrors([], 'account'); // เคลียร์ error bag ของแท็บโปรไฟล์
    }

    public function updatePassword(Request $request)
    {
        $user = $request->user('user');

        $request->validateWithBag('password', [
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'current_password.required' => 'กรุณากรอกรหัสผ่านปัจจุบัน',
            'password.required'         => 'กรุณากรอกรหัสผ่านใหม่',
            'password.min'              => 'รหัสผ่านใหม่ต้องอย่างน้อย :min ตัวอักษร',
            'password.confirmed'        => 'การยืนยันรหัสผ่านไม่ตรงกัน',
        ]);

        if (!Hash::check($request->current_password, $user->password_hash)) {
            return back()
                ->withErrors(['current_password' => 'รหัสผ่านปัจจุบันไม่ถูกต้อง'], 'password')
                ->withInput();
        }

        $user->password_hash = Hash::make($request->password);
        $user->save();


        Alert::success('เปลี่ยนรหัสผ่านแล้ว', 'เข้าสู่ระบบครั้งถัดไปด้วยรหัสผ่านใหม่');

        return redirect()->back()
            ->with('ok', 'เปลี่ยนรหัสผ่านเรียบร้อย')
            ->withErrors([], 'password'); // เคลียร์ error bag ของแท็บรหัสผ่าน
    }
}
