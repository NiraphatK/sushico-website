@extends('home')

@section('js_before')
    @include('sweetalert::alert')
@endsection

@section('header')
@endsection

@section('sidebarMenu')
@endsection

@section('content')

    <h3> :: Form Update Table :: </h3>

    <form action="/table/{{ $table_id }}" method="post">
        @csrf
        @method('put')

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Table Number </label>
            <div class="col-sm-7">
                <input type="text" class="form-control" name="table_number" required
                       placeholder="e.g. A01" minlength="1" maxlength="10"
                       value="{{ old('table_number', $table_number) }}">
                @if (isset($errors) && $errors->has('table_number'))
                    <div class="text-danger">{{ $errors->first('table_number') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Capacity </label>
            <div class="col-sm-6">
                <input type="number" class="form-control" name="capacity" required
                       placeholder="1 - 10" min="1" max="10"
                       value="{{ old('capacity', $capacity) }}">
                @if (isset($errors) && $errors->has('capacity'))
                    <div class="text-danger">{{ $errors->first('capacity') }}</div>
                @endif
                <small class="text-muted d-block">จำนวนที่นั่งของโต๊ะ</small>
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Seat Type </label>
            <div class="col-sm-6">
                @php
                    $oldSeat = old('seat_type', $seat_type);
                @endphp
                <select name="seat_type" class="form-control" required>
                    <option value="">-- Select Seat Type --</option>
                    <option value="TABLE" {{ $oldSeat === 'TABLE' ? 'selected' : '' }}>TABLE</option>
                    <option value="BAR"   {{ $oldSeat === 'BAR' ? 'selected' : '' }}>BAR</option>
                </select>
                @if (isset($errors) && $errors->has('seat_type'))
                    <div class="text-danger">{{ $errors->first('seat_type') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> Active </label>
            <div class="col-sm-6">
                {{-- ค่า default hidden = 0 เพื่อให้ uncheck ก็ส่งค่า --}}
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1"
                       {{ old('is_active', (int) $is_active) == 1 ? 'checked' : '' }}>
                <small class="text-muted">ติ๊กเพื่อเปิดใช้งานโต๊ะนี้</small>
                @if (isset($errors) && $errors->has('is_active'))
                    <div class="text-danger">{{ $errors->first('is_active') }}</div>
                @endif
            </div>
        </div>

        <div class="form-group row mb-2">
            <label class="col-sm-2"> </label>
            <div class="col-sm-5">
                <button type="submit" class="btn btn-primary"> Update </button>
                <a href="/table" class="btn btn-danger">cancel</a>
            </div>
        </div>

    </form>

@endsection

@section('footer')
@endsection
