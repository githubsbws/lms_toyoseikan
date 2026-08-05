@extends('admin.layouts.dashboard-layout')

@section('dashboard-content')
<div class="main-content admin-dashboard" style="max-width: 100%; margin: 0 auto; padding: 0 20px;">
            <div class="container-fluid">
				<!-- SECTION 1 -->
				<section>
					<form method="GET" action="{{ route('admin') }}" id="dashboardFilterForm">
					<div class="row row-eq-height row-filter">
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<div style="margin-top: auto; width: 100%;">
								<span>ช่วงเวลา</span>
								<div class="input-group date-input">
									<input type="text" id="dateSec3" name="date_range" class="form-control" style="border-right: none;">
									<span class="input-group-addon" style="background:#fff;">
										<i class="fa-regular fa-calendar"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<div class="card" style="border: rgb(62, 31, 146) 2px solid; padding: 10px !important;">
								<strong class="card-header">แผนก</strong>
								<div class="card-body">
									<select name="department_id" id="filterDepartment" class="form-control">
										<option value="" @selected(!request('department_id'))>ทั้งหมด</option>
                                        @foreach ( $dept as $depts)
                                            <option value="{{ $depts->id }}" @selected(request('department_id') == $depts->id)>{{ $depts->title }}</option>
                                        @endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<div class="card" style="border: rgb(62, 31, 146) 2px solid; padding: 10px !important;">
								<strong class="card-header">ส่วนงาน</strong>
								<div class="card-body">
									<select name="section_id" id="filterSection" class="form-control" {{ request('department_id') ? '' : 'disabled' }}>
										<option value="" selected>ทั้งหมด</option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<div class="card" style="border: rgb(62, 31, 146) 2px solid; padding: 10px !important;">
								<strong class="card-header">ไลน์ผลิต</strong>
								<div class="card-body">
									<select name="line_id" id="filterLine" class="form-control" {{ request('section_id') ? '' : 'disabled' }}>
										<option value="" selected>ทั้งหมด</option>
									</select>
									<small class="text-muted" id="filterLineNote" style="display:none;">แผนกนี้ไม่มีไลน์ผลิต</small>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<div class="card" style="border: rgb(62, 31, 146) 2px solid; padding: 10px !important;">
								<strong class="card-header">ทีม</strong>
								<div class="card-body">
									<select name="team_id" id="filterTeam" class="form-control">
										<option value="" @selected(!request('team_id'))>ทั้งหมด</option>
                                        @foreach ( $team as $teams)
                                            <option value="{{ $teams->id }}" @selected(request('team_id') == $teams->id)>{{ $teams->name }}</option>
                                        @endforeach
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 fix-export">
							<div style="width: 100%; display:flex; gap: 5px;">
								<button type="submit" class="btn btn-primary w-50" style="border: rgb(62, 31, 146) 2px solid; background: rgb(62, 31, 146); color: white;">
									<i class="fa-solid fa-filter"></i> <strong>ค้นหา</strong>
								</button>
								<button type="button" class="btn btn-default w-50" style="border: rgb(62, 31, 146) 2px solid; color: rgb(62, 31, 146);">
									<i class="fa-solid fa-download"></i> <strong>Export</strong>
								</button>
							</div>
						</div>
					</div>
					</form>
				</section>

				<!-- SECTION 2 -->
				<section class="container-fluid">
					<div class="row row-eq-height four-col custom-row-gap justify-content-center" >
						<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #0d6efd;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: color-mix(in srgb, #0d6efd 15%, transparent);">
											<i class="fa-solid fa-book-open fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span>คอร์สทั้งหมด</span><strong>{{ number_format($dashboard['overview']['total_courses']) }}</strong><span>หลักสูตร</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #198754;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: color-mix(in srgb, #198754 15%, transparent);">
											<i class="fa-regular fa-file-lines fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span>เนื้อหาทั้งหมด</span><strong>{{ number_format($dashboard['overview']['total_files']) }}</strong><span>ไฟล์</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #6f42c1;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: color-mix(in srgb, #6f42c1 15%, transparent);">
											<i class="fa-solid fa-user-group fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span>ผู้ใช้ทั้งหมด</span><strong>{{ number_format($dashboard['overview']['total_users']) }}</strong><span>คน</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
							<div class="card">
								<div class="summary">
									<div class="summary-header">
										<div style="padding: 10px; background-color: color-mix(in srgb, #ffc107 15%, transparent);">
											<i class="fa-solid fa-user-clock fa-xl" style="color: #ffc107;"></i>
										</div>
									</div>
									<div class="summary-body">
										<strong style="font-size: large;">การอนุมัติที่รออยู่</strong>
										<h5><span style="color: #ffc107; font-weight:bold; font-size: large;">{{ $dashboard['overview']['pending_approvals'] }}</span>
											รายการ</h5>
									</div>
								</div>
							</div>
						</div>
					</div>

				</section>

				<!-- SECTION 3 -->
				<section class="section3">
					<div class="row row-eq-height custom-row-gap">
						<div class="col-lg-4 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header"><strong>คอร์สที่ต้องติดตาม (Overdue)</strong></div>
								<div class="card-body">
									<div style="display: flex; flex-direction: column; gap: 10px;">
										@forelse ($dashboard['overdueCourses'] as $index => $course)
										<div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
											<div style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
												<div style="color: #dc3545; display: flex; flex-direction: row; gap:5px; align-items: center;">
													<i class="fa-solid fa-circle"></i><strong>{{ $index + 1 }}</strong>
												</div>
												<div style="display: flex; flex-direction: column;">
													<strong>{{ $course['title'] }}</strong>
													<span style="color: gray;">ครบกำหนด {{ optional($course['deadline'])->format('d/m/Y') }}</span>
												</div>
											</div>
											<div style="display: flex; flex-direction: row; align-items: center; gap:5px;">
												<strong>{{ $course['unfinished'] }}</strong>คน
											</div>
										</div>
										@empty
										<span class="text-muted">ไม่มีคอร์สที่ต้องติดตาม</span>
										@endforelse
									</div>
								</div>

							</div>
						</div>
						<div class="col-lg-4 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header"><strong>ภาพรวมการเรียนรู้</strong></div>
								<div class="card-body">
									<div class="row row-eq-height custom-row-gap" style="margin-bottom: 10px;">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="card" style="padding: 10px; padding-bottom: 0;">
												<div class="card-header">การเรียนรู้เสร็จสิ้น</div>
												<div class="card-body">
													<div class="summary">
														<div style="position: relative; width: 70px; height: 70px;">
															<div id="donut_single" style="width: 70px; height: 70px;">
																<div style="position: relative;">
																	<div dir="ltr" style="position: relative; width: 70px; height: 70px;">
																		<div aria-label="A chart." style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%;"><svg width="70" height="70" aria-label="A chart." style="overflow: hidden;">
																				<defs id="_ABSTRACT_RENDERER_ID_0"></defs>
																				<rect x="0" y="0" width="70" height="70" stroke="none" stroke-width="0" fill="#ffffff"></rect>
																				<g>
																					<path d="M10.93396235714713,30.409157792649744L0.619946224495898,28.441653989499635A35,35,0,0,1,35,0L34.99999999999999,10.5A24.5,24.5,0,0,0,10.93396235714713,30.409157792649744" stroke="#ffffff" stroke-width="1" fill="#f5f5f5"></path>
																				</g>
																				<g>
																					<path d="M11.699115350768743,42.570916362186225L1.7130219296696296,45.815594803123176A35,35,0,0,1,0.6199462244958838,28.441653989499677L10.93396235714712,30.409157792649776A24.5,24.5,0,0,0,11.699115350768743,42.570916362186225" stroke="#ffffff" stroke-width="1" fill="#00ffff"></path>
																				</g>
																				<g>
																					<path d="M35,10.5L35,0A35,35,0,1,1,1.7130219296696296,45.815594803123176L11.699115350768743,42.570916362186225A24.5,24.5,0,1,0,35,10.5" stroke="#ffffff" stroke-width="1" fill="#008000"></path>
																				</g>
																				<g></g>
																			</svg>

																		</div>
																	</div>
																	<div aria-hidden="true" style="display: none; position: absolute; top: 80px; left: 80px; white-space: nowrap; font-family: Arial; font-size: 7px;"></div>
																	<div></div>
																</div>
															</div>
															<div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: #198754; pointer-events: none;">
																78%</div>
														</div>
														<div class="summary-body">
															<h3 style="margin: 0; color: #198754; font-weight: bold;">78%
															</h3>

														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="card" style="padding: 10px;">
												<div class="card-header">อัตราการเข้าเรียน</div>
												<div class="card-body" style="display: flex; align-items: center; height: 100%;">
													<div class="summary">
														<div class="summary-header">
															<div style="background-color: color-mix(in srgb, #6f42c1 15%, transparent); padding-inline: 10px; border-radius: 50%;">
																<i class="fa-solid fa-user-group fa-lg" style="color: #6f42c1;"></i>
															</div>
														</div>
														<div class="summary-body">
															<div style="display: flex; flex-direction: row; gap:5px; align-items: end;">
																<h3 style="margin: 0; font-weight: bold;">356</h3>คน
															</div>

														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
							<div class="card">
								<div class="card-header"><strong>สถานะระบบ</strong></div>
								<div class="card-body">
									<div style="display: flex; flex-direction: column; gap: 5px;">
										<div style="display: flex; flex-direction: row; width: 100%; justify-content: space-between; align-items: end;">
											<div style="display: flex; flex-direction: column; width: 90%;">
												<div style="display: flex; flex-direction: row; justify-content: space-between;">
													<span>พื้นที่จัดเก็บ</span>
													<span>266 GB / 1 TB</span>
												</div>
												<div class="progress" style="height: 15px; width: 100%; margin: 2px;">
													<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100" style="width: 25%;">
													</div>
												</div>
											</div>
											<span>25%</span>
										</div>
										<div class="card">
											<div style="display: flex; flex-direction: row; justify-content: space-between;">
												<span>ผู้ใช้งานออนไลน์</span>
												<div style="display: flex; flex-direction:row; gap: 5px; align-items:center;">
													<strong>45 คน</strong>

												</div>
											</div>
										</div>
										<div class="card">
											<div style="display: flex; flex-direction: row; justify-content: space-between;">
												<span>การใช้งานวันนี้</span>
												<div style="display: flex; flex-direction:row; gap: 5px; align-items:center;">
													<strong>1,246 ครั้ง</strong>

												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</section>

				<!-- SECTION 4 -->
				<section>
					<div class="row row-eq-height custom-row-gap">
						<div class="col-lg-5 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header"><strong>การเรียนรู้ตามแผนก</strong></div>
								<div class="card-body" style="font-size: smaller;">
									<div class="table-responsive">
										<table class="table table-condensed table-no-border">
											<thead>
												<tr>
													<th>แผนก</th>
													<th>ผู้เรียน (คน)</th>
													<th>คอร์สที่ต้องเรียน</th>
													<th>ผ่านแล้ว (รวม)</th>
													<th>Completion Rate</th>
												</tr>
											</thead>
											<tbody>
												@forelse ($dashboard['departmentLearning'] as $dept)
												<tr>
													<td>{{ $dept['department'] }}</td>
													<td>{{ $dept['learner_count'] }}</td>
													<td>{{ $dept['total_courses'] }}</td>
													<td>{{ $dept['passed_count'] }}</td>
													<td>{{ $dept['completion_rate'] }}%</td>
												</tr>
												@empty
												<tr>
													<td colspan="5" class="text-center text-muted">ไม่มีข้อมูล</td>
												</tr>
												@endforelse
											</tbody>
										</table>
									</div>
								</div>

							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-sm-12">
							<div class="card">
								<div class="card-header"><strong>หลักสูตรที่นักเรียนมากที่สุด</strong></div>
								{{-- <div class="card-body" style="font-size: small;">
									<div style="display: flex; flex-direction: column; gap: 5px;">
										@php $maxLearner = $dashboard['popularCourses']->max('learner_count') ?: 1; @endphp
										@forelse ($dashboard['popularCourses'] as $course)
										<div style="display:flex; flex-direction: row; justify-content:space-between;">
											<div style="display:flex; flex-direction: row; align-items: center; gap:20px;">
												<h4 style="margin: 0; font-weight:bold;">{{ $course['rank'] }}</h4>
												<div style="display: flex; flex-direction: column;">
													<strong>{{ $course['title'] }}</strong>
													<span>ผู้เรียน {{ $course['learner_count'] }} คน</span>
												</div>
											</div>
											@php $percent = round(($course['learner_count'] / $maxLearner) * 100); @endphp
											<div style="display:flex; flex-direction: row; align-items: center; gap: 5px; width: 40%">
												<div class="progress" style="height: 15px; flex: 1; margin-bottom: 0;">
													<div class="progress-bar" role="progressbar" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $percent }}%;">
													</div>
												</div>
												<span style="white-space: nowrap;">{{ $percent }}%</span>
											</div>
										</div>
										@empty
										<span class="text-muted">ไม่มีข้อมูลหลักสูตร</span>
										@endforelse
									</div>
								</div> --}}

							</div>
						</div>

					</div>
				</section>
			</div>
