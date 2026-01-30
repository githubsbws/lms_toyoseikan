@extends('layout/mainlayout')
@section('content')
<style>
    .input-error {
        color: white;
        background-color: red;
        padding: 5px;
        margin-top: 5px;
        border-radius: 3px;
        display: inline-block;
    }
</style>
<body>

    <div class="container">
        <div class="page-section login-page">
            <div class="row">
                <div class="col-md-6 col-lg-6 col-md-offset-3 col-lg-offset-3">
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div class="col-xs-12" align="center">
                                <h1>เข้าสู่ระบบ</h1>
                            </div>
                            <div class="form">
                                <form class="form-horizontal" action="{{ route('login') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label"><label
                                                for="UserLogin_username" class="required">username / email <span
                                                    class="required">*</span></label></label>
                                        <div class="col-sm-9">
                                            <input class="form-control" placeholder="Username"
                                                name="username" id="username" type="text" value="{{ old('username') }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"><label for="UserLogin_password"
                                                class="required">รหัสผ่าน <span
                                                    class="required">*</span></label></label>

                                        <div class="col-sm-9">
                                            <input class="form-control" placeholder="Password"
                                                name="password" id="password" type="password">
                                        </div>
                                    </div><br>
                                    {{-- เพิ่ม --}}
                                   
                                    <div class="form-group">
                                        <div class="col-sm-6 col-sm-offset-3" style="padding: 0;">
                                            <p class="hint">
                                                <a href="/forgot-pass">ลืมรหัสผ่าน?</a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6 col-sm-offset-3" style="padding: 0;">
                                            <div class="checkbox icheck">
                                                <input id="ytUserLogin_rememberMe" type="hidden" value="0"
                                                    name="UserLogin[rememberMe]">
                                                <input name="UserLogin[rememberMe]" id="UserLogin_rememberMe"
                                                    value="1" type="checkbox">
                                                <label for="UserLogin_rememberMe">จำการเข้าระบบ</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6 col-sm-offset-3" style="padding: 0;">
                                            <span class="input-error">กรุณาเปลี่ยนรหัสผ่านก่อนเข้าใช้งาน 1 ครั้ง</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-6 col-sm-offset-3" style="padding: 0;">
                                            <div class="g-recaptcha" data-sitekey="6Le3zv8pAAAAACNf85umY6OIKbJYQOJMzoLjR8ZK" data-action="LOGIN"></div>
                                        </div>
                                    </div>
                                   
                                    <div class="form-group">
                                        <div class="col-sm-6 col-sm-offset-3" style="padding: 0;">
                                            <input class="btn btn-primary" type="submit" name="yt0"
                                                value="เข้าสู่ระบบ">
                                        </div>
                                    </div>
                                    @error('username')
                                        <div class="form-group">
                                            <div class="col-sm-6 col-sm-offset-3" style="padding: 0;">
                                                <span class="{{ $errors->has('username') ? 'input-error' : '' }}">{{ $message }}</span>
                                            </div>
                                        </div>
                                    @enderror
                                </form>
                               
                            </div><!-- form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://www.google.com/recaptcha/enterprise.js" async defer></script>
</body>
@endsection