@extends('layout/mainlayout')
@section('title', 'Course')
@section('content')
@php
use App\Models\Teacher;

$teacher = Teacher::where('teacher_id',$course_detail->course_lecturer)->first();
@endphp
<style>
    .swal2-popup {
        font-size: 1.2rem !important;
        font-family: Georgia, serif;
        }
</style>
<script>
    // ตรวจสอบ Session Flash และแสดง SweetAlert2
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด',
            text: '{{ session('error') }}',
            confirmButtonText: 'ตกลง'
        });
    @endif
  </script>
    <body>
        <div id="content">
            {{-- แก้ไข --}}
            <div class="parallax bg-white page-section">
                <div class="parallax-layer container" data-opacity="true">
                    <div class="media v-middle">
                        <div class="media-left">
                            <span class="icon-block s60 bg-default">
                                <img src="{{asset('images/uploads/courseonline/'.$course_detail->course_id.'/original/'.$course_detail->course_picture)}}"
                                    style="height: 60px;" class="img-responsive">
                            </span>
                        </div>
                        <div class="media-body">
                            <h1 class="text-display-1 margin-none">{{ $course_detail->course_title }}</h1>
                        </div>
                        <div class="media-right">
                            <a class="btn btn-white" style="font-size: 22px;"
                                href="{{ url('course') }}">รวมหลักสูตร</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-9 col-md-8">
                        <div class="page-section">
                            <div class="panel panel-default paper-shadow" data-z="0.5" data-hover-z="1" data-animated="">
                                <div class="panel-body">
                                    <div class="width-300 width-250-md width-50pc-xs paragraph-inline">
                                        <img src="{{ asset('images/uploads/courseonline/'.$course_detail->course_id.'/original/' . $course_detail->course_picture) }}"
                                            class="img-responsive">
                                    </div>
                                    <p></p>
                                    <p>{!! htmlspecialchars_decode($course_detail->course_short_title) !!}</p>
                                    <p></p> <!-- <p class="margin-none">
                                                        <span class="label bg-gray-dark">Color</span>
                                                        <span class="label label-grey-200">Technologies</span>
                                                        <span class="label label-grey-200">ASCs</span>
                                                    </p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4">
                        <div class="page-section">
                            <!-- .panel -->
                            <div class="panel panel-primary paper-shadow" data-z="0.5" data-hover-z="1" data-animated="">
                                <div class="panel-heading">
                                    <h4 class="text-headline" style="font-size: 27px;font-weight: bold;">
                                        {{ $course_detail->course_title }}</h4>
                                </div>
                                <div class="panel-body">
                                    <p class="text-caption" style="font-size: 22px;">
                                        <!-- <i class="fa fa-clock-o fa-fw"></i> 4 ชั่วโมง &nbsp; -->
                                        <i class="fa fa-calendar fa-fw"></i> {{ $course_detail->update_date }} <br><br>
                                        <i class="fa fa-user fa-fw"></i> วิทยากร
                                        @if($teacher != null)
                                        : {{ $teacher->teacher_name}} 
                                        @else
                                        : -
                                        @endif
                                    </p>
                                </div>
                                @if ($course_detail->active == 'y')
                                    <div class="panel-body">
                                        <p class="text-caption" style="font-size: 20px;">
                                            <!-- <i class="fa fa-clock-o fa-fw"></i> 4 ชั่วโมง &nbsp; -->
                                            <strong>สถานะการเรียน</strong>
                                            <span style="color:blue;">เริ่มหลักสูตร</span>
                                        </p>
                                    </div>
                                    <hr class="margin-none">
                                    <div class="panel-body text-center">
                                        <p><a class="btn btn-success btn-lg paper-shadow relative" data-z="1"
                                                data-hover-z="2" data-animated="" href="{{ route('course.lesson', ['course_id' => $course_detail->course_id,'id' => $course_lesson->id]) }}">เริ่มหลักสูตร</a>
                                        </p>
                                    </div>
                                @else
                                    <div class="panel-body">
                                        <p class="text-caption" style="font-size: 20px;">
                                            <!-- <i class="fa fa-clock-o fa-fw"></i> 4 ชั่วโมง &nbsp; -->
                                            <strong>สถานะการเรียน</strong>
                                            <span style="color:red;">ยังไม่เปิดหลักสูตร</span>
                                        </p>
                                    </div>
                                    <hr class="margin-none">
                                    <div class="panel-body text-center">
                                        <p><a class="btn btn-success btn-lg paper-shadow relative" data-z="1"
                                                data-hover-z="2" data-animated="" href="{{ route('course.lesson', ['course_id' => $course_detail->course_id,'id' => $course_lesson->id]) }}"
                                                style="display: none;">เริ่มหลักสูตร</a>
                                        </p>
                                    </div>
                                @endif
                                <!-- <ul class="list-group">
                                                        <li class="list-group-item">
                                                        <a href="#" class="text-light"><i class="fa fa-facebook fa-fw"></i> Share on facebook</a>
                                                        </li>
                                                        <li class="list-group-item">
                                                        <a href="#" class="text-light"><i class="fa fa-twitter fa-fw"></i> Tweet this course</a>
                                                        </li>
                                                        </ul> -->
                            </div>
                            <!-- // END .panel -->
                            <!-- .panel -->
                            <!-- <div class="panel panel-default paper-shadow" data-z="0.5" data-hover-z="1" data-animated>
                                                        <div class="panel-body">
                                                         <div class="media v-middle">
                                                        <div class="media-left">
                                                            <img src="images/people/110/guy-6.jpg" alt="About Adrian" width="60" class="img-circle" />
                                                        </div>
                                                        <div class="media-body">
                                                            <h4 class="text-title margin-none"><a href="#">Adrian Demian</a></h4>
                                                            <span class="caption text-light">Biography</span>
                                                        </div>
                                                        </div>
                                                        <br/>
                                                        <div class="expandable expandable-indicator-white expandable-trigger">
                                                        <div class="expandable-content">
                                                            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. A accusamus aut consectetur consequatur cum cupiditate debitis doloribus, error ex explicabo harum illum minima mollitia nisi nostrum officiis omnis optio qui quisquam saepe sit sunt totam vel velit voluptatibus? Adipisci ducimus expedita id nostrum quas quia!</p>
                                                        </div>
                                                        </div>
                                                        </div>
                                                        </div> -->
                            <!-- // END .panel -->
                        </div>
                        <!-- // END .page-section -->
                    </div>
                </div><br>
                {{-- จบแก้ไข --}}
            </div>
        </div>
    </body>

@endsection

