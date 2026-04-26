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
                        <div class="media-body">
                            <h3 class="text-display-2 text-white margin-none">Roadmap สำหรับ พนังงานใหม่</h3>

                        </div>
                    </div>
                </div>
            </div><br>
           <div class="container-fluid">
                <div class="course-content">
                    {{-- ใส่ ID คลุมไว้เพื่อเขียน CSS ดักเฉพาะจุด --}}
                    <div class="row" id="custom-roadmap-area">

                        {{-- ฝั่งซ้าย: Roadmap --}}
                        {{-- ใช้ col-lg-5 ตามเดิม แต่เช็คว่าคลาส .roadmap ใน CSS ของน้องไม่ไปสั่งกว้าง 100% ทับ --}}
                        <div class="col-lg-5 roadmap">
                            <div class="title" style="margin-bottom: 20px;">
                                <span class="title1" style="font-size: 2rem; font-weight: bold; text-transform: uppercase;">
                                    road<span class="title2" style="color: #ff9800;">map</span>
                                </span>
                            </div>

                            <div class="d-flex align-items-start">
                                {{-- เปลี่ยนจาก nav-pills เป็น nav-tabs หรือคลาสที่ Theme น้องรองรับ --}}
                                <div class="nav flex-column me-3" id="v-pills-tab" role="tablist">
                                    @if ($course_detail->count() > 0)
                                        @foreach ($course_detail as $key => $item)
                                            @php $isActive = $key == 0 ? 'active' : ''; @endphp
                                            <div class="road-item" style="margin-bottom: 10px;">
                                                <button class="nav-link {{ $isActive }}"
                                                        id="v-pills-tab-{{ $item->course_id }}"
                                                        data-toggle="tab" {{-- ใช้ data-toggle เฉยๆ ถ้า Theme เป็น BS4 --}}
                                                        href="#v-pills-content-{{ $item->course_id }}"
                                                        style="display: flex; align-items: center; text-align: left; border: 1px solid #ddd; padding: 10px; width: 100%;">

                                                    <div class="wrap" style="flex-grow: 1;">
                                                        <div class="couurse-name-rd">
                                                            <strong style="display: block;">{{ $item->course_title }}</strong>
                                                            <small>{{ $item->course_short_title }}</small>
                                                        </div>
                                                    </div>
                                                    <div class="icon" style="margin-left: 10px;">
                                                        <i class="fa-regular fa-pen-to-square"></i>
                                                    </div>
                                                </button>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- ฝั่งขวา: Content --}}
                        <div class="col-lg-7">
                            <div class="tab-content">
                                @foreach ($course_detail as $key => $item)
                                    <div class="tab-pane fade in {{ $key == 0 ? 'active' : '' }}"
                                        id="v-pills-content-{{ $item->course_id }}">

                                        {{-- ใช้ Class ของ Theme (panel) --}}
                                        <div class="panel panel-default paper-shadow">
                                            <div class="panel-body">
                                                <h4 class="text-headline" style="margin-top: 0;">{{ $item->course_title }}</h4>
                                                <p>รายละเอียดเนื้อหาของบทเรียนนี้...</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </body>

@endsection
