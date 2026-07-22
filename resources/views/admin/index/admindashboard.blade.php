@extends('admin.layouts.dashboard-layout')

@section('dashboard-content')
<div class="main-content admin-dashboard" style="max-width: 1400px; margin: 0 auto; padding: 0 20px;">
            <div class="container-fluid">
				<!-- SECTION 1 -->
				<section>
					<div class="row row-eq-height row-filter">
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<div style="margin-top: auto; width: 100%;">
								<span>ช่วงเวลา</span>
								<div class="input-group date-input">
									<input type="text" id="dateSec3" class="form-control" style="border-right: none;">
									<span class="input-group-addon" style="background:#fff;">
										<i class="fa-regular fa-calendar"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<div class="card" style="border: rgb(160, 126, 255) 2px solid; padding: 10px !important;">
								<strong class="card-header">Department</strong>
								<div class="card-body">
									<select name="" id="" class="form-control">
										<option value="#" selected="">ทั้งหมด</option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<div class="card" style="border: rgb(160, 126, 255) 2px solid; padding: 10px !important;">
								<strong class="card-header">Team</strong>
								<div class="card-body">
									<select name="" id="" class="form-control">
										<option value="#" selected="">ทั้งหมด</option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<div class="card" style="border: rgb(160, 126, 255) 2px solid; padding: 10px !important;">
								<strong class="card-header">Line</strong>
								<div class="card-body">
									<select name="" id="" class="form-control">
										<option value="#" selected="">ทั้งหมด</option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
							<div class="card" style="border: rgb(160, 126, 255) 2px solid; padding: 10px !important;">
								<strong class="card-header">Section</strong>
								<div class="card-body">
									<select name="" id="" class="form-control">
										<option value="#" selected="">ทั้งหมด</option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-4 col-sm-6 col-xs-12 fix-export">
							<div style="width: 100%; display:flex; ">
								<button type="button" class="btn btn-default w-100" style="border: rgb(160, 126, 255) 2px solid; display: flex; flex-direction: row; gap: 5px; align-items: center; color: rgb(160, 126, 255); justify-content: center;"><i class="fa-solid fa-download fa-xl" style="color: rgb(160, 126, 255);"></i><strong>Export</strong></button>
							</div>
						</div>
					</div>
				</section>

				<!-- SECTION 2 -->
				<section class="container-fluid">
					<div class="row row-eq-height five-col custom-row-gap">
						<div class="col-lg-2 col-md-6 col-sm-6 col-xs-12">
							<div class="card" style="color: #0d6efd;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: color-mix(in srgb, #0d6efd 15%, transparent);">
											<i class="fa-solid fa-book-open fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span>คอร์สทั้งหมด</span><strong>156</strong><span>หลักสูตร</span>
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
										<span>เนื้อหาทั้งหมด</span><strong>1,248</strong><span>ไฟล์</span>
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
										<span>ผู้ใช้ทั้งหมด</span><strong>512</strong><span>คน</span>
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
										<h5><span style="color: #ffc107; font-weight:bold; font-size: large;">7</span>
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
										<div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
											<div style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
												<div style="color: #dc3545; display: flex; flex-direction: row; gap:5px; align-items: center;">
													<i class="fa-solid fa-circle"></i><strong>1</strong>
												</div>
												<div style="display: flex; flex-direction: column;">
													<strong>GMP Refresher Training</strong>
													<span style="color: gray;">ครบกำหนด 10 พ.ค. 67</span>
												</div>
											</div>
											<div style="display: flex; flex-direction: row; align-items: center; gap:5px;">
												<strong>28</strong>คน
											</div>
										</div>
										<div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
											<div style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
												<div style="color: #dc3545; display: flex; flex-direction: row; gap:5px; align-items: center;">
													<i class="fa-solid fa-circle"></i><strong>2</strong>
												</div>
												<div style="display: flex; flex-direction: column;">
													<strong>Safety Training Annual 2024</strong>
													<span style="color: gray;">ครบกำหนด 8 พ.ค. 67</span>
												</div>
											</div>
											<div style="display: flex; flex-direction: row; align-items: center; gap:5px;">
												<strong>21</strong>คน
											</div>
										</div>
										<div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
											<div style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
												<div style="color: #dc3545; display: flex; flex-direction: row; gap:5px; align-items: center;">
													<i class="fa-solid fa-circle"></i><strong>3</strong>
												</div>
												<div style="display: flex; flex-direction: column;">
													<strong>HACCP Awareness</strong>
													<span style="color: gray;">ครบกำหนด 5 พ.ค. 67</span>
												</div>
											</div>
											<div style="display: flex; flex-direction: row; align-items: center; gap:5px;">
												<strong>18</strong>คน
											</div>
										</div>
										<div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
											<div style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
												<div style="color: #dc3545; display: flex; flex-direction: row; gap:5px; align-items: center;">
													<i class="fa-solid fa-circle"></i><strong>4</strong>
												</div>
												<div style="display: flex; flex-direction: column;">
													<strong>Machine Setup</strong>
													<span style="color: gray;">ครบกำหนด 2 พ.ค. 67</span>
												</div>
											</div>
											<div style="display: flex; flex-direction: row; align-items: center; gap:5px;">
												<strong>14</strong>คน
											</div>
										</div>
										<div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center;">
											<div style="display: flex; flex-direction: row; gap: 10px; align-items: center;">
												<div style="color: #dc3545; display: flex; flex-direction: row; gap:5px; align-items: center;">
													<i class="fa-solid fa-circle"></i><strong>5</strong>
												</div>
												<div style="display: flex; flex-direction: column;">
													<strong>QC Basic</strong>
													<span style="color: gray;">ครบกำหนด 1 พ.ค. 67</span>
												</div>
											</div>
											<div style="display: flex; flex-direction: row; align-items: center; gap:5px;">
												<strong>9</strong>คน
											</div>
										</div>
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
													<th colspan="2">Completion Rate</th>
													<th colspan="2">Pass Rate</th>
													<th>คอร์สที่กำลังเรียน</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>Production</td>
													<td>248</td>
													<td>82%</td>
													<td style="color: #198754;"><i class="fa-solid fa-caret-up"></i>6%</td>
													<td>76%</td>
													<td style="color: #198754;"><i class="fa-solid fa-caret-up"></i>4%</td>
													<td style="display: flex; flex-direction: row; gap: 5px;">
														<div class="progress" style="height: 15px; width: 70%;">
															<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
															</div>
														</div>
														10
													</td>
												</tr>
												<tr>
													<td>Quality Control</td>
													<td>64</td>
													<td>78%</td>
													<td style="color: #198754;"><i class="fa-solid fa-caret-up"></i>4%</td>
													<td>72%</td>
													<td style="color: #198754;"><i class="fa-solid fa-caret-up"></i>2%</td>
													<td style="display: flex; flex-direction: row; gap: 5px;">
														<div class="progress" style="height: 15px; width: 70%;">
															<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
															</div>
														</div>
														7
													</td>
												</tr>
												<tr>
													<td>Maintanance</td>
													<td>48</td>
													<td>75%</td>
													<td style="color: #198754;"><i class="fa-solid fa-caret-up"></i>3%</td>
													<td>70%</td>
													<td style="color: #198754;"><i class="fa-solid fa-caret-up"></i>3%</td>
													<td style="display: flex; flex-direction: row; gap: 5px;">
														<div class="progress" style="height: 15px; width: 70%;">
															<div class="progress-bar" role="progressbar" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100" style="width: 50%;">
															</div>
														</div>
														6
													</td>
												</tr>
												<tr>
													<td>Supply Chain</td>
													<td>42</td>
													<td>71%</td>
													<td style="color: #198754;"><i class="fa-solid fa-caret-up"></i>2%</td>
													<td>68%</td>
													<td style="color: #198754;"><i class="fa-solid fa-caret-up"></i>1%</td>
													<td style="display: flex; flex-direction: row; gap: 5px;">
														<div class="progress" style="height: 15px; width: 70%;">
															<div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
															</div>
														</div>
														5
													</td>
												</tr>
												<tr>
													<td>Engineering</td>
													<td>38</td>
													<td>69%</td>
													<td style="color: #dc3545;"><i class="fa-solid fa-caret-down"></i>1%
													</td>
													<td>66%</td>
													<td style="color: #dc3545;"><i class="fa-solid fa-caret-down"></i>1%
													</td>
													<td style="display: flex; flex-direction: row; gap: 5px;">
														<div class="progress" style="height: 15px; width: 70%;">
															<div class="progress-bar" role="progressbar" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100" style="width: 30%;">
															</div>
														</div>
														4
													</td>
												</tr>
												<tr>
													<td>Administration</td>
													<td>72</td>
													<td>80%</td>
													<td style="color: #198754;"><i class="fa-solid fa-caret-up"></i>5%</td>
													<td>75%</td>
													<td style="color: #198754;"><i class="fa-solid fa-caret-up"></i>3%</td>
													<td style="display: flex; flex-direction: row; gap: 5px;">
														<div class="progress" style="height: 15px; width: 70%;">
															<div class="progress-bar" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100" style="width: 70%;">
															</div>
														</div>
														8
													</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>

							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-sm-12">
							<div class="card">
								<div class="card-header"><strong>หลักสูตรที่นักเรียกมากที่สุด</strong></div>
								<div class="card-body" style="font-size: small;">
									<div style="display: flex; flex-direction: column; gap: 5px;">
										<div style="display:flex; flex-direction: row; justify-content:space-between;">
											<div style="display:flex; flex-direction: row; align-items: center; gap:20px;">
												<h4 style="margin: 0; font-weight:bold;">1</h4>
												<div style="display: flex; flex-direction: column;">
													<strong>GMP Refresher Training</strong>
													<span>ผู้เรียน 156 คน</span>
												</div>
											</div>
											<div style="display:flex; flex-direction: row; align-items: center; gap: 5px; width: 40%">
												<div class="progress" style="height: 15px; flex: 1; margin-bottom: 0;">
													<div class="progress-bar" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%;">
													</div>
												</div>
												<span style="white-space: nowrap;">60%</span>
											</div>
										</div>
										<div style="display:flex; flex-direction: row; justify-content:space-between;">
											<div style="display:flex; flex-direction: row; align-items: center; gap:20px;">
												<h4 style="margin: 0; font-weight:bold;">2</h4>
												<div style="display: flex; flex-direction: column;">
													<strong>Machine Setup</strong>
													<span>ผู้เรียน 128 คน</span>
												</div>
											</div>
											<div style="display:flex; flex-direction: row; align-items: center; gap: 5px; width: 40%">
												<div class="progress" style="height: 15px; flex: 1; margin-bottom: 0;">
													<div class="progress-bar" role="progressbar" aria-valuenow="48" aria-valuemin="0" aria-valuemax="100" style="width: 48%;">
													</div>
												</div>
												<span style="white-space: nowrap;">48%</span>
											</div>
										</div>
										<div style="display:flex; flex-direction: row; justify-content:space-between;">
											<div style="display:flex; flex-direction: row; align-items: center; gap:20px;">
												<h4 style="margin: 0; font-weight:bold;">3</h4>
												<div style="display: flex; flex-direction: column;">
													<strong>QC Basic</strong>
													<span>ผู้เรียน 102 คน</span>
												</div>
											</div>
											<div style="display:flex; flex-direction: row; align-items: center; gap: 5px; width: 40%">
												<div class="progress" style="height: 15px; flex: 1; margin-bottom: 0;">
													<div class="progress-bar" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%;">
													</div>
												</div>
												<span style="white-space: nowrap;">40%</span>
											</div>
										</div>
										<div style="display:flex; flex-direction: row; justify-content:space-between;">
											<div style="display:flex; flex-direction: row; align-items: center; gap:20px;">
												<h4 style="margin: 0; font-weight:bold;">4</h4>
												<div style="display: flex; flex-direction: column;">
													<strong>Work Instruction Line 1</strong>
													<span>ผู้เรียน 88 คน</span>
												</div>
											</div>
											<div style="display:flex; flex-direction: row; align-items: center; gap: 5px; width: 40%">
												<div class="progress" style="height: 15px; flex: 1; margin-bottom: 0;">
													<div class="progress-bar" role="progressbar" aria-valuenow="35" aria-valuemin="0" aria-valuemax="100" style="width: 35%;">
													</div>
												</div>
												<span style="white-space: nowrap;">35%</span>
											</div>
										</div>
										<div style="display:flex; flex-direction: row; justify-content:space-between;">
											<div style="display:flex; flex-direction: row; align-items: center; gap:20px;">
												<h4 style="margin: 0; font-weight:bold;">5</h4>
												<div style="display: flex; flex-direction: column;">
													<strong>In-progress Quality Control</strong>
													<span>ผู้เรียน 75 คน</span>
												</div>
											</div>
											<div style="display:flex; flex-direction: row; align-items: center; gap: 5px; width: 40%">
												<div class="progress" style="height: 15px; flex: 1; margin-bottom: 0;">
													<div class="progress-bar" role="progressbar" aria-valuenow="28" aria-valuemin="0" aria-valuemax="100" style="width: 28%;">
													</div>
												</div>
												<span style="white-space: nowrap;">28%</span>
											</div>
										</div>
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
