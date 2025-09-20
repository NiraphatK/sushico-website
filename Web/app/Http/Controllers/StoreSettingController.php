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

    public function index()
    {
        // เอาค่าตั้งค่าแถวเดียว (Singleton)
        $setting = StoreSettingModel::first();

        // ถ้ายังไม่มี ให้สร้างใหม่
        if (!$setting) {
            $setting = StoreSettingModel::create([
                'timezone' => 'Asia/Bangkok',
                'cut_off_minutes' => 30,
                'grace_minutes' => 15,
                'buffer_minutes' => 10,
                'slot_granularity_minutes' => 15,
                'default_duration_minutes' => 60,
            ]);
        }

        return view('store-settings.edit', compact('setting'));
    }

    public function update(Request $request)
    {
        //vali msg 
        $messages = [
            'cut_off_minutes.required' => 'กรุณากำหนดค่า Cut-off',
            'grace_minutes.required' => 'กรุณากำหนด Grace Period',
            'buffer_minutes.required' => 'กรุณากำหนด Buffer',
            'slot_granularity_minutes.required' => 'กรุณากำหนด Slot Size',
            'default_duration_minutes.required' => 'กรุณากำหนด Duration',
        ];

        //rule
        $validator = Validator::make($request->all(), [
            'timezone' => 'required|string',
            'cut_off_minutes' => 'required|integer|min:1',
            'grace_minutes' => 'required|integer|min:1',
            'buffer_minutes' => 'required|integer|min:1',
            'slot_granularity_minutes' => 'required|integer|min:1',
            'default_duration_minutes' => 'required|integer|min:1',
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
