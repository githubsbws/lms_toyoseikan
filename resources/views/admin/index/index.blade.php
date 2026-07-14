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
			@include('admin.index.adminmanager')

			<!-- Admin Management -->
			@include('admin.index.adminmanagement')

			<!-- Admin Dashboard -->
			@include('admin.index.admindashboard')

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
