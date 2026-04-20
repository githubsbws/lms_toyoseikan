@extends('admin/layouts/mainlayout')
@section('title', 'Admin')
@section('content')
<body>
    <div id="warpper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <h4 class="m-0">ระบบRoadmapสำหรับพนังงานใหม่</h4>
                        </div>
                        <div class="ml-3">
                            <a href="{{route('admin')}}">
                                <button class="btn btn-warning d-flex align-items-center">
                                    <i class="fas fa-angle-left mr-2"></i>
                                    หน้าหลัก
                                </button>
                            </a>
                        </div>
                    </div>

                    <div class="card shadow-sm mt-4">
                        <div class="card-header bg-white">
                            <h5 class="card-title m-0 text-dark">จัดการ Roadmap</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    @if($newEmpRoadmap)
                                        <a href="{{ route('roadmap.newemp.edit',$newEmpRoadmap->id) }}" class="btn btn-primary btn-block">
                                            <i class="fas fa-plus-circle mr-1"></i> แก้ไข Roadmap
                                        </a>
                                    @else
                                        <a href="{{ route('roadmap.newemp.create') }}" class="btn btn-primary btn-block">
                                            <i class="fas fa-plus-circle mr-1"></i> เพิ่มหลักสูตรเข้า Roadmap
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>บันทึกสำเร็จ</strong>
                        </div>
                    @endif
                    <div class="row">
                        @php
                            $months =  \App\Models\Roadmap::getMilestones();
                            $groupedData = ($newEmpRoadmap->roadmapCourse ?? collect())->groupBy('milestone_days');
                        @endphp
                        @foreach($months as $key => $m)
                        <div class="col-12 mb-4">
                            <div class="card border-left-{{$m['color']}} shadow-sm">
                                <div class="card-header bg-white d-flex align-items-center">
                                    <span class="badge badge-{{$m['color']}} mr-3">M{{$key}}</span>
                                    <h6 class="m-0 font-weight-bold text-{{$m['color']}}">{{$m['title']}}</h6>
                                    <small class="ml-2 text-muted">({{$m['days']}})</small>
                                </div>
                                <div class="card-body p-0">
                                    <table class="table table-hover table-striped mb-0">
                                        <thead class="small text-uppercase bg-light">
                                            <tr>
                                                <th width="10%" class="text-center">ลำดับ</th>
                                                <th width="60%">ชื่อหลักสูตร</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {{-- ดึงวิชาที่ตรงกับ milestone_days ของเดือนนี้มาแสดง --}}
                                            @php
                                                $coursesInMonth = $groupedData->get($m['val']);
                                            @endphp
                                            @if($coursesInMonth && $coursesInMonth->count() > 0)
                                                @foreach($coursesInMonth->sortBy('order') as $index => $item)
                                                <tr>
                                                    <td class="text-center">{{ $index + 1 }}</td>
                                                    <td>
                                                        <strong>{{ $item->course->course_title }}</strong><br>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td colspan="2" class="text-center py-3 text-muted">
                                                        <small>ไม่มีหลักสูตรสำหรับช่วงเดือนนี้</small>
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
    </div>
</body>
@endsection
