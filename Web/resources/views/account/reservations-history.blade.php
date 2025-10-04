{{-- resources/views/account/reservations-history.blade.php --}}
@extends('frontend')

@section('css_before')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="{{ asset('css/reservations-history.css') }}">
@endsection

@section('body')
    @php
        use Carbon\Carbon;

        $tzLocal = $tz ?? 'Asia/Bangkok';
        $now = Carbon::now($tzLocal);
        $cutOffMin = $cutoff ?? 30;
        $activeStatus = $filters['status'] ?? null;

        // Map สถานะให้ใช้ซ้ำได้ (ข้อความ+คลาส)
        $statusMap = [
            'CONFIRMED' => ['text' => 'ยืนยันแล้ว', 'class' => 'st-confirmed', 'icon' => 'bi-patch-check'],
            'SEATED' => ['text' => 'เช็กอินแล้ว', 'class' => 'st-seated', 'icon' => 'bi-person-walking'],
            'COMPLETED' => ['text' => 'เสร็จสิ้น', 'class' => 'st-completed', 'icon' => 'bi-check2-circle'],
            'CANCELLED' => ['text' => 'ยกเลิก', 'class' => 'st-cancelled', 'icon' => 'bi-x-circle'],
            'NO_SHOW' => ['text' => 'ไม่มา', 'class' => 'st-noshow', 'icon' => 'bi-exclamation-triangle'],
        ];

        // ฟังก์ชันช่วย format วันที่ในหัวกลุ่ม
        $dateBadge = function (Carbon $d) use ($now) {
            if ($d->isSameDay($now)) {
                return 'Today';
            }
            if ($d->isSameDay($now->copy()->subDay())) {
                return 'Yesterday';
            }
            if ($d->greaterThan($now)) {
                return 'Upcoming';
            }
            return null;
        };
    @endphp

    <div class="history-page">
        <div class="shell">

            {{-- Hero --}}
            <header class="hero" role="banner" aria-labelledby="heroTitle">
                <div class="hero-row">
                    <div class="hero-titles">
                        <h1 class="hero-title" id="heroTitle">
                            <i class="bi bi-calendar-heart" aria-hidden="true"></i>
                            ประวัติการจองของฉัน
                        </h1>
                        <p class="hero-sub">
                            ตรวจสอบสถานะ ดูรายละเอียด และยกเลิกการจอง
                            <span class="muted"> (ภายใน {{ $cutOffMin }} นาทีล่วงหน้า)</span>
                        </p>
                    </div>

                    {{-- Quick actions --}}
                    <div class="hero-actions">
                        <button type="button" class="btn-ghost btn-sm" id="jumpToday" hidden>
                            <i class="bi bi-skip-forward-circle me-1" aria-hidden="true"></i> ข้ามไปวันนี้
                        </button>
                        <a href="{{ route('reserve.form') }}" class="btn-brand btn-sm">
                            <i class="bi bi-plus-circle me-1" aria-hidden="true"></i> เริ่มจองโต๊ะ
                        </a>
                    </div>
                </div>

                {{-- Segmented quick filters --}}
                <nav class="seg seg-scroller mt-2 pt-1 pb-2" aria-label="Reservation status quick filters" id="segStatus">
                    <a class="seg-pill {{ $activeStatus ? '' : 'active' }}" href="{{ route('reservations.history') }}"
                        data-status="">
                        <i class="bi bi-layers" aria-hidden="true"></i><span>ทั้งหมด</span>
                    </a>
                    @foreach (['CONFIRMED' => 'ยืนยันแล้ว', 'SEATED' => 'เช็กอินแล้ว', 'COMPLETED' => 'เสร็จสิ้น', 'CANCELLED' => 'ยกเลิก', 'NO_SHOW' => 'ไม่มา'] as $val => $label)
                        @php $icon = $statusMap[$val]['icon'] ?? 'bi-dot'; @endphp
                        <a class="seg-pill {{ $activeStatus === $val ? 'active' : '' }}"
                            href="{{ route('reservations.history', ['status' => $val]) }}"
                            data-status="{{ $val }}">
                            <i class="bi {{ $icon }}" aria-hidden="true"></i><span>{{ $label }}</span>
                        </a>
                    @endforeach
                </nav>

            </header>

            {{-- Layout --}}
            <div class="grid">

                {{-- Sidebar filters --}}
                <aside class="filters" aria-label="ตัวกรองประวัติการจอง">
                    <div class="filter-card">
                        <details class="filter-compact" open>
                            <summary>
                                <span><i class="bi bi-funnel me-1" aria-hidden="true"></i> ตัวกรอง</span>
                                <i class="bi bi-chevron-down expand-icon" aria-hidden="true"></i>
                            </summary>

                            <form method="get" action="{{ route('reservations.history') }}" class="mt-2"
                                id="filterForm" onsubmit="return false">
                                <div class="filter-grid">
                                    <div class="w-100">
                                        <label class="form-label" for="filter-status">สถานะ</label>
                                        <select id="filter-status" name="status" class="form-select">
                                            <option value="">— ทั้งหมด —</option>
                                            @foreach (['CONFIRMED' => 'Confirmed', 'SEATED' => 'Seated', 'COMPLETED' => 'Completed', 'CANCELLED' => 'Cancelled', 'NO_SHOW' => 'No Show'] as $val => $txt)
                                                <option value="{{ $val }}" @selected(($filters['status'] ?? '') === $val)>
                                                    {{ $txt }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="w-100">
                                        <label class="form-label" for="filter-seat">ประเภทที่นั่ง</label>
                                        @php $seatSel = ($filters['seat_type'] ?? $filters['seatType'] ?? ''); @endphp
                                        <select id="filter-seat" name="seat_type" class="form-select">
                                            <option value="">— ทั้งหมด —</option>
                                            <option value="BAR" @selected($seatSel === 'BAR')>BAR</option>
                                            <option value="TABLE" @selected($seatSel === 'TABLE')>TABLE</option>
                                        </select>
                                    </div>

                                    <div class="overflow-hidden">
                                        <label class="form-label" for="filter-from">ตั้งแต่วันที่</label>
                                        <input id="filter-from" type="date" name="from"
                                            value="{{ $filters['from'] ?? '' }}" class="form-control" inputmode="none">
                                    </div>

                                    <div class="overflow-hidden">
                                        <label class="form-label" for="filter-to">ถึงวันที่</label>
                                        <input id="filter-to" type="date" name="to"
                                            value="{{ $filters['to'] ?? '' }}" class="form-control" inputmode="none">
                                    </div>
                                </div>

                                {{-- Presets --}}
                                @php
                                    $today = $now->toDateString();
                                    $yesterday = $now->copy()->subDay()->toDateString();
                                    $last7From = $now->copy()->subDays(6)->toDateString();
                                    $monthStart = $now->copy()->startOfMonth()->toDateString();
                                    $monthEnd = $now->copy()->endOfMonth()->toDateString();
                                    $yearStart = $now->copy()->startOfYear()->toDateString();
                                    $yearEnd = $now->copy()->endOfYear()->toDateString();

                                    $keep = array_filter(
                                        [
                                            'status' => $filters['status'] ?? null,
                                            'seat_type' => $seatSel ?: null,
                                        ],
                                        fn($v) => filled($v),
                                    );

                                    $presets = [
                                        'วันนี้' => $keep + ['from' => $today, 'to' => $today],
                                        'เมื่อวาน' => $keep + ['from' => $yesterday, 'to' => $yesterday],
                                        '7 วันล่าสุด' => $keep + ['from' => $last7From, 'to' => $today],
                                        'เดือนนี้' => $keep + ['from' => $monthStart, 'to' => $monthEnd],
                                        'ปีนี้' => $keep + ['from' => $yearStart, 'to' => $yearEnd],
                                    ];
                                @endphp

                                <div class="preset-row">
                                    @foreach ($presets as $label => $params)
                                        <a class="btn-ghost btn-sm"
                                            href="{{ route('reservations.history', $params) }}">{{ $label }}</a>
                                    @endforeach
                                </div>

                                {{-- Client-only toggles --}}
                                <div class="client-toggles" aria-label="ตัวเลือกการแสดงผลแบบรวดเร็ว">
                                    <label class="chk">
                                        <input type="checkbox" id="toggleOnlyCancellable">
                                        <span>เฉพาะรายการที่ <b>ยกเลิกได้</b></span>
                                    </label>
                                    <label class="chk">
                                        <input type="checkbox" id="toggleHideCancelled">
                                        <span>ซ่อน <b>ยกเลิก</b> / <b>ไม่มา</b></span>
                                    </label>
                                </div>

                                <div class="filter-actions">
                                    <a href="{{ route('reservations.history') }}" id="clearFilters" data-clear
                                        class="btn-brand w-100">
                                        ล้างตัวกรอง
                                    </a>
                                </div>

                            </form>
                        </details>
                    </div>
                </aside>

                {{-- Main list (Timeline style) --}}
                <main class="list" role="main" aria-live="polite">
                    @if (($reservations->count() ?? 0) === 0)
                        {{-- empty server-side เหมือนเดิม --}}
                        <section class="empty" aria-label="ไม่มีรายการ">
                            <div class="mb-1"><i class="bi bi-calendar-x" style="font-size:1.35rem"
                                    aria-hidden="true"></i></div>
                            ยังไม่มีประวัติการจองตามตัวกรองนี้
                            <div class="mt-2">
                                <a href="{{ route('reserve.form') }}" class="btn-brand btn-sm">
                                    <i class="bi bi-plus-circle me-1" aria-hidden="true"></i> เริ่มจองโต๊ะ
                                </a>
                            </div>
                        </section>
                    @else
                        @php
                            $groups = $reservations
                                ->getCollection()
                                ->groupBy(fn($r) => Carbon::parse($r->start_at)->tz($tzLocal)->format('Y-m-d'));
                        @endphp

                        @foreach ($groups as $ymd => $items)
                            @php
                                $d = Carbon::parse($ymd, $tzLocal);
                                $dateLabel = $d->isoFormat('DD MMM YYYY');
                                $badge = $dateBadge($d);
                            @endphp

                            <section class="date-group" aria-label="{{ $dateLabel }}"
                                data-date="{{ $d->toDateString() }}">
                                <div class="date-head sticky-date">
                                    <span class="date-chip">
                                        <i class="bi bi-calendar-event me-1" aria-hidden="true"></i>{{ $dateLabel }}
                                    </span>
                                    @if ($badge)
                                        <span class="badge-day"
                                            data-badge="{{ $badge }}">{{ $badge }}</span>
                                    @endif
                                    <span class="date-count">{{ $items->count() }} รายการ</span>
                                </div>

                                <div class="group-items timeline">
                                    @foreach ($items as $res)
                                        @php
                                            $start = Carbon::parse($res->start_at)->tz($tzLocal);
                                            $end = Carbon::parse($res->end_at)->tz($tzLocal);
                                            $deadline = $start->copy()->subMinutes($cutOffMin);
                                            $minsLeft = $now->diffInMinutes($deadline, false);
                                            $cancellable = $res->status === 'CONFIRMED' && $minsLeft > 0;

                                            $meta = $statusMap[$res->status] ?? [
                                                'text' => $res->status,
                                                'class' => 'text-secondary',
                                                'icon' => 'bi-dot',
                                            ];
                                        @endphp

                                        <article class="res-card {{ $cancellable ? 'is-cancellable' : '' }}"
                                            data-status="{{ $res->status }}" data-seat="{{ $res->seat_type }}"
                                            {{-- ✅ เพิ่มไว้กรองตาม BAR/TABLE --}} data-cancellable="{{ $cancellable ? '1' : '0' }}"
                                            aria-label="การจองเวลา {{ $start->format('H:i') }}">
                                            {{-- Col 1: Time & Meta --}}
                                            <div class="res-left">
                                                <div class="res-time" aria-hidden="true">
                                                    <small>{{ $start->format('d/m') }}</small>
                                                    <b>{{ $start->format('H:i') }}</b>
                                                </div>
                                                <div>
                                                    <div class="meta-title">
                                                        {{ $start->format('H:i') }}–{{ $end->format('H:i') }}</div>
                                                    <div class="meta-sub">โซน
                                                        {{ $res->seat_type === 'BAR' ? 'บาร์' : 'โต๊ะนั่ง' }}</div>
                                                </div>
                                            </div>

                                            {{-- Col 2: Chips --}}
                                            <div class="res-pills" aria-label="รายละเอียด">
                                                <span
                                                    class="pill {{ $res->seat_type === 'BAR' ? 'pill-seat-bar' : 'pill-seat-table' }}">
                                                    <i class="bi bi-map" aria-hidden="true"></i> {{ $res->seat_type }}
                                                </span>
                                                <span class="pill pill-party">
                                                    <i class="bi bi-people" aria-hidden="true"></i>
                                                    {{ $res->party_size }} คน
                                                </span>
                                                @if ($res->table?->table_number)
                                                    <span class="pill pill-table">
                                                        <i class="bi bi-grid-3x3-gap" aria-hidden="true"></i> โต๊ะ
                                                        {{ $res->table->table_number }}
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Col 3: Status --}}
                                            <div class="res-status">
                                                <span class="st {{ $meta['class'] }}">
                                                    <i class="bi {{ $meta['icon'] }}" aria-hidden="true"></i>
                                                    {{ $meta['text'] }}
                                                </span>

                                                @if ($res->status === 'CONFIRMED')
                                                    <div class="deadline-hint" role="note">
                                                        @if ($minsLeft > 0)
                                                            <i class="bi bi-stopwatch" aria-hidden="true"></i>
                                                            เหลือสิทธิ์ยกเลิก <b>{{ (int) $minsLeft }}</b> นาที
                                                            <span class="muted"> (ถึง
                                                                {{ $deadline->format('H:i') }})</span>
                                                        @else
                                                            <i class="bi bi-stopwatch" aria-hidden="true"></i>
                                                            หมดเวลายกเลิกแล้ว
                                                            <span class="muted"> ({{ $deadline->format('H:i') }})</span>
                                                        @endif
                                                    </div>
                                                @endif

                                            </div>

                                            {{-- Col 4: Action (เหมือนเดิม) --}}
                                            <div class="res-action">
                                                <form id="cancel-form-{{ $res->reservation_id }}"
                                                    action="{{ route('reservations.cancel', $res->reservation_id) }}"
                                                    method="post" onsubmit="return false;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn-pill btn-danger btn"
                                                        @if (!$cancellable) disabled @endif
                                                        data-bs-toggle="tooltip"
                                                        data-bs-title="{{ $cancellable ? 'ยกเลิกการจอง' : 'เลยกำหนดยกเลิก หรือสถานะนี้ยกเลิกไม่ได้' }}"
                                                        onclick="confirmCancel({{ $res->reservation_id }}, '{{ $start->format('d/m H:i') }}', this)">
                                                        <i class="bi bi-x-octagon" aria-hidden="true"></i> ยกเลิก
                                                    </button>
                                                </form>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </section>
                        @endforeach

                        <div class="mt-3 pagination-wrap">
                            {{ $reservations->links() }}
                        </div>
                    @endif

                    {{-- ✅ empty state สำหรับกรองแบบ client ถ้ากรองจนไม่เหลือ --}}
                    <section class="empty empty-client" aria-label="ไม่มีรายการ (กรองในหน้า)" hidden>
                        <div class="mb-1"><i class="bi bi-search" style="font-size:1.35rem" aria-hidden="true"></i>
                        </div>
                        ไม่พบรายการที่ตรงกับตัวกรองในหน้านี้
                        <div class="mt-2">
                            <a href="{{ route('reservations.history') }}" class="btn-ghost btn-sm">ล้างตัวกรอง</a>
                            <a href="{{ route('reserve.form') }}" class="btn-brand btn-sm">
                                <i class="bi bi-plus-circle me-1" aria-hidden="true"></i> เริ่มจองโต๊ะ
                            </a>
                        </div>
                    </section>
                </main>
            </div>
        </div>
    </div>
@endsection

@section('js_before')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/reservations-history.js') }}"></script>
@endsection
