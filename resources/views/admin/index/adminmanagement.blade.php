@extends('admin.layouts.dashboard-layout')

@section('dashboard-content')
<div class="main-content admin-dashboard" style="max-width: 100%; margin: 0 auto; padding: 0 20px;">
	<div class="container-fluid">

				<section class="section-1 row">

					{{-- พนักงาน --}}
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 custom-5-col">
						<div class="card card-stat color-purple">

							<div class="stat-icon">
								<i class="fa-solid fa-users"></i>
							</div>

							<div class="stat-content">

								<div class="stat-title">
									พนักงานทั้งหมด
								</div>

								<div class="stat-value-row">

									<span class="stat-qty">
										{{ number_format($dashboard['summary']['total_users']) }}
									</span>

									<span class="stat-unit">
										คน
									</span>

								</div>

							</div>
						</div>
					</div>


					{{-- Completion --}}
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 custom-5-col">

						<div class="card card-stat color-green">

							<div class="stat-icon">
								<i class="fa-solid fa-circle-check"></i>
							</div>

							<div class="stat-content">

								<div class="stat-title2">
									Completion Rate
								</div>

								<div class="stat-value-row">

									<span class="stat-qty2">
										{{ $dashboard['summary']['completion_rate'] }}%
									</span>

								</div>

							</div>
						</div>
					</div>


					{{-- Pass Rate --}}
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 custom-5-col">

						<div class="card card-stat color-blue">

							<div class="stat-icon">
								<i class="fa-solid fa-medal"></i>
							</div>

							<div class="stat-content">

								<div class="stat-title3">
									Pass Rate
								</div>

								<div class="stat-value-row">

									<span class="stat-qty3">
										{{ $dashboard['summary']['pass_rate'] }}%
									</span>

								</div>

							</div>
						</div>
					</div>


					{{-- Overdue --}}
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 custom-5-col">

						<div class="card card-stat color-orange">

							<div class="stat-icon">
								<i class="fa-solid fa-clock"></i>
							</div>

							<div class="stat-content">

								<div class="stat-title4">
									Course Overdue
								</div>

								<div class="stat-value-row">

									<span class="stat-qty4">
										{{ number_format($dashboard['summary']['overdue_courses']) }}
									</span>

									<span class="stat-unit">
										หลักสูตร
									</span>

								</div>

							</div>
						</div>
					</div>


					{{-- Retry --}}
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 custom-5-col">

						<div class="card card-stat color-red">

							<div class="stat-icon">
								<i class="fa-solid fa-circle-exclamation"></i>
							</div>

							<div class="stat-content">

								<div class="stat-title">
									ต้องสอบซ่อม
								</div>

								<div class="stat-value-row">

									<span class="stat-qty5">
										{{ number_format($dashboard['summary']['retry_users']) }}
									</span>

									<span class="stat-unit">
										คน
									</span>

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

										<span class="pct">
											{{ $dashboard['summary']['pass_rate'] }}%
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