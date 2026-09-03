@extends('admin.layouts.dashboard-layout')

@section('dashboard-content')
<style>
	/* หลักสูตรที่นักเรียนมากที่สุด */
	.popular-course-list {
		display: flex;
		flex-direction: column;
	}

	.popular-course-row {
		display: flex;
		align-items: center;
		gap: 12px;
		padding: 10px 0;
		border-bottom: 1px solid #f0f0f0;
	}

	.popular-course-row:last-child {
		border-bottom: none;
	}

	.popular-course-rank {
		flex: 0 0 24px;
		height: 24px;
		display: inline-flex;
		align-items: center;
		justify-content: center;
		border-radius: 50%;
		background-color: #f1f3f5;
		color: #6c757d;
		font-size: 12px;
		font-weight: 700;
	}

	.popular-course-row:nth-child(1) .popular-course-rank {
		/* ใช้ rgba ไม่ใช่ color-mix() เพราะ html2canvas (ปุ่ม Export) parse color-mix ไม่ได้
		   ค่าที่ได้เท่ากันเป๊ะ: color-mix(in srgb, #3e1f92 12%, transparent) = rgba(62,31,146,.12) */
		background-color: rgba(62, 31, 146, 0.12);
		color: #3e1f92;
	}

	.popular-course-title {
		flex: 1 1 auto;
		min-width: 0;
		font-size: 13px;
		line-height: 1.4;
		color: #212529;
		overflow: hidden;
		text-overflow: ellipsis;
		white-space: nowrap;
	}

	.popular-course-count {
		flex: 0 0 auto;
		font-size: 12px;
		color: #6c757d;
		white-space: nowrap;
	}

	.popular-course-count strong {
		font-size: 14px;
		color: #212529;
	}
