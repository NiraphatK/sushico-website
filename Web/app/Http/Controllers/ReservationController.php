<?php

namespace App\Http\Controllers;

use App\Models\ReservationModel;
use App\Models\UserModel;
use App\Models\TableModel;
use App\Models\StoreSettingModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Pagination\Paginator;
use Carbon\Carbon;

class ReservationController extends Controller
{
    // แสดงรายการจอง
    public function index()
    {
        try {
            Paginator::useBootstrap();

            // อัปเดตสถานะที่เลยเวลาแล้วเป็น NO_SHOW
            ReservationModel::where('status', 'CONFIRMED')
                ->where('end_at', '<', Carbon::now())
                ->update(['status' => 'NO_SHOW']);

            $ReservationList = ReservationModel::with('user', 'table')
                ->orderBy('reservation_id', 'desc')
                ->paginate(10);

            return view('reservations.list', compact('ReservationList'));
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    // ฟอร์มเพิ่ม
    public function adding()
    {
        $users = UserModel::where('is_active', 1)->orderBy('full_name')->get();
        $settings = StoreSettingModel::first();
        return view('reservations.create', compact('users', 'settings'));
    }

    // บันทึกการจองใหม่
    public function create(Request $request)
    {
        $settings = StoreSettingModel::first();

        $validator = Validator::make($request->all(), [
            'user_id'    => 'required|exists:users,user_id',
            'party_size' => 'required|integer|min:1|max:10',
            'seat_type'  => 'required|in:BAR,TABLE',
            'start_time' => 'required|date_format:H:i',
        ], [
            'user_id.required'    => 'กรุณาเลือกผู้ใช้งาน',
            'party_size.required' => 'กรุณาระบุจำนวนลูกค้า',
            'seat_type.required'  => 'กรุณาเลือกประเภทที่นั่ง',
            'start_time.required' => 'กรุณาเลือกเวลาเริ่มต้น',
        ]);

        if ($validator->fails()) {
            return redirect('reservations/adding')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $today   = Carbon::today()->format('Y-m-d');
            $startAt = Carbon::parse($today . ' ' . $request->input('start_time'));
            $endAt   = $startAt->copy()->addMinutes($settings->default_duration_minutes);

            // ตรวจสอบว่าลูกค้าคนนี้มีการจองที่ยังไม่จบอยู่แล้วหรือไม่
            $hasActive = ReservationModel::where('user_id', $request->input('user_id'))
                ->whereDate('start_at', $today) // จำกัดว่าเป็นวันเดียวกัน
                ->whereIn('status', ['CONFIRMED', 'SEATED'])
                ->exists();

            if ($hasActive) {
                Alert::error('ไม่สามารถจองได้', 'ลูกค้านี้มีการจองที่ยังไม่เสร็จสิ้นอยู่แล้ว');
                return redirect('reservations/adding')->withInput();
            }

            // ถ้าไม่มีการจองซ้ำ → บันทึกใหม่
            ReservationModel::create([
                'user_id'    => $request->input('user_id'),
                'table_id'   => null,
                'party_size' => $request->input('party_size'),
                'seat_type'  => $request->input('seat_type'),
                'start_at'   => $startAt,
                'end_at'     => $endAt,
                'status'     => 'CONFIRMED',
            ]);

            Alert::success('เพิ่มการจองสำเร็จ');
            return redirect('/reservations');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }


    // ฟอร์มแก้ไข
    public function edit($id)
    {
        try {
            $reservation = ReservationModel::findOrFail($id);
            $users = UserModel::where('is_active', 1)->orderBy('full_name')->get();
            $settings = StoreSettingModel::first();
            return view('reservations.edit', compact('reservation', 'users', 'settings'));
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    // อัปเดต
    public function update($id, Request $request)
    {
        $settings = StoreSettingModel::first();

        $validator = Validator::make($request->all(), [
            'user_id'    => 'required|exists:users,user_id',
            'party_size' => 'required|integer|min:1|max:10',
            'seat_type'  => 'required|in:BAR,TABLE',
            'start_time' => 'required|date_format:H:i',
            'status'     => 'required|in:CONFIRMED,SEATED,COMPLETED,CANCELLED,NO_SHOW',
        ], [
            'user_id.required'    => 'กรุณาเลือกผู้ใช้งาน',
            'party_size.required' => 'กรุณาระบุจำนวนลูกค้า',
            'seat_type.required'  => 'กรุณาเลือกประเภทที่นั่ง',
            'start_time.required' => 'กรุณาเลือกเวลาเริ่มต้น',
            'status.required'     => 'กรุณาเลือกสถานะ',
        ]);

        if ($validator->fails()) {
            return redirect('reservations/' . $id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $today   = Carbon::today()->format('Y-m-d');
            $startAt = Carbon::parse($today . ' ' . $request->input('start_time'));
            $endAt   = $startAt->copy()->addMinutes($settings->default_duration_minutes);

            $reservation = ReservationModel::findOrFail($id);
            $reservation->update([
                'user_id'    => $request->input('user_id'),
                'party_size' => $request->input('party_size'),
                'seat_type'  => $request->input('seat_type'),
                'start_at'   => $startAt,
                'end_at'     => $endAt,
                'status'     => $request->input('status'),
            ]);

            Alert::success('แก้ไขการจองสำเร็จ');
            return redirect('/reservations');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    // ลบ
    public function remove($id)
    {
        try {
            $reservation = ReservationModel::findOrFail($id);
            $reservation->delete();
            Alert::success('ลบการจองสำเร็จ');
            return redirect('/reservations');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }

    // Check-in
    public function checkIn($id)
    {
        try {
            $reservation = ReservationModel::findOrFail($id);

            if ($reservation->status !== 'CONFIRMED') {
                Alert::error('ไม่สามารถ Check-in ได้');
                return redirect('/reservations');
            }

            // หาโต๊ะว่างที่เล็กที่สุดแต่จุได้พอดี
            $table = TableModel::where('seat_type', $reservation->seat_type)
                ->where('capacity', '>=', $reservation->party_size)
                ->where('is_active', 1)
                ->orderBy('capacity', 'asc') // เลือกโต๊ะที่จุน้อยที่สุดก่อน
                ->whereDoesntHave('reservations', function ($q) use ($reservation) {
                    $q->whereIn('status', ['SEATED']) // โต๊ะที่มีคนกำลังนั่ง
                        ->where(function ($q2) use ($reservation) {
                            $q2->whereBetween('start_at', [$reservation->start_at, $reservation->end_at])
                                ->orWhereBetween('end_at', [$reservation->start_at, $reservation->end_at])
                                ->orWhere(function ($q3) use ($reservation) {
                                    $q3->where('start_at', '<=', $reservation->start_at)
                                        ->where('end_at', '>=', $reservation->end_at);
                                });
                        });
                })
                ->first();

            if (!$table) {
                Alert::error('ไม่มีโต๊ะว่างสำหรับ Check-in');
                return redirect('/reservations');
            }

            // อัปเดตการจอง
            $reservation->update([
                'table_id' => $table->table_id,
                'status'   => 'SEATED'
            ]);

            Alert::success('Check-in สำเร็จ', 'โต๊ะหมายเลข ' . $table->table_number . ' (จุได้ ' . $table->capacity . ' คน)');
            return redirect('/reservations');
        } catch (\Exception $e) {
            return view('errors.404');
        }
    }
}
