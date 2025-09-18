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
            <div class="col-sm-12">

                <h3> :: Form Update Password :: </h3>


                <form action="/users/reset/{{ $user_id }}" method="post">
                    @csrf
                    @method('put')

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Full Name</label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" disabled value="{{ $full_name }}">
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Email</label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" disabled value="{{ $email }}">
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">New Password</label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" name="password" required minlength="6"
                                placeholder="Enter new password">
                            @if (isset($errors) && $errors->has('password'))
                                <div class="text-danger">{{ $errors->first('password') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2">Confirm Password</label>
                        <div class="col-sm-6">
                            <input type="password" class="form-control" name="password_confirmation" required minlength="6"
                                placeholder="Confirm new password">
                            @if (isset($errors) && $errors->has('password_confirmation'))
                                <div class="text-danger">{{ $errors->first('password_confirmation') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-2">
                        <label class="col-sm-2"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">Update</button>
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

@section('js_before')
@endsection