</style>
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
									<input type="text" id="dateSec3" name="date_range" class="form-control" style="border-right: none;" value="{{ request('date_range') }}" placeholder="ทั้งหมด" autocomplete="off" readonly>
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
									<small class="text-muted" id="filterLineNote" style="display:none;">ส่วนงานนี้ไม่มีไลน์ผลิต</small>
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
								<button type="button" id="btnExportImage" class="btn btn-default w-50" style="border: rgb(62, 31, 146) 2px solid; color: rgb(62, 31, 146);">
									<i class="fa-solid fa-download"></i> <strong>Export</strong>
								</button>
							</div>
						</div>
					</div>
					</form>
				</section>

				<!-- SECTION 2 -->
				<section class="container-fluid">
					<div class="row row-eq-height five-col custom-row-gap justify-content-center" >
						<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #0d6efd;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: rgba(13, 110, 253, 0.15);">
											<i class="fa-solid fa-book-open fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span style="display:block;">คอร์สพนักงานทั่วไป</span>
										<div><strong>{{ number_format($dashboard['overview']['total_courses_general']) }}</strong> <span>หลักสูตร</span></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #0dcaf0;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: rgba(13, 202, 240, 0.15);">
											<i class="fa-solid fa-user-graduate fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span style="display:block;">คอร์สพนักงานใหม่</span>
										<div><strong>{{ number_format($dashboard['overview']['total_courses_new_employee']) }}</strong> <span>หลักสูตร</span></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #0d6efd;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: rgba(13, 110, 253, 0.15);">
											<i class="fa-solid fa-video fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span style="display:block;">วิดีโอ</span>
										<div><strong>{{ number_format($dashboard['overview']['total_videos']) }}</strong> <span>ไฟล์</span></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #198754;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: rgba(25, 135, 84, 0.15);">
											<i class="fa-regular fa-file-lines fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span style="display:block;">ไฟล์</span>
										<div><strong>{{ number_format($dashboard['overview']['total_documents']) }}</strong> <span>ไฟล์</span></div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #6f42c1;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: rgba(111, 66, 193, 0.15);">
											<i class="fa-solid fa-user-group fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span>ผู้ใช้ทั้งหมด</span><strong> {{ number_format($dashboard['overview']['total_users']) }} </strong><span>คน</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #9e7804;">
								<div class="summary">
									<div class="summary-header">
										<div style="padding: 10px; background-color: rgba(255, 193, 7, 0.15);">
											<i class="fa-solid fa-user-clock fa-xl" style="color: #ffc107;"></i>
										</div>
									</div>
									<div class="summary-body">
										<span style="display:block;">ข้อสอบที่รอตรวจ</span>
										<div><strong>{{ number_format($dashboard['overview']['pending_approvals']) }}</strong> <span>รายการ</span></div>
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
															<div style="background-color: rgba(111, 66, 193, 0.15); padding-inline: 10px; border-radius: 50%;">
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
									<div style="display: flex; flex-direction: column;">
										<div>
											<div style="display: flex; flex-direction: row; justify-content: space-between; align-items: baseline;">
												<span style="font-size: 13px; color: #6c757d;">พื้นที่จัดเก็บ</span>
												<span style="font-size: 13px; font-weight: 600;">{{ $dashboard['systemStatus']['disk_used_gb'] }} GB / {{ $dashboard['systemStatus']['disk_total_gb'] }} GB</span>
											</div>
											<div style="display: flex; flex-direction: row; align-items: center; gap: 8px; margin-top: 6px;">
												<div class="progress" style="height: 10px; width: 100%; margin: 0;">
													<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="{{ $dashboard['systemStatus']['disk_used_percent'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $dashboard['systemStatus']['disk_used_percent'] }}%;">
													</div>
												</div>
												<span style="font-size: 12px; color: #6c757d; white-space: nowrap;">{{ $dashboard['systemStatus']['disk_used_percent'] }}%</span>
											</div>
										</div>
										<div class="card" style="margin-top: 20px;">
											<div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
												<span style="font-size: 16px; color: #6c757d;">ผู้ใช้งานออนไลน์ทั้งหมดในระบบ</span>
												<strong style="font-size: 16px;">{{ number_format($dashboard['systemStatus']['online_users']) }} คน</strong>
											</div>
										</div>
										{{-- <div class="card" style="margin-top: 10px; padding: 10px 12px;">
											<div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
												<span style="font-size: 13px; color: #6c757d;">การใช้งานวันนี้</span>
												<strong style="font-size: 14px;">{{ number_format($dashboard['systemStatus']['today_usage']) }} ครั้ง</strong>
											</div>
										</div> --}}
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
								<div class="card-body">
									<div class="popular-course-list">
										@forelse ($dashboard['popularCourses'] as $course)
										<div class="popular-course-row">
											<span class="popular-course-rank">{{ $course['rank'] }}</span>
											<span class="popular-course-title" title="{{ $course['title'] }}">{{ $course['title'] }}</span>
											<span class="popular-course-count">
												<strong>{{ number_format($course['learner_count']) }}</strong> คน
											</span>
										</div>
										@empty
										<span class="text-muted">ไม่มีข้อมูลหลักสูตร</span>
										@endforelse
									</div>
								</div>

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
        // บอก server ว่าอยากได้ "ชนิด" ไหน ไม่ส่งเลข level ไปเอง เพราะโครงสร้าง org
        // ลึกไม่เท่ากันทุกสาย (สาย HR ไม่มีชั้นไลน์ ทำให้ตำแหน่งไปอยู่ level เดียวกับ
        // ไลน์ผลิตของสายปกติ) ปล่อยให้ service เป็นคนตัดสินที่เดียว
        const TYPE_SECTION = '{{ \App\Services\AdminDashboardService::ORG_TYPE_SECTION }}';
        const TYPE_LINE = '{{ \App\Services\AdminDashboardService::ORG_TYPE_LINE }}';
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

        // ดึงตัวเลือกจาก server ตามชนิดที่ขอ
        function fetchOrgChildren(parentId, type) {
            return $.getJSON(orgChildrenUrl, { parent_id: parentId, type: type });
        }

        // โหลดตัวเลือกไม่สำเร็จ: บอกผู้ใช้ในช่องนั้นเลย ไม่ปล่อยให้ค้างว่างเงียบ ๆ
        //
        // การเช็ค session หมดอายุที่นี่ดูเป็น 2 แบบ เพราะระบบตอบกลับไม่เหมือนกัน:
        // - 401 มาจาก controller เอง (ล็อกอินอยู่แต่ไม่ใช่ admin)
        // - parsererror ทั้งที่ status 200 มาจาก middleware CheckIdleTimeout /
        //   CheckTokenValidityAdmin ที่ใช้ redirect() ไปหน้า login ไม่ได้คืน 401
        //   jQuery จะตาม redirect ไปเองแล้วได้ HTML หน้า login กลับมา พอ dataType เป็น json
        //   จึง parse ไม่ผ่าน อาการนี้คือ session หมดอายุ ไม่ใช่เซิร์ฟเวอร์พัง
        function showLoadError($select, jqXHR, textStatus, label) {
            const gotHtmlInsteadOfJson = textStatus === 'parsererror';
            const sessionExpired = jqXHR.status === 401 || jqXHR.status === 419 || gotHtmlInsteadOfJson;

            fillSelect($select, [], sessionExpired
                ? 'เซสชันหมดอายุ กรุณาเข้าสู่ระบบใหม่'
                : 'โหลด' + label + 'ไม่สำเร็จ ลองเลือกใหม่อีกครั้ง');

            $select.prop('disabled', true);
        }

        // เติมช่อง "ไลน์ผลิต" จากส่วนงานที่เลือก
        // ถ้าสายงานนั้นไม่มีไลน์ (เช่น HR ที่จบที่ส่วนงานแล้วไปตำแหน่งเลย) จะได้ [] กลับมา
        // ให้ปิดช่องไว้พร้อมบอกเหตุผล ไม่ใช่ปิดเงียบ ๆ ให้ผู้ใช้เดา
        function loadLines(sectionId, selectedId) {
            return fetchOrgChildren(sectionId, TYPE_LINE).done(function (lines) {
                if (lines.length > 0) {
                    fillSelect($line, lines);
                    $line.prop('disabled', false);
                    $lineNote.hide();

                    if (selectedId) {
                        $line.val(selectedId);
                    }
                } else {
                    $line.prop('disabled', true);
                    $lineNote.show();
                }
            }).fail(function (jqXHR, textStatus) {
                $lineNote.hide();
                showLoadError($line, jqXHR, textStatus, 'ไลน์ผลิต');
            });
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

            fetchOrgChildren(deptId, TYPE_SECTION).done(function (sections) {
                if (sections.length > 0) {
                    fillSelect($section, sections);
                    $section.prop('disabled', false);
                } else {
                    // แผนกที่ไม่มีส่วนงานผูกไว้ บอกไปตรง ๆ ดีกว่าปล่อยช่องว่างให้เดา
                    fillSelect($section, [], 'ไม่มีส่วนงาน');
                    $section.prop('disabled', true);
                }
            }).fail(function (jqXHR, textStatus) {
                showLoadError($section, jqXHR, textStatus, 'ส่วนงาน');
            });
        });

        // เมื่อเลือกส่วนงาน: โหลดไลน์ของส่วนงานนั้น
        $section.on('change', function () {
            const sectionId = $(this).val();

            fillSelect($line, []);
            $line.prop('disabled', true);
            $lineNote.hide();

            if (!sectionId) {
                return;
            }

            loadLines(sectionId);
        });

        // Init: ถ้ามีค่า department_id จาก query string ให้โหลด section/line กลับมา
        const selectedDeptId = '{{ request('department_id') }}';
        const selectedSectionId = '{{ request('section_id') }}';
        const selectedLineId = '{{ request('line_id') }}';

        if (selectedDeptId) {
            fetchOrgChildren(selectedDeptId, TYPE_SECTION).done(function (sections) {
                if (sections.length === 0) {
                    return;
                }

                fillSelect($section, sections);
                $section.prop('disabled', false);

                if (selectedSectionId) {
                    $section.val(selectedSectionId);
                    loadLines(selectedSectionId, selectedLineId);
                }
            }).fail(function (jqXHR, textStatus) {
                showLoadError($section, jqXHR, textStatus, 'ส่วนงาน');
            });
        }

        // ช่วงเวลา: autoUpdateInput เป็น false เพื่อให้ "เปิดหน้ามาแล้วยังไม่กรองเวลา"
        // ถ้าเป็น true ปลั๊กอินจะเติมวันที่ของวันนี้ลงช่องให้เองทันทีที่โหลด
        // ทำให้ค่าเริ่มต้นกลายเป็นกรองแค่วันนี้วันเดียวโดยที่ผู้ใช้ไม่ได้เลือก
        // ช่องจะมีค่าเฉพาะเมื่อผู้ใช้กด "เลือก" เอง หรือส่งค่ากลับมาจาก request เท่านั้น
        const $dateRange = $('#dateSec3');

        $dateRange.daterangepicker({
            autoUpdateInput: false,
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

        // กด "เลือก" = ใส่ค่าลงช่องเอง (เพราะปิด autoUpdateInput ไว้)
        $dateRange.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        // กด "ล้าง" = เคลียร์ช่องให้กลับไปเป็นไม่กรองเวลา
        $dateRange.on('cancel.daterangepicker', function () {
            $(this).val('');
        });

        // ===== Export: แคปหน้า dashboard ทั้งหน้าเป็นรูป PNG =====
        //
        // แคปฝั่ง browser ด้วย html2canvas เพราะได้ภาพตรงกับที่ผู้ใช้เห็นจริง
        // (รวมผลของ filter ที่เลือกอยู่) โดยไม่ต้องมี headless browser ฝั่ง server
        //
        // จุดที่ต้องจัดการ ไม่ใช่เรียก html2canvas แล้วจบ:
        // - ต้องแคปให้ครบความสูงจริงของเนื้อหา ไม่ใช่แค่ส่วนที่เห็นในจอ
        // - ต้องใส่พื้นหลังขาว เพราะค่าเริ่มต้นของ canvas เป็นโปร่งใส เปิดใน viewer
        //   บางตัวจะกลายเป็นพื้นดำ
        // - ต้องไม่แคปตัวปุ่ม Export เอง (ตอนกดมันจะอยู่ในสถานะกำลังโหลด ติดไปในภาพ)
        const $exportBtn = $('#btnExportImage');
        const exportBtnHtml = $exportBtn.html();

        $exportBtn.on('click', function () {
            // html2canvas-pro ปล่อย global มาเป็นชื่อไหนขึ้นกับรุ่นของ bundle
            // เลยหาให้ครบทั้งสองชื่อ ไม่เดาชื่อเดียวแล้วพังเงียบ ๆ ตอนอัปเวอร์ชัน
            const capture = window.html2canvas || window.html2canvasPro;

            // CDN โหลดไม่ติด (เน็ตองค์กรบล็อก / ออฟไลน์) บอกผู้ใช้ตรง ๆ ดีกว่าปล่อยให้กดแล้วเงียบ
            if (typeof capture !== 'function') {
                alert('ยังโหลดตัวช่วยบันทึกภาพไม่สำเร็จ กรุณาตรวจสอบอินเทอร์เน็ตแล้วรีเฟรชหน้าอีกครั้ง');
                return;
            }

            const target = document.querySelector('.main-content.admin-dashboard');

            if (!target) {
                alert('ไม่พบพื้นที่เนื้อหาสำหรับบันทึกภาพ');
                return;
            }

            $exportBtn.prop('disabled', true)
                .html('<i class="fa-solid fa-spinner fa-spin"></i> <strong>กำลังบันทึก...</strong>');

            capture(target, {
                backgroundColor: '#ffffff',
                scale: 2,                    // ให้ตัวหนังสือคมพออ่านได้ ไม่เบลอ
                useCORS: true,               // รูป/ไอคอนที่มาจาก CDN จะไม่หลุดหาย
                scrollX: 0,
                scrollY: 0,
                windowWidth: target.scrollWidth,
                windowHeight: target.scrollHeight,
                ignoreElements: function (el) {
                    return el.id === 'btnExportImage';
                }
            }).then(function (canvas) {
                const stamp = moment().format('YYYYMMDD_HHmmss');
                const link = document.createElement('a');

                link.download = 'dashboard_' + stamp + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            }).catch(function (err) {
                console.error('Export screenshot failed:', err);
                alert('บันทึกภาพไม่สำเร็จ กรุณาลองอีกครั้ง');
            }).finally(function () {
                $exportBtn.prop('disabled', false).html(exportBtnHtml);
            });
        });
    });

</script>
@endpush
