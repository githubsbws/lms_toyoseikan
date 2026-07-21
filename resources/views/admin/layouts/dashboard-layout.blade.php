@extends('admin/layouts/mainlayout')
@section('title', 'Dashboard')
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
			
			{{-- Dashboard Navbar --}}
			@include('admin.layouts.partials.dashboard.navbar')
			
			{{-- เนื้อหาหลักของแต่ละ Dashboard --}}
			@yield('dashboard-content')
			
		</div>
	</div>
	
	<div class="clearfix"></div>
	
	{{-- Scripts เฉพาะแต่ละหน้า --}}
	@stack('scripts')
</body>

@endsection
