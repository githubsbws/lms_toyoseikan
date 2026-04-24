@extends('admin/layouts/mainlayout')
@section('title', 'Admin')
@section('content')
@php
use App\Models\Choice;
@endphp
<body class="">
	<div id="wrapper">
		<div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <h4 class="m-0">ระบบชุดข้อสอบบทเรียนออนไลน์</h4>
                        </div>
                        <div class="ml-3">
                            <a href="{{ redirect()->back()->getTargetUrl() }}">
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
                        รายละเอียดข้อสอบบทเรียนออนไลน์
                    </div>
                    <div class="card-body">
                            <div class="form-group">
                                <label for="course_title">รายละเอียดข้อสอบ</label>
                                <h4>{!! htmlspecialchars_decode($group->ques_title) !!}</h4>
                            </div>
                            @if($group->images->count())
                                <div class="form-group">
                                    <label>รูปภาพประกอบ</label>

                                    <div style="display:flex; gap:10px; flex-wrap:wrap;">
                                        @foreach($group->images as $img)
                                            <img src="{{ asset('storage/'.$img->path) }}"
                                                style="width:200px; border-radius:8px;">
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                            @if($group->ques_type == '2')

                                <div class="form-group">
                                    <label>ตัวเลือกคำตอบ</label>

                                    @php
                                        $choice = Choice::where('ques_id',$group->ques_id)
                                                    ->where('active','y')
                                                    ->get();
                                    @endphp

                                    @foreach($choice as $c)
                                        @if($c->choice_answer == '1')
                                            <h5 style="color: red">
                                                ✔ {!! htmlspecialchars_decode($c->choice_detail) !!}
                                            </h5>
                                        @else
                                            <h5>
                                                {!! htmlspecialchars_decode($c->choice_detail) !!}
                                            </h5>
                                        @endif
                                    @endforeach
                                </div>

                            @elseif($group->ques_type == '3')

                                <div class="form-group">
                                    <label>คำตอบ</label>

                                    <div style="background:#f8f9fa; padding:15px; border-radius:8px;">
                                        {!! nl2br(e($group->answer)) !!}
                                    </div>
                                </div>

                            @endif
                    </div>
                </div>
            </div>
			<div id="sidebar">
			</div><!-- sidebar -->
		</div>
	</div>
	<div class="clearfix"></div>
</body>
@endsection