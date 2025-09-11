@extends('home')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-sm-12">

                <h3> :: form Update User :: </h3>

                <form action="/users/{{ $user_id }}" method="post">
                    @csrf
                    @method('put')

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Full Name</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="full_name" required placeholder="ชื่อ-สกุล"
                                value="{{ old('full_name', $full_name) }}">
                            @if (isset($errors))
                                @if ($errors->has('full_name'))
                                    <div class="text-danger"> {{ $errors->first('full_name') }}</div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Phone</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="phone" required placeholder="เบอร์โทรศัพท์"
                                value="{{ old('phone', $phone) }}">
                            @if (isset($errors))
                                @if ($errors->has('phone'))
                                    <div class="text-danger"> {{ $errors->first('phone') }}</div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Email</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" name="email" placeholder="example@email.com"
                                value="{{ old('email', $email) }}">
                            @if (isset($errors))
                                @if ($errors->has('email'))
                                    <div class="text-danger"> {{ $errors->first('email') }}</div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Role</label>
                        <div class="col-sm-6">
                            <select name="role" class="form-control">
                                <option value="CUSTOMER" @if (old('role', $role) == 'CUSTOMER') selected @endif>Customer</option>
                                <option value="STAFF" @if (old('role', $role) == 'STAFF') selected @endif>Staff</option>
                                <option value="ADMIN" @if (old('role', $role) == 'ADMIN') selected @endif>Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Status</label>
                        <div class="col-sm-6">
                            <select name="is_active" class="form-control">
                                <option value="1" @if (old('is_active', $is_active) == 1) selected @endif>Active</option>
                                <option value="0" @if (old('is_active', $is_active) == 0) selected @endif>Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2"></label>
                        <div class="col-sm-5">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <a href="/users" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
