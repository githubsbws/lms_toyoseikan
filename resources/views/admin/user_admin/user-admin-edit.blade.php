@extends('admin/layouts/mainlayout')
@section('title', 'Admin')
@section('content')
@php
use App\Models\Company;
use App\Models\Division;
use App\Models\Position;
use App\Models\ProfilesTitle;
use App\Models\ASC;
@endphp
<body>
    <div id="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <h4 class="m-0">ระบบจัดการสมาชิก</h4>
                        </div>
                        <div class="ml-3">
                            <a href="{{route('user_admin')}}">
                                <button class="btn btn-warning d-flex align-items-center">
                                    <i class="fas fa-angle-left mr-2"></i>
                                    กลับหน้าหลัก
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container mt-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        แก้ไขสมาชิก
                    </div>
                    <div class="card-body">
                        <form action="{{ route('user_update') }}" enctype="multipart/form-data" method="post" id="question-form">
                            @csrf
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" class="form-control" value="{{ $user->username }}" disabled>
                            </div>

                            <div class="form-group">
                                <label for="password">Password</label>
                                <input id="password" type="password" class="form-control" name="password"  autocomplete="new-password" oninput="checkPassword()" >
                                <input type="hidden" name="old_password" value="{{ $user->password }}">
                                
                            </div>
                            <div class="form-group">
                                <label for="password-length">รหัสผ่านต้องมีความยาวมากว่า 8 ตัว</label>
                                <div class="col-md-6">
                                    <span id="password-length-status"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password-special">รหัสผ่านต้องมีสัญลักษณ์พิเศษอย่างน้อย 1 ตัว</label>
                                <div class="col-md-6">
                                    <span id="password-special-status"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password-confirm">Confirm Password</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation"  autocomplete="new-password">
                            </div>
                            @error('password')
                            <div class="form-group">
                                <div class="col-sm-6 col-sm-offset-3" style="padding: 0;">
                                    <span class="{{ $errors->has('password') ? 'input-error' : '' }}" style="color:red;">{{ $message }}</span>
                                </div>
                            </div>
                            @enderror
                            @php
                            $pro = ProfilesTitle::where('prof_id',$user->Profiles->title_id)->first();
                            @endphp
                            <div class="form-group">
                                <label for="">คำนำหน้าชื่อ</label>
                                <select class="form-control" name="title">
                                    <option value="">---เลือก---</option>
                                    @foreach ($profTitle as $title)
                                        <option value="{{ $title->prof_id }}"
                                            {{ $user->Profiles->title_id == $title->prof_id ? 'selected' : '' }}>
                                            {{ $title->prof_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">ชื่อ(EN)</label>
                                <input type="text"  class="form-control" name="firstname_en" value="{{ $user->Profiles->firstname_en ?? '-'}}">
                            </div>
                            <div class="form-group">
                                <label for="">นามสกุล(EN)</label>
                                <input type="text"  class="form-control" name="lastname_en" value="{{ $user->Profiles->lastname_en ?? null }}">
                            </div>
                            <div class="form-group">
                                <label for="">ชื่อ(TH)<span style="color:red">*</span></label>
                                <input type="text"  class="form-control" name="firstname" value="{{ $user->Profiles->firstname ?? null }}">
                            </div>
                            <div class="form-group">
                                <label for="">นามสกุล(TH)<span style="color:red">*</span></label>
                                <input type="text"  class="form-control" name="lastname" value="{{ $user->Profiles->lastname ?? null }}">
                            </div>
                            <div class="form-group">
                                <label for="">เลขบัตรประชาชน</label>
                                <input type="text"  class="form-control" name="identification" value="{{ $user->Profiles->identification ?? null }}">
                            </div>
                            <div class="form-group">
                                <label for="">Email</label>
                                <input type="email"  class="form-control" name="email" value="{{ $user->email ?? null }}">
                            </div>
                            <div class="form-group">
                                <label for="">เบอร์โทรศัพท์</label>
                                <input type="text"  class="form-control" name="phone" value="{{ $user->Profiles->phone ?? null }}">
                            </div>
                            <div class="form-group">
                                @php
                                $user_com = Company::find($user->company_id);
                                @endphp
                                <label for="">company</label>
                                <select class="form-control" name="company" id="company">
                                    <option value="">---เลือก---</option>
                                    @foreach ($company as $comp)
                                        <option value="{{ $comp->company_id }}"
                                            {{ $user->company_id == $comp->company_id ? 'selected' : '' }}>
                                            {{ $comp->company_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @php
                            $asc_all = ASC::where('active','y')->get();
                            @endphp
                                <div class="form-group">
                                    <label for="">ASC</label>
                                    <select class="form-control" name="asc" id="asc">
                                        <option value="">---เลือก---</option>
                                        @foreach ($asc_all as $as)
                                            <option value="{{ $as->id }}"
                                                {{ $user->asc_id == $as->id ? 'selected' : '' }}>
                                                {{ $as->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            <div class="form-group">
                                @php
                                $user_position = Position::find($user->position_id)
                                @endphp
                                <label for="">ตำแหน่ง</label>
                                @if($user_position != null)

                                <select name="position" id="position"  class="form-control">
                                    <option value="{{$user_position->id}}">{{ $user_position->position_title}}</option>
                                </select>
                                @else
                                <select name="position" id="position"  class="form-control">
                                    
                                </select>
                                @endif
                            </div>
                            <input type="hidden" name="id" value="{{ $user->id }}">

                            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i>บันทึก</button>
                        </form>
                    </div>
                </div>
            </div>
            <div id="sidebar">
            </div><!-- sidebar -->
        </div>
    </div>
    <div class="clearfix"></div>
<script type="text/javascript">
    document.getElementById('company').addEventListener('change', function() {
        var selectedId = this.value;

        // ส่งค่าไปยัง backend เพื่อคิวรี่ข้อมูลสองอันที่เหลือ
        fetch('/useradmin_create/company_selector/' + selectedId)
            .then(response => response.json())
            .then(data => {
                populateSecondDropdown(data.position);
                populateThirdDropdown(data.division);
            });
    });

    function populateSecondDropdown(data) {
        var secondDropdown = document.getElementById('position');
        secondDropdown.innerHTML = '<option value="" selected disabled>เลือกตำแหน่ง</option>';

        data.forEach(function(item) {
            var option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.position_title;
            secondDropdown.appendChild(option);
        });
    }
</script>
<script>
    function checkPassword() {
        var password = document.getElementById("password").value;

        // ตรวจสอบความยาวของรหัสผ่าน (ต้องมีอย่างน้อย 8 ตัวอักษร)
        if (password.length < 8) {
            document.getElementById("password-length-status").innerHTML = "<p style='color:red;'>รหัสผ่านต้องมีอย่างน้อย 8 ตัว</p>";
        } else {
            document.getElementById("password-length-status").innerHTML = "<p style='color:green;'>&#x2714;</p>";
        }

        // ตรวจสอบว่ามีอักขระพิเศษอย่างน้อย 1 ตัว
        if (!/[!@#$%^&*]/.test(password)) {
            document.getElementById("password-special-status").innerHTML = "<p style='color:red;'>รหัสผ่านต้องมีสัญลักษณ์พิเศษอย่างน้อย 1 ตัว</p>";
        } else {
            document.getElementById("password-special-status").innerHTML = "<p style='color:green;'>&#x2714;</p>";
        }
    }
    function validatePassword() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password-confirm').value;

    if (password !== confirmPassword) {
        alert('รหัสผ่านไม่ตรงกัน');
        return false;
    }

    return true;
}
</script>
</body>
@endsection
