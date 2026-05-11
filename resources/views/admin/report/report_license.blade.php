@extends('admin/layouts/mainlayout')
@section('title', 'Admin')
@section('content')
@php

function getSkillLevel($percent) {
    if ($percent == 100) return 5;
    if ($percent >= 80) return 4;
    if ($percent >= 60) return 3;
    if ($percent >= 25) return 2;
    if ($percent >= 0) return 1;
    return 0;
}
@endphp
<style>
.license-lp3 {
    background: #00ff00;
    text-align: center;
    font-size: 22px;
    font-weight: bold;
}

.license-lp2 {
    background: #fff3cd;
    text-align: center;
    font-size: 22px;
    font-weight: bold;
}

.license-lp1 {
    background: #ff4d4d;
    color: white;
    text-align: center;
    font-size: 22px;
    font-weight: bold;
}

.license-na {
    background: #bdbdbd;
}
.skill-icon {
    width: 22px;
    height: 22px;
    border-radius: 50%;
    display: inline-block;
    border: 1px solid #999;
}

.group-header {
    background: yellow;
    text-align: center;
    font-weight: bold;
}
thead th[rowspan] {
    vertical-align: middle !important;
    text-align: center;
}
th.rotate {
    height: 180px;
	width: 60px;
	position: relative;
	text-align: center;
    vertical-align: middle;
	border: 1px solid #ccc;
    padding: 0;
}

th.rotate > div {
	position: absolute;
	top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) rotate(-90deg);
    transform-origin: center;
    white-space: nowrap;
}

/* Skill 5 ⭐ */
.skill-5 {
    background: transparent;
    clip-path: polygon(50% 0%, 61% 35%, 98% 35%, 68% 57%, 
                       79% 91%, 50% 70%, 21% 91%, 32% 57%, 
                       2% 35%, 39% 35%);
}

/* Skill 4 ● */
.skill-4 {
    background: #333;
}

/* Skill 3 ◕ */
.skill-3 {
    background: conic-gradient(#333 75%, #eee 0%);
}

/* Skill 2 ◑ */
.skill-2 {
    background: conic-gradient(#333 50%, #eee 0%);
}

/* Skill 1 ◔ */
.skill-1 {
    background: conic-gradient(#333 25%, #eee 0%);
}

/* N/A */
.skill-0 {
    background: #ccc;
}
</style>
<body class="">
	<div id="wrapper">
		<div class="content-wrapper">
			<div class="content-header">
				<div class="container-fluid">
					<div class="d-flex align-items-center">
						<div class="">
							<h4 class="m-0">ระบบReport</h4>
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
			<div class="content">
				<div class="container-fluid">
					<div class="card m-0">
						<div class="card-body">
							<table id="settingTable" class="table table-striped table-bordered nowrap" style="width:100%">
								 <thead>

									<tr>
										<th rowspan="2">No.</th>
										<th rowspan="2">Name</th>
										<th rowspan="2">Emp Code</th>
										<th rowspan="2">Position</th>

										<th colspan="{{ count($operateMachines) }}">
											Operate Machine
										</th>

										<th colspan="{{ count($parameterSettings) }}">
											Parameter Setting
										</th>
									</tr>

									<tr>

										@foreach($operateMachines as $machine)
											<th>{{ $machine->operation_name }}</th>
										@endforeach

										@foreach($parameterSettings as $setting)
											<th>{{ $setting->parameter_name }}</th>
										@endforeach

									</tr>

								</thead>
								<tbody>
									@foreach($users as $index => $user)
									@php
									$userLicenses = $licenses[$user->id] ?? collect();

									$operateMap = $userLicenses
										->whereNotNull('operation_machine_id')
										->keyBy('operation_machine_id');

									$parameterMap = $userLicenses
										->whereNotNull('parameter_setting_id')
										->keyBy('parameter_setting_id');
									@endphp
									<tr>
										<td>{{ $index+1 }}</td>
										<td>{{ $user->firstname }} {{ $user->lastname }}</td>
										<td>{{ $user->staff_id }}</td>
										<td>{{ $user->Orgchart->title }}</td>

											@foreach($operateMachines as $machine)

											@php
											$license = $operateMap[$machine->id] ?? null;
											@endphp

											<td class="
												@if(!$license)
													license-na
												@elseif($license->license_level == 3)
													license-lp3
												@elseif($license->license_level == 2)
													license-lp2
												@else
													license-lp1
												@endif
												">

												@if($license)

													@if($license->license_level == 3)
														✔
													@elseif($license->license_level == 2)
														⚠
													@else
														✖
													@endif

												@endif

											</td>

											@endforeach
											@foreach($parameterSettings as $setting)

											@php
											$license = $parameterMap[$setting->id] ?? null;
											@endphp

											<td class="
												{{ $license ? 'license-pass' : 'license-na' }}
											">

											@if($license)
												O
											@endif

											</td>

											@endforeach
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div id="sidebar">
			</div><!-- sidebar -->
		</div>
	</div>
	<div class="clearfix"></div>
<script>
	$(document).ready(function() {
		// Initialize DataTable
		$('#settingTable').DataTable({
			ordering: false,
			responsive: true,
			scrollX: true,
			paging: false,
			language: {
				url: '/include/languageDataTable.json',
			}
		});
	});
</script>
</body>
@endsection
