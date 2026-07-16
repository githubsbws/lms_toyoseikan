		<div class="main-content admin-manager">

				<div class="container-fluid">

					<!-- SECTION 1 -->
					<section class="section1">
						<div class="row row-eq-height custom-row-gap seven-col">
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-3">
								<div class="card">
									<div class="card-body">
										<div class="summary">
											<div class="summary-header">
												<div class="summary-icon"
													style="background-color: color-mix(in srgb, var(--primary-color) 15%, transparent);">
													<i class="fa-solid fa-users fa-xl" style="color: var(--primary-color);"></i>
												</div>
											</div>
											<div class="summary-body">
												<strong>พนักงานในทีมทั้งหมด</strong>
												<strong>
													<span style="font-size: x-large;">{{ $count_all_team }}</span> คน
												</strong>
												<span>ทั้งหมดในไลน์ {{ $count_all_line }} คน</span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-3">
								<div class="card">
									<div class="card-body">
										<div class="summary">
											<div class="summary-header">
												<div class="summary-icon"
													style="background-color: color-mix(in srgb, var(--success-color) 15%, transparent);">
													<i class="fa-solid fa-clipboard-check fa-xl"
														style="color: var(--success-color);"></i>
												</div>
											</div>
											<div class="summary-body">
												<strong>อัตราการเรียนครบ</strong>
												<strong style="font-size: x-large;">{{ $course_user_roadmap['per_pass'] }} %</strong>
												<span>{{ $course_user_roadmap['pass'] }} จาก {{ $course_user_roadmap['total_user'] }} คน</span>

											</div>
										</div>
									</div>
									{{-- <div class="card-footer-end" style="font-size: smaller;">
										<span><strong style="color: var(--success-color);"><i class="fa-solid fa-caret-up"></i>
												8%</strong> จากเดือนที่แล้ว</span>
									</div> --}}
								</div>
							</div>

							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-3">
								<div class="card">
									<div class="card-body">
										<div class="summary">
											<div class="summary-header">
												<div class="summary-icon"
													style="background-color: color-mix(in srgb, var(--orange-color) 15%, transparent);">
													<i class="fa-solid fa-book-open fa-xl"
														style="color: var(--orange-color);"></i>
												</div>
											</div>
											<div class="summary-body">
												<strong>หลักสูตรที่กำลังเรียน</strong>
												<strong>
													<span style="font-size: x-large;">{{ $course_roadmap['count_course'] }}</span> หลักสูตร
												</strong>
												{{-- <span>กำลังเรียน 48 รายการ</span> --}}
												{{-- <span>หลักสูตรที่เปิดอยู่ {{ $course_roadmap['open'] }} หลักสูตรที่ปิดอยู่ {{ $course_roadmap['close'] }} </span> --}}
												<span> หลักสูตรที่ปิดอยู่ {{ $course_roadmap['close'] }} </span>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-3">
								<div class="card">
									<div class="card-body">
										<div class="summary">
											<div class="summary-header">
												<div class="summary-icon"
													style="background-color: color-mix(in srgb, var(--danger-color) 15%, transparent);">
													<i class="fa-solid fa-triangle-exclamation fa-xl"
														style="color: var(--danger-color);"></i>
												</div>
											</div>
											<div class="summary-body">
												<strong>ผู้เรียนค้างกำหนด</strong>
												<strong>
													<span style="font-size: x-large;">{{ $course_user_roadmap['not_pass'] }}</span> คน
												</strong>
												<span>คิดเป็น {{ $course_user_roadmap['per_not'] }} % ของทีม</span>
											</div>
										</div>
									</div>
									{{-- <div class="card-footer-end" style="font-size: smaller;">
										<span><strong style="color: var(--danger-color);"><i class="fa-solid fa-caret-up"></i>
												2 คน</strong> จากเดือนที่แล้ว</span>
									</div> --}}
								</div>
							</div>
							{{-- <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-3">
								<div class="card">
									<div class="card-body">
										<div class="summary">
											<div class="summary-header">
												<div class="summary-icon"
													style="background-color: color-mix(in srgb, var(--purple-color) 15%, transparent);">
													<i class="fa-solid fa-clock fa-xl" style="color: var(--purple-color);"></i>
												</div>
											</div>
											<div class="summary-body" style="min-width: 0;">
												<strong>ชั่วโมงการเรียนเฉลี่ย</strong>
												<strong>
													<span style="font-size: x-large;">14.2</span> ชม.
												</strong>
												<span>ต่อคน / ต่อเดือน</span>
											</div>
										</div>
									</div>
									<div class="card-footer-end" style="font-size: smaller;">
										<span><strong style="color: var(--success-color);"><i class="fa-solid fa-caret-up"></i>
												2.3 ชั่วโมง</strong> จากเดือนที่แล้ว</span>
									</div>
								</div>
							</div> --}}
							<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-3">
								<div class="card">
									<div class="card-body">
										<div class="summary">
											<div class="summary-header">
												<div class="summary-icon"
													style="background-color: color-mix(in srgb, var(--primary-color) 15%, transparent);">
													<i class="fa-solid fa-star fa-xl" style="color: var(--primary-color);"></i>
												</div>
											</div>
											<div class="summary-body" style="min-width: 0;">
												<strong>คะแนนเฉลี่ยแบบทดสอบ</strong>
												<strong style="font-size: x-large;">
													{{ $avgPercent }}%
												</strong>
												<span>จากทุกหลักสูตร</span>
											</div>
										</div>
									</div>
									{{-- <div class="card-footer-end" style="font-size: smaller;">
										<span><strong style="color: var(--success-color);"><i class="fa-solid fa-caret-up"></i>
												4%</strong> จากเดือนที่แล้ว</span>
									</div> --}}
								</div>
							</div>
							<div class="col-lg-9 col-md-12 col-sm-12 mb-3">
								<div class="card">
									<div class="card-header1" style="display:flex;justify-content:space-between;align-items:center;">

										<strong>สถานะการเรียนรู้ของพนักงานในทีม</strong>

										<div class="input-group" style="width:350px;">

											<input
												type="text"
												id="keyword"
												class="form-control"
												placeholder="ค้นหาชื่อ - นามสกุล">

										</div>

									</div>
									<div class="card-body">
										<div class="space-between employee-header" style="margin-bottom: 5px;">
										</div>
									<div id="team-learning-wrapper" style="position:relative;">

										<div id="loading-overlay"
											style="
												display:none;
												position:absolute;
												top:0;
												left:0;
												right:0;
												bottom:0;
												background:rgba(255,255,255,.6);
												z-index:999;
												align-items:center;
												justify-content:center;">

											<div class="text-center">
												<i class="fa fa-spinner fa-spin fa-2x"></i>
												<div style="margin-top:10px;">กำลังค้นหาข้อมูล...</div>
											</div>

										</div>

										<div class="table-responsive" style="font-size:smaller;" id="team-learning-table">

											@include('admin.index.partials.team-learning')

										</div>

									</div>
								</div>
							</div>
							{{-- <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 mb-3">
								<div class="card">
									<div class="card-body">
										<div class="summary">
											<div class="summary-header">
												<div class="summary-icon"
													style="background-color: color-mix(in srgb, var(--success-color) 15%, transparent);">
													<i class="fa-solid fa-shield fa-xl"
														style="color: var(--success-color);"></i>
												</div>
											</div>
											<div class="summary-body" style="min-width: 0;">
												<strong>การอบรมบังคับครบท่วน</strong>
												<strong style="font-size: x-large;">
													90%
												</strong>
												<span>25 จาก 28 คน</span>
											</div>
										</div>
									</div>
									<div class="card-footer-end" style="font-size: smaller;">
										<span><strong style="color: var(--success-color);"><i class="fa-solid fa-caret-up"></i>
												5%</strong> จากเดือนที่แล้ว</span>
									</div>
								</div> --}}
							</div>
						</div>
					</section>

					<!-- SECTION 2 -->
					<section>
						<div class="row row-eq-height custom-row-gap">
							<div class="col-lg-6 col-md-6 col-sm-12 mb-3">
								<div class="card">
									<div class="card-header space-between">
										<strong>แนวโน้มการเรียนรู้ของทีม</strong>
										{{-- <select name="" id="" class="form-control"
											style="width: fit-content; font-size: smaller;">
											<option value="" selected>อัตราการเรียนครบ (%)</option>
										</select> --}}
									</div>
									<div class="card-body" style="flex: 1; min-height: fit-content;">
										<div id="team-learning-trends" style="width: 100%; height: 100%;"></div>
									</div>
								</div>
							</div>
							{{-- <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12 mb-3">
								<div class="card">
									<div class="card-header">
										<strong>สถานะการอบรมบังคับ (Mandatory)</strong>
									</div>
									<div class="card-body">
										<div class="summary mandatory">
											<div class="mandatory-donut"
												style="position: relative; width: 150px; height: 150px;">
												<div id="donut_single" style="width: 150px; height: 150px;">
												</div>
												<div
													style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); pointer-events: none; font-size: large; display: flex; flex-direction: column; align-items: center;">
													<strong>{{ $mandatorySummary['complete_percent'] ?? 0 }} %</strong><span style="font-size: smaller;">ครบถ้วน</span>
												</div>
											</div>
											<div class="summary-body" style="width: 100%; justify-content: space-evenly;">
												<div
													style="display: flex; flex-direction: row; justify-content: space-between; width:100%;">
													<div
														style="display: flex; flex-direction: row; gap: 5px; align-items: center;">
														<i class="fa-solid fa-circle" style="color: var(--success-color);"></i>
														<strong>ครบถ้วน</strong>
													</div>
													<span>
														<strong>{{ $mandatorySummary['complete'] ?? 0 }}</strong> คน ({{ $mandatorySummary['complete_percent'] ?? 0 }}%)
													</span>
												</div>
												<div
													style="display: flex; flex-direction: row; justify-content: space-between; width:100%;">
													<div
														style="display: flex; flex-direction: row; gap: 5px; align-items: center;">
														<i class="fa-solid fa-circle" style="color: var(--warning-color);"></i>
														<strong>ใกล้ครบกำหนด</strong>
													</div>
													<span>
														<strong>{{ $mandatorySummary['warning'] ?? 0 }}</strong> คน ({{ $mandatorySummary['warning_percent'] ?? 0 }}%)
													</span>
												</div>
												<div
													style="display: flex; flex-direction: row; justify-content: space-between; width:100%;">
													<div
														style="display: flex; flex-direction: row; gap: 5px; align-items: center;">
														<i class="fa-solid fa-circle" style="color: var(--danger-color);"></i>
														<strong>ยังไม่ครบ</strong>
													</div>
													<span>
														<strong>{{ $mandatorySummary['not_complete'] ?? 0 }}</strong> คน ({{ $mandatorySummary['not_complete_percent'] ?? 0 }}%)
													</span>
												</div>
											</div>
										</div>
									</div>
									<!-- <div class="card-footer">
										<a href="" class="btn btn-primary"
											style="background-color: var(--primary-color) !important;">ดูรายละเอียดทั้งหมด</a>
									</div> -->
								</div>
							</div> --}}
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
								<div class="card">
									<div class="card-header space-between">
										<strong>การอบรมใกล้หมดอายุ</strong>
									</div>

									<div class="card-body" style="height:100%;">
										<div style="display:flex; flex-direction:column; gap:10px;">

											@forelse($nearExpireCourses as $course)

												<div style="display:flex;
															justify-content:space-between;
															align-items:center;
															border:1px whitesmoke solid;
															border-radius:5px;
															padding:8px;">

													<div class="summary">
														<div class="summary-header-center">
															<div class="summary-icon"
																style="background-color:color-mix(in srgb, var(--danger-color) 15%, transparent);">
																<i class="fa-solid fa-triangle-exclamation fa-xl"
																	style="color:var(--danger-color)"></i>
															</div>
														</div>

														<div class="summary-body" style="gap:5px;">
															<strong>{{ $course['course_name'] }}</strong>

															<span style="color:var(--danger-color);">
																หมดอายุ
																{{
																	\Carbon\Carbon::parse($course['end_date'])
																		->locale('th')
																		->translatedFormat('d M')
																}}
																{{ \Carbon\Carbon::parse($course['end_date'])->year + 543 }}
															</span>
														</div>
													</div>

													<span style="min-width:70px;text-align:right;">
														<strong style="font-size:larger;">
															{{ $course['unfinished'] }}
														</strong>
														คน
													</span>

												</div>

											@empty

												<div class="text-center text-muted py-5">
													ไม่มีหลักสูตรใกล้หมดอายุ
												</div>

											@endforelse

										</div>
									</div>
								</div>
							</div>
							{{-- <div class="col-lg-6 col-md-6 col-sm-12">
								<div class="card">
									<div class="card-header">
										<i class="fa-solid fa-bell" style="color: var(--purple-color)"></i> <strong>Pedding
											(รอดำเนินการ)</strong>
									</div>
									<div class="card-body">
										<div style="display:flex; flex-direction: column; gap:5px;">
											<div
												style="display: flex; flex-direction: row; border: 1px whitesmoke solid; border-radius:5px; padding: 5px; justify-content: space-between;">
												<div style="display: flex; flex-direction: row; align-items:center; gap: 5px;">
													<i class="fa-solid fa-angle-right"></i>
													<strong>รอประเมินภาคปฎิบัตื</strong>
												</div>
												<strong
													style="color: var(--primary-color); background-color: color-mix(in srgb, var(--primary-color) 15%, transparent); padding-block: 5px; padding-inline: 10px; border-radius: 5px;">5
													คน</strong>
											</div>
											<div
												style="display: flex; flex-direction: row; border: 1px whitesmoke solid; border-radius:5px; padding: 5px; justify-content: space-between;">
												<div style="display: flex; flex-direction: row; align-items:center; gap: 5px;">
													<i class="fa-solid fa-angle-right"></i>
													<strong>รอทดสอบซ้ำ (ไม่ผ่าน)</strong>
												</div>
												<strong
													style="color: var(--danger-color); background-color: color-mix(in srgb, var(--danger-color) 15%, transparent); padding-block: 5px; padding-inline: 10px; border-radius: 5px;">3
													คน</strong>
											</div>
											<div
												style="display: flex; flex-direction: row; border: 1px whitesmoke solid; border-radius:5px; padding: 5px; justify-content: space-between;">
												<div style="display: flex; flex-direction: row; align-items:center; gap: 5px;">
													<i class="fa-solid fa-angle-right"></i>
													<strong>เอกสารใกล้หมดอายุ</strong>
												</div>
												<strong
													style="color: var(--primary-color); background-color: color-mix(in srgb, var(--primary-color) 15%, transparent); padding-block: 5px; padding-inline: 10px; border-radius: 5px;">9
													รายการ</strong>
											</div>
										</div>
									</div>
									<!-- <div class="card-footer">
										<a href="" class="btn btn-warning"
											style="background-color: var(--orange-color); width:100%;">ตรวจให้คะแนน /
											อนุมัติ</a>
									</div> -->
								</div>
							</div>
						</div> --}}
					</section>

					<!-- SECTION 3 -->
					<section>
						<div class="row row-eq-height custom-row-gap">
							{{-- <div class="col-lg-4 col-md-6 col-sm-12 mb-3">
								<div class="card">
									<div class="card-header space-between">
										<strong>เปรียนเทียบทีมในแผนกเดียวกัน</strong>
										<select name="" id="" class="form-control" style="width: fit-content;">
											<option value="" selected>รายเดือน</option>
										</select>
									</div>
									<div class="card-body">
										<div id="teamChart" style="width: 100%; height: 250px;"></div>
									</div>
								</div>
							</div> --}}
							{{-- <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
								<div class="card">
									<div class="card-header space-between"><strong>หลักสูตรที่มีผู้ไม่ผ่านมากที่สุด</strong>
									</div>
									<div class="card-body">
										<div style="display: flex; flex-direction: column; gap:20px;">
											<div class="space-between">
												<div class="summary" style="align-items: center;">
													<div class="summary-header-center">
														<div class="summary-icon"
															style="color: var(--danger-color); background-color: var(--danger-color-transparent); border-radius: 5px; padding-block:5px;">
															<strong>1</strong>
														</div>
													</div>
													<strong>Safety Training Annual 2025</strong>
												</div>
												<div
													style="display: flex; flex-direction:row; justify-content:space-between; gap: 5px; width: 30%;">
													<span>5 คน</span>
													<span>(21%)</span>
												</div>
											</div>
											<div class="space-between">
												<div class="summary" style="align-items: center;">
													<div class="summary-header-center">
														<div class="summary-icon"
															style="color: var(--success-color); background-color: var(--success-color-transparent); border-radius: 5px; padding-block:5px;">
															<strong>2</strong>
														</div>
													</div>
													<strong>GMP Refresher Training</strong>
												</div>
												<div
													style="display: flex; flex-direction:row; justify-content:space-between; gap: 5px; width: 30%;">
													<span>3 คน</span>
													<span>(13%)</span>
												</div>
											</div>
											<div class="space-between">
												<div class="summary" style="align-items: center;">
													<div class="summary-header-center">
														<div class="summary-icon"
															style="color: var(--primary-color); background-color: var(--primary-color-transparent); border-radius: 5px; padding-block:5px;">
															<strong>3</strong>
														</div>
													</div>
													<strong>Machine Setup</strong>
												</div>
												<div
													style="display: flex; flex-direction:row; justify-content:space-between; gap: 5px; width: 30%;">
													<span>2 คน</span>
													<span>(9%)</span>
												</div>
											</div>
											<div class="space-between">
												<div class="summary" style="align-items: center;">
													<div class="summary-header-center">
														<div class="summary-icon"
															style="color: var(--warning-color); background-color: var(--warning-color-transparent); border-radius: 5px; padding-block:5px;">
															<strong>4</strong>
														</div>
													</div>
													<strong>HACCP Awareness</strong>
												</div>
												<div
													style="display: flex; flex-direction:row; justify-content:space-between; gap: 5px; width: 30%;">
													<span>2 คน</span>
													<span>(9%)</span>
												</div>
											</div>
											<div class="space-between">
												<div class="summary" style="align-items: center;">
													<div class="summary-header-center">
														<div class="summary-icon"
															style=" background-color: var(--black-color-transparent); border-radius: 5px; padding-block:5px;">
															<strong>5</strong>
														</div>
													</div>
													<strong>QC Basic</strong>
												</div>
												<div
													style="display: flex; flex-direction:row; justify-content:space-between; gap: 5px; width: 30%;">
													<span>1 คน</span>
													<span>(4%)</span>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div> --}}
							{{-- <div class="col-lg-6 col-md-12 col-sm-12 mb-3">
								<div class="card">
									<div class="card-header space-between">
										<strong>การแจ้งเตือนและความเสี่ยง</strong>
									</div>
									<div class="card-body">
										<div class="row row-eq-height custom-row-gap">
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<div
													style="padding-inline: 10px; padding-block:20px; color:var(--danger-color); background-color: var(--danger-color-transparent); display: flex; flex-direction: column; gap:10px; justify-content: center; align-items:center; width: 100%; height: 100%; border-radius:10px;">
													<div class="space-between" style="align-items: center;"><i
															class="fa-solid fa-triangle-exclamation"></i><span
															style="font-size: smaller;">เกินกำหนดเรียน</span>
													</div>
													<strong><span style="font-size: medium;">3</span> คน</strong>
													<a href="" style="color: var(--danger-color);">ดูรายชื่อ</a>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<div
													style="padding-inline: 10px; padding-block:20px; color:var(--orange-color); background-color: var(--orange-color-transparent); display: flex; flex-direction: column; gap:10px; justify-content: center; align-items:center; width: 100%; height: 100%; border-radius:10px;">
													<div class="space-between" style="align-items: center;"><i
															class="fa-solid fa-alarm-clock"></i><span
															style="font-size: smaller;">ใกล้ครบกำหนด</span>
													</div>
													<strong><span style="font-size: medium;">2</span> คน</strong>
													<a href="" style="color: var(--orange-color);">ดูรายชื่อ</a>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<div
													style="padding-inline: 10px; padding-block:20px; color:var(--warning-color); background-color: var(--warning-color-transparent); display: flex; flex-direction: column; gap:10px; justify-content: center; align-items:center; width: 100%; height: 100%; border-radius:10px;">
													<div class="space-between" style="align-items: center;"><i
															class="fa-solid fa-anchor"></i><span
															style="font-size: smaller;">คะแนนต่ำกว่า 80%</span>
													</div>
													<strong><span style="font-size: medium;">4</span> คน</strong>
													<a href="" style="color: var(--warning-color);">ดูรายชื่อ</a>
												</div>
											</div>
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
												<div
													style="padding-inline: 10px; padding-block:20px; color:var(--primary-color); background-color: var(--primary-color-transparent); display: flex; flex-direction: column; gap:10px; justify-content: center; align-items:center; width: 100%; height: 100%; border-radius:10px;">
													<div class="space-between" style="align-items: center;"><i
															class="fa-solid fa-triangle-exclamation"></i><span
															style="font-size: smaller;">เอกสารใกล้หมดอายุ</span>
													</div>
													<strong><span style="font-size: medium;">9</span> รายการ</strong>
													<a href="" style="color: var(--primary-color);">ดูรายการ</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div> --}}
						</div>
					</section>

					<!-- SECTION 4 -->
					<section>
						<div class="row row-eq-height custom-row-gap">

							{{-- <div class="col-lg-6 col-md-6 col-sm-12">
								<div class="card">
									<div class="card-header space-between">
										<strong>แผนการเรียน (Team Roadmap)</strong>
									</div>
									<div class="card-body">
										<div style="display: flex; flex-direction: column; gap: 10px;">
											<div
												style="display: flex; flex-direction: row; justify-content: space-between; align-items:center;">
												<div class="summary" style="align-items: center; width:45%;">
													<div class="summary-header-center">
														<div class="summary-icon"
															style="color: var(--primary-color); background-color: var(--primary-color-transparent); padding:5px;">
															<i class="fa-solid fa-shield fa-lg"></i>
														</div>
													</div>
													<span>Mandatory Training</span>
												</div>
												<div class="space-between" style="width: fit-content; width:35%;">
													<div class="progress" style="width: 80%; height:10px; margin:0;">
														<div class="progress-bar progress-bar-success" role="progressbar"
															aria-valuenow="90" aria-valuemin="0" aria-valuemax="100"
															style="width: 90%;">
														</div>
													</div>
													<span>90%</span>
												</div>
												<span style="width: 15%; text-align:end;">25/28 คน</span>
											</div>
											<div
												style="display: flex; flex-direction: row; justify-content: space-between; align-items:center;">
												<div class="summary" style="align-items: center; width:45%;">
													<div class="summary-header-center">
														<div class="summary-icon"
															style="color: var(--success-color); background-color: var(--success-color-transparent); padding:5px;">
															<i class="fa-solid fa-clipboard-check fa-lg"></i>
														</div>
													</div>
													<span>Technical Skill</span>
												</div>
												<div class="space-between" style="width: fit-content; width:35%;">
													<div class="progress" style="width: 80%; height:10px; margin:0;">
														<div class="progress-bar progress-bar-success" role="progressbar"
															aria-valuenow="78" aria-valuemin="0" aria-valuemax="100"
															style="width: 78%;">
														</div>
													</div>
													<span>78%</span>
												</div>
												<span style="width: 15%; text-align:end;">22/28 คน</span>
											</div>
											<div
												style="display: flex; flex-direction: row; justify-content: space-between; align-items:center;">
												<div class="summary" style="align-items: center; width:45%;">
													<div class="summary-header-center">
														<div class="summary-icon"
															style="color: var(--warning-color); background-color: var(--warning-color-transparent); padding:5px;">
															<i class="fa-brands fa-bitbucket fa-lg"></i>
														</div>
													</div>
													<span>Soft Skill</span>
												</div>
												<div class="space-between" style="width: fit-content; width:35%;">
													<div class="progress" style="width: 80%; height:10px; margin:0;">
														<div class="progress-bar progress-bar-warning" role="progressbar"
															aria-valuenow="60" aria-valuemin="0" aria-valuemax="100"
															style="width: 60%;">
														</div>
													</div>
													<span>60%</span>
												</div>
												<span style="width: 15%; text-align:end;">17/28 คน</span>
											</div>
										</div>
									</div>
								</div>
							</div> --}}
							<div class="col-lg-6 col-md-6 col-sm-12">
								<div class="card">

									<div class="card-header space-between">
										<strong>Activity ล่าสุดของทีม</strong>
									</div>

									<div class="card-body">

										<div class="table-responsive">

											<table class="table table-no-border table-hover" style="font-size:smaller;">

												<tbody>

													@forelse($teamLatestActivity as $item)

														<tr>

															{{-- <td width="40">

																<img
																	src="{{ $item['pic_user']
																			? asset('images/uploads/user/'.$item['pic_user'])
																			: asset('images/avatar-default.png') }}"
																	class="img-circle"
																	style="height:28px;width:28px;object-fit:cover;">

															</td> --}}

															<td>

																<strong>{{ $item['fullname'] }}</strong>

															</td>

															<td>

																เรียนจบ {{ $item['course_name'] }}

															</td>

															<td>

																@php
																	$date = \Carbon\Carbon::parse($item['date']);
																@endphp

																{{ $date->locale('th')->translatedFormat('d M') }}
																{{ $date->year + 543 }}

															</td>

															<td>

																{{ $item['time'] }}

															</td>

														</tr>

													@empty

														<tr>

															<td colspan="5" class="text-center text-muted">

																ยังไม่พบข้อมูล Activity

															</td>

														</tr>

													@endforelse

												</tbody>

											</table>

										</div>

									</div>

								</div>

							</div>
						</div>
					</section>
				</div>

			</div>