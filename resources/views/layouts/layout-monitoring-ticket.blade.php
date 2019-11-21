<!DOCTYPE html>  
<html lang="en">
	<head>
		<meta charset="utf-8">
		<!--<meta http-equiv="refresh" content="300">-->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" sizes="16x16" href="plugins/images/favicon.png">
		<title>{{$title}}</title>

		<!-- Bootstrap Core CSS -->
		{!! Html::style('bootstrap/dist/css/bootstrap.min.css') !!}
		<!-- Menu CSS -->
		{!! Html::style('css/components.css') !!}

		{!! Html::style('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.css') !!}
		<!-- toast CSS -->
		{!! Html::style('plugins/bower_components/toast-master/css/jquery.toast.css') !!}
		<!-- morris CSS -->
		{!! Html::style('plugins/bower_components/morrisjs/morris.css') !!}
		<!-- animation CSS -->
		{!! Html::style('css/animate.css') !!}
		<!-- Custom CSS -->
		{!! Html::style('css/style.css') !!}
		<!-- color CSS -->
		{!! Html::style('css/colors/default.css') !!}

		{!! Html::style('css/gauge/jquery-ui.css') !!}
		{!! Html::style('css/gauge/jquery.dynameter.css') !!}
		{!! Html::style('css/gauge/index.css') !!}

		{!! Html::script('plugins/bower_components/jquery/dist/jquery.min.js') !!}

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->

		<script>var url = "<?php echo url('/'); ?>";</script>
		<meta name="csrf-token" content="{{ Session::token() }}">
	</head>
	<body>
		<!-- Preloader -->
		<div class="preloader">
			<div class="cssload-speeding-wheel"></div>
		</div>
		<div id="wrapper">
			<!-- Left navbar-header -->
			<!--@include('layouts.sidebar')-->
			<!-- Left navbar-header end -->
			<!-- Page Content -->
			<div id="page-wrapper">
				<div class="container-fluid">
					<div class="row bg-title">
						<div class="col-lg-10 col-md-10 col-sm-10 col-xs-12">
							<h4 class="page-title">{{ $title }} ( Date today : <span id="tanggal">{{ $date }}</span> )</h4>
						</div>
						<div class="col-lg-2 col-sm-2 col-md-2 col-xs-12">
							<ol class="breadcrumb">
								<li><a id="time-part"></a></li>
							</ol>
						</div>
						<!-- /.col-lg-12 -->
					</div>
					<!-- /.row -->

					@yield('content')
					<!-- /.container-fluid -->
					<!--<footer class="footer text-center"> 2016 &copy; Triputra Agro Persada </footer>-->
				</div>
				<!-- /#page-wrapper -->
			</div>
			<!-- /#wrapper -->
		</div>


		<!-- Bootstrap Core JavaScript -->
		{!! Html::script('bootstrap/dist/js/bootstrap.min.js') !!}
		{!! Html::script('js/jquery.dynameter.js') !!}

		<!-- Menu Plugin JavaScript -->
		{!! Html::script('plugins/bower_components/sidebar-nav/dist/sidebar-nav.min.js') !!}
		<!--slimscroll JavaScript -->
		{!! Html::script('js/jquery.slimscroll.js') !!}
		<!--Wave Effects -->
		{!! Html::script('js/waves.js') !!}
		<!-- Custom Theme JavaScript -->
		{!! Html::script('js/custom.min.js') !!}
		<!-- Moment JS -->
		{!! Html::script('js/moment.js') !!}

		{!! Html::script('js/highcharts.js') !!}


		<script type="text/javascript">
			$(document).ready(function() {
    				var interval = setInterval(function() {
        				var momentNow = moment();
        				$('#time-part').html(momentNow.format('HH : mm : ss'));
    				}, 100);
			});
		</script>

		<script type="text/javascript">
			
		</script>


		<script type="text/javascript">
			
		</script>

		<script type="text/javascript">
			
		</script>

	</body>
</html>