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
                'password_hash' => bcrypt($request->input('password_hash')),
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


    public function update($id, Request $request)
    {
        //vali msg 
        $messages = [
            'admin_username.required' => 'กรุณากรอกข้อมูล',
            'admin_username.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'admin_username.unique' => 'Email ซ้ำ เพิ่มใหม่อีกครั้ง !!',

            'admin_name.required' => 'กรุณากรอกข้อมูล',
            'admin_name.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัว',
        ];

        //rule
        $validator = Validator::make($request->all(), [
            'admin_username' => [
                'required',
                'email',
                Rule::unique('tbl_admin', 'admin_username')->ignore($id, 'id'), //ห้ามแก้ซ้ำ
            ],

            'admin_name' => 'required|min:4',

        ], $messages);

        //check 
        if ($validator->fails()) {
            return redirect('admin/' . $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $admin = UserModel::find($id);
            $admin->update([
                'admin_username' => strip_tags($request->input('admin_username')),
                'admin_name' => strip_tags($request->input('admin_name')),
            ]);
            // แสดง Alert ก่อน return
            Alert::success('ปรับปรุงข้อมูลสำเร็จ');
            return redirect('/admin');
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

    public function reset($id)
    {
        try {
            //query data for form edit 
            $admin = UserModel::findOrFail($id); // ใช้ findOrFail เพื่อให้เจอหรือ 404
            if (isset($admin)) {
                $id = $admin->id;
                $admin_name = $admin->admin_name;
                $admin_username = $admin->admin_username;
                // $password = $admin->password;
                return view('admin.editPassword', compact('id', 'admin_name', 'admin_username'));
            }
        } catch (\Exception $e) {
            // return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //func reset

    public function resetPassword($id, Request $request)
    {
        //vali msg 
        $messages = [
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัว',
            'password.confirmed' => 'รหัสผ่านไม่ตรงกัน',

            'password_confirmation.required' => 'กรุณากรอกข้อมูล',
            'password_confirmation.min' => 'กรอกข้อมูลขั้นต่ำ :min ตัว',
        ];

        //rule
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:4|confirmed',
            'password_confirmation' => 'required|min:3',

        ], $messages);

        //check 
        if ($validator->fails()) {
            return redirect('admin/reset/' . $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $admin = UserModel::find($id);
            $admin->update([
                'admin_password' => bcrypt($request->input('password')),
            ]);
            // แสดง Alert ก่อน return
            Alert::success('แก้ไขรหัสผ่านสำเร็จ');
            return redirect('/admin');
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //fun resetPassword 


} //class
