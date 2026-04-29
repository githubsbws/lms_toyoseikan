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
        <div class="container-fluid" style="min-height: 60vh; margin-bottom: auto;">
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
                            {{-- เปลี่ยนจาก nav-pills เป็น nav-tabs หรือคลาสที่ Theme รองรับ --}}
                            <div class="nav flex-column me-3" id="v-pills-tab" role="tablist">
                                @if ($course_detail->count() > 0)
                                    @foreach ($course_detail as $key => $item)
                                        {{-- @php $isActive = $key == 0 ? 'active' : ''; @endphp --}}
                                        <div class="road-item" style="margin-bottom: 15px;">
                                            <button class="nav-link btn-success"
                                                    id="v-pills-tab-{{ $item->course_id }}"
                                                    data-toggle="tab" {{-- ใช้ data-toggle เฉยๆ ถ้า Theme เป็น BS4 --}}
                                                    href="#v-pills-content-{{ $item->course_id }}"
                                                    style="display: flex; align-items: center; text-align: left; border: 1px; width: 100%;padding:20px;border-radius:15px">

                                                <div class="wrap" style="flex-grow: 1;">
                                                    <div class="couurse-name-rd">
                                                        <strong style="display: block;font-size:24px">{{ $item->course_title }}</strong>
                                                        <small>{!! strip_tags(html_entity_decode($item->course_short_title), '<b><strong><i><em><u>') !!}</small>
                                                    </div>
                                                </div>
                                                <div class="icon" style="margin-left: 10px;">
                                                    <i class="fa-regular fa-pen-to-square"></i>
                                                </div>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div><p>ยังไม่มีหลักสูตรการเรียนการสอน</p></div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ฝั่งขวา: Content (Learning Mode) --}}
                    <div class="col-lg-7 col-md-7">
                        <div class="tab-content">
                            @foreach ($course_detail as $key => $item)
                                <div class="tab-pane fade in {{ $key == 0 ? 'active' : '' }}"
                                    id="v-pills-content-{{ $item->course_id }}" style="border-radius: 12px;background: transparent">

                                    {{-- Card หลักตามดีไซน์ในรูป --}}
                                    <div class="panel panel-default paper-shadow mb-3" data-z="0.5" style="border-radius: 12px">
                                        <div class="panel-body p-5">

                                            {{-- ส่วนรูปภาพ (Thumbnail/Video Placeholder) --}}
                                            <div class="image-container" style="border-radius: 12px; overflow: hidden;">
                                                <img src="{{ asset('images/uploads/courseonline/'.$item->course_id.'/original/' . $item->course_picture) }}"
                                                    class="img-responsive" style="width: 100%;" loading="lazy">
                                            </div>

                                            {{-- ส่วนข้อมูลเนื้อหา --}}
                                            <div style="padding: 20px;">
                                                <h3 class="text-display-1">
                                                    {{ $item->course_title }}
                                                </h3>

                                                {{-- Progress Bar (100% สำเร็จ ตามรูป) --}}
                                                <div class="progress">
                                                    <div class="progress-bar progress-bar-primary" role="progressbar"
                                                        style="width: 100%;border-radius:12px" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <h5 class="card-title">100 % สำเร็จ</h5> {{-- progressbar --}}

                                                <hr style="margin: 15px 0;">

                                                {{-- รายละเอียดวิทยากรและวันที่ --}}
                                                <div class="course-meta" style="font-size: 20px; color: #666;">
                                                    <p><i class="fa fa-user fa-fw"></i> <strong>เจ้าของหลักสูตร :</strong>
                                                    {{ $item->teacher ? $item->teacher->teacher_name : '-' }}
                                                    </p>
                                                </div>

                                                {{-- ส่วนคำอธิบายแบบย่อ (Short Title) --}}
                                                ลายละเอียดหลักสูตร
                                                <div class="margin-t-15" style="background: #f9f9f9; padding: 15px; border-radius: 5px;">
                                                    {!! htmlspecialchars_decode($item->course_short_title) !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="panel panel-default paper-shadow mb-5" data-z="0.5" style="border-radius: 12px">
                                        <div class="panel-body p-5">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <h5 class="mb-0">จำนวนบทเรียน</h5>
                                                    <div class="col-6 m-5">
                                                        <i class="fa fa-book fa-5x" style="color: #428bca;"></i>
                                                        <p class="mx-0">4 บทเรียน</p>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <h5 class="mb-0">สถานะการประเมิน</h5>
                                                    <div class="col-6 m-5">
                                                        <i class="fa fa-certificate fa-5x m-3" style="color: #428bca;"></i>
                                                        <p class="mx-0">กรุณาเรียนให้จบก่อน</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="panel-head">
                                            <h3>รายการบทเรียน</h3>
                                    </div>
                                   <div class="panel panel-default paper-shadow mb-5" data-z="0.5"
                                        style="border-radius: 12px; background-color: #dbeafe !important; overflow: hidden; border: none;">

                                        <div class="panel-body" style="padding: 20px;">
                                            <div class="row">
                                                <div class="col-12">
                                                    @foreach ($item->lesson as $index => $lessons)
                                                        {{-- แถบหัวข้อบทเรียน --}}
                                                        <div style="background-color: #1F7BCC; color: white; padding: 15px 15px; border-radius: 8px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                                                            <span style="font-size:28px">บทที่ {{ $index + 1 }} {{ $lessons->title }}</span>
                                                        </div>

                                                        {{-- รายการย่อย (ทำเป็นโครงไว้ก่อน) --}}
                                                        <div style="padding: 5px 15px 20px 15px;">
                                                            @if($lessons->file->count() > 0)
                                                                @foreach($lessons->file as $vdo)
                                                                    {{-- ย้าย d-flex และ justify-between มาไว้ที่นี่ เพื่อคุม "ชื่อ" กับ "ปุ่ม" ในแต่ละแถว --}}
                                                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; width: 100%;">

                                                                        {{-- ฝั่งซ้าย: ไอคอน + ชื่อวิดีโอ --}}
                                                                        <div style="display: flex; align-items: center;">
                                                                            <i class="fa fa-play-circle text-primary" style="font-size: 1.2rem;padding-right:10px"></i>
                                                                            <span style="font-size:24px ">วิดีโอ: {{ $vdo->file_name ?? 'วิดีโอไม่มีชื่อ' }}</span>
                                                                        </div>

                                                                        {{-- ฝั่งขวา: เวลา (ถ้ามี) + ปุ่ม --}}
                                                                        <div style="display: flex; align-items: center;">
                                                                            {{-- ถ้าใน DB มีเก็บเวลา (Duration) ก็เอามาโชว์ตรงนี้ได้ครับ --}}
                                                                            {{-- <span class="text-muted me-3">
                                                                                <i class="fa fa-clock-o"></i> 30 นาที
                                                                            </span> --}}
                                                                            <button class="btn btn-primary" style="border-radius: 6px;font-size:24px; padding: 5px 40px;">ดูวิดีโอ</button>
                                                                        </div>

                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                            @if($lessons->filedoc->count() > 0)
                                                                @foreach($lessons->filedoc as $doc)
                                                                    <div class="lesson-item d-flex justify-content-between align-items-center mb-2" style="padding: 10px; background: rgba(255,255,255,0.3); border-radius: 8px;">
                                                                        <span>
                                                                            <i class="fa fa-file-pdf-o text-danger me-2"></i>
                                                                            {{ $doc->file_name_display ?? 'เอกสารประกอบ' }}
                                                                        </span>
                                                                        <a href="{{ asset($doc->file_path) }}" target="_blank" class="btn btn-secondary btn-sm">
                                                                            <i class="fa fa-download"></i> ดาวน์โหลด
                                                                        </a>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        @if($loop->last)
                                                            <div style="background-color: #1F7BCC; color: white; padding: 15px 15px; border-radius: 8px; margin-top: 20px; margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
                                                                <span style="font-size:28px">แบบทดสอบ Final Exam</span>
                                                                <button class="btn btn-light" style="font-size: 20px; font-weight: bold; color: #1F7BCC; border-radius: 6px; padding: 5px 30px;">
                                                                    เริ่มทำข้อสอบ
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
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
{{-- เก็บไว้ตอนทำคะแนน --}}
{{-- <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
    <span><i class="fa fa-check-circle text-primary"></i> ทำข้อสอบก่อนเรียน</span>
    <span>คะแนนที่ได้ <b style="background: white; padding: 2px 10px; border-radius: 5px; margin-left: 5px;">15/15</b></span>
</div> --}}
@endsection
