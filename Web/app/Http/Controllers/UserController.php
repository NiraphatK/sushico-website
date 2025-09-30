<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Pagination\Paginator;


class UserController extends Controller
{
    public function __construct()
    {
        // ใช้ middleware 'auth:user' เพื่อบังคับให้ต้องล็อกอินในฐานะ admin ก่อนใช้งาน controller นี้
        // ถ้าไม่ล็อกอินหรือไม้ได้ใช้ guard 'user' จะถูก redirect ไปหน้า login
        $this->middleware(['auth:user', 'role:ADMIN']);
    }

    public function index()
    {
        try {
            Paginator::useBootstrap();
            $UserList = UserModel::orderBy('user_id', 'desc')->paginate(10); //order by & pagination
            return view('users.list', compact('UserList'));
        } catch (\Exception $e) {
            // Log::error('Admin list error: '.$e->getMessage());
            return view('errors.404');
        }
    }

    public function adding()
    {
        return view('users.create');
    }

    public function create(Request $request)
    {
        // echo '<pre>';
        // dd($_POST);
        // exit();

        //vali msg 
        $messages = [
            'full_name.required' => 'กรุณากรอกชื่อ-สกุล',
            'phone.required'     => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.unique'       => 'เบอร์โทรนี้มีในระบบแล้ว',
            'email.email'        => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique'       => 'อีเมลนี้มีในระบบแล้ว',
            'password.required'  => 'กรุณากรอกรหัสผ่าน',
            'password.min'       => 'รหัสผ่านต้องอย่างน้อย :min ตัวอักษร',
        ];

        //rule 
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:100',
            'phone'     => 'required|string|max:20|unique:users',
            'email'     => 'nullable|email|unique:users',
            'password'  => 'required|min:6',
            'role'      => 'required|in:CUSTOMER,STAFF,ADMIN',
        ], $messages);

        //check vali 
        if ($validator->fails()) {
            return redirect('users/adding')
                ->withErrors($validator)
                ->withInput();
        }

        try {

            //ปลอดภัย: กัน XSS ที่มาจาก <script>, <img onerror=...> ได้
            UserModel::create([
                'full_name'     => strip_tags($request->input('full_name')),
                'phone'         => strip_tags($request->input('phone')),
                'email'         => $request->input('email'),
                'password_hash' => bcrypt($request->input('password')),
                'role'          => $request->input('role'),
                'is_active'     => 1,


            ]);
            // แสดง Alert ก่อน return
            Alert::success('เพิ่มข้อมูลสำเร็จ');
            return redirect('/users');
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //fun create



    public function edit($user_id)
    {
        try {
            //query data for form edit 
            $user = UserModel::findOrFail($user_id); // ใช้ findOrFail เพื่อให้เจอหรือ 404
            if (isset($user)) {
                $user_id    = $user->user_id;
                $full_name  = $user->full_name;
                $phone      = $user->phone;
                $email      = $user->email;
                $role       = $user->role;
                $is_active  = $user->is_active;
                return view('users.edit', compact('user_id', 'full_name', 'phone', 'email', 'role', 'is_active'));
            }
            return view('users.edit', compact('user'));
        } catch (\Exception $e) {
            // return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //func edit


    public function update($user_id, Request $request)
    {
        //vali msg 
        $messages = [
            'full_name.required' => 'กรุณากรอกชื่อ-สกุล',
            'phone.required'     => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.unique'       => 'เบอร์โทรนี้มีในระบบแล้ว',
            'email.email'        => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique'       => 'อีเมลนี้มีในระบบแล้ว',
        ];

        //rule
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:100',
            'phone'     => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($user_id, 'user_id'),
            ],
            'email'     => [
                'nullable',
                'email',
                Rule::unique('users', 'email')->ignore($user_id, 'user_id'),
            ],
            'role'      => 'required|in:CUSTOMER,STAFF,ADMIN',
            'is_active' => 'required|boolean',

        ], $messages);

        //check 
        if ($validator->fails()) {
            return redirect('users/' . $user_id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = UserModel::findOrFail($user_id);
            $user->update([
                'full_name' => strip_tags($request->input('full_name')),
                'phone'     => strip_tags($request->input('phone')),
                'email'     => $request->input('email'),
                'role'      => $request->input('role'),
                'is_active' => $request->input('is_active'),
            ]);
            // แสดง Alert ก่อน return
            Alert::success('ปรับปรุงข้อมูลสำเร็จ');
            return redirect('/users');
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //fun update 


    public function remove($id)
    {
        try {
            $user = UserModel::find($id);  //query หาว่ามีไอดีนี้อยู่จริงไหม 
            $user->delete();
            Alert::success('ลบข้อมูลสำเร็จ');
            return redirect('/users');
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //remove 

    public function reset($user_id)
    {
        try {
            //query data for form edit 
            $user = UserModel::findOrFail($user_id); // ใช้ findOrFail เพื่อให้เจอหรือ 404
            if (isset($user)) {
                $user_id   = $user->user_id;
                $full_name = $user->full_name;
                $email     = $user->email;
                $phone     = $user->phone;
                return view('users.editPassword', compact('user_id', 'full_name', 'email', 'phone'));
            }
        } catch (\Exception $e) {
            // return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //func reset

    public function resetPassword($user_id, Request $request)
    {
        //vali msg 
        $messages = [
            'password.required'   => 'กรุณากรอกรหัสผ่าน',
            'password.min'        => 'รหัสผ่านต้องอย่างน้อย :min ตัวอักษร',
            'password.confirmed'  => 'รหัสผ่านไม่ตรงกัน',

            'password_confirmation.required' => 'กรุณากรอกยืนยันรหัสผ่าน',
            'password_confirmation.min'      => 'ยืนยันรหัสผ่านต้องอย่างน้อย :min ตัวอักษร',
        ];

        //rule
        $validator = Validator::make($request->all(), [
            'password'              => 'required|min:6|confirmed',
            'password_confirmation' => 'required|min:6',

        ], $messages);

        //check 
        if ($validator->fails()) {
            return redirect('users/reset/' . $user_id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $user = UserModel::find($user_id);
            $user->update([
                'password_hash' => bcrypt($request->input('password')),
            ]);
            // แสดง Alert ก่อน return
            Alert::success('แก้ไขรหัสผ่านสำเร็จ');
            return redirect('/users');
        } catch (\Exception $e) {
            // return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //fun resetPassword 


} //class
