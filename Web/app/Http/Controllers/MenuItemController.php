<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; //รับค่าจากฟอร์ม
use Illuminate\Support\Facades\Validator; //form validation
use RealRashid\SweetAlert\Facades\Alert; //sweet alert
use Illuminate\Support\Facades\Storage; //สำหรับเก็บไฟล์ภาพ
use Illuminate\Pagination\Paginator; //แบ่งหน้า
use App\Models\MenuItemModel; //model



class MenuItemController extends Controller
{

    public function __construct()
    {
        // ใช้ middleware 'auth:user' เพื่อบังคับให้ต้องล็อกอินในฐานะ admin ก่อนใช้งาน controller นี้
        // ถ้าไม่ล็อกอินหรือไม้ได้ใช้ guard 'user' จะถูก redirect ไปหน้า login
        $this->middleware(['auth:user', 'role:ADMIN']);
    }

    public function index()
    {
        Paginator::useBootstrap(); // ใช้ Bootstrap pagination
        $menu = MenuItemModel::orderBy('menu_id', 'desc')->paginate(5); //order by & pagination
        //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
        return view('menu.list', compact('menu'));
    }

    public function adding()
    {
        return view('menu.create');
    }


    public function create(Request $request)
    {
        //msg
        $messages = [
            'name.required' => 'กรุณากรอกชื่อเมนู',
            'name.max' => 'ชื่อเมนูยาวเกินไป',
            'price.required' => 'กรุณากรอกราคา',
            'price.numeric' => 'ราคาต้องเป็นตัวเลข',
            'price.min' => 'ราคาต้องมากกว่าหรือเท่ากับ 0',
            'image_path.mimes' => 'รองรับไฟล์ JPG/PNG เท่านั้น',
            'image_path.max' => 'ขนาดไฟล์ไม่เกิน 2MB',
        ];

        //rule ตั้งขึ้นว่าจะเช็คอะไรบ้าง
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'detail' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'nullable|in:0,1',
        ], $messages);


        //ถ้าผิดกฏให้อยู่หน้าเดิม และแสดง msg ออกมา
        if ($validator->fails()) {
            return redirect('menu/adding')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $imagePath = null;
            if ($request->hasFile('image_path')) {
                $imagePath = $request->file('image_path')->store('uploads/menu', 'public');
            }

            //insert เพิ่มข้อมูลลงตาราง
            MenuItemModel::create([
                'name' => strip_tags($request->name),
                'description' => $request->filled('description') ? strip_tags($request->description) : null,
                'detail' => $request->detail,
                'price' => $request->price,
                'image_path' => $imagePath,
                'is_active' => $request->boolean('is_active', true) ? 1 : 0,
            ]);

            //แสดง sweet alert
            Alert::success('เพิ่มเมนูสำเร็จ');
            return redirect('/menu');
        } catch (\Exception $e) {  //error debug
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //create 

    public function edit($menu_id)
    {
        try {
            $menu = MenuItemModel::findOrFail($menu_id); // ใช้ findOrFail เพื่อให้เจอหรือ 404

            //ประกาศตัวแปรเพื่อส่งไปที่ view
            if (isset($menu)) {
                $menu_id = $menu->menu_id;
                $name = $menu->name;
                $description = $menu->description;
                $detail = $menu->detail;
                $price = $menu->price;
                $image_path = $menu->image_path;
                $is_active = $menu->is_active;
                return view('menu.edit', compact('menu_id', 'name', 'description', 'detail', 'price', 'image_path', 'is_active'));
            }
        } catch (\Exception $e) {
            // return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');
        }
    } //func edit

    public function update($menu_id, Request $request)
    {

        //error msg
        $messages = [
            'name.required' => 'กรุณากรอกชื่อเมนู',
            'name.max' => 'ชื่อเมนูยาวเกินไป',
            'price.required' => 'กรุณากรอกราคา',
            'price.numeric' => 'ราคาต้องเป็นตัวเลข',
            'price.min' => 'ราคาต้องมากกว่าหรือเท่ากับ 0',
            'image_path.mimes' => 'รองรับไฟล์ JPG/PNG เท่านั้น',
            'image_path.max' => 'ขนาดไฟล์ไม่เกิน 2MB',
        ];


        // ตรวจสอบข้อมูลจากฟอร์มด้วย Validator
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'detail' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'nullable|in:0,1',
        ], $messages);

        // ถ้า validation ไม่ผ่าน ให้กลับไปหน้าฟอร์มพร้อมแสดง error และข้อมูลเดิม
        if ($validator->fails()) {
            return redirect('menu/' . $menu_id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // ดึงข้อมูลสินค้าตามไอดี ถ้าไม่เจอจะ throw Exception
            $menu = MenuItemModel::findOrFail($menu_id);

            // ตรวจสอบว่ามีไฟล์รูปใหม่ถูกอัปโหลดมาหรือไม่
            if ($request->hasFile('image_path')) {
                // ถ้ามีรูปเดิมให้ลบไฟล์รูปเก่าออกจาก storage
                if ($menu->image_path) {
                    Storage::disk('public')->delete($menu->image_path);
                }
                // บันทึกไฟล์รูปใหม่ลงโฟลเดอร์ 'uploads/menu' ใน disk 'public'
                $imagePath = $request->file('image_path')->store('uploads/menu', 'public');
                // อัปเดต path รูปภาพใหม่ใน model
                $menu->image_path = $imagePath;
            }

            // อัปเดต โดยใช้ strip_tags ป้องกันการแทรกโค้ด HTML/JS
            $menu->name = strip_tags($request->name);
            $menu->description = $request->filled('description') ? strip_tags($request->description) : null;
            if ($request->filled('detail')) {
                $menu->detail = $request->detail; // rich text (HTML)
            }
            $menu->price = $request->price;
            $menu->is_active = $request->boolean('is_active', $menu->is_active) ? 1 : 0;

            // บันทึกการเปลี่ยนแปลงในฐานข้อมูล
            $menu->save();

            // แสดง SweetAlert แจ้งว่าบันทึกสำเร็จ
            Alert::success('อัปเดตเมนูสำเร็จ');

            // เปลี่ยนเส้นทางกลับไปหน้ารายการสินค้า
            return redirect('/menu');
        } catch (\Exception $e) {
            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            return view('errors.404');

            //return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            //return view('errors.404');
        }
    } //update  



    public function remove($menu_id)
    {
        try {
            $menu = MenuItemModel::find($menu_id); //คิวรี่เช็คว่ามีไอดีนี้อยู่ในตารางหรือไม่

            if (!$menu) {   //ถ้าไม่มี
                Alert::error('Menu item not found.');
                return redirect('menu');
            }

            //ถ้ามีภาพ ลบภาพในโฟลเดอร์ 
            if ($menu->image_path && Storage::disk('public')->exists($menu->image_path)) {
                Storage::disk('public')->delete($menu->image_path);
            }

            // ลบข้อมูลจาก DB
            $menu->delete();

            Alert::success('ลบเมนูสำเร็จ');
            return redirect('menu');
        } catch (\Exception $e) {
            Alert::error('เกิดข้อผิดพลาด: ' . $e->getMessage());
            return redirect('menu');
        }
    } //remove 



} //class
