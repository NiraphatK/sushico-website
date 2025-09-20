@extends('home')

@section('content')
    <div class="container mt-4">
        <h3> :: Store Settings :: </h3>

        <form id="settings-form" action="/store-settings/update" method="post">
            @csrf
            @method('put')

            <!-- Timezone -->
            <div class="form-group row mb-2">
                <label class="col-sm-3">Time Zone</label>
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
                        <div class="text-danger"> {{ $errors->first('timezone') }}</div>
                    @endif
                </div>
            </div>

            <!-- Cut-off -->
            <div class="form-group row mb-2">
                <label class="col-sm-3">Cut-off Minutes</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="cut_off_minutes"
                        value="{{ old('cut_off_minutes', $setting->cut_off_minutes) }}">
                </div>
            </div>

            <!-- Grace -->
            <div class="form-group row mb-2">
                <label class="col-sm-3">Grace Minutes</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="grace_minutes"
                        value="{{ old('grace_minutes', $setting->grace_minutes) }}">
                </div>
            </div>

            <!-- Buffer -->
            <div class="form-group row mb-2">
                <label class="col-sm-3">Buffer Minutes</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="buffer_minutes"
                        value="{{ old('buffer_minutes', $setting->buffer_minutes) }}">
                </div>
            </div>

            <!-- Slot -->
            <div class="form-group row mb-2">
                <label class="col-sm-3">Slot Granularity Minutes</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="slot_granularity_minutes"
                        value="{{ old('slot_granularity_minutes', $setting->slot_granularity_minutes) }}">
                </div>
            </div>

            <!-- Duration -->
            <div class="form-group row mb-2">
                <label class="col-sm-3">Default Duration Minutes</label>
                <div class="col-sm-3">
                    <input type="number" class="form-control" name="default_duration_minutes"
                        value="{{ old('default_duration_minutes', $setting->default_duration_minutes) }}">
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

        <!-- ฟอร์ม Reset แยก (hidden) -->
        <form id="reset-form" action="/store-settings/reset" method="post" style="display:none;">
            @csrf
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
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
