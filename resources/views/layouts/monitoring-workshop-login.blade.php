<?php //echo $id_company; die(); ?>

<!DOCTYPE html>  
<html lang="en">
<head>
	<meta charset="utf-8">
	<!--<meta http-equiv="refresh" content="300">-->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" sizes="16x16" href="plugins/images/icon.png">
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
	{!! Html::style('css/style_monitoring.css') !!}
	<!-- color CSS -->
	{!! Html::style('css/colors/default.css') !!}

	{!! Html::style('css/gauge/jquery.dynameter.css') !!}
	{!! Html::style('css/gauge/index.css') !!}

	{!! Html::script('plugins/bower_components/jquery/dist/jquery.min.js') !!}
	
	<!-- ChartJs Plugin -->
	{!! Html::script('plugins/bower_components/Chart.js/Chart.bundle.js') !!}
	{!! Html::script('plugins/bower_components/Chart.js/utils.js') !!}
	
</head>
	
<body>

<!-- Preloader -->
		<div class="preloader">
			<div class="cssload-speeding-wheel"></div>
		</div>
		<div id="wrapper">
			<!-- Left navbar-header -->
			<!-- Left navbar-header end -->
			<!-- Page Content -->
			<div id="page-wrapper">
				<div class="container-fluid">
					<div class="row bg-title">
						<div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">
							<h4 class="page-title">{{ $title }}</h4>
						</div>
						<div class="col-lg-3 col-sm-3 col-md-3 col-xs-12">
							<ol class="breadcrumb">
								<li><a id="time-part">{{ @$date }}</a></li>
							</ol>
						</div>
						<!-- /.col-lg-12 -->
					</div>
					<!-- /.row -->					
					<!-- /.container-fluid -->
					<!--<footer class="footer text-center"> 2016 &copy; Triputra Agro Persada </footer>-->
				</div>
				<!-- /#page-wrapper -->
				
				<div class="container">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<h1>Sign In </h1>
						<form id="form-login" method="POST" action="<?php echo url('/monitoring-workshop-dologin/'.$id_company.''); ?>">
							{{ csrf_field() }}
							
							@if (session('status'))
								<div class="alert alert-danger">
									{{ session('status') }}
								</div>
							@endif
							
							<table class="table table-responsive" width="200">
								<tr>
									<td>USERNAME</td>
									<td>:</td>
									<td><input class="form-control" type="text" id="iuser" name="iuser" value=""/></td>
								</tr>
								<tr>
									<td>PASSWORD</td>
									<td>:</td>
									<td><input class="form-control" type="password" id="ipass" name="ipass" value=""/></td>
								</tr>
								<tr>
									<td colspan="3" align="right"><input class="btn btn-success" type="submit" id="isubmit" name="isubmit" value="LOGIN"/></td>
								</tr>
							</table>
						</form>
					</div>
					<div class="col-md-4"></div>
				</div>
				
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

		<!-- {!! Html::script('js/highcharts.js') !!} -->


		<script type="text/javascript">
			$(document).ready(function() {
    				/*var interval = setInterval(function() {
        				var momentNow = moment();
        				$('#time-part').html(momentNow.format('HH : mm : ss'));
    				}, 100);*/
			});
		</script>
		
</body>

</html>


