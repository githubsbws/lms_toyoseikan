@extends('admin/layouts/mainlayout')
@section('title', 'Admin')
@section('content')
	<div id="wrapper">
		<div class="content-wrapper">
			<div class="content-header">
				<div class="container-fluid">
					<div class="d-flex align-items-center">
						<div class="">
							<h4 class="m-0">Learning Potential</h4>
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
                    <div class="card m-0">
                        <div class="card-header">
                            <form action="" method="GET">
                                <div class="row align-items-end">
                                    <div class="col-md-4">
                                        <label>ค้นหาชื่อ หรือรหัสพนักงาน</label>
                                        <input type="text" name="search" class="form-control" placeholder="ค้นหาข้อมูลในตาราง...">
                                    </div>
                                    <div class="col-md-3">
                                        <label>หัวข้ออบรม</label>
                                        <select name="course" class="form-control">
                                            <option value="">-- เลือกหัวข้ออบรม --</option>
                                            <option value="Safety">Safety</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            <i class="fa fa-search"></i> ค้นหา
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
				</div>
			</div>
			<div class="content">
				<div class="container-fluid">
					<div class="card m-0">
						<div class="card-body">
							<div class="table-responsive">
                                <table id="settingTable" class="table table-bordered text-center align-middle" style="width:100%">
                                    <thead class="bg-light">
                                        <tr>
                                            <th colspan="4" class="bg-warning-light" style="background-color: #ffffcc;">หัวข้ออบรม ({{ $courseName ?? 'Safety' }})</th>
                                            <th colspan="8" style="background-color: #ffffff;">ประเมินศักยภาพผู้เข้าอบรม</th>
                                        </tr>
                                        <tr>
                                            <th rowspan="2" style="background-color: #ccffcc;">ที่</th>
                                            <th rowspan="2" style="background-color: #ccffcc;">ชื่อ - สกุล</th>
                                            <th rowspan="2" style="background-color: #ccffcc;">รหัสพนักงาน</th>
                                            <th rowspan="2" style="background-color: #ccffcc;">ตำแหน่ง</th>
                                            <th colspan="5" style="background-color: #ffff99;">ผลประเมินศักยภาพผู้ผ่านการอบรม</th>
                                            <th rowspan="2" style="background-color: #ffff99; width: 80px;">การประเมินศักยภาพ</th>
                                            <th rowspan="2" style="background-color: #ffff99; width: 80px;">การประเมินผลิตภาพ</th>
                                            <th rowspan="2" style="background-color: #ffff99;">ลายเซ็น (Signature)</th>
                                        </tr>
                                        <tr>
                                            <th style="background-color: #ffff99; font-size: 12px;">1. ความรู้จากการฝึกอบรม</th>
                                            <th style="background-color: #ffff99; font-size: 12px;">2. ทักษะในการปฏิบัติงาน</th>
                                            <th style="background-color: #ffff99; font-size: 12px;">3. ทัศนคติที่มีต่อการปฏิบัติงาน</th>
                                            <th style="background-color: #ffff99; font-size: 12px;">4. การแก้ปัญหาในการทำงาน</th>
                                            <th style="background-color: #ffff99; font-size: 12px;">5. ความตระหนักในด้านการทำงาน</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td class="text-left">นาย A</td>
                                            <td>100000</td>
                                            <td>Supervisor</td>
                                            <td></td> <td></td> <td></td> <td></td> <td></td> <td class="text-muted" style="font-size: 11px;">คงเดิม</td>
                                            <td class="text-muted" style="font-size: 11px;">คงเดิม</td>
                                            <td></td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td class="text-left">นาย B</td>
                                            <td>200000</td>
                                            <td>Group Leader</td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-muted" style="font-size: 11px;">คงเดิม</td>
                                            <td class="text-muted" style="font-size: 11px;">คงเดิม</td>
                                            <td></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-3">
                                <p class="text-primary mb-1"><strong>Remark</strong></p>
                                <table class="table-sm border-0">
                                    <tr>
                                        <td width="30" class="font-weight-bold">3</td>
                                        <td class="text-primary">แปรผันตรงกับ Skill 1 หรือ 2</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">2</td>
                                        <td class="text-primary">แปรผันตรงกับ Skill 0</td>
                                    </tr>
                                    <tr>
                                        <td class="font-weight-bold">0</td>
                                        <td class="text-primary">หัวข้อไม่ส่งผลกระทบศักยภาพ</td>
                                    </tr>
                                </table>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
