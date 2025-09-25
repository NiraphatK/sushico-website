<?php

namespace App\Http\Controllers;

use App\Models\StoreSettingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Pagination\Paginator;


class StoreSettingController extends Controller
{

    public function __construct()
    {
        // ใช้ middleware 'auth:user' เพื่อบังคับให้ต้องล็อกอินในฐานะ admin/staff ก่อนใช้งาน controller นี้
        // ถ้าไม่ล็อกอินหรือไม้ได้ใช้ guard 'user' จะถูก redirect ไปหน้า login
        $this->middleware(['auth:user', 'role:ADMIN,STAFF']);
    }

    public function index()
    {
        // เอาค่าตั้งค่าแถวเดียว (Singleton)
        $setting = StoreSettingModel::first();

        // ถ้ายังไม่มี ให้สร้างใหม่
        if (!$setting) {
            $setting = StoreSettingModel::create(attributes: [
                'timezone' => 'Asia/Bangkok',
                'cut_off_minutes' => 30,
                'grace_minutes' => 15,
                'buffer_minutes' => 10,
                'slot_granularity_minutes' => 15,
                'default_duration_minutes' => 60,
                'open_time' => '9:00',
                'close_time' => '20:00',
            ]);
        }

        return view('store-settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        //vali msg 
        $messages = [
            'timezone.required' => 'กรุณาเลือก Timezone',

            'cut_off_minutes.required' => 'กรุณากำหนดค่า Cut-off',
            'cut_off_minutes.integer'  => 'Cut-off ต้องเป็นตัวเลข',
            'cut_off_minutes.min'      => 'Cut-off ต้องไม่น้อยกว่า :min นาที',
            'cut_off_minutes.max'      => 'Cut-off ต้องไม่เกิน :max นาที',

            'grace_minutes.required' => 'กรุณากำหนด Grace Period',
            'grace_minutes.integer'  => 'Grace ต้องเป็นตัวเลข',
            'grace_minutes.min'      => 'Grace ต้องไม่น้อยกว่า :min นาที',
            'grace_minutes.max'      => 'Grace ต้องไม่เกิน :max นาที',

            'buffer_minutes.required' => 'กรุณากำหนด Buffer',
            'buffer_minutes.integer'  => 'Buffer ต้องเป็นตัวเลข',
            'buffer_minutes.min'      => 'Buffer ต้องไม่น้อยกว่า :min นาที',
            'buffer_minutes.max'      => 'Buffer ต้องไม่เกิน :max นาที',

            'slot_granularity_minutes.required' => 'กรุณากำหนด Slot Size',
            'slot_granularity_minutes.integer'  => 'Slot Size ต้องเป็นตัวเลข',
            'slot_granularity_minutes.min'      => 'Slot Size ต้องไม่น้อยกว่า :min นาที',
            'slot_granularity_minutes.max'      => 'Slot Size ต้องไม่เกิน :max นาที',

            'default_duration_minutes.required' => 'กรุณากำหนด Duration',
            'default_duration_minutes.integer'  => 'Duration ต้องเป็นตัวเลข',
            'default_duration_minutes.min'      => 'Duration ต้องไม่น้อยกว่า :min นาที',
            'default_duration_minutes.max'      => 'Duration ต้องไม่เกิน :max นาที',

            'open_time.required'  => 'กรุณาระบุเวลาเปิดร้าน',
            'open_time.date_format' => 'รูปแบบเวลาเปิดร้านไม่ถูกต้อง (ตัวอย่าง 09:00)',
            'close_time.required' => 'กรุณาระบุเวลาปิดร้าน',
            'close_time.date_format' => 'รูปแบบเวลาปิดร้านไม่ถูกต้อง (ตัวอย่าง 20:00)',
            'close_time.after' => 'เวลาปิดร้าน ต้องอยู่หลังเวลาเปิดร้าน',
        ];

        //rule
        $validator = Validator::make($request->all(), [
            'timezone'                 => 'required|string',
            'cut_off_minutes'          => 'required|integer|min:1|max:127',
            'grace_minutes'            => 'required|integer|min:1|max:127',
            'buffer_minutes'           => 'required|integer|min:1|max:127',
            'slot_granularity_minutes' => 'required|integer|min:1|max:127',
            'default_duration_minutes' => 'required|integer|min:1|max:127',
            'open_time'  => 'required|date_format:H:i',
            'close_time' => 'required|date_format:H:i|after:open_time',
        ], $messages);

        //check 
        if ($validator->fails()) {
            return redirect('/store-settings')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $setting = StoreSettingModel::first();
            $setting->update([
                'timezone' => $request->timezone,
                'cut_off_minutes' => $request->cut_off_minutes,
                'grace_minutes' => $request->grace_minutes,
                'buffer_minutes' => $request->buffer_minutes,
                'slot_granularity_minutes' => $request->slot_granularity_minutes,
                'default_duration_minutes' => $request->default_duration_minutes,
                'open_time' => $request->open_time,
                'close_time' => $request->close_time,
            ]);
            // แสดง Alert ก่อน return
            Alert::success('อัปเดตการตั้งค่าสำเร็จ');
            return redirect('/store-settings');
        } catch (\Exception $e) {
            // return response()->json(['error' => $e->getMessage()], 500); //สำหรับ debug
            // return view('errors.404');
            Alert::error('เกิดข้อผิดพลาด', $e->getMessage());
            return redirect('/store-settings');
        }
    } //fun update 

    public function reset()
    {
        try {
            $setting = StoreSettingModel::first();

            if ($setting) {
                $setting->update([
                    'timezone' => 'Asia/Bangkok',
                    'cut_off_minutes' => 30,
                    'grace_minutes' => 15,
                    'buffer_minutes' => 10,
                    'slot_granularity_minutes' => 15,
                    'default_duration_minutes' => 60,
                    'open_time' => '9:00',
                    'close_time' => '20:00',
                ]);
            } else {
                // ถ้าไม่มี record ให้สร้างใหม่
                $setting = StoreSettingModel::create([
                    'timezone' => 'Asia/Bangkok',
                    'cut_off_minutes' => 30,
                    'grace_minutes' => 15,
                    'buffer_minutes' => 10,
                    'slot_granularity_minutes' => 15,
                    'default_duration_minutes' => 60,
                    'open_time' => '9:00',
                    'close_time' => '20:00',
                ]);
            }

            Alert::success('รีเซ็ตการตั้งค่าสำเร็จ', 'กลับไปใช้ค่าตั้งต้นแล้ว');
            return redirect('/store-settings');
        } catch (\Exception $e) {
            Alert::error('เกิดข้อผิดพลาด', $e->getMessage());
            return redirect('/store-settings');
        }
    }
} //class
