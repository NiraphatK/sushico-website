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
    /** ---------- Helpers: รูปแบบ Alert ---------- */

    /**
     * แสดง Error เป็น SweetAlert (modal) พร้อม bullet-list ที่อ่านง่าย
     * Usage: return $this->errorModal('หัวข้อ', ['บรรทัด1', 'บรรทัด2']);
     */
    private function errorModal(string $title, array $lines)
    {
        $html = '<ul style="text-align:left;margin:.4rem 0 0;padding-left:1.1rem">';
        foreach ($lines as $line) {
            $html .= '<li>' . $line . '</li>';
        }
        $html .= '</ul>';

        Alert::html($title, $html, 'error')
            ->showCloseButton()
            ->showConfirmButton('ตกลง', '#1A3636');

        return back()->withInput();
    }

    /**
     * แสดง Toast สำเร็จ (มุมขวาบน พร้อม progress bar)
     * Usage: $this->successToast('จองสำเร็จ ', 'ระบบยืนยันการจองของคุณแล้ว');
     */
    private function successToast(string $title, ?string $subtitle = null): void
    {
        $text = $subtitle ? ' — ' . $subtitle : '';
        Alert::toast($title . $text, 'success')
            ->position('top-end')
            ->autoClose(4000)
            ->timerProgressBar();
    }

    /**
     * สร้าง time slots ให้จบก่อนเวลาปิด
     */
    private function buildSlots(Carbon $openAt, Carbon $closeAt, int $duration, int $gran): array
    {
        $slots = [];
        $lastStart = $closeAt->copy()->subMinutes($duration);
        for ($t = $openAt->copy(); $t->lte($lastStart); $t->addMinutes($gran)) {
            $slots[] = $t->format('H:i');
        }
        return $slots;
    }

    /** ---------- หน้าแบบฟอร์ม ---------- */
    public function form()
    {
        $settings = StoreSettingModel::firstOrFail();
        $tz       = $settings->timezone ?: 'Asia/Bangkok';

        $today   = Carbon::today($tz)->format('Y-m-d');
        $openAt  = Carbon::parse("$today {$settings->open_time}", $tz);
        $closeAt = Carbon::parse("$today {$settings->close_time}", $tz);

        $slots = $this->buildSlots(
            $openAt,
            $closeAt,
            (int) $settings->default_duration_minutes,
            (int) $settings->slot_granularity_minutes
        );

        return view('home.reserve', compact('settings', 'slots'));
    }

    /** ---------- บันทึกการจอง (วันนี้) ---------- */
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
        $startAt  = Carbon::parse("$today {$request->start_time}", $tz);
        $endAt    = $startAt->copy()->addMinutes($settings->default_duration_minutes);
        $openAt   = Carbon::parse("$today {$settings->open_time}", $tz);
        $closeAt  = Carbon::parse("$today {$settings->close_time}", $tz);
        $now      = Carbon::now($tz);

        // Capacity ตามประเภทที่นั่ง
        $maxBySeat = ['BAR' => 1, 'TABLE' => 10];
        $seat = $request->seat_type;
        $max  = $maxBySeat[$seat] ?? 10;

        if ((int)$request->party_size > $max) {
            return $this->errorModal('ไม่สามารถจองได้', [
                "ที่นั่ง <b>{$seat}</b> รองรับสูงสุด <b>{$max}</b> คน",
                'กรุณาลดจำนวนคนหรือเปลี่ยนประเภทที่นั่ง',
            ]);
        }

        // ก่อนร้านเปิด
        if ($startAt->lt($openAt)) {
            return $this->errorModal('ไม่สามารถจองได้', [
                'เวลาที่เลือกอยู่ก่อนเวลาเปิดร้าน',
                'เวลาเปิดวันนี้: <b>' . $settings->open_time . '</b>',
            ]);
        }

        // ต้องจบก่อนเวลาปิด
        if ($endAt->gt($closeAt)) {
            return $this->errorModal('ไม่สามารถจองได้', [
                'เวลาที่เลือกทำให้สิ้นสุดเกินเวลาปิดร้าน',
                'เวลาปิดวันนี้: <b>' . $settings->close_time . '</b>',
                'โปรดเลือกเวลาให้การรับประทานจบก่อนร้านปิด',
            ]);
        }

        // Cut-off (ใกล้เกินไป)
        $cutoffLimit = $now->copy()->addMinutes($settings->cut_off_minutes);
        if ($startAt->lt($cutoffLimit)) {
            return $this->errorModal('ไม่สามารถจองได้', [
                'เวลาที่เลือกผ่านไปแล้วหรือใกล้เกินไป',
                'คุณต้องจองล่วงหน้าอย่างน้อย <b>' . $settings->cut_off_minutes . '</b> นาที',
                'เลือกเวลาหลัง <b>' . $cutoffLimit->format('H:i') . '</b> เป็นต้นไป',
            ]);
        }

        // ป้องกันการจองซ้ำในวันนี้
        $hasActive = ReservationModel::where('user_id', Auth::user()->user_id)
            ->whereDate('start_at', $today)
            ->whereIn('status', ['CONFIRMED', 'SEATED'])
            ->exists();

        if ($hasActive) {
            return $this->errorModal('ไม่สามารถจองได้', [
                'คุณมีการจองที่ยังไม่เสร็จสิ้นอยู่แล้วในวันนี้',
                'หากต้องการจองเพิ่ม โปรดติดต่อพนักงาน',
            ]);
        }

        // บันทึก
        ReservationModel::create([
            'user_id'    => Auth::user()->user_id,
            'table_id'   => null,
            'party_size' => (int) $request->party_size,
            'seat_type'  => $seat,
            'start_at'   => $startAt,
            'end_at'     => $endAt,
            'status'     => 'CONFIRMED',
        ]);

        // Toast สำเร็จ
        $this->successToast('จองสำเร็จ ', 'ระบบยืนยันการจองของคุณแล้ว');

        return redirect()->route('reserve.form');
    }
}
