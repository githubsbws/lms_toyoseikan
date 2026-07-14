@extends('admin/layouts/mainlayout')
@section('title', 'Admin')
@section('content')

<head>
	<!-- Google Charts -->
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="{{asset('asset_admin/includes/css/style.css')}}">
	<!-- Date Picker -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

	<!-- Chart Js -->
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

</head>


<style>

</style>

<style>
	.row-eq-height {
		display: flex;
		flex-wrap: wrap;
	}

	.row-eq-height>[class*='col-'] {
		display: flex;
	}

	.five-col {
		margin-inline: -20px !important;
	}

	.custom-row-gap>[class*="col-"] {
		padding-inline: 5px;
	}

	.custom-row-gap {
		margin-inline: -5px;
	}

	.summary {
		display: flex;
		flex-direction: row;
		gap: 20px;
	}

	.summary>.summary-header {
		align-items: center;

		>div {
			padding-block: 10px;
			padding-inline: 5px;
			border-radius: 25%;
		}
	}

	.navbar-nav>li>a {
		display: flex;
		align-items: center;
		height: 50px;
	}

	.navbar-nav>li>a .fa-bell {
		line-height: 1;
	}

	.navbar .navbar-toggle .icon-bar {
		background-color: #333;
	}

	.row-filter .fix-export {
		justify-content: end;
		align-items: start;
	}
</style>

