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
                            <h4 class="m-0">ระบบหมวดหลักสูตร</h4>
                        </div>
                        <div class="ml-3">
                            <a href="{{route('category')}}">
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
                        รายละเอียดหมวดหลักสูตร
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12 mb-2">
                                <div class="border rounded p-3 bg-light">
                                    <h6 class="text-secondary mb-2">ชื่อหมวดหลักสูตร</h6>
                                    <p class="mb-0 font-weight-bold">{{ $category->cate_title ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6 mb-2">
                                <div class="card border-secondary h-100">
                                    <div class="card-header bg-white font-weight-bold">รายละเอียดย่อ</div>
                                    <div class="card-body">
                                        {!! htmlspecialchars_decode($category->cate_short_detail ?? '-') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-2">
                                <div class="card border-secondary h-100">
                                    <div class="card-header bg-white font-weight-bold">รายละเอียด</div>
                                    <div class="card-body">
                                        {!! htmlspecialchars_decode($category->cate_detail ?? '-') !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-secondary">
                                    <div class="card-header bg-white font-weight-bold">ภาพประกอบ</div>
                                    <div class="card-body text-center">
                                        @if(!empty($category->cate_image))
                                            <img src="{{ asset('images/uploads/category/'.$category->cate_id.'/original/'. $category->cate_image) }}" alt="รูปภาพ" class="img-fluid" />
                                        @else
                                            <div class="text-muted py-4">ไม่มีภาพประกอบ</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
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
