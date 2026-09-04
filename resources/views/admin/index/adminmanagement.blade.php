@extends('admin.layouts.dashboard-layout')

@section('dashboard-content')
<div class="main-content admin-dashboard" style="max-width: 100%; margin: 0 auto; padding: 0 20px;">
	<div class="container-fluid">

				<!-- แถบค้นหา: เหมือนกับ admindashboard.blade.php ทั้งหมด เพราะใช้ dropdown
				     กรองแบบเดียวกัน (department -> section -> line ผ่าน route('admin.org_children')) -->
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
							</div>
						</div>
					</div>
					</form>
				</section>

				<!-- SECTION 1: ปรับขนาด/style ให้เหมือน admindashboard.blade.php ทั้งหมด
				     (ใช้ .summary/.summary-header/.summary-body แทน .card-stat/.stat-* เดิม
				     และ 4 การ์ดในแถวเดียวแบบเดียวกับ admindashboard ไม่ใช้ .custom-5-col 3 คอลัมน์เดิม) -->
				<section class="container-fluid">
					<div class="row row-eq-height five-col custom-row-gap justify-content-center">

						{{-- พนักงานทั้งหมด --}}
						<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #6f42c1;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: rgba(111, 66, 193, 0.15);">
											<i class="fa-solid fa-user-group fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span style="display:block;">พนักงานทั้งหมด</span>
										<div><strong>{{ number_format($dashboard['summary']['total_users']) }}</strong> <span>คน</span></div>
									</div>
								</div>
							</div>
						</div>

						{{-- Completion Rate --}}
						<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #198754;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: rgba(25, 135, 84, 0.15);">
											<i class="fa-solid fa-circle-check fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span style="display:block;">Completion Rate</span>
										<div><strong>{{ $dashboard['summary']['completion_rate'] }}</strong> <span>%</span></div>
									</div>
								</div>
							</div>
						</div>

						{{-- Course Overdue --}}
						<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #e67e22;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: rgba(230, 126, 34, 0.15);">
											<i class="fa-solid fa-clock fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span style="display:block;">Course Overdue</span>
										<div><strong>{{ number_format($dashboard['summary']['overdue_courses']) }}</strong> <span>หลักสูตร</span></div>
									</div>
								</div>
							</div>
						</div>

						{{-- ต้องสอบซ่อม --}}
						<div class="col-lg-3 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #c62828;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: rgba(198, 40, 40, 0.15);">
											<i class="fa-solid fa-circle-exclamation fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span style="display:block;">ต้องสอบซ่อม</span>
										<div><strong>{{ number_format($dashboard['summary']['retry_users']) }}</strong> <span>คน</span></div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</section>

				<section class="section-2 row row-eq-height">
					<div class="col-lg-6 col-md-12 col-12">

						<div class="card h-100">

							<div class="card-header">
								<h5>
									Completion Rate ของแต่ละ Line
								</h5>
							</div>

							<div class="completion-rate-list">

								@forelse($dashboard['lineCompletion'] as $line)

									<div class="cr-row">

										<div class="cr-label">
											{{ $line['name'] }}
										</div>

										<div class="cr-bar-container">

											<div
												class="cr-bar"
												style="width: {{ $line['completion_rate'] }}%;"
											></div>

											<div class="cr-pct">
												{{ $line['completion_rate'] }}%
											</div>

										</div>

										<div class="cr-trend text-up">
											<i class="fa-solid fa-caret-up"></i>
											{{ $line['trend'] }}%
										</div>

									</div>

								@empty

									<div class="text-muted text-center p-3">
										ไม่พบข้อมูล
									</div>

								@endforelse


								<div class="cr-axis-row">

									<div style="width:80px; flex-shrink:0;"></div>

									<div class="cr-axis-labels">

										<span>0%</span>
										<span>25%</span>
										<span>50%</span>
										<span>75%</span>
										<span>100%</span>

									</div>

									<div style="width:90px; flex-shrink:0;"></div>

								</div>

							</div>

						</div>

					</div>

					<div class="col-lg-6 col-md-6 col-12">

						<div class="card h-100">

							<div class="card-header">

								<h5>
									Pass Rate ของแต่ละ Section
								</h5>

							</div>

							<div class="donut-chart-layout">

								<div class="donut-chart-wrapper">

									<canvas id="passRateChart"></canvas>

									<div class="donut-center-text">

										{{-- summary.pass_rate ถูกตัดออกจาก section-1 แล้ว (ตัดสินใจร่วมกับผู้ใช้ว่าซ้ำซ้อน)
										     ใส่ ?? 0 กันไว้ชั่วคราวเพื่อไม่ให้ error ตรงนี้ยังไม่ใช่ scope ที่แก้รอบนี้
										     (section-2 จะแก้ในรอบถัดไป) --}}
										<span class="pct">
											{{ $dashboard['summary']['pass_rate'] ?? 0 }}%
										</span>

										<span class="label">
											Pass Rate
										</span>

									</div>

								</div>


								<div class="custom-donut-legend">

									@foreach($dashboard['sectionPassRate'] as $section)

										<div class="legend-item-row">

											<div class="leg-left">

												<span
													class="leg-color"
													style="background:#3b82f6;"
												></span>

												{{ $section['name'] }}

											</div>

											<div class="leg-right">

												{{ $section['pass_rate'] }}%

											</div>

										</div>

									@endforeach

								</div>

							</div>

						</div>

					</div>

					<div class="col-lg-12 col-md-6 col-12">

						<div class="card h-100">

							<div class="card-header">

								<h5>
									Top 5 หลักสูตรที่ไม่ผ่านมากที่สุด
								</h5>

								<span class="sub-text">
									จำนวนผู้ไม่ผ่าน
								</span>

							</div>

							<div class="card-body">

								<div class="top5-list">

									@forelse($dashboard['failedCourses'] as $course)

										<div class="top5-item">

											<span class="top5-rank">
												{{ $course['rank'] }}
											</span>

											<span class="top5-name">
												{{ $course['title'] }}
											</span>

											<span class="top5-status">
												ไม่ผ่าน
											</span>

											<span class="top5-count">
												{{ number_format($course['failed_count']) }}
												คน
											</span>

										</div>

									@empty

										<div class="text-muted text-center p-3">
											ไม่พบข้อมูล
										</div>

									@endforelse

								</div>

							</div>

						</div>

					</div>
				</section>

				<section class="section-3 row row-eq-height">


					<div class="col-lg-6 col-md-6 col-12">
						<div class="card h-100">
							<div class="card-header">
								<h5>พนักงานใหม่ (ภายใน 120 วัน)</h5>
							</div>
							<div class="card-body" style="justify-content: center; align-items: center;">
								<div class="donut-chart-layout">
									<div class="donut-chart-wrapper">
										<canvas id="newEmployeeChart"></canvas>
										<div class="donut-center-text text-sm">
											<span class="label">Now</span>
											<span class="label">Employee</span>
											<span class="label">Progress</span>
										</div>
									</div>

									<div class="custom-donut-legend employee-legend">

										<div class="legend-item-row">
											<div class="leg-left">
												<span class="leg-color"></span>
												ครบ 30 วัน
											</div>

											<div class="leg-right">
												{{ $dashboard['newEmployees']['30'] }} คน
											</div>
										</div>


										<div class="legend-item-row">
											<div class="leg-left">
												<span class="leg-color"></span>
												ครบ 60 วัน
											</div>

											<div class="leg-right">
												{{ $dashboard['newEmployees']['60'] }} คน
											</div>
										</div>


										<div class="legend-item-row">
											<div class="leg-left">
												<span class="leg-color"></span>
												ครบ 90 วัน
											</div>

											<div class="leg-right">
												{{ $dashboard['newEmployees']['90'] }} คน
											</div>
										</div>


										<div class="legend-item-row">
											<div class="leg-left">
												<span class="leg-color"></span>
												ครบ 120 วัน
											</div>

											<div class="leg-right">
												{{ $dashboard['newEmployees']['120'] }} คน
											</div>
										</div>


										<div class="legend-item-row">
											<div class="leg-left">
												<span class="leg-color"></span>
												เกิน 120 วัน
											</div>

											<div class="leg-right">
												{{ $dashboard['newEmployees']['over120'] }} คน
											</div>
										</div>

									</div>
								</div>
							</div>
							<!-- <div class="card-footer"><a href="#" class="btn-outline-purple">ดูรายละเอียด</a></div> -->
						</div>
					</div>

					<div class="col-lg-6 col-md-6 col-12">
						<div class="card h-100">
							<div class="card-header">
								<h5>Team ที่มี Skill Gap สูงสุด</h5>
							</div>
							<div class="card-body">
								<div class="team-gap-list">
									<div class="team-gap-header">
										<span style="width:40px; text-align:center;">Rank</span>
										<span style="width:100px;">Team</span>
										<span style="flex:1; text-align:right;">Completion Rate</span>
									</div>
									<div class="team-gap-item">
										<span class="gap-rank">1</span><span class="gap-team">Line 4 - Team C</span>
										<div class="gap-bar-wrap">
											<div class="gap-bar-fill" style="width: 65%;"></div>
										</div>
										<span class="gap-pct">65%</span>
									</div>
									<div class="team-gap-item">
										<span class="gap-rank">2</span><span class="gap-team">Line 3 - Team B</span>
										<div class="gap-bar-wrap">
											<div class="gap-bar-fill" style="width: 60%;"></div>
										</div>
										<span class="gap-pct">60%</span>
									</div>
									<div class="team-gap-item">
										<span class="gap-rank">3</span><span class="gap-team">Line 2 - Team C</span>
										<div class="gap-bar-wrap">
											<div class="gap-bar-fill" style="width: 58%;"></div>
										</div>
										<span class="gap-pct">58%</span>
									</div>
									<div class="team-gap-item">
										<span class="gap-rank">4</span><span class="gap-team">Line 4 - Team B</span>
										<div class="gap-bar-wrap">
											<div class="gap-bar-fill" style="width: 55%;"></div>
										</div>
										<span class="gap-pct">55%</span>
									</div>
									<div class="team-gap-item">
										<span class="gap-rank">5</span><span class="gap-team">Line 5 - Team A</span>
										<div class="gap-bar-wrap">
											<div class="gap-bar-fill" style="width: 50%;"></div>
										</div>
										<span class="gap-pct">50%</span>
									</div>
								</div>
							</div>
							<!-- <div class="card-footer"><a href="#" class="btn-outline-purple">ดูรายละเอียด</a></div> -->
						</div>
					</div>
				</section>

				<section class="section-4 row row-eq-height">
					<div class="col-lg-12 col-12">
						<div class="card h-100">
							<div class="card-header">
								<h5>เปรียบเทียบผลการเรียนรู้ระหว่างแผนก (Department Comparison)</h5>
							</div>
							<div class="table-responsive">
								<table class="dept-table">
									<thead>
										<tr>
											<th>Department</th>
											<th>พนักงาน (คน)</th>
											<th>Completion Rate</th>
											<th>Pass Rate</th>
											<th>Course Overdue</th>
											<th>ต้องสอบซ่อม</th>
											<th>Skill Gap เฉลี่ย</th>
											<th></th>
										</tr>
									</thead>
									<tbody>

										@forelse($dashboard['departmentComparison'] as $department)

											<tr>

												<td>
													{{ $department['department'] }}
												</td>

												<td>
													{{ number_format($department['employees']) }}
												</td>

												<td>
													{{ $department['completion_rate'] }}%
												</td>

												<td>
													{{ $department['pass_rate'] }}%
												</td>

												<td>
													{{ number_format($department['overdue']) }}
												</td>

												<td>
													{{ number_format($department['retry']) }}
												</td>

												<td>
													{{ $department['skill_gap'] }}%
												</td>

												<td>

													<a
														href="#"
														class="btn-table-outline"
													>
														ดูรายละเอียด
													</a>

												</td>

											</tr>

										@empty

											<tr>

												<td
													colspan="8"
													class="text-center text-muted"
												>
													ไม่พบข้อมูล
												</td>

											</tr>

										@endforelse

										</tbody>
								</table>
							</div>
						</div>
					</div>
					<div class="col-lg-12 col-12">
						<div class="card h-100">
							<div class="card-header">
								<h5>แนวโน้มการเรียนรู้รายเดือน</h5>
							</div>
							<div class="line-chart-layout">
								<div class="line-chart-legend">
									<div class="leg-item"><span class="leg-color" style="background:#6b4ce6;"></span> Completion Rate (%)</div>
									<div class="leg-item"><span class="leg-color" style="background:#3b82f6;"></span> Pass Rate (%)</div>
									<div class="leg-item"><span class="leg-color" style="background:#ef4444;"></span> ต้องสอบซ่อม (คน)</div>
								</div>
								<div class="line-chart-wrapper">
									<canvas id="trendLineChart"></canvas>
								</div>
							</div>
							<!-- <div class="card-footer"><a href="#" class="btn-outline-purple">ดูรายละเอียด</a></div> -->
						</div>
					</div>
				</section>
			</div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $(function() {
        // dropdown filter (แผนก/ส่วนงาน/ไลน์) ใช้ logic เดียวกับ admindashboard.blade.php ทั้งหมด
        // เพราะเรียก endpoint org_children ตัวเดียวกัน (ส่ง type ไม่ใช่เดา level จาก response)
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
        function showLoadError($select, jqXHR, textStatus, label) {
            const gotHtmlInsteadOfJson = textStatus === 'parsererror';
            const sessionExpired = jqXHR.status === 401 || jqXHR.status === 419 || gotHtmlInsteadOfJson;

            fillSelect($select, [], sessionExpired
                ? 'เซสชันหมดอายุ กรุณาเข้าสู่ระบบใหม่'
                : 'โหลด' + label + 'ไม่สำเร็จ ลองเลือกใหม่อีกครั้ง');

            $select.prop('disabled', true);
        }

        // เติมช่อง "ไลน์ผลิต" จากส่วนงานที่เลือก
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

        // ช่วงเวลา: autoUpdateInput เป็น false เหมือน admindashboard เพื่อไม่ให้เติมวันที่วันนี้เองตอนโหลดหน้า
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

        $dateRange.on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
        });

        $dateRange.on('cancel.daterangepicker', function () {
            $(this).val('');
        });
    });

</script>
@endpush