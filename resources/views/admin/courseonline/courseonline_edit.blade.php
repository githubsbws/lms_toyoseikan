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
                                <label for="cate_id">หมวดอบรมออนไลน์</label>
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
                                <label for="teacher_name">ชื่อวิยากร</label>
                                <select class="form-control" name="teacher_name">
                                    <option value="{{ $course_detail->teacher->teacher_id ?? '-' }}">{{ $course_detail->teacher->teacher_name ?? '-'}}</option>
                                    @foreach($teacher as $t)
                                    <option value="{{ $t->teacher_id }}">{{ $t->teacher_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="course_title">ชื่อหลักสูตรอบรมออนไลน์</label>
                                <input type="text" name="course_title" class="form-control" value="{{ $course_detail->course_title }}">
                            </div>
                            <div class="form-group">
                                <label for="course_short_title">รายละเอียดย่อ</label>
                                <textarea name="course_short_title" id="summernote" class="form-control">{!!  htmlspecialchars_decode($course_detail->course_short_title) !!}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="course_detail">รายละเอียด </label>
                                <textarea name="course_detail" id="summernote2" class="form-control">{{ htmlspecialchars_decode(htmlspecialchars_decode($course_detail->course_detail)) }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="recommend">ปักหมุดหลักสูตรแนะนำ</label>
                                <div>
                                    @php
                                        $checked = $course_detail->recommend == 'y' ? 'checked' : '';
                                    @endphp
                                    <input type="checkbox" name="recommend" value="y" data-toggle="toggle" data-on="แสดง" data-off="ไม่แสดง" data-onstyle="success" data-offstyle="danger" {{ $checked }} />
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="course_note">หมายเหตุ</label>
                                <input type="text" name="course_note" class="form-control" value="{{ $course_detail->course_note }}">
                            </div>

                            <div class="form-group">
                                <div id="picture_show" style="">
                                    ภาพประกอบ <br>
                                    <img src="{{ asset('images/uploads/courseonline/'.$course_detail->course_id.'/original/'.$course_detail->course_picture) }}" name="course_picture"><br><br>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="course_picture">รูปภาพ</label>
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
</script>
</body>
@endsection
