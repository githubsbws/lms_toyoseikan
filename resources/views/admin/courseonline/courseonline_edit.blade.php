@extends('admin/layouts/mainlayout')
@section('title', 'Admin')
@section('content')
<body>
    <div id="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <h4 class="m-0">ระบบหลักสูตรนิสิต/นักศึกษา</h4>
                        </div>
                        <div class="ml-3">
                            <a href="{{route('courseonline')}}">
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
                        แก้ไขหลักสูตรนิสิต/นักศึกษา
                    </div>
                    <div class="card-body">
                        <form action="{{ route('courseonline.edit', ['id'=>$course_detail->course_id]) }}" enctype="multipart/form-data" method="post" id="question-form">
                            @csrf
                            <div class="form-group">
                                <label for="cate_id"><u>หมวดอบรมออนไลน์</u></label>
                                <select class="form-control" name="cate_id">
                                    <option value="">ทั้งหมด</option>
                                    @foreach ($category as $cate_id => $cate_title)
                                        <option value="{{ $cate_id }}"
                                            {{ $course_detail->cate_id == $cate_id ? 'selected' : '' }}>
                                            {{ $cate_title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="teacher_name"><u>ชื่อวิยากร</u></label>
                                <select class="form-control" name="teacher_name">
                                    <option value="{{ $course_detail->teacher->teacher_id ?? '-' }}">{{ $course_detail->teacher->teacher_name ?? '-'}}</option>
                                    @foreach($teacher as $t)
                                    <option value="{{ $t->teacher_id }}">{{ $t->teacher_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="course_title"><u>ชื่อหลักสูตรอบรมออนไลน์</u></label>
                                <input type="text" name="course_title" class="form-control" value="{{ $course_detail->course_title }}">
                            </div>
                            <div class="form-group">
                                <label for="course_short_title"><u>รายละเอียดย่อ</u></label>
                                <textarea name="course_short_title" id="summernote" class="form-control">{!!  htmlspecialchars_decode($course_detail->course_short_title) !!}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="course_detail"><u>รายละเอียด</u></label>
                                <textarea name="course_detail" id="summernote2" class="form-control">{{ htmlspecialchars_decode(htmlspecialchars_decode($course_detail->course_detail)) }}</textarea>
                            </div>

                            <hr class="my-4" style="border-top: 2px solid #eee;">

                            <div class="form-group">
                                <label for=""><u>ระยะเวลาของหลักสูตร</u></label>
                                <div class="col-12 mt-2">
                                    <input type="checkbox" id="onboarding" name="onboarding" @checked($course_detail->is_onboarding ?? false)>
                                    <label for="onboarding">เป็นหลักสูตรสำหรับพนักงานใหม่<span class="text-danger">(หลักสูตรถาวร-หากเลือกแล้วช่องวันที่จะหายไป)</span></label>
                                </div>
                                <div id="date-select">
                                    <div class="my-2" >
                                        <label for="start_date" class="col-12">วันที่เริ่มหลักสูตร</label>
                                        <div class="col-4">
                                            <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $course_detail->start_date ?? '' }}">
                                        </div>
                                    </div>

                                    <div class="mt-2">
                                        <label for="end_date" class="col-12">วันที่ปิดหลักสูตร</label>
                                        <div class="col-4">
                                            <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $course_detail->end_date ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4" style="border-top: 2px solid #eee;">

                            <div class="form-group">
                                <label for=""><u>ผลกระทบต่อ License Person</u></label>
                                <div class="col-4 ml-2">
                                    <label for="">Operating Machine</label>
                                    <select class="form-control" name="op_mac_id" id="">
                                        <option value="1">silo</option>
                                    </select>
                                </div>

                                <div class="col-4 m-2">
                                    <label for="">Parameter Setting</label>
                                    <select class="form-control" name="par_st_id" id="">
                                        <option value="1">silo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for=""><u>เกณฑ์นํ้าหนักคะแนน<span class="text-danger">(ไม่จำเป็นต้องใส่หมดใส่เท่าที่หลักสูตรกำหนด)</span></u></label>
                                <div class="row m-2">
                                    <label class="col-1">ถาม-ตอบ:</label>
                                        <div class="col-sm-2">
                                            <input class="form-control form-control-sm" type="number" placeholder="0" name="w_q_and_a" value="{{ $course_detail->courseWeight->q_a_weight ?? '' }}">
                                        </div>
                                    <div class="col-sm-1 p-0">%</div>
                                </div>

                                <div class="row m-2">
                                    <label class="col-1">ปฏิบัติจริง:</label>
                                    <div class="col-sm-2">
                                        <input class="form-control form-control-sm" type="number" placeholder="0" name="w_operate" value="{{ $course_detail->courseWeight->operate_weight ?? '' }}">
                                    </div>
                                    <div class="col-sm-1 p-0">%</div>
                                </div>

                                <div class="row m-2">
                                    <label class="col-1">สังเกตุ:</label>
                                    <div class="col-sm-2">
                                        <input class="form-control form-control-sm" type="number" placeholder="0" name="w_observe" value="{{ $course_detail->courseWeight->observe_weight ?? '' }}">
                                    </div>
                                    <div class="col-sm-1 p-0">%</div>
                                </div>

                                <div class="row m-2">
                                    <label class="col-1">ข้อสอบ:</label>
                                    <div class="col-sm-2">
                                        <input class="form-control form-control-sm" type="number" placeholder="0" name="w_exam" value="{{ $course_detail->courseWeight->exam_weight ?? '' }}">
                                    </div>
                                    <div class="col-sm-1 p-0">%</div>
                                </div>

                                <div class="row m-2">
                                    <label class="col-1">ผลงาน:</label>
                                    <div class="col-sm-2">
                                        <input class="form-control form-control-sm" type="number" placeholder="0" name="w_assign" value="{{ $course_detail->courseWeight->assign_weight ?? '' }}">
                                    </div>
                                    <div class="col-sm-1 p-0">%</div>
                                </div>
                            </div>

                            <hr class="my-4" style="border-top: 2px solid #eee;">

                            <div class="form-group">
                                <label for=""><u>เลือกสายที่ต้องการให้เห็นหลักสูตร</u></label>
                            </div>
                            <div class="form-group" id="org_jstree"></div>

                            <div class="form-group">
                                <label for="course_note"><u>หมายเหตุ</u></label>
                                <input type="text" name="course_note" class="form-control" value="{{ $course_detail->course_note }}">
                            </div>

                            <div class="form-group">
                                <div id="picture_show" style="">
                                    ภาพประกอบ <br>
                                    <img src="{{ asset('images/uploads/courseonline/'.$course_detail->course_id.'/original/'.$course_detail->course_picture) }}" name="course_picture"><br><br>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="course_picture"><u>รูปภาพ</u></label>
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="input-append">
                                        <div class="uneditable-input span3">
                                            <i class="icon-file fileupload-exists"></i>
                                            <span class="fileupload-preview"></span>
                                        </div>
                                        <img id="previewImage" src="#" alt="Preview Image" style="display: none;">
                                        <span class="btn btn-default btn-file">
                                            <span class="fileupload-new">Select file</span>
                                            <span class="fileupload-exists">Change</span>
                                            <input id="ytNews_cms_picture" type="hidden" value="{{$course_detail->course_picture}}" name="course_picture">
                                            <input name="image" id="imageInput"  type="file" >
                                        </span>
                                        <a href="#" class="btn fileupload-exists" data-dismiss="fileupload">Remove</a>
                                        {{-- <input type="file" id="imageInput" name="image"> --}}

                                    </div>
                                    <script>
                                        document.addEventListener('DOMContentLoaded', function() {
                                            var imageInput = document.getElementById('imageInput');
                                            var previewImage = document.getElementById('previewImage');

                                            imageInput.addEventListener('change', function() {
                                                previewImageFile(this);
                                            });

                                            function previewImageFile(input) {
                                                var file = input.files[0];
                                                if (file) {
                                                    var reader = new FileReader();
                                                    reader.onload = function(e) {
                                                        previewImage.src = e.target.result;
                                                        previewImage.style.display = 'block';
                                                    };
                                                    reader.readAsDataURL(file);
                                                }
                                            }
                                        });
                                    </script>
                                </div>
                            </div>

                            <div class="form-group">
                                <font color="#990000">
                                    รูปภาพควรมีขนาด 250x180(แนวนอน) หรือ ขนาด 250x(xxx) (แนวยาว)
                                </font>
                            </div>

                            <input type="hidden" name="org_ids" id="org_ids">

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
<script>
    $(document).ready(function() {
        $('#summernote').summernote();
        });

    $(document).ready(function() {
        $('#summernote2').summernote();
        });
    $(document).ready(function() {
        if($('#onboarding').is(':checked')){
            $('#date-select').hide();
        }

        $('#onboarding').change(function(){
            if($(this).is(':checked')){
                $('#date-select').hide();
            }else{
                $('#date-select').show();
            }
        })
    });
    $(document).ready(function () {
        var OrgChartTree = @json($orgtree);
        // console.log(OrgChartTree);
        $('#org_jstree').jstree({
            'core': {
            'data': OrgChartTree, // ใช้ข้อมูลที่เรา map มา
            'themes': {
                'dots': true, // มีเส้นประเชื่อมกิ่งเหมือน Registry Editor
                'icons': true,
                'responsive': true,
            }
        },
        'plugins': ["checkbox"] // เพิ่ม wholerow ให้จิ้มง่าย และ search ให้ค้นหาได้
        });

        $('#org_jstree').on("changed.jstree", function (e, data) {
            const tree = data.instance;

            const leafNodes = data.selected.filter(id => {
                return tree.is_leaf(id);
            })
            // console.log(leafNodes);
            $('#org_ids').val(leafNodes.join(','));
        });
    });
</script>
</body>
@endsection
