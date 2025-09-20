@extends('home')

@section('css_before')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css"
        integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')
    <div class="container mt-4">
        <h3> :: Store Settings :: </h3>

        <form id="settings-form" action="/store-settings/update" method="post">
            @csrf
            @method('put')

            <!-- Timezone -->
            <div class="form-group row mb-2">
                <label class="col-sm-3">
                    Time Zone
                    <i class="fa-solid fa-circle-info text-primary" data-bs-toggle="tooltip"
                        title="กำหนดโซนเวลา เช่น Asia/Bangkok เพื่อใช้ในการคำนวณเวลาการจอง"></i>
                </label>
                <div class="col-sm-6">
                    <select name="timezone" class="form-control">
                        @php
                            $timezones = ['Asia/Bangkok', 'UTC'];
                        @endphp
                        @foreach ($timezones as $tz)
                            <option value="{{ $tz }}"
                                {{ old('timezone', $setting->timezone) == $tz ? 'selected' : '' }}>
                                {{ $tz }}
                            </option>
                        @endforeach
                    </select>
                    @if ($errors->has('timezone'))
                        <div class="text-danger">{{ $errors->first('timezone') }}</div>
                    @endif
                </div>
            </div>

            <!-- Cut-off -->
            <div class="form-group row mb-2">
                <label class="col-sm-3">
                    Cut-off Minutes
                    <i class="fa-solid fa-circle-info text-primary" data-bs-toggle="tooltip"
                        title="เวลาปิดรับการจองล่วงหน้าก่อนถึงเวลา เช่น 30 นาที"></i>
                </label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="cut_off_minutes"
                        value="{{ old('cut_off_minutes', $setting->cut_off_minutes) }}">
                    @if ($errors->has('cut_off_minutes'))
                        <div class="text-danger">{{ $errors->first('cut_off_minutes') }}</div>
                    @endif
                </div>
            </div>

            <!-- Grace -->
            <div class="form-group row mb-2">
                <label class="col-sm-3">
                    Grace Minutes
                    <i class="fa-solid fa-circle-info text-primary" data-bs-toggle="tooltip"
                        title="เวลาผ่อนผันเมื่อลูกค้ามาสาย เช่น 15 นาที ก่อนเปลี่ยนสถานะเป็น No Show"></i>
                </label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="grace_minutes"
                        value="{{ old('grace_minutes', $setting->grace_minutes) }}">
                    @if ($errors->has('grace_minutes'))
                        <div class="text-danger">{{ $errors->first('grace_minutes') }}</div>
                    @endif
                </div>
            </div>

            <!-- Buffer -->
            <div class="form-group row mb-2">
                <label class="col-sm-3">
                    Buffer Minutes
                    <i class="fa-solid fa-circle-info text-primary" data-bs-toggle="tooltip"
                        title="เวลาสำหรับทำความสะอาดโต๊ะก่อนรอบถัดไป เช่น 10 นาที"></i>
                </label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="buffer_minutes"
                        value="{{ old('buffer_minutes', $setting->buffer_minutes) }}">
                    @if ($errors->has('buffer_minutes'))
                        <div class="text-danger">{{ $errors->first('buffer_minutes') }}</div>
                    @endif
                </div>
            </div>

            <!-- Slot -->
            <div class="form-group row mb-2">
                <label class="col-sm-3">
                    Slot Granularity Minutes
                    <i class="fa-solid fa-circle-info text-primary" data-bs-toggle="tooltip"
                        title="ช่วงเวลาในการจอง เช่น 15 นาทีต่อ 1 slot"></i>
                </label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="slot_granularity_minutes"
                        value="{{ old('slot_granularity_minutes', $setting->slot_granularity_minutes) }}">
                    @if ($errors->has('slot_granularity_minutes'))
                        <div class="text-danger">{{ $errors->first('slot_granularity_minutes') }}</div>
                    @endif
                </div>
            </div>

            <!-- Duration -->
            <div class="form-group row mb-2">
                <label class="col-sm-3">
                    Default Duration Minutes
                    <i class="fa-solid fa-circle-info text-primary" data-bs-toggle="tooltip"
                        title="ระยะเวลาเริ่มต้นที่ลูกค้าสามารถใช้โต๊ะ เช่น 60 นาที"></i>
                </label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="default_duration_minutes"
                        value="{{ old('default_duration_minutes', $setting->default_duration_minutes) }}">
                    @if ($errors->has('default_duration_minutes'))
                        <div class="text-danger">{{ $errors->first('default_duration_minutes') }}</div>
                    @endif
                </div>
            </div>

            <!-- ปุ่ม Update + Reset -->
            <div class="form-group row mt-3">
                <div class="col-sm-6 offset-sm-3 d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Update Settings</button>
                    <button type="button" class="btn btn-danger" onclick="resetConfirm()">Reset to Default</button>
                </div>
            </div>
        </form>

        <!-- ฟอร์ม Reset แยก -->
        <form id="reset-form" action="/store-settings/reset" method="post" style="display:none;">
            @csrf
        </form>
    </div>

    <!-- Enable Tooltips -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        });

        function resetConfirm() {
            Swal.fire({
                title: 'แน่ใจหรือไม่?',
                text: "คุณต้องการรีเซ็ตค่ากลับไปเป็นค่าเริ่มต้นทั้งหมด",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'ใช่, รีเซ็ตเลย!',
                cancelButtonText: 'ยกเลิก'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reset-form').submit();
                }
            });
        }
    </script>
@endsection
