<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ReservationModel;
use App\Models\StoreSettingModel;
use RealRashid\SweetAlert\Facades\Alert;
use Carbon\Carbon;

class FrontReservationController extends Controller
{
    // หน้าแบบฟอร์ม
    public function form()
    {
        $settings = StoreSettingModel::firstOrFail();
        $tz       = $settings->timezone ?: 'Asia/Bangkok';

        $today   = Carbon::today($tz)->format('Y-m-d');
        $openAt  = Carbon::parse($today . ' ' . $settings->open_time, $tz);
        $closeAt = Carbon::parse($today . ' ' . $settings->close_time, $tz);

        $duration = (int) $settings->default_duration_minutes;
        $gran     = (int) $settings->slot_granularity_minutes;

        // สร้าง time slots แบบง่าย ๆ (ให้จบก่อนเวลาปิด)
        $slots = [];
        $lastStart = $closeAt->copy()->subMinutes($duration);
        for ($t = $openAt->copy(); $t->lte($lastStart); $t->addMinutes($gran)) {
            $slots[] = $t->format('H:i');
        }

        return view('home.reserve', compact('settings', 'slots'));
    }

    // บันทึกการจอง (วันนี้)
    public function create(Request $request)
    {
        $request->validate([
            'party_size' => 'required|integer|min:1|max:10',
            'seat_type'  => 'required|in:BAR,TABLE',
            'start_time' => 'required|date_format:H:i',
        ]);

        $settings = StoreSettingModel::firstOrFail();
        $tz       = $settings->timezone ?: 'Asia/Bangkok';

        $today    = Carbon::today($tz)->format('Y-m-d');
        $startAt  = Carbon::parse($today . ' ' . $request->start_time, $tz);
        $endAt    = $startAt->copy()->addMinutes($settings->default_duration_minutes);
        $openAt   = Carbon::parse($today . ' ' . $settings->open_time, $tz);
        $closeAt  = Carbon::parse($today . ' ' . $settings->close_time, $tz);
        $now      = Carbon::now($tz);

        // ==== เช็กเหมือนฝั่งแอดมิน ====
        // เช็กจำนวนคนตามประเภทที่นั่ง
        $maxBySeat = ['BAR' => 1, 'TABLE' => 10];
        $seat = $request->seat_type;
        $max  = $maxBySeat[$seat] ?? 10;

        if ((int)$request->party_size > $max) {
            Alert::error(
                'ไม่สามารถจองได้',
                "ที่นั่ง {$seat} รองรับสูงสุด {$max} คน"
            );
            return back()->withInput();
        }

        // ก่อนร้านเปิด
        if ($startAt->lt($openAt)) {
            Alert::error('ไม่สามารถจองได้', 'เวลาที่เลือกก่อนเวลาเปิดร้าน (' . $settings->open_time . ')');
            return back()->withInput();
        }

        // เกินเวลาปิด (ต้องจบก่อนปิด)
        if ($endAt->gt($closeAt)) {
            Alert::error(
                'ไม่สามารถจองได้',
                'เวลาที่เลือกเกินเวลาปิดร้าน (' . $settings->close_time . ') ' .
                    '(กรุณาจองให้เสร็จสิ้นก่อนเวลาปิดร้าน และต้องจองล่วงหน้าอย่างน้อย ' . $settings->cut_off_minutes . ' นาที)'
            );
            return back()->withInput();
        }

        // ผ่านไปแล้ว/ใกล้เกินไป (cut-off)
        if ($startAt->lt($now->copy()->addMinutes($settings->cut_off_minutes))) {
            $nextAvailable = $now->copy()->addMinutes($settings->cut_off_minutes)->format('H:i');
            Alert::error(
                'ไม่สามารถจองได้',
                'เวลาที่เลือกผ่านไปแล้วหรือใกล้เกินไป (กรุณาจองเวลาหลัง ' . $nextAvailable . ')'
            );
            return back()->withInput();
        }

        // ย้ำให้อยู่ในช่วงเปิด–ปิด (เหมือนหลังบ้านมีเช็กซ้ำ)
        if ($startAt->lt($openAt) || $endAt->gt($closeAt)) {
            Alert::error(
                'ไม่สามารถจองได้',
                'กรุณาเลือกเวลาภายในช่วง ' . $settings->open_time . ' - ' . $settings->close_time .
                    ' และต้องจองล่วงหน้าอย่างน้อย ' . $settings->cut_off_minutes . ' นาที'
            );
            return back()->withInput();
        }

        // กันการจองซ้ำของผู้ใช้คนเดิมในวันนี้ (เหมือนหลังบ้าน)
        $hasActive = ReservationModel::where('user_id', Auth::user()->user_id)
            ->whereDate('start_at', $today)
            ->whereIn('status', ['CONFIRMED', 'SEATED'])
            ->exists();

        if ($hasActive) {
            Alert::error('ไม่สามารถจองได้', 'คุณมีการจองที่ยังไม่เสร็จสิ้นอยู่แล้ว');
            return back()->withInput();
        }

        // บันทึก
        ReservationModel::create([
            'user_id'    => Auth::user()->user_id,
            'table_id'   => null,
            'party_size' => (int) $request->party_size,
            'seat_type'  => $request->seat_type,
            'start_at'   => $startAt,
            'end_at'     => $endAt,
            'status'     => 'CONFIRMED',
        ]);

        Alert::success('จองสำเร็จ', 'ระบบยืนยันการจองของคุณแล้ว');
        return redirect()->route('reserve.form');
    }
}
