@extends('frontendAuth')
@section('css_before')
@section('navbar')
@endsection

@section('body')


    <div class="container mt-4">
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-6">

                <h3> :: form Login :: </h3>


                <form action="/login" method="post">
                    @csrf



                    <div class="form-group row mb-2">

                        <div class="col-sm-7">
                            <input type="text" class="form-control" name="phone" required placeholder="กรอกเบอร์โทรศัพท์"
                                value="{{ old('phone') }}">
                            @if (isset($errors))
                                @if ($errors->has('phone'))
                                    <div class="text-danger"> {{ $errors->first('phone') }}</div>
                                @endif
                            @endif
                        </div>
                    </div>

                    <div class="form-group row mb-2">

                        <div class="col-sm-7">
                            <input type="password" class="form-control" name="password" required placeholder="กรอกรหัสผ่าน"
                                minlength="6">
                            @if (isset($errors))
                                @if ($errors->has('password'))
                                    <div class="text-danger"> {{ $errors->first('password') }}</div>
                                @endif
                            @endif
                        </div>
                    </div>




                    <div class="form-group row mb-2">

                        <div class="col-sm-5">

                            <button type="submit" class="btn btn-primary"> Login </button>
                            <a href="/" class="btn btn-danger">cancel</a>
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

{{-- devbanban.com --}}
