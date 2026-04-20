@extends('admin/layouts/mainlayout')
@section('title', 'Admin')
@section('content')
<style>
    .cursor-pointer { cursor: grab; }
    .cursor-pointer:active { cursor: grabbing; }
    .list-group-item { border: 1px solid #eee !important; }
</style>
<body>
    <div id="warpper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="d-flex align-items-center">
                        <div class="">
                            <h4 class="m-0">เพิ่มRoadmapสำหรับพนังงานใหม่</h4>
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
                </div>
            </div>
            <div class="container mt-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        เพิ่มหลักสูตรใน Roadmap
                    </div>
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>บันทึกไม่สำเร็จ!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="card-body">
                        <form action="{{ route('roadmap.newemp.store') }}" method="POST" enctype="multipart/form-data" id="roadmapForm">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="font-weight-bold"><i class="fas fa-building mr-1"></i> แผนก (Department)</label>
                                        <input type="text" class="form-control bg-light" value="{{ Auth::user()->Department->title }}" readonly>
                                        <input type="hidden" name="department_id" value="{{ Auth::user()->department_org_id }}">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="sticky-top" style="top: 20px;">
                                        <div class="card shadow-sm">
                                            <div class="card-header bg-dark text-white d-flex justify-content-between">
                                                <span><i class="fas fa-archive mr-2"></i>คลังหลักสูตร</span>
                                            </div>
                                            <div class="card-body p-2 drag-area" id="courseInventory" style="max-height: 70vh; overflow-y: auto;">
                                                @forelse ( $newEmpCourse as $course )
                                                    <div class="list-group-item list-group-item-action mb-1 p-2 rounded draggable-item" data-id="{{ $course->course_id }}">
                                                        <small class="text-muted">หลักสูตร:</small> | {{ $course->course_title }}
                                                    </div>
                                                @empty
                                                    <div class="text-center p-4 text-muted" id="empty-msg">
                                                        <i class="fas fa-box-open mb-2"></i><br>
                                                        <small>ไม่พบหลักสูตรในคลังของแผนกคุณ</small>
                                                    </div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <h5 class="mb-3">เส้นทางบังคับ (4 เดือนแรก)</h5>
                                    <div id="roadmapAccordion">
                                        @foreach([30 => 'เดือนที่ 1', 60 => 'เดือนที่ 2', 90 => 'เดือนที่ 3', 119 => 'เดือนที่ 4'] as $day => $label)
                                        <div class="card mb-2">
                                            <div class="card-header py-2 bg-light cursor-pointer" data-toggle="collapse" data-target="#collapse-{{$day}}">
                                                <strong class="text-primary">{{ $label }}</strong>
                                                {{-- <span class="badge badge-pill badge-secondary float-right counter">0 วิชา</span> --}}
                                            </div>
                                            <div id="collapse-{{$day}}" class="collapse show">
                                                <div class="card-body p-3 drag-area" data-milestone="{{$day}}" id="month-{{$day}}" style="min-height: 80px; background-color: #fcfcfc; border: 1px dashed #ccc;">
                                                    </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>

                                    <div class="alert alert-info mt-4">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        <strong>Note:</strong> หลักสูตรที่ยังค้างอยู่ใน "คลังหลักสูตร" ฝั่งซ้าย จะถูกจัดอยู่ในหมวด <strong>"หลักสูตรเรียนรู้หลัง 4 เดือน"</strong> โดยอัตโนมัติ
                                    </div>
                                </div>
                                <input type="hidden" name="roadmap_items" id="roadmap_items_input">
                            </div>

                            <div class="text-right mt-4">
                                <button type="button" class="btn btn-lg btn-success shadow" onclick="submitRoadmap()">
                                    <i class="fas fa-save mr-2"></i> บันทึกข้อมูล Roadmap ทั้งหมด
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. ดึงพื้นที่ที่อนุญาตให้ลากวางทั้งหมดมา
        const dragAreas = document.querySelectorAll('.drag-area');

        // 2. วนลูปสร้าง Sortable ให้แต่ละพื้นที่
        dragAreas.forEach(area => {
            new Sortable(area, {
                group: 'roadmap_group', // ชื่อกลุ่ม (ต้องเหมือนกันถึงจะลากข้ามกันได้)
                animation: 150,        // ความเร็วตอนขยับ (มิลลิวินาที)
                ghostClass: 'bg-light-blue', // สีตอนที่กำลังลาก (แต่ง CSS เพิ่มได้)

                // จังหวะที่ลากของมาวาง
                onAdd: function (evt) {
                    console.log('ย้ายหลักสูตร ID:', evt.item.getAttribute('data-id'));
                    console.log('ไปที่:', evt.to.id); // ดูว่าไปลงเดือนไหน
                }
            });
        });
    });

    function submitRoadmap() {
        let roadmapData = [];
        let globalOrder = 1;

        // 1. ดึงพื้นที่เป้าหมาย (รายเดือน) ทั้งหมดมาวนลูป
        const allArea = document.querySelectorAll('.drag-area');
        allArea.forEach(target => {
            console.log(target.id);
            if(target.id !== 'courseInventory') {
            // ดึงค่า Milestone วันจาก attribute data-milestone (30, 60, 90, 119)
                const milestone = target.getAttribute('data-milestone');
                // หาหลักสูตรทั้งหมดที่ถูกลากมาวางในเดือนนี้
                const courses = target.querySelectorAll('.draggable-item');
                courses.forEach((course) => {
                    roadmapData.push({
                        course_id: course.getAttribute('data-id'), // จาก data-id="1"
                        milestone_days: milestone,
                        order: globalOrder // ลำดับที่วาง (บนลงล่าง)
                    });
                    globalOrder++;
                });
            }else{
                const remainingItems = target.querySelectorAll('.draggable-item');
                remainingItems.forEach((course) => {
                    roadmapData.push({
                        course_id: course.getAttribute('data-id'),
                        milestone_days: null,
                        order: null
                    });
                });
            }
        });
        console.log(roadmapData);
        // 2. ตรวจสอบเบื้องต้น (Validation)
        // if (roadmapData.length === 0) {
        //     alert('กรุณาลากหลักสูตรเข้าใน Roadmap อย่างน้อย 1 วิชาครับ');
        //     return;
        // }
        // 3. แปลง Array เป็น JSON String แล้วใส่ใน Hidden Input
        document.getElementById('roadmap_items_input').value = JSON.stringify(roadmapData);
        // 4. สั่งส่งฟอร์มไปที่ Controller
        document.getElementById('roadmapForm').submit();
    }
</script>
@endsection