</div>

@endsection

@push('scripts')
<script type="text/javascript">
    $(function() {
        const LINE_LEVEL = '{{ \App\Services\AdminDashboardService::LINE_LEVEL }}';
        const POSITION_LEVEL = '{{ \App\Services\AdminDashboardService::POSITION_LEVEL }}';
        const orgChildrenUrl = "{{ route('admin.org_children') }}";

        const $department = $('#filterDepartment');
        const $section = $('#filterSection');
        const $line = $('#filterLine');
        const $lineNote = $('#filterLineNote');

        // เติม option ให้ select ตัวใดตัวหนึ่ง
        function fillSelect($select, items, placeholder = 'ทั้งหมด') {
            $select.empty();
            $select.append($('<option>', { value: '', text: placeholder }));
            items.forEach(function (item) {
                $select.append($('<option>', { value: item.id, text: item.title }));
            });
        }

        // ดึงลูกของ orgchart node จาก server
        function fetchOrgChildren(parentId) {
            return $.getJSON(orgChildrenUrl, { parent_id: parentId });
        }

        // เมื่อเลือกแผนก: โหลดส่วนงาน แล้วรีเซ็ตไลน์
        $department.on('change', function () {
            const deptId = $(this).val();

            fillSelect($section, []);
            fillSelect($line, []);
            $section.prop('disabled', true);
            $line.prop('disabled', true);
            $lineNote.hide();

            if (!deptId) {
                return;
            }

            fetchOrgChildren(deptId).done(function (sections) {
                if (sections.length > 0) {
                    fillSelect($section, sections);
                    $section.prop('disabled', false);
                }
            });
        });

        // เมื่อเลือกส่วนงาน: โหลดไลน์ (หรืออาจได้ตำแหน่งมาตรงถ้าไม่มีไลน์)
        $section.on('change', function () {
            const sectionId = $(this).val();

            fillSelect($line, []);
            $line.prop('disabled', true);
            $lineNote.hide();

            if (!sectionId) {
                return;
            }

            fetchOrgChildren(sectionId).done(function (children) {
                if (children.length === 0) {
                    return;
                }

                const firstLevel = String(children[0].level);

                if (firstLevel === LINE_LEVEL) {
                    // แผนกนี้มีไลน์ผลิต
                    fillSelect($line, children);
                    $line.prop('disabled', false);
                    $lineNote.hide();
                } else if (firstLevel === POSITION_LEVEL) {
                    // แผนกนี้ไม่มีไลน์ ข้ามไปตำแหน่งเลย (filter ยังไม่รองรับ position)
                    $line.prop('disabled', true);
                    $lineNote.show();
                }
            });
        });

        // Init: ถ้ามีค่า department_id จาก query string ให้โหลด section/line กลับมา
        const selectedDeptId = '{{ request('department_id') }}';
        const selectedSectionId = '{{ request('section_id') }}';
        const selectedLineId = '{{ request('line_id') }}';

        if (selectedDeptId) {
            fetchOrgChildren(selectedDeptId).done(function (sections) {
                if (sections.length > 0) {
                    fillSelect($section, sections);
                    $section.prop('disabled', false);

                    if (selectedSectionId) {
                        $section.val(selectedSectionId);

                        fetchOrgChildren(selectedSectionId).done(function (children) {
                            if (children.length === 0) return;

                            const firstLevel = String(children[0].level);

                            if (firstLevel === LINE_LEVEL) {
                                fillSelect($line, children);
                                $line.prop('disabled', false);

                                if (selectedLineId) {
                                    $line.val(selectedLineId);
                                }
                            } else if (firstLevel === POSITION_LEVEL) {
                                $line.prop('disabled', true);
                                $lineNote.show();
                            }
                        });
                    }
                }
            });
        }

        $('#dateSec3').daterangepicker({
            autoUpdateInput: true,
            locale: {
                format: 'DD/MM/YYYY',
                separator: ' - ',
                applyLabel: 'เลือก',
                cancelLabel: 'ล้าง',
                fromLabel: 'จาก',
                toLabel: 'ถึง',
                customRangeLabel: 'กำหนดเอง',
                weekLabel: 'W',
                daysOfWeek: ['อา', 'จ', 'อ', 'พ', 'พฤ', 'ศ', 'ส'],
                monthNames: [
                    'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                    'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
                ],
                firstDay: 1
            }
        });
    });

</script>
@endpush
