@extends('layout/mainlayout')
@section('title', 'Course')
@section('content')
    @php
        use App\Models\Manage;
        use App\Models\Lesson;
        use App\Models\Learn;
        use App\Models\LearnFile;
        use App\Models\Score;
        use App\Models\Grouptesting;
        use App\Models\File;
        use App\Models\Teacher;
        use App\Models\Images;
    @endphp
    <style type="text/css">
        .video-js {
            max-width: 100%
        }

        /* the usual RWD shebang */

        .video-js {
            width: auto !important;
            /* override the plugin's inline dims to let vids scale fluidly */
            height: auto !important;
        }

        .video-js video {
            position: relative !important;
        }

        .split-me>div:first-child {
            background: #555;
        }

        .split-me>div:last-child {
            background: #666;
        }

        
        .split-me-container {
            position: absolute;
            top: 3em;
            left: 1em;
            right: 1em;
            bottom: 1em;
            border-radius: 6px;
            overflow: hidden;
        }

        .splitter-bar {
            background: #333;
        }

        /* ------------- Flexcroll CSS ------------ */
        .scrollgeneric {
            line-height: 1px;
            font-size: 1px;
            position: absolute;
            top: 0;
            left: 0;
        }

        .hscrollerbase {
            height: 17px;
            background: #A3CBE0;
        }

        .hscrollerbar {
            height: 12px;
            background: #000;
            cursor: e-resize;
            padding: 3px;
            border: 1px solid #A3CBE0;
        }

        .hscrollerbar:hover {
            background: #222;
            border: 1px solid #222;
        }

        .menu_li_padding {
            padding: 10px 15px !important;
        }

        .collapse {
            background-color: #f9f9f9;
            /* สีพื้นหลังขณะที่ถูกย่อหรือขยาย */
        }

        .panel-collapse {
            padding-left: 20px;
        }

        #showslidethumb1 ul {
            display: flex;
            list-style: none;
            column-gap: 12px;
            padding: 0;

            li {
                width: 60%;
            }
        }
    </style>

    <body>
        @foreach ($course_lesson as $lesson)
            <div class="parallax bg-white page-section third">
                <div class="container parallax-layer" data-opacity="true"
                    style="transform: translate3d(0px, 0px, 0px); opacity: 1;">
                    <div class="media v-middle media-overflow-visible">
                        <div class="media-left">
                            <span class="icon-block s30 bg-default"><img
                                    src="{{ asset('themes/bws/images/logo_course2.png') }}" width="30"
                                    class="img-responsive"></span>
                        </div>
                        <div class="media-body">
                            <div class="text-headline" style="font-size: 25px;">{{ $lesson->course_title }}</div>
                        </div>
                        <div class="media-right">
                            <div class="dropdown">
                                <a class="btn btn-white dropdown-toggle" style="font-size: 22px;" data-toggle="dropdown"
                                    href="#">หลักสูตร <span class="caret"></span></a>
                                <ul class="dropdown-menu pull-right">
                                    <li>
                                        <a
                                            href="{{ route('course.detail', ['id' => $lesson->course_id]) }}">{{ $lesson->course_title }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="container">
                <div class="page-section">
                    <div class="row">
                        <div class="col-md-9">

                            <div class="page-section padding-top-none" style="padding-bottom: 0;">
                                <div class="media media-grid v-middle">
                                    <div class="panel panel-default paper-shadow" data-z="0.5" data-hover-z="1"
                                        data-animated="">
                                        <div class="panel-body" style="padding: 10px;">
                                            <div class="media-left">
                                                <!-- <span class="icon-block half bg-blue-300 text-white">2</span> -->
                                                <span class="icon-block half bg-default text-white"><img
                                                        src="{{ asset('themes/bws/images/logo_course2.png') }}"
                                                        width="50" class="img-responsive"></span>
                                            </div>
                                            <div class="media-body">
                                                <h1 class="text-display-1 margin-none">{{ $lesson->title }}</h1>
                                            </div>
                                            <br>
                                            <p class="text-body-2">
                                            </p>
                                            <p></p>
                                            <p>{!!  htmlspecialchars_decode($lesson->description) !!}</p>
                                            <p></p>
                                            <h4>ไฟล์ประกอบการเรียน</h4>
                                            @if ($file->isEmpty())
                                                -
                                            @else
                                                @foreach ($file as $fs)
                                                    @if($fs->id)
                                                        <a href="{{ route('course.downloadfile', ['id' => $fs->id]) }}" target="_blank">
                                                            {{ $fs->file_name }}
                                                        </a>
                                                        <br>
                                                    @else
                                                        -
                                                    @endif
                                                @endforeach
                                            @endif
                                            <p></p>

                                        </div>
                                    </div>
                                </div>
                                <div class="lessonContent">

                                    <div class="panel-group" id="accordion2" role="tablist" aria-multiselectable="true">
                                        @php
                                            if ($learn_id) {
                                                $learn_sta = LearnFile::where('file_id', $file_id->id)
                                                    ->where('user_id_file', Auth::user()->id)
                                                    ->first();
                                                // dd($learn_sta->toArray());
                                                if ($learn_sta != null) {
                                                    if ($learn_sta->learn_file_status == 's') {
                                                        $statusValue =
                                                            '<img src="' .
                                                            asset('images/icon_checkpast.png') .
                                                            '" alt="ผ่าน" title="ผ่าน" style="margin-bottom: 8px;">';
                                                    } elseif ($learn_sta->learn_file_status == 'l') {
                                                        $statusValue =
                                                            '<img src="' .
                                                            asset('images/icon_checklost.png') .
                                                            '" alt="เรียนยังไม่ผ่าน" title="เรียนยังไม่ผ่าน" style="margin-bottom: 8px;">';
                                                    }
                                                } else {
                                                    $statusValue =
                                                        '<img src="' .
                                                        asset('images/icon_checkbox.png') .
                                                        '" alt="ยังไม่ได้เรียน" title="ยังไม่ได้เรียน" style="margin-bottom: 8px;">';
                                                }
                                                $displayStyle = ($learn_sta != null && $learn_sta->learn_file_status == 's') ? 'block' : 'none';
                                            }
                                        @endphp
                                        <style>
                                            .vjs-progress-control {
                                                display: {{ $displayStyle }};
                                            }
                                        </style>
                                        <div class="panel panel-default">

                                            <div class="panel-heading" role="tab" id="heading{{ $file_id->id }}">
                                                <h4 class="panel-title">
                                                    <a data-toggle="collapse" data-parent="#accordion2"
                                                        href="#collapse{{ $file_id->id }}" aria-expanded="true"
                                                        aria-controls="collapse{{ $file_id->id }}">
                                                        <?php
                                                        if ($file_id->file_name == '') {
                                                            $fileNameCheck = '-';
                                                        } else {
                                                            $fileNameCheck = $file_id->file_name;
                                                        }
                                                        ?>
                                                        <?php echo '<div style="float: left;" id="imageCheck' . $file_id->id . '">' . $statusValue . '</div> ' . $fileNameCheck; ?>
                                                        : view <?= $file_id->views ?>
                                                    </a>
                                                </h4>
                                            </div>
                                            <div id="collapse{{ $file_id->id }}" class="panel-collapse collapse in"
                                                role="tabpanel" aria-labelledby="heading{{ $file_id->id }}">
                                                <div class="panel-body" style="background-color: #666;">
                                                    <div>
                                                        <div class="split-me" id="split-me1">
                                                            <div class="col-md-6">
                                                                <video id="example_video_1"
                                                                    class="video-js vjs-default-skin" controls=""
                                                                    preload="none" data-setup="{}"
                                                                    controlsList="nodownload">
                                                                    {{-- <source src="/../images/storage/uploads/lesson/{{$lesson->filename}}" type="video/mp4"> --}}
                                                                    <source
                                                                        src="{{ asset('images/uploads/lesson/' . $file_id->filename) }}"
                                                                        type="video/mp4">
                                                                    <!-- <source src="http://video-js.zencoder.com/oceans-clip.webm" type='video/webm' />
                                                                            <source src="http://video-js.zencoder.com/oceans-clip.ogv" type='video/ogg' /> -->
                                                                    <!-- <track kind="captions" src="demo.captions.vtt" srclang="en" label="English"></track> -->
                                                                    <!-- Tracks need an ending tag thanks to IE9 -->
                                                                    <!-- <track kind="subtitles" src="demo.captions.vtt" srclang="en" label="English"></track> -->
                                                                    <!-- Tracks need an ending tag thanks to IE9 -->
                                                                    <p class="vjs-no-js">To view this video please
                                                                        enable JavaScript, and consider upgrading to
                                                                        a
                                                                        web browser that <a
                                                                            href="http://videojs.com/html5-video-support/"
                                                                            target="_blank">supports HTML5 video</a>
                                                                    </p>
                                                                </video>
                                                            </div>

                                                            <div class="col-md-6">

                                                                <div class="col-md-12">
                                                                    <a href="#" id="showslide1" rel="prettyPhoto">

                                                                    </a>
                                                                </div>
                                                            </div>
                                                            @php
                                                                $images = Images::where('user_id', Auth::user()->id)
                                                                    ->where('lesson_id', $lesson_id)
                                                                    ->where('file_id', $file_id->id)
                                                                    ->where('active', 'y')
                                                                    ->get();
                                                                $allTimesFromDatabase = [];
                                                            @endphp
                                                            <div class="col-md-12 showslidethumb" id="showslidethumb1">
                                                                <ul>
                                                                    @foreach ($images as $img)
                                                                        @php
                                                                            $allTimesFromDatabase[] = $img->image_time;
                                                                            $img_time = ($img->image_time / 60);
                                                                        @endphp
                                                                        <li>
                                                                            <img id="slide1_0"
                                                                                class="slidehide1 img-responsive"
                                                                                style=""
                                                                                data-time="{{ $img->image_time }}"
                                                                                src="{{ $img->image_picture }}"
                                                                                onclick="sendTime('{{ $img->image_time }}')"><a onclick="sendTime('{{ $img->image_time }}')"  style="cursor: pointer; color: white;">นาทีที่ {{ $img_time }}</a>
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>

                                                        <script type="text/javascript">
                                                            console.log('first')
                                                            var startTime;
                                                            var myPlayer2 = videojs('example_video_1');
                                                            var video = document.querySelector('video');
                                                            var allTimesFromDatabase = [
                                                                @foreach ($images as $img)
                                                                    {{ $img->image_time }},
                                                                @endforeach
                                                            ];

                                                            function checkTimeAndCaptureImage() {
                                                                // เวลาปัจจุบัน
                                                                var video = document.getElementById('example_video_1');


                                                                // ตรวจสอบว่าวิดีโอได้เริ่มต้นการเล่นแล้วหรือยัง
                                                                if (myPlayer2.paused()) {
                                                                    return; // ถ้าวิดีโอยังไม่ได้เริ่มต้นการเล่น ให้ยกเลิกฟังก์ชัน
                                                                }

                                                                var currentTime = Math.floor(myPlayer2.currentTime()); // แปลงเป็นมิลลิวินาที

                                                                console.log(currentTime)

                                                                // ถ้าผ่านไป 1 นาที
                                                                if (currentTime !== 0 && currentTime % 60 === 0) {
                                                                    // ทำการบันทึกรูปภาพพร้อมกับเวลา
                                                                    captureImageAndSend(currentTime);
                                                                    // รีเซ็ตเวลาเริ่มต้น
                                                                    startTime = currentTime;

                                                                    console.log('send')
                                                                }
                                                            }

                                                            setInterval(checkTimeAndCaptureImage, 1000);

                                                            // ฟังก์ชันสำหรับบันทึกรูปภาพและส่งไปยัง Controller
                                                            function captureImageAndSend(currentTime) {


                                                                // ตรวจสอบว่า video ไม่เป็น null และ readyState เป็น HAVE_ENOUGH_DATA
                                                                if (!video || video.readyState < 3) {
                                                                    console.error('Video is not ready yet.');
                                                                    return;
                                                                }

                                                                // console.log('Video metadata loaded');
                                                                // console.log('Video width:', video.videoWidth);
                                                                // console.log('Video height:', video.videoHeight);

                                                                var canvas = document.createElement('canvas');
                                                                var ctx = canvas.getContext('2d');
                                                                canvas.width = 80;
                                                                canvas.height = 80;

                                                                // วาดภาพจากวิดีโอไปยัง Canvas
                                                                ctx.drawImage(video, 0, 0, 80, 80);
                                                                var image = canvas.toDataURL('image/png');

                                                                var route = "{{ route('images.store') }}"; // นี่คือเส้นทางของ Laravel Route
                                                                var csrfTokenMetaTag = document.querySelector('meta[name="csrf-token"]');
                                                                var csrfToken = csrfTokenMetaTag ? csrfTokenMetaTag.getAttribute('content') : null;
                                                                var lesson = "{{ $lesson_id }}";
                                                                var file_id = "{{ $file_id->id }}";

                                                                if (!csrfToken) {
                                                                    console.error('CSRF token not found.');
                                                                    return;
                                                                }
                                                                console.log(image)
                                                                console.log(currentTime)
                                                                console.log(lesson)
                                                                console.log(file_id)
                                                                fetch(route, {
                                                                        method: 'POST',
                                                                        headers: {
                                                                            'Content-Type': 'application/json',
                                                                            'X-CSRF-TOKEN': csrfToken
                                                                        },
                                                                        body: JSON.stringify({
                                                                            image: image,
                                                                            time: currentTime,
                                                                            lesson: lesson,
                                                                            file_id: file_id
                                                                        })
                                                                    })
                                                                    .then(response => {
                                                                        console.log(response)
                                                                        if (!response.ok) {
                                                                            throw new Error('Failed to save image');
                                                                        }
                                                                        return response.json();
                                                                    })
                                                                    .then(data => {
                                                                        console.log(data.message); // แสดงข้อความที่ได้จาก Controller
                                                                    })
                                                                    .catch(error => {
                                                                        console.error(error);
                                                                    });
                                                            }

                                                            function sendTime(time) {
                                                                // เรียกใช้ฟังก์ชันที่เล่นวิดีโอที่เวลาที่คลิก
                                                                // console.log(time);
                                                                if (allTimesFromDatabase.includes(time)) {
                                                                    console.log("เวลานี้มีอยู่ในฐานข้อมูลแล้ว");
                                                                    return; // ไม่ต้องทำอะไรเพิ่มเติม
                                                                }

                                                                playVideoAtTime(parseInt(time));
                                                            }

                                                            // ฟังก์ชันเล่นวิดีโอที่เวลาที่กำหนด
                                                            function playVideoAtTime(time) {


                                                                video.currentTime = time;
                                                                video.play();
                                                            }

                                                            var myPlayer1 = videojs('example_video_1');
                                                            var link = '{{ url('course.LearnVdo') }}';
                                                            var id = '{{ $file_id->id }}';
                                                            var learn_id = '{{ $learn_id }}';
                                                            
                                                           

                                                            console.log(id)
                                                            console.log(learn_id)
                                                            myPlayer1.on('play', function() {
                                                                console.log('play')
                                                                var counter = 'counter';
                                                                $.getJSON(
                                                                    '{{ route('course.LearnVdo', ['id' => ':id', 'learn_id' => ':learn_id', 'counter' => ':counter']) }}'
                                                                    .replace(':id', id)
                                                                    .replace(':learn_id', learn_id)
                                                                    .replace(':counter', counter),
                                                                    function(data) {
                                                                        // console.log(data);
                                                                        // console.log(learn_id);
                                                                        $('#imageCheck' + data.no).html(data.image);
                                                                    });
                                                            });
                                                            myPlayer1.on('ended', function() {
                                                                console.log('ended')
                                                                var counter = 'success';
                                                                $.getJSON(
                                                                    '{{ route('course.LearnVdo', ['id' => ':id', 'learn_id' => ':learn_id', 'counter' => ':counter']) }}'
                                                                    .replace(':id', id)
                                                                    .replace(':learn_id', learn_id)
                                                                    .replace(':counter', counter),
                                                                    function(data) {
                                                                        // console.log(data);
                                                                        // console.log(learn_id);
                                                                        $('#imageCheck' + data.no).html(data.image);
                                                                        window.location.reload();
                                                                    });
                                                            });

                                                            function preventSeek(event) {
                                                                var video = document.getElementById('example_video_1');
                                                                video.currentTime = event.target.currentTime;
                                                            }
                                                        </script>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                </div>
                                <script type="text/javascript">
                                    var myVar;
                                    var width = $(document).width();
                                    var height = $(document).height();
                                    var timer = 3000;

                                    $(document).ready(function() {



                                        // $('.container').attr('class', 'container-fluid');
                                        $(".video-js").each(function(videoIndex) {
                                            var videoId = $(this).attr("id");
                                            var iOS = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
                                            var myPlayer = videojs("example_video_1");


                                            var $win = $(window); // or $box parent container
                                            var $box = $(".video-js");
                                            var statusCheckCountdown = true;

                                            var checkFullScreen = true;




                                            function countdown(timer) {

                                                var myPlayer_alert = videojs(videoId);
                                                // console.log(timer)

                                                if ((window.fullScreen) ||
                                                    (window.innerWidth == screen.width && window.innerHeight == screen.height)) {
                                                    checkFullScreen = true;
                                                } else {
                                                    checkFullScreen = false;

                                                }


                                                if (timer === 0) {
                                                    myPlayer_alert.pause();
                                                    if (checkFullScreen) {
                                                        myPlayer_alert.exitFullscreen();
                                                    }
                                                    swal({
                                                            title: "ท่านยังอยู่ที่หน้าจอคอมพิวเตอร์",
                                                            text: 'ของท่านหรือไม่ ?',
                                                            icon: 'warning',
                                                            buttons: [false, "ตกลง"],
                                                        })
                                                        .then((confirm) => {
                                                            if (confirm) {
                                                                // alert("ok");
                                                                if (checkFullScreen) {
                                                                    myPlayer_alert.requestFullscreen();
                                                                    countdown(300);
                                                                }
                                                                myPlayer_alert.play();
                                                                statusCheckCountdown = false;
                                                            }
                                                        });
                                                    setTimeout();


                                                }
                                                // setTimeout(() => countdown(timer - 1), 1000);
                                                var timers = setTimeout(() => countdown(timer - 1), 1000);

                                                var keys = {};
                                                $(document).keydown(function(e) {
                                                    if (e.which == 17 || e.which == 18 || e.which == 9) {
                                                        myPlayer.pause();
                                                        clearTimeout(timers);
                                                    }
                                                });





                                                $win.on("click.Bst", function(event) {
                                                    if ($box.has(event.target).length == 0 && !$box.is(event.target)) {
                                                        // alert('you clicked outside the video');
                                                        if (statusCheckCountdown) {
                                                            myPlayer.pause();
                                                        }
                                                        clearTimeout(timers);
                                                    }
                                                    // else {
                                                    //     console.log('you clicked inside the video');
                                                    // }
                                                });

                                                // var keys = {};
                                                // $(document).keydown(function (e) {
                                                //     if(e.which == 17 || e.which == 18 || e.which == 9){
                                                //         myPlayer.pause();
                                                //     }
                                                // });

                                                $(window).bind('resize', function() {
                                                    // alert('STOP');
                                                    var current_width = $(document).width();
                                                    var current_height = $(document).height();
                                                    if (statusCheckCountdown) {
                                                        clearTimeout(timers);
                                                        myPlayer.pause();
                                                    }
                                                    if (current_width < width && current_height < height) {
                                                        console.log('HIDE !!!!!!!!!!!');
                                                        //vjs-paused
                                                        $(".vjs-control-bar").css("display", "none");
                                                        $("video").css("display", "none");
                                                        $('video').click(function() {
                                                            return false;
                                                        });
                                                        $('#stopLearn').modal({
                                                            backdrop: 'static',
                                                            keyboard: false
                                                        });
                                                        $('#stopLearn').modal('show');
                                                        // $('video').click(false);
                                                        myPlayer.pause();
                                                    } else {
                                                        $(".vjs-control-bar").css("display", "block");
                                                        $("video").css("display", "block");
                                                        $('video').click(function() {
                                                            return false;
                                                        });
                                                        $('#stopLearn').modal('hide');
                                                        // $('video').click(true);
                                                    }

                                                });

                                                window.onblur = function() {
                                                    // console.log('blur');
                                                    clearTimeout(timers);
                                                    myPlayer.pause();
                                                }

                                            }


                                            _V_(videoId).ready(function() {
                                                this.on("play", function(e) {
                                                    countdown(300);

                                                    // alert('asdad');

                                                    //pause other video
                                                    $(".video-js").each(function(index) {
                                                        if (videoIndex !== index) {
                                                            this.player.pause();
                                                        }
                                                    });

                                                });

                                            });
                                        });
                                        $("a[rel^='prettyPhoto']").prettyPhoto({
                                            social_tools: false
                                        });
                                    });
                                </script>
                            </div>

                            <!-- <h5 class="text-subhead-2 text-light">บทเรียน</h5> -->
                            <div class="panel panel-default curriculum open paper-shadow" data-z="0.5">
                                <div class="panel-heading panel-heading-gray" data-toggle="collapse"
                                    data-target="#curriculum-2">
                                    <div class="media">
                                        <div class="media-left">
                                            <span class="icon-block img-circle bg-orange-300 half text-white"><i
                                                    class="fa fa-graduation-cap"></i></span>
                                        </div>
                                        <div class="media-body">
                                            <h3 class="text-headline"><strong>บทเรียน</strong></h3>
                                        </div>
                                    </div>
                                    <!-- <span class="collapse-status collapse-open">Open</span> -->
                                    <!-- <span class="collapse-status collapse-close">Close</span> -->
                                </div>
                                @foreach ($lesson_list as $list)
                                    @php
                                        $sta = Learn::where([
                                            'lesson_id' => $list->id,
                                            'user_id' => Auth::user()->id,
                                            'lesson_active' => 'y',
                                        ])->get();
                                        $files = File::where(['lesson_id' => $list->id, 'active' => 'y'])
                                            ->orderBy('id', 'ASC')
                                            ->get();
                                    @endphp
                                    <div class="list-group collapse in" id="curriculum-2">
                                        @if (count($files) == 1)
                                            <a
                                                href="{{ route('course.lesson', ['course_id' => $list->course_id, 'id' => $list->id]) }}">

                                                <div class="list-group-item media active"
                                                    data-target="{{ route('course.lesson', ['course_id' => $list->course_id, 'id' => $list->id]) }}">
                                                    <div class="media-left">
                                                        <li class="text-crt"></li>
                                                    </div>
                                                    <div class="media-body">
                                                        @php
                                                            if ($sta->isEmpty()) {
                                                                echo "<i class='fa fa-fw fa-circle text-grey-300'></i>";
                                                            } else {
                                                                foreach ($sta as $status) {
                                                                    if ($status->lesson_status == 'pass') {
                                                                        echo "<i class='fa fa-fw fa-circle text-green-300'></i>";
                                                                    } else {
                                                                        echo "<i class='fa fa-fw fa-circle text-orange-300'></i>";
                                                                    }
                                                                }
                                                            }
                                                        @endphp
                                                        {{ $list->title }}
                                                    </div>
                                                    <!-- <div class="media-right">
                                            <div class="width-100 text-right text-caption">10:00 min</div>
                                        </div> -->
                                                </div>
                                            @else
                                                <a href="#lesson{{ $list->id }}" class="list-group collapse in"
                                                    data-toggle="collapse">

                                                    <div class="list-group-item media active"
                                                        data-target="{{ route('course.lesson', ['course_id' => $list->course_id, 'id' => $list->id]) }}">
                                                        <div class="media-left">
                                                            <li class="text-crt"></li>
                                                        </div>
                                                        <div class="media-body">
                                                            @php
                                                                if ($sta->isEmpty()) {
                                                                    echo "<i class='fa fa-fw fa-circle text-grey-300'></i>";
                                                                } else {
                                                                    foreach ($sta as $status) {
                                                                        if ($status->lesson_status == 'pass') {
                                                                            echo "<i class='fa fa-fw fa-circle text-green-300'></i>";
                                                                        } else {
                                                                            echo "<i class='fa fa-fw fa-circle text-orange-300'></i>";
                                                                        }
                                                                    }
                                                                }
                                                            @endphp
                                                            {{ $list->title }}
                                                        </div>
                                                        <!-- <div class="media-right">
                                                <div class="width-100 text-right text-caption">10:00 min</div>
                                            </div> -->
                                                    </div>
                                        @endif
                                        </a>
                                        <div id="lesson{{ $list->id }}" class="panel-collapse collapse">
                                            @if (count($files) > 1)
                                                @foreach ($files as $fsa)
                                                    @php
                                                        $sta = LearnFile::where([
                                                            'file_id' => $fsa->id,
                                                            'user_id_file' => Auth::user()->id,
                                                        ])->first();
                                                    @endphp
                                                    @if ($track->id == $fsa->id)
                                                    @else
                                                        <a
                                                            href="{{ route('course.lesson', ['course_id' => $list->course_id, 'id' => $list->id, 'files' => $fsa->id]) }}">

                                                            <div class="list-group-item media active"
                                                                data-target="{{ route('course.lesson', ['course_id' => $list->course_id, 'id' => $list->id, 'files' => $fsa->id]) }}">
                                                                <div class="media-left">
                                                                    <li class="text-crt"></li>
                                                                </div>
                                                                <div class="media-body">
                                                                    @php
                                                                        // dd($sta->toArray());
                                                                        if ($sta == null) {
                                                                            echo "<i class='fa fa-fw fa-circle text-grey-300'></i>";
                                                                        } else {
                                                                            if ($sta->learn_file_status == 's') {
                                                                                echo "<i class='fa fa-fw fa-circle text-green-300'></i>";
                                                                            } else {
                                                                                echo "<i class='fa fa-fw fa-circle text-orange-300'></i>";
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    {{ $fsa->file_name }}

                                                                </div>
                                                                <!-- <div class="media-right">
                                                <div class="width-100 text-right text-caption">10:00 min</div>
                                            </div> -->
                                                            </div>
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <br>
                            <br>
                        </div>
                        <div class="col-md-3">

                            <div class="panel panel-primary" data-toggle="panel-collapse" data-open="true">
                                <div class="panel-heading panel-collapse-trigger collapse in" data-toggle="collapse"
                                    data-target="#b4ae6923-e10f-aefb-6fa1-189cbe03d8f5" aria-expanded="true"
                                    style="">
                                    <h4 class="panel-title" style="font-weight: bold;">หัวข้อเกี่ยวกับหลักสูตร</h4>
                                </div>

                                <div id="b4ae6923-e10f-aefb-6fa1-189cbe03d8f5" class="collapse in">
                                    <div class="panel-body list-group">
                                        <ul class="list-group list-group-menu">
                                            <li class="list-group-item active"><a class="link-text-color"
                                                    href="{{ route('course.lesson', ['course_id' => $course_id, 'id' => $lesson_id]) }}">บทเรียน</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>




                            <div class="panel panel-primary" data-toggle="panel-collapse" data-open="true">
                                <div class="panel-heading panel-collapse-trigger collapse in" data-toggle="collapse"
                                    data-target="#a61ed05a-9654-8825-8ff5-72531f405beb" aria-expanded="true"
                                    style="">
                                    <h4 class="panel-title" style="font-size: 22px;font-weight: bold;">สรุปผลการเรียน</h4>
                                </div>

                                <div id="a61ed05a-9654-8825-8ff5-72531f405beb" class="collapse in">
                                    <div class="panel-body list-group">
                                        <ul class="list-group list-group-menu">


                                            <li class="list-group-item menu_li_padding"
                                                style="font-size: 20px;font-weight: bold;">ผลการเรียน<br>
                                                @php
                                                    foreach ($course_lesson as $less) {
                                                        $learns = Learn::where('lesson_id', $less->id)
                                                            ->where('user_id', Auth::user()->id)
                                                            ->get();
                                                        if ($learns->isEmpty()) {
                                                            echo "<p style='font-weight: normal;color: #045BAB;''><span style='color:blue;''>เรียนยังไม่ผ่าน</span></p>";
                                                        } else {
                                                            foreach ($learns as $lea) {
                                                                if ($lea->lesson_status == 'pass') {
                                                                    echo "<p style='font-weight: normal;color: #045BAB;''><span style='color:blue;''>เรียนผ่าน</span></p>";
                                                                } elseif (
                                                                    $lea->lesson_status == 'learning' ||
                                                                    $lea->lesson_status == null
                                                                ) {
                                                                    echo "<p style='font-weight: normal;color: #045BAB;''><span style='color:blue;''>กำลังเรียน</span></p>";
                                                                }
                                                            }
                                                        }
                                                    }
                                                @endphp

                                            </li>
                                            <li class="list-group-item menu_li_padding" style="font-size: 20px;font-weight: bold;">
                                                ผลการสอบก่อนเรียน<br>
                                                @php
                                                    $pre_score = Score::where('lesson_id', $lesson_id)
                                                        ->where('user_id', Auth::user()->id)
                                                        ->where('type', 'pre')
                                                        ->where('active', 'y')
                                                        ->orderBy('update_date', 'DESC')
                                                        ->first();
                                                @endphp

                                                @if(!$pre_score)
                                                    <p style="font-weight: normal;color: #045BAB;">-</p>
                                                @else
                                                    <p style="font-weight: normal;color: #045BAB;">
                                                        {{ $pre_score->score_past == 'y' ? 'ผ่านแบบทดสอบ' : 'ไม่ผ่านแบบทดสอบ' }}
                                                    </p>
                                                @endif
                                            </li>

                                            <li class="list-group-item menu_li_padding" style="font-size: 20px;font-weight: bold;">
                                                คะแนนที่ดีที่สุดก่อนเรียน<br>
                                                <p style="font-weight: normal;color: #045BAB;">
                                                    {{ $pre_score ? $pre_score->score_number.'/'.$pre_score->score_total : '0/0' }}
                                                </p>
                                            </li>
                                            <li class="list-group-item menu_li_padding"
                                                style="font-size: 20px;font-weight: bold;">สิทธิการทำแบบทดสอบ<br>

                                                <p style="font-weight: normal;color: #045BAB;">-</p>
                                            </li>
                                            <li class="list-group-item menu_li_padding">แบบสอบถาม<br>
                                                @php
                                                    $learn_chk = Learn::where([
                                                        'course_id' => $course_id,
                                                        'user_id' => Auth::user()->id,
                                                        'lesson_status' => 'pass',
                                                        'lesson_active' => 'y',
                                                    ])->get();
                                                    $count_learn = $learn_chk->count();
                                                    $count_lesson = $lesson_list->count();
                                                @endphp
                                                @if ($count_learn == $count_lesson)
                                                    {{-- <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                @if (Session::has('sweetAlert'))
                                                var sweetAlertData = @json(Session::get('sweetAlert'));
                                                swal({
                                                        title: sweetAlertData.title,
                                                        text: sweetAlertData.text,
                                                        icon: sweetAlertData.icon,
                                                        closeOnClickOutside: true,
                                                        closeOnEsc: true,
                                                        buttons: {
                                                            confirm: {
                                                                text: "ตกลง",
                                                                value: true,
                                                                visible: true,
                                                                className: "btn-primary",
                                                                closeModal: true
                                                            }
                                                        }
                                                    });
                                                @else
                                                swal({
                                                    title: "บทเรียนผ่าน",
                                                    text: "แบบทดสอบหลังเรียน",
                                                    icon: "info",
                                                    closeOnClickOutside: true,
                                                    closeOnEsc: true,
                                                    buttons: true // ไม่แสดงปุ่ม
                                                }).then(() => {
                                                    // Redirect to your link when the SweetAlert is closed
                                                    window.location.href = "{{ route('course.coursequestion', ['course_id' => $course_id ,'id' => $lesson_id]) }}";
                                                });
                                                @endif
                                                
                                            });
                                        </script> --}}
                                                    <p style="font-weight: normal;color: #045BAB;">ทำแบบทดสอบ</p>
                                                @else
                                                    <p style="font-weight: normal;color: #045BAB;">-</p>
                                                @endif
                                            </li>
                                            {{-- <li class="list-group-item menu_li_padding">
                                                <b>คะแนนผลการสอบ</b> <span style="color: #045BAB;">
                                                    @php
                                                        if (!$score) {
                                                            echo '0.00%';
                                                        } else {
                                                            $s = ($score->score_number / $score->score_total) * 100;
                                                            echo $s.'%';
                                                        }
                                                    @endphp
                                                </span>
                                            </li> --}}

                                            <li class="list-group-item menu_li_padding" style="font-size: 20px;font-weight: bold;">
                                                ผลการสอบหลังเรียน<br>
                                                @php
                                                    $lesson = Lesson::find($lesson_id);
                                                    $max_attempt = $lesson->cate_amount ?? 1;

                                                    $post_score_count = Score::where('lesson_id', $lesson_id)
                                                        ->where('user_id', Auth::user()->id)
                                                        ->where('type', 'post')
                                                        ->where('active', 'y')
                                                        ->count();

                                                    $remaining = $max_attempt - $post_score_count;

                                                    $post_score = Score::where('lesson_id', $lesson_id)
                                                        ->where('user_id', Auth::user()->id)
                                                        ->where('type', 'post')
                                                        ->where('active', 'y')
                                                        ->orderBy('score_number','DESC')
                                                        ->first();
                                                @endphp

                                                @if($remaining > 0)
                                                    <p style="font-weight: normal;color: #045BAB;">
                                                        <a href="{{ route('course.coursequestion', ['course_id'=>$course_id,'id'=>$lesson_id]) }}" style="cursor: pointer;">
                                                            ทำแบบทดสอบหลังเรียน
                                                        </a> (เหลือ {{ $remaining }} ครั้ง)
                                                    </p>
                                                @elseif($post_score)
                                                    <p style="font-weight: normal;color: #045BAB;">คุณทำแบบทดสอบครบจำนวนครั้งแล้ว</p>
                                                @else
                                                    <p style="font-weight: normal;color: #045BAB;">-</p>
                                                @endif
                                            </li>

                                            <li class="list-group-item menu_li_padding" style="font-size: 20px;font-weight: bold;">
                                                คะแนนที่ดีที่สุดหลังเรียน<br>
                                                <p style="font-weight: normal;color: #045BAB;">
                                                    {{ $post_score ? $post_score->score_number.'/'.$post_score->score_total : '0/0' }}
                                                </p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>




                            <div class="panel panel-primary" data-toggle="panel-collapse" data-open="true">
                                <div class="panel-heading panel-collapse-trigger collapse in" data-toggle="collapse"
                                    data-target="#338482d2-9782-f9f0-f7c9-4e51b69d603e" aria-expanded="true"
                                    style="">
                                    <h4 class="panel-title" style="font-size: 22px;font-weight: bold;">ผู้สอน</h4>
                                </div>
                                @php
                                    $teacher = Teacher::where('teacher_id', $course_detail->course_lecturer)->first();
                                @endphp
                                <div id="338482d2-9782-f9f0-f7c9-4e51b69d603e" class="collapse in">
                                    <div class="panel-body">
                                        <div class="media v-middle">
                                            <div class="media-left">
                                                @if ($teacher)
                                                    <img class="img-circle width-40"
                                                        src="{{ asset('images/uploads/teacher/' . $teacher->teacher_id . '/thumb/' . $teacher->teacher_picture) }}">
                                                @else
                                                    <img class="img-circle width-40"
                                                        src="{{ asset('themes/bws/images/default-avatar.png') }}"
                                                        alt="No Image">
                                                @endif
                                            </div>
                                            <div class="media-body">
                                                @if ($teacher)
                                                    <h4 class="text-title margin-none"><a
                                                            href="#">{{ $teacher->teacher_name }}</a>
                                                    @else
                                                        <h4>-</h4>
                                                @endif
                                                </h4>
                                                <span class="caption text-light">ชื่อวิทยากร</span>
                                            </div>
                                        </div>
                                        <br>

                                        <div class="expandable expandable-indicator-white expandable-trigger">
                                            <div class="expandable-content">
                                                <p></p>
                                                @if ($teacher)
                                                    <p><label id="lblPositionInfo">{!! htmlspecialchars_decode($teacher->teacher_detail) !!}</label></p>
                                                @else
                                                    <p> - </p>
                                                @endif
                                                <p></p>
                                                <div class="expandable-indicator"><i></i></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <script>
    document.addEventListener('contextmenu', event => event.preventDefault()); // Prevent right-click menu
    document.addEventListener('keydown', event => { // Prevent keyboard shortcuts
      if (event.ctrlKey && (event.key === 'S' || event.key === 's')) {
        event.preventDefault();
      }
    });
    document.addEventListener('mousedown', event => { // Prevent mouse down event
      if (event.button === 2) {
        event.preventDefault();
      }
    });
  </script>
    </body>
@endsection
