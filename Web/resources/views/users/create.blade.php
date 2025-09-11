@extends('home')

@section('css_before')
@endsection

@section('header')
@endsection

@section('sidebarMenu')
@endsection

@section('content')
    <div class="container mt-4">
        <div class="row">
            <div class="col-sm-9">

                <h3> :: Form Add User :: </h3>

                <form action="/users/" method="post">
                    @csrf

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Full Name</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="full_name" required placeholder="ชื่อ-สกุล"
                                value="{{ old('full_name') }}">
                            @if (isset($errors))
                                @if ($errors->has('full_name'))
                                    <div class="text-danger"> {{ $errors->first('full_name') }}</div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Email</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" name="email" placeholder="example@email.com"
                                value="{{ old('email') }}">
                            @if (isset($errors))
                                @if ($errors->has('email'))
                                    <div class="text-danger"> {{ $errors->first('email') }}</div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Phone</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" name="phone" required placeholder="เบอร์โทรศัพท์"
                                value="{{ old('phone') }}">
                            @if (isset($errors))
                                @if ($errors->has('phone'))
                                    <div class="text-danger"> {{ $errors->first('phone') }}</div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Password</label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" name="password" required placeholder="รหัสผ่าน">
                            @if (isset($errors))
                                @if ($errors->has('password'))
                                    <div class="text-danger"> {{ $errors->first('password') }}</div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Role</label>
                        <div class="col-sm-6">
                            <select name="role" class="form-control">
                                <option value="CUSTOMER">Customer</option>
                                <option value="STAFF">Staff</option>
                                <option value="ADMIN">Admin</option>
                            </select>
                            @if (isset($errors))
                                @if ($errors->has('role'))
                                    <div class="text-danger"> {{ $errors->first('role') }}</div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2"></label>
                        <div class="col-sm-5">
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href="/users" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@section('footer')
@endsection

@section('js_before')
@endsection
