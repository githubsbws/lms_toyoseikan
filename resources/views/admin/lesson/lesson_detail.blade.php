@extends('admin/layouts/mainlayout')
@section('title', 'Admin')
@section('content')
<style>
    .video-js {
        max-width: 100%
    }

    /* the usual RWD shebang */

    .video-js {
        width: 350px !important; /* override the plugin's inline dims to let vids scale fluidly */
        height: 350px !important;
    }

    .video-js video {
        position: relative !important;
    }
</style>
<body class="">
    <div id="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <h4 class="m-0">ระบบบทเรียน</h4>
                        </div>
                        <div class="ml-3">
                            <a href="{{route('lesson')}}">
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
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        รายละเอียดบทเรียน
                    </div>
                    <div class="card-body">
                        <div class="row mb-2">
                            <div class="col-12 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <h6 class="text-secondary mb-2"><u>หลักสูตรอบรมออนไลน์</u></h6>
                                    <p class="mb-0 font-weight-bold">{{ $lesson->course_title ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <div class="col-12 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <h6 class="text-secondary mb-2"><u>ชื่อบทเรียน</u></h6>
                                    <p class="mb-0 font-weight-bold">{{ $lesson->title ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12 mb-3">
                                <div class="border rounded p-3 bg-light">
                                    <h6 class="text-secondary mb-2"><u>รายละเอียดย่อ</u></h6>
                                    <div>{!! htmlspecialchars_decode(htmlspecialchars_decode($lesson->description ?? '-')) !!}</div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-2">
                            <div class="col-12">
                                <div class="card border-secondary">
                                    <div class="card-header bg-white font-weight-bold"><u>เนื้อหา</u></div>
                                    <div class="card-body">
                                        {!! htmlspecialchars_decode(htmlspecialchars_decode($lesson->content ?? '-')) !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card h-100 border-secondary">
                                    <div class="card-header bg-white font-weight-bold"><u>ไฟล์บทเรียน (mp3, mp4)</u></div>
                                    <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                        @if($file != null)
                                            <div class="w-100">
                                                <video id="example_video_1" class="video-js vjs-default-skin w-100" controls preload="none" data-setup="{}" controlsList="nodownload">
                                                    <source src="{{ asset('images/uploads/lesson/'.$file->filename) }}" type="video/mp4">
                                                    <p class="vjs-no-js">To view this video please enable JavaScript, and consider upgrading to a web browser that <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a></p>
                                                </video>
                                            </div>
                                        @else
                                            <div class="text-center text-muted py-4">ไม่มีวิดีโอ</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="card border-secondary">
                                    <div class="card-header bg-white font-weight-bold"><u>ไฟล์ประกอบบทเรียน (pdf)</u></div>
                                    <div class="card-body">
                                        @if($filedoc != null)
                                            <a href="{{ route('lesson.downloadfile', ['id' => $filedoc->id]) }}" target="_blank">{{ $filedoc->file_name }}</a>
                                        @else
                                            <div class="text-center text-muted py-4">ไม่มีไฟล์ประกอบบทเรียน</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            </div>
            <div class="clearfix"></div>
</body>
@endsection
