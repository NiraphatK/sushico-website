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
    public function __construct()
    {
        // ใช้ middleware 'auth:user' เพื่อบังคับให้ต้องล็อกอินในฐานะ admin/staff ก่อนใช้งาน controller นี้
        // ถ้าไม่ล็อกอินหรือไม้ได้ใช้ guard 'user' จะถูก redirect ไปหน้า login
        $this->middleware(['auth:user', 'role:ADMIN,STAFF']);
    }

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

            // ตรวจสอบว่าเวลาจองอยู่ในช่วงเปิด–ปิดร้าน
            $openAt  = Carbon::parse($today . ' ' . $settings->open_time);
            $closeAt = Carbon::parse($today . ' ' . $settings->close_time);
            $now     = Carbon::now($settings->timezone ?? 'Asia/Bangkok');

            // กันไม่ให้จองก่อนร้านเปิด
            if ($startAt->lt($openAt)) {
                Alert::error(
                    'ไม่สามารถจองได้',
                    'เวลาที่เลือกก่อนเวลาเปิดร้าน (' . $settings->open_time . ')'
                );
                return redirect('reservations/adding')->withInput();
            }

            // กันไม่ให้จองเกินเวลาปิด
            if ($endAt->gt($closeAt)) {
                Alert::error(
                    'ไม่สามารถจองได้',
                    'เวลาที่เลือกเกินเวลาปิดร้าน (' . $settings->close_time . ') ' .
                        '(กรุณาจองให้เสร็จสิ้นก่อนเวลาปิดร้าน และต้องจองล่วงหน้าอย่างน้อย ' . $settings->cut_off_minutes . ' นาที)'
                );
                return redirect('reservations/adding')->withInput();
            }

            // กันไม่ให้จอง เวลาที่ผ่านมาแล้ว / ใกล้เกินไป
            if ($startAt->lt($now->copy()->addMinutes($settings->cut_off_minutes))) {
                $nextAvailable = $now->copy()->addMinutes($settings->cut_off_minutes)->format('H:i');
                Alert::error(
                    'ไม่สามารถจองได้',
                    'เวลาที่เลือกผ่านไปแล้วหรือใกล้เกินไป (กรุณาจองเวลาหลัง ' . $nextAvailable . ')'
                );

                return redirect('reservations/adding')->withInput();
            }

            // ตรวจสอบว่าเวลาที่เลือกอยู่ในช่วงเวลาเปิด–ปิดร้าน
            if ($startAt->lt($openAt) || $endAt->gt($closeAt)) {
                Alert::error(
                    'ไม่สามารถจองได้',
                    'กรุณาเลือกเวลาภายในช่วง ' . $settings->open_time . ' - ' . $settings->close_time .
                        ' และต้องจองล่วงหน้าอย่างน้อย ' . $settings->cut_off_minutes . ' นาที'
                );
                return redirect('reservations/adding')->withInput();
            }



            // ตรวจสอบว่าลูกค้ามีจองอยู่แล้วหรือไม่
            $hasActive = ReservationModel::where('user_id', $request->input('user_id'))
                ->whereDate('start_at', $today)
                ->whereIn('status', ['CONFIRMED', 'SEATED'])
                ->exists();

            // ถ้าลูกค้ามีจองอยู่แล้ว
            if ($hasActive) {
                Alert::error('ไม่สามารถจองได้', 'ลูกค้านี้มีการจองที่ยังไม่เสร็จสิ้นอยู่แล้ว');
                return redirect('reservations/adding')->withInput();
            }

            // ถ้าผ่านทุก validation
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

            $openAt  = Carbon::parse($today . ' ' . $settings->open_time);
            $closeAt = Carbon::parse($today . ' ' . $settings->close_time);
            $now     = Carbon::now($settings->timezone ?? 'Asia/Bangkok');


            // กันไม่ให้จองก่อนร้านเปิด
            if ($startAt->lt($openAt)) {
                Alert::error(
                    'ไม่สามารถแก้ไขได้',
                    'เวลาที่เลือกก่อนเวลาเปิดร้าน (' . $settings->open_time . ')'
                );
                return redirect('reservations/' . $id)->withInput();
            }

            // กันไม่ให้จองเกินเวลาปิด
            if ($endAt->gt($closeAt)) {
                Alert::error(
                    'ไม่สามารถแก้ไขได้',
                    'เวลาที่เลือกเกินเวลาปิดร้าน (' . $settings->close_time . ') ' .
                        '(กรุณาจองให้เสร็จสิ้นก่อนเวลาปิดร้าน และต้องจองล่วงหน้าอย่างน้อย ' . $settings->cut_off_minutes . ' นาที)'
                );
                return redirect('reservations/' . $id)->withInput();
            }

            // กันไม่ให้จองเวลาที่ผ่านมาแล้ว
            if ($startAt->lt($now->copy()->addMinutes($settings->cut_off_minutes))) {
                $nextAvailable = $now->copy()
                    ->addMinutes($settings->cut_off_minutes)
                    ->ceilMinute($settings->slot_granularity_minutes);

                Alert::error(
                    'ไม่สามารถแก้ไขได้',
                    'เวลาที่เลือกผ่านไปแล้วหรือใกล้เกินไป (กรุณาเลือกเวลาหลัง ' . $nextAvailable->format('H:i') . ')'
                );
                return redirect('reservations/' . $id)->withInput();
            }

            // ตรวจสอบว่าอยู่นอกช่วงเวลาเปิด–ปิดร้าน
            if ($startAt->lt($openAt) || $endAt->gt($closeAt)) {
                Alert::error(
                    'ไม่สามารถแก้ไขได้',
                    'กรุณาเลือกเวลาภายในช่วง ' . $settings->open_time . ' - ' . $settings->close_time .
                        ' และต้องจองล่วงหน้าอย่างน้อย ' . $settings->cut_off_minutes . ' นาที'
                );
                return redirect('reservations/' . $id)->withInput();
            }

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
