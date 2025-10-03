{{-- resources/views/home/reserve.blade.php --}}
@extends('frontend')

@section('css_before')
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
@endsection

@section('navbar')
@endsection

@section('body')
    @php
        $tz = $settings->timezone ?? 'Asia/Bangkok';
        $duration = (int) ($settings->default_duration_minutes ?? 60);
        $allowAfter = \Carbon\Carbon::now($tz)
            ->addMinutes($settings->cut_off_minutes ?? 0)
            ->format('H:i');

        $groups = collect($slots ?? [])->groupBy(fn($t) => substr($t, 0, 2));
        $usableHours = $groups
            ->filter(fn($items) => collect($items)->contains(fn($t) => $t > $allowAfter))
            ->keys()
            ->values();
        $hasUsable = $usableHours->isNotEmpty();

        $initialTime = old('start_time');
        if (!$initialTime || $initialTime <= $allowAfter || !collect($slots)->contains($initialTime)) {
            $initialTime = collect($slots)->first(fn($t) => $t > $allowAfter);
        }
        $initialHour = $initialTime ? substr($initialTime, 0, 2) : $usableHours[0] ?? null;

        $oldSeat = old('seat_type', 'TABLE');
        $initQty = (int) old('party_size', $oldSeat === 'BAR' ? 1 : 1);

        // ใช้บอก JS ว่าเวลาที่ checked มาจากระบบ auto-select ไม่ใช่ผู้ใช้
        $initialTimeOld = old('start_time');
        $initialTimeFromOld =
            $initialTimeOld && $initialTimeOld > $allowAfter && collect($slots)->contains($initialTimeOld);
    @endphp

    <div class="container reserve-page">
        {{-- HERO --}}
        <div class="hero mb-3">
            <h2 class="hero-title">
                <i class="bi bi-calendar-check"></i>
                จองโต๊ะวันที่ {{ \Carbon\Carbon::now($tz)->format('d/m/Y') }}
            </h2>
            <p class="lead-mini">เลือกที่นั่ง → จำนวนลูกค้า → เวลา → ยืนยันการจอง</p>
            <div class="chips mt-1">
                <span class="chip">
                    <i class="bi bi-door-open"></i> เปิด–ปิด:
                    <b>{{ $settings->open_time }}</b>–<b>{{ $settings->close_time }}</b>
                </span>
                {{-- <span class="chip">
                    <i class="bi bi-hourglass-split"></i> ต่อรอบ: <b>{{ $duration }}</b> นาที
                </span>
                @if (($settings->cut_off_minutes ?? 0) > 0)
                    <span class="chip">
                        <i class="bi bi-shield-check"></i> ตัดรอบล่วงหน้า:
                        <b>{{ (int) ($settings->cut_off_minutes ?? 0) }}</b> นาที
                    </span>
                @endif --}}
            </div>
        </div>

        <div class="grid">
            {{-- MAIN --}}
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger mb-3" role="alert" aria-live="polite">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $e)
                                    <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (!$hasUsable)
                        <div class="alert alert-warning mb-0" role="status">
                            วันนี้เลยเวลาตัดรอบหรือไม่มีช่วงเวลาที่จองได้
                        </div>
                    @else
                        {{-- STEPPER --}}
                        <div class="stepper" id="stepper" aria-label="ขั้นตอนการจอง">
                            <div class="step is-active" data-step="1" aria-current="step">
                                <span class="dot">1</span><span class="label">ประเภทที่นั่ง</span>
                            </div>
                            <div class="rail" aria-hidden="true"></div>
                            <div class="step" data-step="2">
                                <span class="dot">2</span><span class="label">จำนวนลูกค้า</span>
                            </div>
                            <div class="rail" aria-hidden="true"></div>
                            <div class="step" data-step="3">
                                <span class="dot">3</span><span class="label">เลือกเวลา</span>
                            </div>
                            <div class="rail" aria-hidden="true"></div>
                            <div class="step" data-step="4">
                                <span class="dot">4</span><span class="label">ยืนยัน</span>
                            </div>
                        </div>

                        {{-- FORM (Multi-steps) --}}
                        <form id="reserveForm" method="POST" action="{{ route('reserve.create') }}" novalidate
                            data-allow-after="{{ $allowAfter }}" data-duration="{{ $duration }}">
                            @csrf

                            {{-- STEP 1 --}}
                            <section class="step-pane is-active" data-step-pane="1" aria-labelledby="step1">
                                <h3 id="step1" class="title">ประเภทที่นั่ง *</h3>

                                <div class="seg seg-cards" role="radiogroup" aria-label="เลือกประเภทที่นั่ง">
                                    <input type="radio" class="btn-check" name="seat_type" id="seatTable" value="TABLE"
                                        {{ $oldSeat == 'TABLE' ? 'checked' : '' }} required>
                                    <label class="seg-btn seg-card" for="seatTable" title="นั่งโต๊ะปกติ">
                                        <i class="bi bi-grid-1x2"></i>
                                        <span class="seg-card-text">
                                            <span><strong>TABLE</strong> • สูงสุด 10 คน</span>
                                        </span>
                                    </label>

                                    <input type="radio" class="btn-check" name="seat_type" id="seatBar" value="BAR"
                                        {{ $oldSeat == 'BAR' ? 'checked' : '' }} required>
                                    <label class="seg-btn seg-card" for="seatBar" title="ที่นั่งบาร์">
                                        <i class="bi bi-cup-straw"></i>
                                        <span class="seg-card-text">
                                            <span><strong>BAR</strong> • สูงสุด 1 คน</span>
                                        </span>
                                    </label>
                                </div>
                            </section>

                            {{-- STEP 2 --}}
                            <section class="step-pane" data-step-pane="2" aria-labelledby="step2">
                                <h3 id="step2" class="title">จำนวนลูกค้า *</h3>

                                <div class="qty-chips">
                                    @foreach ([1, 2, 3, 4, 5, 6, 8, 10] as $q)
                                        <button type="button" class="pill btn-sm"
                                            data-qty-chip="{{ $q }}">{{ $q }} คน</button>
                                    @endforeach
                                </div>

                                <div class="qty" aria-label="จำนวนลูกค้า">
                                    <button class="qty-btn" type="button" data-qty="-1" aria-label="ลด">
                                        <i class="bi bi-dash-lg"></i>
                                    </button>
                                    <input type="number" id="party_size" name="party_size" class="form-control qty-input"
                                        min="1" max="{{ $oldSeat === 'BAR' ? 1 : 10 }}"
                                        value="{{ $initQty }}" required inputmode="numeric" pattern="[0-9]*"
                                        aria-describedby="capHint">
                                    <button class="qty-btn" type="button" data-qty="+1" aria-label="เพิ่ม">
                                        <i class="bi bi-plus-lg"></i>
                                    </button>
                                </div>

                                <div class="help" id="capHint">
                                    {{ $oldSeat === 'BAR' ? 'BAR รองรับสูงสุด 1 คน' : 'TABLE รองรับสูงสุด 10 คน' }}
                                </div>
                            </section>

                            {{-- STEP 3 --}}
                            <section class="step-pane" data-step-pane="3" aria-labelledby="step3">
                                <div class="title d-flex align-items-center justify-content-between">
                                    <h3 id="step3" class="m-0">เวลาเริ่ม *</h3>
                                    <span id="preview" class="preview">
                                        @if ($initialTime)
                                            {{ $initialTime }} –
                                            {{ \Carbon\Carbon::createFromFormat('H:i', $initialTime, $tz)->addMinutes($duration)->format('H:i') }}
                                        @else
                                            <span class="text-muted" style="font-weight:600">เลือกเวลา</span>
                                        @endif
                                    </span>
                                </div>

                                <div class="time-helpers">
                                    <button type="button" id="btnPickEarliest" class="btn-ghost btn-sm mb-3">
                                        <i class="bi bi-lightning-charge"></i> เลือกเวลาที่เร็วที่สุด
                                    </button>
                                </div>

                                {{-- hours tabs --}}
                                <div class="hours-wrap" aria-label="เลือกชั่วโมง">
                                    <button type="button" class="hours-arrow left" id="hPrev"
                                        aria-label="เลื่อนชั่วโมงก่อนหน้า">
                                        <i class="bi bi-chevron-left"></i>
                                    </button>

                                    <div class="hours" id="hours" role="tablist">
                                        @foreach ($usableHours as $h)
                                            @php $count = collect($groups[$h])->filter(fn($t)=> $t > $allowAfter)->count(); @endphp
                                            <input type="radio" class="btn-check" name="hour_tab"
                                                id="h-{{ $h }}" {{ $h === $initialHour ? 'checked' : '' }}>
                                            <label class="hour" for="h-{{ $h }}"
                                                data-hour-tab="{{ $h }}" role="tab"
                                                aria-selected="{{ $h === $initialHour ? 'true' : 'false' }}">
                                                <i class="bi bi-clock-history"></i> {{ $h }}:00
                                                <small>×{{ $count }}</small>
                                            </label>
                                        @endforeach
                                    </div>

                                    <button type="button" class="hours-arrow right" id="hNext"
                                        aria-label="เลื่อนชั่วโมงถัดไป">
                                        <i class="bi bi-chevron-right"></i>
                                    </button>

                                    <div class="hours-fade"></div>
                                    <div class="hours-fade right"></div>
                                </div>

                                {{-- slots --}}
                                <div class="slots" id="slots">
                                    @foreach ($slots as $i => $t)
                                        @php
                                            $h = substr($t, 0, 2);
                                            if (!$usableHours->contains($h)) {
                                                continue;
                                            }
                                            $disabled = $t <= $allowAfter;
                                            $autoSelected = !$initialTimeFromOld && $initialTime === $t;
                                        @endphp
                                        <input type="radio" class="btn-check" name="start_time"
                                            id="slot-{{ $i }}" value="{{ $t }}"
                                            data-hour="{{ $h }}" {{ $disabled ? 'disabled' : '' }}
                                            {{ $initialTime === $t ? 'checked' : '' }}
                                            {{ $autoSelected ? 'data-autoselect=1' : '' }} required>
                                        <label class="slot" for="slot-{{ $i }}"
                                            data-hour="{{ $h }}"
                                            title="{{ $disabled ? 'เลยเวลาตัดรอบ' : 'เลือกเวลา' }}"
                                            {{ $disabled ? 'aria-disabled=true' : '' }}>
                                            <i class="bi bi-clock"></i> {{ $t }}
                                        </label>
                                    @endforeach
                                </div>

                                <div id="noSlotsHour" class="alert alert-warning mt-2 d-none" role="status">
                                    ชั่วโมงนี้ไม่มีช่วงเวลาที่จองได้
                                </div>
                                <div class="help">ระบบคำนวณเวลาสิ้นสุดอัตโนมัติ ({{ $duration }} นาที)</div>
                            </section>

                            {{-- STEP 4 --}}
                            <section class="step-pane" data-step-pane="4" aria-labelledby="step4">
                                <h3 id="step4" class="title">ยืนยันการจอง</h3>
                                <div class="review-grid">
                                    <div class="review-item">
                                        <span class="muted"><i class="bi bi-people"></i> จำนวนลูกค้า</span>
                                        <span class="review-right">
                                            <b id="sumP">{{ $initQty }} คน</b>
                                            <button type="button" class="link-edit" data-goto-step="2">แก้ไข</button>
                                        </span>
                                    </div>
                                    <div class="review-item">
                                        <span class="muted"><i class="bi bi-cup-hot"></i> ที่นั่ง</span>
                                        <span class="review-right">
                                            <b id="sumS">{{ $oldSeat }}</b>
                                            <button type="button" class="link-edit" data-goto-step="1">แก้ไข</button>
                                        </span>
                                    </div>
                                    <div class="review-item">
                                        <span class="muted"><i class="bi bi-clock"></i> เวลา</span>
                                        <span class="review-right">
                                            <b id="sumT">{{ $initialTime ?? '—' }}</b>
                                            <button type="button" class="link-edit" data-goto-step="3">แก้ไข</button>
                                        </span>
                                    </div>
                                </div>
                                <p class="help">ตรวจสอบความถูกต้องก่อนยืนยัน</p>
                            </section>

                            {{-- ACTIONS --}}
                            <div class="step-actions">
                                <button type="button" class="btn-ghost" id="btnPrevStep">
                                    <i class="bi bi-arrow-left"></i> ย้อนกลับ
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn-brand" id="btnNextStep">
                                        <span class="btn-text">ถัดไป</span>
                                        <span class="spinner d-none" aria-hidden="true"></span>
                                    </button>
                                    <button class="btn-brand d-none" type="submit" id="btnSubmit">
                                        <span class="btn-text"><i class="bi bi-check2-circle"></i> ยืนยันการจอง</span>
                                        <span class="spinner d-none" aria-hidden="true"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    @endif
                </div>
            </div>

            {{-- SIDE SUMMARY --}}
            <aside class="card sticky d-none d-lg-block" aria-label="สรุปการจอง">
                <div class="summary-h">สรุปการจอง</div>
                <div class="summary-b">
                    <div class="sumrow">
                        <span class="text-muted"><i class="bi bi-people"></i> จำนวนลูกค้า</span>
                        <strong id="sumP_side">{{ $initQty }} คน</strong>
                    </div>
                    <div class="sumrow">
                        <span class="text-muted"><i class="bi bi-cup-hot"></i> ที่นั่ง</span>
                        <strong id="sumS_side">{{ $oldSeat }}</strong>
                    </div>
                    <div class="sumrow">
                        <span class="text-muted"><i class="bi bi-clock"></i> เวลา</span>
                        <strong id="sumT_side">{{ $initialTime ?? '—' }}</strong>
                    </div>
                </div>
            </aside>
        </div>

        {{-- MOBILE CTA (สโคปใน .reserve-page) --}}
        <div class="mobile-cta d-lg-none" id="mobileCta" hidden>
            <button type="button" class="icon-btn" id="btnMobileBack" aria-label="ย้อนกลับ">
                <i class="bi bi-arrow-left"></i>
            </button>

            <div class="mini">
                <span><i class="bi bi-people"></i> <b id="mParty">{{ $initQty }}</b> คน</span>
                <span><i class="bi bi-cup-hot"></i> <b id="mSeat">{{ $oldSeat }}</b></span>
                <span><i class="bi bi-clock"></i> <b id="mTime">{{ $initialTime ?? '—' }}</b></span>
            </div>

            <button type="button" class="icon-btn" id="btnMobilePrimary" aria-label="ต่อไป">
                <i class="bi bi-arrow-right"></i>
            </button>
        </div>

    </div>
@endsection

@section('footer')
@endsection

@section('js_before')
    @include('sweetalert::alert')
    <script src="{{ asset('js/home.js') }}"></script>
@endsection