<body class="">
	<div id="wrapper">



		<div class="content-wrapper">

			<nav style="width: 100%; display: flex; flex-direction: row; justify-content: space-between; align-items: center; padding-inline: 20px; padding-block: 5px; margin-bottom: 20px; background-color: #fff;"
				class="custom-navbar">
				<a href="#" style=" font-size: larger;">
					<div style="display: flex; flex-direction: row; align-items: center; gap: 10px;">
						<div
							style="color: var(--primary-color); background-color: var(--primary-color-transparent); padding-block: 10px; padding-inline: 5px; border-radius: 100%;">
							<i class="fa-solid fa-user-group fa-xl"></i>
						</div>
						<div style="display: flex; flex-direction: column;">
							<strong>Dashboard หัวหน้างาน</strong>
							<span style="font-size: smaller;">ภาพรวมการเรียนรู้ของทีม Team {{ $user->team_id }} - {{ $user->orgchart->line->title ?? '' }}</span>
						</div>
					</div>

				</a>
				<div style="display: flex; flex-direction: column; gap: 5px;">
					<div
						style="display: flex; flex-direction: row; justify-content: end; align-items: center; gap: 40px; width: 100%;">
						{{-- <div style="display: flex; align-items: center;">
							<a href="#" class="notification-link">
								<span style="position: relative; display: inline-block;">
									<i class="fa-regular fa-bell fa-xl"></i>
									<span class="badge"
										style="position: absolute; top: -6px; right: -10px; background-color: #dc3545; color: #fff; padding-inline: 3px; padding-block: 3px; font-size: smaller;">12</span>
								</span>
							</a>
						</div> --}}
						{{-- <a href=""><i class="fa-regular fa-circle-question fa-xl"></i>
						</a> --}}
						{{-- <div class="dropdown"> --}}
							{{-- <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
								aria-expanded="false" style="display: flex; flex-direction: row; align-items: center;"> --}}
								<div style="display: flex; flex-direction: row; align-items: center; gap: 15px;">
									{{-- <img src="https://img.magnific.com/free-photo/handsome-young-cheerful-man-with-arms-crossed_171337-1073.jpg?semt=ais_hybrid&w=740&q=80"
										alt="" class="img-circle" style="width: 40px; height: 40px;"> --}}
									<div style="display: flex; flex-direction: column; font-size: smaller;">
										<strong>คุณ {{ $user->Profiles->firstname }} {{ $user->Profiles->lastname }}</strong>
										<span>{{ $user->orgchart->title ?? '' }}</span>
									</div>
								</div> <span class="caret"></span>
							{{-- </a> --}}
							{{-- <ul class="dropdown-menu">
								<li><a href="#">Action</a></li>
								<li><a href="#">Another action</a></li>
								<li><a href="#">Something else here</a></li>
								<li role="separator" class="divider"></li>
								<li><a href="#">Separated link</a></li>
							</ul> --}}
						{{-- </div> --}}
					</div>
					<form action="" style="display: flex; flex-direction: row; gap: 10px; width: 100%;">
						{{-- <div class="input-group date-input">
							<input type="text" id="dateRange" class="form-control" style="border-right: none;" />
							<span class="input-group-addon" style="background:#fff;">
								<i class="fa-regular fa-calendar"></i>
							</span>
						</div> --}}

						{{-- <div class="dropdown">
							<button class="btn btn-light dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fa-solid fa-filter"></i><span>ตัวกรอง</span></button>
							</button>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<a class="dropdown-item" href="#">Action</a>
								<a class="dropdown-item" href="#">Another action</a>
								<a class="dropdown-item" href="#">Something else here</a>
							</div>
						</div> --}}
					</form>
				</div>
			</nav>

			<!-- Admin manager -->
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

			<!-- Admin Management -->
			<div class="container-fluid">

				<section class="section-1 row">
					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 custom-5-col">
						<div class="card card-stat color-purple">
							<div class="stat-icon"><i class="fa-solid fa-users"></i></div>
							<div class="stat-content">
								<div class="stat-title">พนักงานทั้งหมด</div>
								<div class="stat-value-row">
									<span class="stat-qty">512</span><span class="stat-unit">คน</span>
								</div>
								<div class="stat-trend">
									<span class="text-up"><i class="fa-solid fa-caret-up"></i> 8</span> <span class="trend-desc">จากช่วงก่อนหน้า</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 custom-5-col">
						<div class="card card-stat color-green">
							<div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
							<div class="stat-content">
								<div class="stat-title2">Completion Rate</div>
								<div class="stat-value-row">
									<span class="stat-qty2">78%</span>
								</div>
								<div class="stat-trend">
									<span class="text-up"><i class="fa-solid fa-caret-up"></i> 6%</span> <span class="trend-desc">จากช่วงก่อนหน้า</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 custom-5-col">
						<div class="card card-stat color-blue">
							<div class="stat-icon"><i class="fa-solid fa-medal"></i></div>
							<div class="stat-content">
								<div class="stat-title3">Pass Rate</div>
								<div class="stat-value-row">
									<span class="stat-qty3">72%</span>
								</div>
								<div class="stat-trend">
									<span class="text-up"><i class="fa-solid fa-caret-up"></i> 4%</span> <span class="trend-desc">จากช่วงก่อนหน้า</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 custom-5-col">
						<div class="card card-stat color-orange">
							<div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
							<div class="stat-content">
								<div class="stat-title4">Course Overdue</div>
								<div class="stat-value-row">
									<span class="stat-qty4">34</span><span class="stat-unit">หลักสูตร</span>
								</div>
								<div class="stat-trend">
									<span class="text-up"><i class="fa-solid fa-caret-up"></i> 5</span> <span class="trend-desc">หลักสูตร</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 custom-5-col">
						<div class="card card-stat color-red">
							<div class="stat-icon"><i class="fa-solid fa-circle-exclamation"></i></div>
							<div class="stat-content">
								<div class="stat-title">ต้องสอบซ่อม</div>
								<div class="stat-value-row">
									<span class="stat-qty5">28</span><span class="stat-unit">คน</span>
								</div>
								<div class="stat-trend">
									<span class="text-up"><i class="fa-solid fa-caret-up"></i> 7</span> <span class="trend-desc">คน</span>
								</div>
							</div>
						</div>
					</div>
				</section>

				<section class="section-2 row row-eq-height">
					<div class="col-lg-6 col-md-12 col-12">
						<div class="card h-100">
							<div class="card-header">
								<h5>Completion Rate ของแต่ละ Line</h5>
							</div>

							<div class="completion-rate-list">
								<div class="cr-row">
									<div class="cr-label">Line 1</div>
									<div class="cr-bar-container">
										<div class="cr-bar" style="width: 85%;"></div>
										<div class="cr-pct">85%</div>
									</div>
									<div class="cr-trend text-up"><i class="fa-solid fa-caret-up"></i> 5%</div>
								</div>
								<div class="cr-row">
									<div class="cr-label">Line 2</div>
									<div class="cr-bar-container">
										<div class="cr-bar" style="width: 70%;"></div>
										<div class="cr-pct">70%</div>
									</div>
									<div class="cr-trend text-down"><i class="fa-solid fa-caret-down"></i> 2%</div>
								</div>
								<div class="cr-row">
									<div class="cr-label">Line 3</div>
									<div class="cr-bar-container">
										<div class="cr-bar" style="width: 72%;"></div>
										<div class="cr-pct">72%</div>
									</div>
									<div class="cr-trend text-up"><i class="fa-solid fa-caret-up"></i> 4%</div>
								</div>
								<div class="cr-row">
									<div class="cr-label">Line 4</div>
									<div class="cr-bar-container">
										<div class="cr-bar" style="width: 70%;"></div>
										<div class="cr-pct">70%</div>
									</div>
									<div class="cr-trend text-up"><i class="fa-solid fa-caret-up"></i> 1%</div>
								</div>
								<div class="cr-row" style="margin-bottom: 5px;">
									<div class="cr-label">Line 5<br><span class="cr-sub-label">(Headquarter)</span></div>
									<div class="cr-bar-container">
										<div class="cr-bar" style="width: 40%;"></div>
										<div class="cr-pct">40%</div>
									</div>
									<div class="cr-trend text-down"><i class="fa-solid fa-caret-down"></i> 3%</div>
								</div>

								<div class="cr-axis-row">
									<div style="width: 80px; flex-shrink: 0;"></div>
									<div class="cr-axis-labels">
										<span>0%</span>
										<span>25%</span>
										<span>50%</span>
										<span>75%</span>
										<span>100%</span>
									</div>
									<div style="width: 90px; flex-shrink: 0;"></div>
								</div>
							</div>
							<!-- <div class="card-footer"><a href="#" class="btn-outline-purple">ดูรายละเอียด</a></div> -->
						</div>
					</div>

					<div class="col-lg-6 col-md-6 col-12">
						<div class="card h-100">
							<div class="card-header">
								<h5>Pass Rate ของแต่ละ Section</h5>
							</div>
							<div class="donut-chart-layout">
								<div class="donut-chart-wrapper">
									<canvas id="passRateChart"></canvas>
									<div class="donut-center-text">
										<span class="pct">72%</span>
										<span class="label">Pass Rate</span>
									</div>
								</div>

								<div class="custom-donut-legend">
									<div class="legend-item-row">
										<div class="leg-left"><span class="leg-color" style="background:#3b82f6;"></span> Raw material</div>
										<div class="leg-right">75%</div>
									</div>
									<div class="legend-item-row">
										<div class="leg-left"><span class="leg-color" style="background:#ef4444;"></span> Blowing</div>
										<div class="leg-right">72%</div>
									</div>
									<div class="legend-item-row">
										<div class="leg-left"><span class="leg-color" style="background:#f59e0b;"></span> Mixing</div>
										<div class="leg-right">78%</div>
									</div>
									<div class="legend-item-row">
										<div class="leg-left"><span class="leg-color" style="background:#6366f1;"></span> Filling</div>
										<div class="leg-right">73%</div>
									</div>
									<div class="legend-item-row">
										<div class="leg-left"><span class="leg-color" style="background:#14b8a6;"></span> Packing</div>
										<div class="leg-right">70%</div>
									</div>
									<div class="legend-item-row">
										<div class="leg-left"><span class="leg-color" style="background:#0d9488;"></span> Packaging material</div>
										<div class="leg-right">68%</div>
									</div>
								</div>
							</div>
							<!-- <div class="card-footer"><a href="#" class="btn-outline-purple">ดูรายละเอียด</a></div> -->
						</div>
					</div>

					<div class="col-lg-12 col-md-6 col-12">
						<div class="card h-100">
							<div class="card-header">
								<h5>Top 5 หลักสูตรที่ไม่ผ่านมากที่สุด</h5>
								<span class="sub-text">จำนวนผู้ไม่ผ่าน</span>
							</div>
							<div class="card-body">
								<div class="top5-list">
									<div class="top5-item">
										<span class="top5-rank">1</span>
										<span class="top5-name">ความปลอดภัยในการทำงาน</span>
										<span class="top5-status">ไม่ผ่าน</span>
										<span class="top5-count">18 คน</span>
									</div>
									<div class="top5-item">
										<span class="top5-rank">2</span>
										<span class="top5-name">การควบคุมคุณภาพในกระบวนการผลิต</span>
										<span class="top5-status">ไม่ผ่าน</span>
										<span class="top5-count">15 คน</span>
									</div>
									<div class="top5-item">
										<span class="top5-rank">3</span>
										<span class="top5-name">การบำรุงรักษาเบื้องต้น</span>
										<span class="top5-status">ไม่ผ่าน</span>
										<span class="top5-count">12 คน</span>
									</div>
									<div class="top5-item">
										<span class="top5-rank">4</span>
										<span class="top5-name">การตั้งค่าเครื่องจักรเบื้องต้น</span>
										<span class="top5-status">ไม่ผ่าน</span>
										<span class="top5-count">10 คน</span>
									</div>
									<div class="top5-item">
										<span class="top5-rank">5</span>
										<span class="top5-name">5 ส ในการปฏิบัติงาน</span>
										<span class="top5-status">ไม่ผ่าน</span>
										<span class="top5-count">9 คน</span>
									</div>
								</div>
							</div>
							<!-- <div class="card-footer"><a href="#" class="btn-outline-purple">ดูรายละเอียด</a></div> -->
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
											<div class="leg-left"><span class="leg-color" style="background:#6b4ce6;"></span> ครบ 30 วัน</div>
											<div class="leg-right">
												<span class="leg-count">42 คน</span>
												<span class="leg-pct">(85%)</span>
											</div>
										</div>
										<div class="legend-item-row">
											<div class="leg-left"><span class="leg-color" style="background:#f87171;"></span> ครบ 60 วัน</div>
											<div class="leg-right">
												<span class="leg-count">38 คน</span>
												<span class="leg-pct">(76%)</span>
											</div>
										</div>
										<div class="legend-item-row">
											<div class="leg-left"><span class="leg-color" style="background:#60a5fa;"></span> ครบ 90 วัน</div>
											<div class="leg-right">
												<span class="leg-count">26 คน</span>
												<span class="leg-pct">(64%)</span>
											</div>
										</div>
										<div class="legend-item-row">
											<div class="leg-left"><span class="leg-color" style="background:#2dd4bf;"></span> ครบ 120 วัน</div>
											<div class="leg-right">
												<span class="leg-count">22 คน</span>
												<span class="leg-pct">(54%)</span>
											</div>
										</div>
										<div class="legend-item-row">
											<div class="leg-left"><span class="leg-color" style="background:#059669;"></span> เกิน 120 วัน</div>
											<div class="leg-right">
												<span class="leg-count">22 คน</span>
												<span class="leg-pct">(44%)</span>
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
										<tr>
											<td>Production</td>
											<td>248</td>
											<td>80% <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 5%</span></td>
											<td>74% <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 4%</span></td>
											<td>18 <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 3</span></td>
											<td>12 <span class="trend-down"><i class="fa-solid fa-caret-down"></i> 1</span></td>
											<td>29%</td>
											<td><a href="#" class="btn-table-outline">ดูรายละเอียด</a></td>
										</tr>
										<tr>
											<td>Quality Control</td>
											<td>64</td>
											<td>82% <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 3%</span></td>
											<td>76% <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 2%</span></td>
											<td>6 <span class="trend-dash">-</span></td>
											<td>5 <span class="trend-down"><i class="fa-solid fa-caret-down"></i> 1</span></td>
											<td>24%</td>
											<td><a href="#" class="btn-table-outline">ดูรายละเอียด</a></td>
										</tr>
										<tr>
											<td>Maintenance</td>
											<td>48</td>
											<td>75% <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 2%</span></td>
											<td>70% <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 3%</span></td>
											<td>7 <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 1</span></td>
											<td>6 <span class="trend-dash">-</span></td>
											<td>27%</td>
											<td><a href="#" class="btn-table-outline">ดูรายละเอียด</a></td>
										</tr>
										<tr>
											<td>Supply Chain</td>
											<td>42</td>
											<td>78% <span class="trend-down"><i class="fa-solid fa-caret-down"></i> 1%</span></td>
											<td>71% <span class="trend-down"><i class="fa-solid fa-caret-down"></i> 1%</span></td>
											<td>5 <span class="trend-dash">-</span></td>
											<td>3 <span class="trend-dash">-</span></td>
											<td>22%</td>
											<td><a href="#" class="btn-table-outline">ดูรายละเอียด</a></td>
										</tr>
										<tr>
											<td>Engineering</td>
											<td>38</td>
											<td>79% <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 6%</span></td>
											<td>74% <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 5%</span></td>
											<td>4 <span class="trend-down"><i class="fa-solid fa-caret-down"></i> 1</span></td>
											<td>2 <span class="trend-dash">-</span></td>
											<td>19%</td>
											<td><a href="#" class="btn-table-outline">ดูรายละเอียด</a></td>
										</tr>
										<tr>
											<td>Administration</td>
											<td>72</td>
											<td>74% <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 4%</span></td>
											<td>69% <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 4%</span></td>
											<td>6 <span class="trend-dash">-</span></td>
											<td>4 <span class="trend-up"><i class="fa-solid fa-caret-up"></i> 1</span></td>
											<td>21%</td>
											<td><a href="#" class="btn-table-outline">ดูรายละเอียด</a></td>
										</tr>
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

			<!-- Admin Dashboard -->
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
							<div class="card" style="color: #ffc107;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: color-mix(in srgb, #ffc107 15%, transparent);">
											<i class="fa-solid fa-clipboard-list fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span>แบบทดสอบทั้งหมด</span><strong>342</strong><span>ข้อ</span>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-2 col-md-12 col-sm-12 col-xs-12">
							<div class="card" style="color: #000000;">
								<div class="summary">
									<div class="summary-header">
										<div style="background-color: color-mix(in srgb, #000000 15%, transparent);">
											<i class="fa-regular fa-rectangle-list fa-2xl"></i>
										</div>
									</div>
									<div class="summary-body">
										<span>บันทึกการใช้งาน (Logs)</span><strong>2,853</strong><span>รายการ</span>
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
								<div class="card-header"><strong>การอัปโหลดเนื้อหาล่าสุด</strong></div>
								<div class="card-body" style="font-size: smaller;">
									<div class="table-responsive">
										<table class="table table-no-border">
											<tbody>
												<tr style="color: gray;">
													<td><i class="fa-regular fa-file-lines" style="color: #dc3545;"></i>
													</td>
													<td style="color:#000000;">คู่มือการตั้งค่าเครื่องจักร รุ่นใหม่</td>
													<td>PDF</td>
													<td>15/05/2024 10:30</td>
													<td>Admin</td>
												</tr>
												<tr style="color: gray;">
													<td><i class="fa-regular fa-file-lines" style="color: #198754;"></i>
													</td>
													<td style="color:#000000;">แนวทางการควบคุมคุณภาพ</td>
													<td style="color:#198754;">PPTX</td>
													<td>15/05/2024 09:15</td>
													<td>Trainer01</td>
												</tr>
												<tr style="color: gray;">
													<td><i class="fa-regular fa-file-video" style="color: #0d6efd;"></i>
													</td>
													<td style="color:#000000;">คลิปการบำรุงรักษาเครื่องจักร</td>
													<td style="color:#198754;">Video</td>
													<td>14/05/2024 16:45</td>
													<td>Trainer02</td>
												</tr>
												<tr style="color: gray;">
													<td><i class="fa-regular fa-file-excel" style="color: #198754;"></i>
													</td>
													<td style="color:#000000;">แบบฟอร์มตรวจสุขภาพ</td>
													<td style="color:#198754;">Excel</td>
													<td>15/05/2024 14:30</td>
													<td>Admin</td>
												</tr>
												<tr style="color: gray;">
													<td><i class="fa-regular fa-file-image" style="color: #0d6efd;"></i>
													</td>
													<td style="color:#000000;">ภาพขั้นตอนงานไลน์</td>
													<td style="color:#198754;">Image</td>
													<td>14/05/2024 10:15</td>
													<td>Admin</td>
												</tr>
											</tbody>

										</table>
									</div>
								</div>
							</div>
						</div>
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
																			<div aria-label="A tabular representation of the data in the chart." style="position: absolute; left: -10000px; top: auto; width: 1px; height: 1px; overflow: hidden;">
																				<table>
																					<thead>
																						<tr>
																							<th>Effort</th>
																							<th>Amount given</th>
																						</tr>
																					</thead>
																					<tbody>
																						<tr>
																							<td>Success</td>
																							<td>70</td>
																						</tr>
																						<tr>
																							<td>Bonus</td>
																							<td>8</td>
																						</tr>
																						<tr>
																							<td>Unfinished</td>
																							<td>22</td>
																						</tr>
																					</tbody>
																				</table>
																			</div>
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
															<div><span style="color: #198754;">+6%</span> จากเดือนก่อน
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="card" style="padding: 10px;">
												<div class="card-header">อัตตราการเข้าเรียน</div>
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
															<div><span style="color: #198754;">+8%</span> จากเดือนก่อน</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row row-eq-height custom-row-gap">
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="card" style="padding: 10px;">
												<div class="card-header">เวลาเรียนรวม</div>
												<div class="card-body" style="display: flex; align-items: center; height: 100%;">
													<div class="summary">
														<div class="summary-header" style="display: flex; align-items: center;">
															<i class="fa-regular fa-clock fa-2xl" style="color: #000;"></i>
														</div>
														<div class="summary-body">
															<div style="display: flex; flex-direction: row; gap:5px; align-items: end;">
																<h3 style="margin: 0; font-weight: bold;">1,248</h3>ชม.
															</div>
															<div><span style="color: #198754;">+120 ชม.</span> จากเดือนก่อน
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
											<div class="card" style="padding: 10px;">
												<div class="card-header">การเรียนรู้ต่อคน (เฉลี่ย)</div>
												<div class="card-body" style="display: flex; align-items: center; height: 100%;">
													<div class="summary">
														<div class="summary-header">
															<div style="background-color: color-mix(in srgb, #ffc107 15%, transparent); padding-inline: 10px; border-radius: 50%;">
																<i class="fa-regular fa-user fa-lg" style="color:#ffc107;"></i>
															</div>
														</div>
														<div class="summary-body">
															<div style="display: flex; flex-direction: row; gap:5px; align-items: end;">
																<h3 style="margin: 0; font-weight: bold;">2.4</h3>หลักสูตร
															</div>
															<div><span style="color: #198754;">+0.3%</span> จากเดือนก่อน
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--  <div class="card-footer">
                                <a href="">ดูรายงานฉบับเต็ม<i class="fa-solid fa-angle-right"></i></a>
                            </div> -->
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
													<i class="fa-solid fa-angle-down"></i>
												</div>
											</div>
										</div>
										<div class="card">
											<div style="display: flex; flex-direction: row; justify-content: space-between;">
												<span>การใช้งานวันนี้</span>
												<div style="display: flex; flex-direction:row; gap: 5px; align-items:center;">
													<strong>1,246 ครั้ง</strong>
													<i class="fa-solid fa-angle-down"></i>
												</div>
											</div>
										</div>
										<div class="card">
											<div style="display: flex; flex-direction: row; justify-content: space-between;">
												<span>Backup ล่าสุด</span>
												<div style="display: flex; flex-direction:row; gap: 5px; align-items:center;">
													<strong>15/05/2024 02:00</strong>
													<i class="fa-solid fa-angle-down"></i>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card-footer"><span style="color: #198754;"><i class="fa-solid fa-circle-check"></i> ทำงานปกติ</span></div>
							</div>
						</div>
					</div>
				</section>

				<!-- SECTION 5 -->
				<section>
					<div class="row row-eq-height custom-row-gap">
						<div class="col-lg-5 col-md-12 col-sm-12">
							<div class="card">
								<div class="card-header"><strong>ประกาศจากผู้ดูแลระบบ</strong></div>
								<div class="card-body">
									<div class="summary">
										<div class="summary-header">
											<div style="background-color: color-mix(in srgb, #0d6efd 15%, transparent); padding-inline: 10px;">
												<i class="fa-solid fa-bullhorn fa-lg" style="color: #0d6efd;"></i>
											</div>
										</div>
										<div style="display: flex; flex-direction: column; gap:5px;">
											<strong>ระบบจะปิดปรับปรูงในวันที่ 25 พฤษภาคม 2567 เวลา 22:00 - 02:00 น.</strong>
											<span>อาจไม่สามารถใช้งานระบบในช่วงเวลาดังกล่าว ขออภัยในความไม่สดวก</span>
										</div>
									</div>
								</div>

							</div>
						</div>
						<div class="col-lg-4 col-md-6 col-sm-12">
							<div class="card">
								<div class="card-header"><strong>นโยบายและกำหนดการสำคัญ</strong></div>
								<div class="card-body">
									<div style="display: flex; flex-direction:column; gap:5px;">
										<div style="display: flex; flex-direction:row; justify-content: space-between; align-items: center;">
											<div class="summary">
												<div class="summary-header">
													<div style="background-color: color-mix(in srgb, #0d6efd 15%, transparent); padding-inline: 10px;">
														<i class="fa-regular fa-file-lines fa-lg" style="color: #0d6efd;"></i>
													</div>
												</div>
												<div style="display: flex; flex-direction: column; gap:5px;">
													<strong>นโยบายความปลอดภัยในการทำงาน</strong>
													<span>อัปเดตล่าสุด 01/05/2024</span>
												</div>
											</div>
											<a href="" class="btn btn-default" style="color:#0d6efd; border:#0d6efd 1px solid;">ดูเอกสาร</a>
										</div>
										<div style="display: flex; flex-direction:row; justify-content: space-between; align-items: center;">
											<div class="summary">
												<div class="summary-header">
													<div style="background-color: color-mix(in srgb, #198754 15%, transparent); padding-inline: 10px;">
														<i class="fa-regular fa-file-lines fa-lg" style="color: #198754;"></i>
													</div>
												</div>
												<div style="display: flex; flex-direction: column; gap:5px;">
													<strong>แผนการอบรมประจำปี 2567</strong>
													<span>อัปเดตล่าสุด 01/01/2024</span>
												</div>
											</div>
											<a href="" class="btn btn-default" style="color:#0d6efd; border:#0d6efd 1px solid;">ดูเอกสาร</a>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-lg-3 col-md-6 col-sm-12">
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
			</div>

		</div>

	</div>
	<div class="clearfix"></div>


	<!-- Admin-Manager -->
	<script type="text/javascript">

