@extends('layout/mainlayout')
@section('title', 'Course')
@section('content')
    @php
        use App\Models\Teacher;
    @endphp

    <style>
        .img-admin-course img {
            width: 80px !important;
            height: 80px !important;
        }
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
            <div class="parallax overflow-hidden page-section bg-blue-300" style="background-color: cornflowerblue">
                <div class="container parallax-layer" data-opacity="true">
                    <div class="media media-grid v-middle">
                        <div class="media-left">
                            <span class="icon-block half bg-blue-500 text-white" style="height: 45px;"><i
                                    class="fa fa-fw fa-book"></i></span>
                        </div>
                        <div class="media-body">
                            <h3 class="text-display-2 text-white margin-none">หลักสูตร</h3>

                            <p class="text-white text-subhead" style="font-size: 1.6rem;">
                                รวมหลักสูตร การทำงานของ Product ของ Brother
                            </p>
                        </div>
                    </div>
                </div>
            </div><br>
            <div class="container">
                <div>
                    <ul class="pagination margin-top-none" id="yw0">
                        <li class="first "><a href="{{ url('course') }}">&lt;&lt; หน้าแรก</a></li>
                        @if ($course_detail->currentPage() > 1)
                            <li class="previous "><a href="{{ $course_detail->previousPageUrl() }}"
                                    class="pagination-link">หน้าที่แล้ว</a></li>
                        @endif
                        @for ($i = max(1, $course_detail->currentPage() - 3); $i <= min($course_detail->lastPage(), $course_detail->currentPage() + 3); $i++)
                            <li class="page"><a href="{{ $course_detail->url($i) }}"
                                    class="pagination-link {{ $i == $course_detail->currentPage() ? 'active' : '' }}">{{ $i }}</a>
                            </li>
                        @endfor
                        @if ($course_detail->currentPage() < $course_detail->lastPage())
                            <li class="next"><a href="{{ $course_detail->nextPageUrl() }}"
                                    class="pagination-link">หน้าถัดไป</a></li>
                        @endif
                        @if ($course_detail->currentPage() < $course_detail->lastPage())
                            <li class="last"><a href="{{ url('course?page=' . $course_detail->lastPage()) }}"
                                    class="pagination-link">หน้าสุดท้าย &gt;&gt;</a></li>
                        @endif
                    </ul>
                </div>
                <div class="page-section">
                    <div class="row">
                        <div class="col-md-9">
                            {{-- แก้ไข --}}
                            @if ($course_detail->count() > 0)
                                <div class="row" data-toggle="isotope" style="position: relative; ">
                                    @foreach ($course_detail as $item)
                                        @php
                                            $teacher = Teacher::where('teacher_id', $item->course_lecturer)->first();
                                        @endphp
                                        <div class="col-lg-6 col-md-6">
                                            <div class="news-box gridalicious" data-toggle="gridalicious">

                                                <div class="galcolumn" id="item02grJH"
                                                    style="width: 95.7983%; padding-left: 15px; padding-bottom: 15px; float: left; box-sizing: border-box;">
                                                    <div class="panel panel-default paper-shadow" data-z="0.5"
                                                        style="z-index:99;">
                                                        <div class="cover overlay cover-image-full hover">
                                                            <span class="img icon-block height-150 bg-primary"></span>
                                                            <a href="{{ route('course.detail', ['id' => $item->course_id]) }}"
                                                                class="padding-none overlay overlay-full icon-block bg-primary">
                                                                <span class="v-center">
                                                                    @if ($item->course_picture != null)
                                                                        <img src="{{ asset('images/uploads/courseonline/' . $item->course_id . '/original/' . $item->course_picture) }}"
                                                                            style="height: 150px">
                                                                    @else
                                                                        <img src="{{ asset('themes/bws/images/logo_course2.png') }}"
                                                                            style="height: 150px">
                                                                    @endif
                                                                </span>
                                                            </a>
                                                            <a href="{{ route('course.detail', ['id' => $item->course_id]) }}"
                                                                class="overlay overlay-full overlay-hover overlay-bg-white">
                                                                <span class="v-center">
                                                                    <span class="btn btn-circle btn-primary btn-lg"><i
                                                                            class="fa fa-graduation-cap"></i></span>
                                                                </span>
                                                            </a>
                                                        </div>
                                                        <div
                                                            class="expandable expandable-indicator-white expandable-trigger">
                                                            <div class="expandable-content">
                                                                <div class="panel-body">
                                                                    <h4 class="text-headline margin-v-0-10"
                                                                        style="font-size: 23px;"><a
                                                                            href="{{ route('course.detail', ['id' => $item->course_id]) }}">{{ $item->course_title }}</a>
                                                                    </h4>
                                                                </div>
                                                                <hr class="margin-none">
                                                                <div class="panel-body">
                                                                    <p style="font-size: 1.5rem;color: rgb(20, 20, 20);">
                                                                        {{ $item->course_short_title }}</p>

                                                                    <div class="media v-middle">
                                                                        <div class="media-left img-admin-course">
                                                                            @if ($teacher != null)
                                                                                <img src="{{ asset('images/uploads/teacher/' . $teacher->teacher_id . '/thumb/' . $teacher->teacher_picture) }}"
                                                                                    style="width: 150px" class="img-circle">
                                                                            @else
                                                                                <img src="{{ asset('themes/bws/images/logo_course2.png') }}"
                                                                                    style="width: 150px" class="img-circle">
                                                                            @endif
                                                                        </div>
                                                                        <div class="media-body">
                                                                            @if ($teacher != null)
                                                                                <h4><a
                                                                                        href="">{{ $teacher->teacher_name }}</a>
                                                                                @else
                                                                                    <h4>-</h4>
                                                                            @endif
                                                                            <br>
                                                                            </h4>
                                                                            <span
                                                                                style="font-size: 19px;">ชื่อวิทยากร</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="expandable-indicator"><i></i></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div id="clear2grJH"
                                                    style="clear: both; height: 0px; width: 0px; display: block;"></div>
                                            </div>
                                            <div class="clearfix"></div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p>ไม่พบคอร์สที่ตรงกัน</p>
                            @endif
                            {{-- จบแก้ไข --}}
                        </div>
                        <div class="col-md-3">
                            <form action="{{ url('search') }}" method="get" class="search-form">
                                <div class="panel panel-primary" data-toggle="panel-collapse" data-open="true">
                                    <div class="panel-heading panel-collapse-trigger collapse in" data-toggle="collapse"
                                        data-target="#53a72aaf-2f2d-d3bb-4ee9-cd2922844b76" aria-expanded="true"
                                        style="">
                                        <h4 class="panel-title" style="font-weight: bold;">ค้นหา</h4>
                                    </div>
                                    <div id="53a72aaf-2f2d-d3bb-4ee9-cd2922844b76" class="collapse in">
                                        <div class="panel-body">
                                            <div class="form-group input-group margin-none">
                                                <div class="row margin-none">
                                                    <div class="col-xs-12 padding-none">
                                                        <input class="form-control" type="text" name="search_text"
                                                            placeholder="คำค้นหา" value="">
                                                    </div>
                                                </div>
                                                <div class="input-group-btn">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="fa fa-search"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form><br>
                            <h4 style="font-weight: bold;">หลักสูตรแนะนำ</h4>

                            <div class="slick-basic slick-slider slick-initialized" data-items="1" data-items-lg="1"
                                data-items-md="1" data-items-sm="1" data-items-xs="1">

                                <div class="slick-list draggable" tabindex="0">
                                    <div class="slick-track" style="opacity: 1; ">
                                        @foreach ($course_recom as $cs)
                                            <div class="item slick-slide slick-cloned" index="-1"
                                                style="width: 264px;">
                                                <div class="panel panel-default paper-shadow box-course" data-z="0.5"
                                                    data-hover-z="1" data-animated="">
                                                    <div class="panel-body">
                                                        <div class="media media-clearfix-xs">
                                                            <div class="media-left">
                                                                <div
                                                                    class="cover width-90 width-100pc-xs overlay cover-image-full hover">
                                                                    <span class="img icon-block s90 bg-default"></span>
                                                                    <span
                                                                        class="overlay overlay-full padding-none icon-block s90 bg-default">
                                                                        <span class="v-center">
                                                                            @if ($cs->course_picture != null)
                                                                                <img src="{{ asset('images/uploads/courseonline/' . $cs->course_id . '/original/' . $cs->course_picture) }}"
                                                                                    style="height: 90px; width: 90px;"
                                                                                    class="img-responsive">
                                                                            @else
                                                                                <img src="{{ asset('themes/bws/images/logo_course2.png') }}"
                                                                                    style="height: 90px; width: 90px;"
                                                                                    class="img-responsive">
                                                                            @endif
                                                                        </span>
                                                                    </span>
                                                                    <a href="{{ route('course.detail', ['id' => $cs->course_id]) }}"
                                                                        class="overlay overlay-full overlay-hover overlay-bg-white">
                                                                        <span class="v-center">
                                                                            <span
                                                                                class="btn btn-circle btn-white btn-lg"><i
                                                                                    class="fa fa-graduation-cap"></i></span>
                                                                        </span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="media-body">
                                                                <h6 class="media-heading margin-v-5-3"><a
                                                                        href="{{ route('course.detail', ['id' => $cs->course_id]) }}">{{ $cs->course_title }}</a>
                                                                </h6>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                {{-- <button type="button" data-role="none" class="slick-prev"
                                    style="display: block;">Previous</button><button type="button" data-role="none"
                                    class="slick-next" style="display: block;">Next</button>
                                <ul class="slick-dots" style="display: block;">
                                    <li class="slick-active"><button type="button" data-role="none">1</button></li>
                                    <li><button type="button" data-role="none">2</button></li>
                                    <li><button type="button" data-role="none">3</button></li>
                                    <li><button type="button" data-role="none">4</button></li>
                                    <li><button type="button" data-role="none">5</button></li>
                                    <li><button type="button" data-role="none">6</button></li>
                                    <li><button type="button" data-role="none">7</button></li>
                                </ul> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

@endsection
