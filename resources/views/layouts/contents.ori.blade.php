@extends('layouts.layout')

@section('content')

<div class="row">
	<div class="col-lg-12 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> Mean Time to Response (70%)</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-4 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> DAILY</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-info" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="a_result">{{$a_result}}</span></h1>
									</div>
								</div>
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-success" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="b_result">{{$b_result}}</span></h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"><br /></div>
					<div class="row" style="text-align:center;">
						<div class="col-md-12 col-xs-12 col-sm-4">
							<div class="white-box text-center bg-persen" style="margin-bottom:0px; border:none;">
								<div id="mtrDaily"></div>
								<!--<h1 class="text-white counter text-info"><span id="persen_mtr">{{$persen_mtr}}</span></h1>-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> MONTH TO DATE</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-info" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="a_mtd">{{$a_mtd}}</span></h1>
									</div>
								</div>
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-success" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="b_mtd">{{$b_mtd}}</span></h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"><br /></div>
					<div class="row" style="text-align:center;">
						<div class="col-md-12 col-xs-12 col-sm-4">
							<div class="white-box text-center bg-persen" style="margin-bottom:0px;">
								<div id="mtrMTD"></div>
								<!--<h1 class="text-white counter text-info"><span id="persen_mtr_mtd">{{$persen_mtr_mtd}}</span></h1>-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> YEAR TO DATE</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-info" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="a_ytd">{{number_format($a_ytd, 0, ',', '.')}}</span></h1>
									</div>
								</div>
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-success" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="b_ytd">{{number_format($b_ytd, 0, ',', '.')}}</span></h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"><br /></div>
					<div class="row" style="text-align:center;">
						<div class="col-md-12 col-xs-12 col-sm-4">
							<div class="white-box text-center bg-persen" style="margin-bottom:0px;">
								<div id="mtrYTD"></div>
								<!--<h1 class="text-white counter text-info"><span id="persen_mtr_ytd">{{$persen_mtr_ytd}}</span></h1>-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<hr  style="border: 3px solid #000;" />
<div class="clear"></div>

<div class="row">
	<div class="col-lg-12 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> Mean Time to Resolve (80%)</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-lg-4 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> DAILY</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-warning" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="c_result">{{$c_result}}</span></h1>
									</div>
								</div>
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-danger" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="d_result">{{$d_result}}</span></h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"><br /></div>
					<div class="row" style="text-align:center;">
						<div class="col-md-12 col-xs-12 col-sm-6">
							<div class="white-box text-center bg-persen" style="margin-bottom:0px;">
								<div id="mtsDaily"></div>
								<!--<h1 class="text-white counter text-info"><span id="persen_mts">{{$persen_mts}}</span></h1>-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> MONTH TO DATE</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-warning" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="c_mtd">{{$c_mtd}}</span></h1>
									</div>
								</div>
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-danger" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="d_mtd">{{$d_mtd}}</span></h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"><br /></div>
					<div class="row" style="text-align:center;">
						<div class="col-md-12 col-xs-12 col-sm-6">
							<div class="white-box text-center bg-persen" style="margin-bottom:0px;">
								<div id="mtsMTD"></div>
								<!--<h1 class="text-white counter text-info"><span id="persen_mts_mtd">{{$persen_mts_mtd}}</span></h1>-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-4 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> YEAR TO DATE</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-warning" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="c_ytd">{{number_format($c_ytd, 0, ',', '.')}}</span></h1>
									</div>
								</div>
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-danger" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="d_ytd">{{number_format($d_ytd, 0, ',', '.')}}</span></h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"><br /></div>
					<div class="row" style="text-align:center;">
						<div class="col-md-12 col-xs-12 col-sm-6">
							<div class="white-box text-center bg-persen" style="margin-bottom:0px;">
								<div id="mtsYTD"></div>
								<!--<h1 class="text-white counter text-info"><span id="persen_mts_ytd">{{$persen_mts_ytd}}</span></h1>-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop