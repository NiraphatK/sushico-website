@extends('home')

@section('content')
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">

                <div class="card shadow-sm border-0 rounded-3">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">รายการจอง</h5>
                        <a href="{{ url('reservations/adding') }}" class="btn btn-primary btn-sm">+ เพิ่มการจอง</a>
                    </div>

                    <div class="card-body">
                        <table class="table table-bordered table-striped align-middle text-center">
                            <thead class="table-light">
                                <tr>
                                    <th>รหัส</th>
                                    <th>ผู้จอง</th>
                                    <th>จำนวน</th>
                                    <th>ประเภทที่นั่ง</th>
                                    <th>เวลาเริ่ม</th>
                                    <th>เวลาสิ้นสุด</th>
                                    <th>สถานะ</th>
                                    <th>โต๊ะ</th>
                                    <th>จัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($ReservationList as $r)
                                    <tr>
                                        <td>{{ $r->reservation_id }}</td>
                                        <td>{{ $r->user->full_name ?? '-' }}</td>
                                        <td>{{ $r->party_size }}</td>
                                        <td>{{ $r->seat_type == 'BAR' ? 'เคาน์เตอร์บาร์' : 'โต๊ะอาหาร' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($r->start_at)->format('d/m/Y H:i') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($r->end_at)->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @switch($r->status)
                                                @case('CONFIRMED')
                                                    <span class="badge bg-info">ยืนยันแล้ว</span>
                                                @break

                                                @case('SEATED')
                                                    <span class="badge bg-success">นั่งแล้ว</span>
                                                @break

                                                @case('COMPLETED')
                                                    <span class="badge bg-secondary">เสร็จสิ้น</span>
                                                @break

                                                @case('CANCELLED')
                                                    <span class="badge bg-danger">ยกเลิก</span>
                                                @break

                                                @case('NO_SHOW')
                                                    <span class="badge bg-dark">ไม่มา</span>
                                                @break
                                            @endswitch
                                        </td>
                                        <td>{{ $r->table->table_number ?? '-' }}</td>
                                        <td>
                                            {{-- ปุ่มแก้ไข --}}
                                            <a href="{{ url('reservations/' . $r->reservation_id) }}"
                                                class="btn btn-sm btn-warning">แก้ไข</a>

                                            {{-- ปุ่มลบ --}}
                                            <form action="{{ url('reservations/remove/' . $r->reservation_id) }}"
                                                method="POST" class="d-inline delete-form"
                                                data-user="{{ $r->user->full_name ?? '-' }}"
                                                data-time="{{ \Carbon\Carbon::parse($r->start_at)->format('d/m/Y H:i') }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">ลบ</button>
                                            </form>

                                            {{-- ปุ่ม Check-in --}}
                                            @if ($r->status == 'CONFIRMED')
                                                <form action="{{ url('reservations/checkin/' . $r->reservation_id) }}"
                                                    method="POST" class="d-inline checkin-form"
                                                    data-user="{{ $r->user->full_name ?? '-' }}"
                                                    data-time="{{ \Carbon\Carbon::parse($r->start_at)->format('d/m/Y H:i') }}">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success">Check-in</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-muted">ยังไม่มีการจอง</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <div class="mt-3">
                                {{ $ReservationList->links() }}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endsection


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // ยืนยันการลบ
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const user = form.getAttribute('data-user');
                    const time = form.getAttribute('data-time');

                    Swal.fire({
                        title: 'ยืนยันการลบ?',
                        html: `คุณต้องการลบการจองของ <b>${user}</b><br>เวลา <b>${time}</b> หรือไม่`,
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'ใช่, ลบเลย!',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // ยืนยัน Check-in
            document.querySelectorAll('.checkin-form').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const user = form.getAttribute('data-user');
                    const time = form.getAttribute('data-time');

                    Swal.fire({
                        title: 'ยืนยัน Check-in?',
                        html: `คุณต้องการ Check-in การจองของ <b>${user}</b><br>เวลา <b>${time}</b> หรือไม่`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'ใช่, Check-in',
                        cancelButtonText: 'ยกเลิก'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
