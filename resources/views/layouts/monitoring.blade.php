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
		{!! Html::style('css/style.css') !!}
		<!-- color CSS -->
		{!! Html::style('css/colors/default.css') !!}

		{!! Html::style('css/gauge/jquery.dynameter.css') !!}
		{!! Html::style('css/gauge/index.css') !!}

		{!! Html::script('plugins/bower_components/jquery/dist/jquery.min.js') !!}
		
		<!-- ChartJs Plugin -->
		{!! Html::script('plugins/bower_components/Chart.js/Chart.bundle.js') !!}
		{!! Html::script('plugins/bower_components/Chart.js/utils.js') !!}

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
								<li><a id="time-part">{{ $date }}</a></li>
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
    				/*var interval = setInterval(function() {
        				var momentNow = moment();
        				$('#time-part').html(momentNow.format('HH : mm : ss'));
    				}, 100);*/
			});
		</script>

		<!--<script type="text/javascript">
			$mtrDaily = $("div#mtrDaily").dynameter({
				width: 200,
				label: '',
				value: <?php //echo (int) $persen_mtr; ?>,
				min: 0,
				max: 100,
				regions: {
					0: 'error',
					55: 'warn',
					65: 'normal'
				}
			});

			$mtrMTD = $("div#mtrMTD").dynameter({
				width: 200,
				label: '',
				value: <?php //echo (int) $persen_mtr_mtd; ?>,
				min: 0,
				max: 100,
				regions: {
					0: 'error',
					55: 'warn',
					65: 'normal'
				}
			});

			$mtrYTD = $("div#mtrYTD").dynameter({
				width: 200,
				label: '',
				value: <?php //echo (int) $persen_mtr_ytd; ?>,
				min: 0,
				max: 100,
				regions: {
					0: 'error',
					55: 'warn',
					65: 'normal'
				}
			});

			$mtsDaily = $("div#mtsDaily").dynameter({
				width: 200,
				label: '',
				value: <?php //echo (int) $persen_mts; ?>,
				min: 0,
				max: 100,
				regions: {
					0: 'error',
					65: 'warn',
					75: 'normal'
				}
			});

			$mtsMTD = $("div#mtsMTD").dynameter({
				width: 200,
				label: '',
				value: <?php //echo (int) $persen_mts_mtd; ?>,
				min: 0,
				max: 100,
				regions: {
					0: 'error',
					65: 'warn',
					75: 'normal'
				}
			});

			$mtsYTD = $("div#mtsYTD").dynameter({
				width: 200,
				label: '',
				value: <?php //echo (int) $persen_mts_ytd; ?>,
				min: 0,
				max: 100,
				regions: {
					0: 'error',
					65: 'warn',
					75: 'normal'
				}
			});
		</script>


		<script type="text/javascript">
			var yourLabels = <?php //echo $bulan_mtr; ?>;
			Highcharts.chart('graph_mtr', {
				title: {
					useHTML: true,
					text: '',
					style: {
						fontWeight: 'bold'
					}
				},
				credits: { enabled: false },
				exporting: { enabled: false },
				chart: { height: '235px' },
				xAxis: {
					title: {
						useHTML: true,
						text: 'Month',
						style: {
							fontWeight: 'bold',
							fontSize: '20px'
						}
					},
					categories: [0, 1, 2],
					labels: {
						formatter: function() { return yourLabels[this.value]; },
						style: {
							color: '#ab8ce4',
							fontSize:'20px',
							fontWeight: 'bold'
						}
					}
				},
				yAxis: {
					min: 0,
					max: 100,
					endOnTick:false,
					tickInterval:10,
					lineWidth: 1,
					title: {
						useHTML: true,
						text: 'Percentage (%)',
						style: {
							fontWeight: 'bold',
							fontSize: '20px'
						}
					},
					labels: {
						style: {
							color: '#01c0c8',
							fontSize:'20px',
							fontWeight: 'bold'
						}
					},
					plotLines: [{
						value: 0,
						width: 10
					}]
				},
				plotOptions: {
					line : {
						dataLabels : {
							enabled : true,
							formatter : function() {
								if (this.y != 0) {
									return this.y;
								} else {
									return '';
								}
							},
							style: {
								color: '#e46c0a',
								fontSize: '20px',
								fontWeight: 'bold'
							}
						}
					}
				},
				legend: {
					layout: 'vertical',
					align: 'right',
					borderWidth: 0
				},
				series: [{
					showInLegend: false,
					name: 'Value',
					data: [{{}}]
				}]
			});
		</script>

		<script type="text/javascript">
			var yourLabels = <?php //echo $bulan_mts; ?>;
			Highcharts.chart('graph_mts', {
				title: {
					useHTML: true,
					text: '',
					style: {
						fontWeight: 'bold'
					}
				},
				credits: { enabled: false },
				exporting: { enabled: false },
				chart: { height: '235px' },
				xAxis: {
					title: {
						useHTML: true,
						text: 'Month',
						style: {
							fontWeight: 'bold',
							fontSize: '20px'
						}
					},
					categories: [0, 1, 2],
					labels: {
						formatter: function() { return yourLabels[this.value]; },
						style: {
							color: '#ab8ce4',
							fontSize:'20px',
							fontWeight: 'bold'
						}
					}
				},
				yAxis: {
					min: 0,
					max: 100,
					endOnTick:false,
					tickInterval:10,
					lineWidth: 1,
					title: {
						useHTML: true,
						text: 'Percentage (%)',
						style: {
							fontWeight: 'bold',
							fontSize: '20px'
						}
					},
					labels: {
						style: {
							color: '#01c0c8',
							fontSize:'20px',
							fontWeight: 'bold'
						}
					},
					plotLines: [{
						value: 0,
						width: 10
					}]
				},
				plotOptions: {
					line : {
						dataLabels : {
							enabled : true,
							formatter : function() {
								if (this.y != 0) {
									return this.y;
								} else {
									return '';
								}
							},
							style: {
								color: '#e46c0a',
								fontSize: '20px',
								fontWeight: 'bold'
							}
						}
					}
				},
				legend: {
					layout: 'vertical',
					align: 'right',
					borderWidth: 0
				},
				series: [{
					showInLegend: false,
					name: 'Value',
					data: [{{}}]
				}]
			});
		</script>-->
		
		<script>
			var MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
			var config = {
				type: 'line',
				data: {
					labels: [<?php echo $listday; ?>],
					datasets: [{
						label: 'Plan',
						backgroundColor: window.chartColors.red,
						borderColor: window.chartColors.red,
						data: [<?php echo $plan; ?>],
						fill: false,
					}, {
						label: 'Actual',
						fill: false,
						backgroundColor: window.chartColors.blue,
						borderColor: window.chartColors.blue,
						data: [<?php echo $actual; ?>],
					}]
				},
				options: {
					responsive: true,
					title: {
						display: false,
						text: 'Chart.js Line Chart'
					},
					tooltips: {
						mode: 'index',
						intersect: false,
					},
					hover: {
						mode: 'nearest',
						intersect: true
					},
					scales: {
						xAxes: [{
							display: true,
							scaleLabel: {
								display: true,
								labelString: 'DAY'
							}
						}],
						yAxes: [{
							display: true,
							scaleLabel: {
								display: false,
								labelString: 'Value'
							}
						}]
					}
				}
			};

			window.onload = function() {
				var ctx = document.getElementById('canvas').getContext('2d');
				window.myLine = new Chart(ctx, config);
			};

			document.getElementById('randomizeData').addEventListener('click', function() {
				config.data.datasets.forEach(function(dataset) {
					dataset.data = dataset.data.map(function() {
						return randomScalingFactor();
					});

				});

				window.myLine.update();
			});

			var colorNames = Object.keys(window.chartColors);
			document.getElementById('addDataset').addEventListener('click', function() {
				var colorName = colorNames[config.data.datasets.length % colorNames.length];
				var newColor = window.chartColors[colorName];
				var newDataset = {
					label: 'Dataset ' + config.data.datasets.length,
					backgroundColor: newColor,
					borderColor: newColor,
					data: [],
					fill: false
				};

				for (var index = 0; index < config.data.labels.length; ++index) {
					newDataset.data.push(randomScalingFactor());
				}

				config.data.datasets.push(newDataset);
				window.myLine.update();
			});

			document.getElementById('addData').addEventListener('click', function() {
				if (config.data.datasets.length > 0) {
					var month = MONTHS[config.data.labels.length % MONTHS.length];
					config.data.labels.push(month);

					config.data.datasets.forEach(function(dataset) {
						dataset.data.push(randomScalingFactor());
					});

					window.myLine.update();
				}
			});

			document.getElementById('removeDataset').addEventListener('click', function() {
				config.data.datasets.splice(0, 1);
				window.myLine.update();
			});

			document.getElementById('removeData').addEventListener('click', function() {
				config.data.labels.splice(-1, 1); // remove the label first

				config.data.datasets.forEach(function(dataset) {
					dataset.data.pop();
				});

				window.myLine.update();
			});
			
		</script>

	</body>
</html>