let typingTimer;

function loadTable(page = 1){

    $.ajax({

        url: "{{ route('dashboard.team-learning.ajax') }}",
        type: "GET",

        data:{
            keyword: $('#keyword').val(),
            page: page
        },

        beforeSend:function(){

            $('#loading-overlay').css('display','flex');

            $('#team-learning-table').css({
                opacity:.4,
                pointerEvents:'none'
            });

        },

        success:function(res){

            $('#team-learning-table').html(res);

        },

        complete:function(){

            $('#loading-overlay').hide();

            $('#team-learning-table').css({
                opacity:1,
                pointerEvents:'auto'
            });

        },

        error:function(){

            alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');

        }

    });

}

$('#keyword').on('input', function(){

    clearTimeout(typingTimer);

    typingTimer = setTimeout(function(){

        loadTable(1);

    },300);

});

$(document).on('click','.pagination a',function(e){

    e.preventDefault();

    let page = new URL($(this).attr('href')).searchParams.get('page');

    loadTable(page);

});

			


		$(function () {
			$('[data-toggle="tooltip"]').tooltip();
		});


		google.charts.load('current', {
			packages: ['corechart']
		});
		google.charts.setOnLoadCallback(drawChart);
		google.charts.setOnLoadCallback(drawDonutChart);

		const roadmapMonthly = @json($roadmapMonthly);
		const mandatorySummary = @json($mandatorySummary);

		const chartData = [
			['Month', 'Percent']
		];

		roadmapMonthly.forEach(item => {
			chartData.push([
				item.month,
				item.percent
			]);
		});

		function drawChart() {

			const data = google.visualization.arrayToDataTable(chartData);

			const options = {
				legend: 'none',
				areaOpacity: 0.15,
				pointSize: 6,
				hAxis: {
					textStyle: {
						fontSize: 10
					}
				},
				vAxis: {
					minValue: 0,
					maxValue: 100,
					textStyle: {
						fontSize: 10
					}
				},
				colors: ['#1a73e8'],
				chartArea: {
					left: 25,
					top: 10,
					width: '88%',
					height: '75%'
				}
			};

			const chart = new google.visualization.AreaChart(
				document.getElementById('team-learning-trends')
			);

			chart.draw(data, options);
		}

		function drawDonutChart() {

			var data = google.visualization.arrayToDataTable([
				['Status', 'Percent'],
				['Success', mandatorySummary.complete_percent],
				['Near', mandatorySummary.warning_percent],
				['Unfinished', mandatorySummary.not_complete_percent]
			]);

			
			var options = {
				pieHole: 0.7,
				pieSliceText: 'none',
				tooltip: {
					trigger: 'none'
				},
				legend: 'none',
				chartArea: {
					left: 0,
					top: 0,
					width: '100%',
					height: '100%'
				},
				slices: {
					0: {
						color: "green"
					},
					1: {
						color: "yellow"
					},
					2: {
						color: "red"
					}
				},
				enableInteractivity: false
			};

			var chart = new google.visualization.PieChart(document.getElementById('donut_single'));
			chart.draw(data, options);
		}
		window.addEventListener('resize', drawChart);
		window.addEventListener('resize', drawDonutChart);

		google.charts.setOnLoadCallback(drawTeamChart);

		function drawTeamChart() {
			const data = google.visualization.arrayToDataTable([
				['Team', 'Completion Rate', {
					role: 'annotation'
				}, 'Pass Rate', {
					role: 'annotation'
				}],
				['Team A\n(ของฉัน)', 90, '90%', 83, '83%'],
				['Team B', 75, '75%', 68, '68%'],
				['Team C', 60, '60%', 55, '55%']
			]);

			const options = {
				legend: {
					position: 'bottom'
				},
				colors: ['#18a957', '#1f66e5'],
				backgroundColor: 'transparent',
				bar: {
					groupWidth: '55%'
				},
				chartArea: {
					left: 45,
					top: 20,
					width: '88%',
					height: '72%'
				},
				vAxis: {
					minValue: 0,
					maxValue: 100,
					ticks: [0, 20, 40, 60, 80, 100],
					textStyle: {
						fontSize: 11
					}
				},
				hAxis: {
					textStyle: {
						fontSize: 11
					}
				},
				annotations: {
					alwaysOutside: true,
					textStyle: {
						fontSize: 12,
						bold: true,
						color: '#111'
					}
				},
				tooltip: {
					trigger: 'none'
				}
			};

			const chart = new google.visualization.ColumnChart(document.getElementById('teamChart'));
			chart.draw(data, options);
		}

		window.addEventListener('resize', drawTeamChart);

		$(function() {
			$('#dateRange').daterangepicker({
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

	<!-- Admin-management -->
	<script>
		$(document).ready(function() {
			var ctx = document.getElementById('passRateChart').getContext('2d');
			var passRateChart = new Chart(ctx, {
				type: 'doughnut',
				data: {
					labels: ['Raw material', 'Blowing', 'Mixing', 'Filling', 'Packing', 'Packaging'],
					datasets: [{
						data: [1, 1, 1, 1, 1, 1],
						backgroundColor: [
							'#3b82f6',
							'#ef4444',
							'#f59e0b',
							'#6366f1',
							'#14b8a6',
							'#fca5a5'
						],
						borderWidth: 0,
						hoverOffset: 4
					}]
				},
				options: {
					cutout: '75%',
					responsive: true,
					maintainAspectRatio: false,
					plugins: {
						legend: {
							display: false
						},
						tooltip: {
							enabled: false
						}
					}
				}
			});
		});


		var ctxNewEmp = document.getElementById('newEmployeeChart').getContext('2d');
		var newEmployeeChart = new Chart(ctxNewEmp, {
			type: 'doughnut',
			data: {
				labels: ['ครบ 30 วัน', 'ครบ 60 วัน', 'ครบ 90 วัน', 'ครบ 120 วัน', 'เกิน 120 วัน'],
				datasets: [{
					data: [42, 38, 26, 22, 22],
					backgroundColor: [
						'#6b4ce6',
						'#f87171',
						'#60a5fa',
						'#2dd4bf',
						'#059669'
					],
					borderWidth: 0,
					hoverOffset: 4
				}]
			},
			options: {
				cutout: '75%',
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						display: false
					},
					tooltip: {
						enabled: false
					}
				}
			}
		});

		var ctxTrend = document.getElementById('trendLineChart').getContext('2d');
		var trendLineChart = new Chart(ctxTrend, {
			type: 'line',
			data: {
				labels: ['ธ.ค. 66', 'ม.ค. 67', 'ก.พ. 67', 'มี.ค. 67', 'เม.ย. 67', 'พ.ค. 67'],
				datasets: [{
						label: 'Completion Rate (%)',
						data: [65, 67, 69, 72, 75, 78],
						borderColor: '#6b4ce6',
						backgroundColor: '#6b4ce6',
						tension: 0.1,
						borderWidth: 2,
						pointRadius: 4,
						pointBackgroundColor: '#6b4ce6'
					},
					{
						label: 'Pass Rate (%)',
						data: [60, 62, 64, 67, 69, 72],
						borderColor: '#3b82f6',
						backgroundColor: '#3b82f6',
						tension: 0.1,
						borderWidth: 2,
						pointRadius: 4,
						pointBackgroundColor: '#3b82f6'
					},
					{
						label: 'ต้องสอบซ่อม (คน)',
						data: [35, 32, 30, 28, 30, 28],
						borderColor: '#ef4444',
						backgroundColor: '#ef4444',
						tension: 0.1,
						borderWidth: 2,
						pointRadius: 4,
						pointBackgroundColor: '#ef4444'
					}
				]
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				plugins: {
					legend: {
						display: false
					}
				},
				scales: {
					y: {
						min: 0,
						max: 100,
						ticks: {
							stepSize: 20
						},
						grid: {
							color: '#f0f0f0'
						}
					},
					x: {
						grid: {
							display: false
						},
						offset: true
					}
				}
			},
			plugins: [{
				id: 'alwaysShowDataLabels',
				afterDatasetsDraw: function(chart) {
					var ctx = chart.ctx;
					chart.data.datasets.forEach(function(dataset, i) {
						var meta = chart.getDatasetMeta(i);
						if (!meta.hidden) {
							meta.data.forEach(function(element, index) {
								ctx.fillStyle = dataset.borderColor;
								ctx.font = 'bold 12px "SupermarketCustom", sans-serif';
								ctx.textAlign = 'center';
								ctx.textBaseline = 'middle';

								var dataString = dataset.data[index].toString();
								var yPos;

								if (i === 0) {
									yPos = element.y - 15;
									dataString += '%';
								} else if (i === 1) {
									yPos = element.y + 15;
									dataString += '%';
								} else {
									yPos = element.y + 15;
								}

								ctx.fillText(dataString, element.x, yPos);
							});

						}
					});
				}
			}]
		});
	</script>

	<!-- Admin Dashboard -->
	<script>
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

</body>
@endsection
