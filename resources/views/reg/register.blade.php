@extends('layout/mainlayout')
@section('content')
<style>
    .error-message {
        color: red;
        font-size: 0.9em;
    }
    .input-error {
        border: 1px solid red;
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
                                <h1>สมัครสมาชิก</h1>
                            </div>
                            @if ($errors->any())
                                    <div>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            <div class="form">
                                <form class="form-horizontal" action="{{ route('register') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label"><label
                                                for="UserLogin_username" class="required">รหัสพนักงาน <span
                                                    class="required">*</span></label></label>
                                        <div class="col-sm-9">
                                            <input class="form-control" placeholder="Staff ID"
                                                name="username" type="text">
                                        </div>
                                        @error('username')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"><label for="UserLogin_password"
                                                class="required">รหัสผ่าน <span
                                                    class="required">*</span></label></label>

                                        <div class="col-sm-9">
                                            <input class="form-control" placeholder="Password"
                                                name="password" type="password">
                                        </div>
                                    </div><br>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label"><label
                                                for="UserLogin_username" class="required">ชื่อ - นามสกุล <span
                                                    class="required">*</span></label></label>
                                        <div class="col-sm-9">
                                            <input class="form-control" placeholder="firstname"
                                                name="firstname" type="text">
                                            <br>
                                            <input class="form-control" placeholder="lastname"
                                                name="lastname" type="text">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-3 control-label"><label for="UserLogin_password"
                                                class="required">อีเมล <span
                                                    class="required">*</span></label></label>

                                        <div class="col-sm-9">
                                            <input class="form-control" placeholder="Email"
                                                name="email" type="email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label"><label
                                                for="UserLogin_username" class="required">องค์กร <span
                                                    class="required">*</span></label></label>
                                        <div class="col-sm-9">
                                        <select class="form-control" name="orgchart_id">
                                            <option value="">---เลือก---</option>
                                            @foreach ($orgchart as $org)
                                                <option value="{{ $org->id }}">
                                                    {{ $org->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label"><label
                                                for="UserLogin_username" class="required">สายการผลิต <span
                                                    class="required">*</span></label></label>
                                        <div class="col-sm-9">
                                        <select class="form-control" name="line_id">
                                            <option value="">---เลือก---</option>
                                            @foreach ($line as $ln)
                                                <option value="{{ $ln->id }}">
                                                    {{ $ln->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label"><label
                                                for="UserLogin_username" class="required">แผนก <span
                                                    class="required">*</span></label></label>
                                        <div class="col-sm-9">
                                        <select class="form-control" name="department_id">
                                            <option value="">---เลือก---</option>
                                            @foreach ($department as $depart)
                                                <option value="{{ $depart->id }}">
                                                    {{ $depart->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label"><label
                                                for="UserLogin_username" class="required">ส่วนงาน <span
                                                    class="required">*</span></label></label>
                                        <div class="col-sm-9">
                                        <select class="form-control" name="section_id">
                                            <option value="">---เลือก---</option>
                                            @foreach ($section as $sec)
                                                <option value="{{ $sec->id }}">
                                                    {{ $sec->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label"><label
                                                for="UserLogin_username" class="required">ทีม <span
                                                    class="required">*</span></label></label>
                                        <div class="col-sm-9">
                                        <select class="form-control" name="team_id">
                                            <option value="">---เลือก---</option>
                                            @foreach ($team as $te)
                                                <option value="{{ $te->id }}">
                                                    {{ $te->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail3" class="col-sm-3 control-label"><label
                                                for="UserLogin_username" class="required">ตำแหน่ง <span
                                                    class="required">*</span></label></label>
                                        <div class="col-sm-9">
                                        <select class="form-control" name="position_id">
                                            <option value="">---เลือก---</option>
                                            @foreach ($position as $pos)
                                                <option value="{{ $pos->id }}">
                                                    {{ $pos->position_title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        </div>
                                    </div>

                                    
                                    <div class="form-group">
                                        <div class="col-sm-6 col-sm-offset-3" style="padding: 0;">
                                            <input class="btn btn-primary" type="submit" name="yt0"
                                                value="เข้าสู่ระบบ">
                                        </div>
                                    </div>
                                </form>
                            </div><!-- form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection