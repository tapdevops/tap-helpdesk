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
	<div class="col-lg-2 col-sm-6">
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
						<div class="col-md-12 col-xs-12 col-sm-12">
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
	<div class="col-lg-2 col-sm-6">
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
						<div class="col-md-12 col-xs-12 col-sm-12">
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
	<div class="col-lg-3 col-sm-12">
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
						<div class="col-md-12 col-xs-12 col-sm-12">
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
	<div class="col-lg-5 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> MONTH TO MONTH - {{$year}}</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div id="graph_mtr" style="max-width:100%; max-height:220px !important;"></div>
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
	<div class="col-lg-2 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> DAILY</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-info" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="c_mtd">{{$c_result}}</span></h1>
									</div>
								</div>
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-success" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="d_mtd">{{$d_result}}</span></h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"><br /></div>
					<div class="row" style="text-align:center;">
						<div class="col-md-12 col-xs-12 col-sm-12">
							<div class="white-box text-center bg-persen" style="margin-bottom:0px;">
								<div id="mtsDaily"></div>
								<!--<h1 class="text-white counter text-info"><span id="persen_mts_mtd">{{$persen_mts_mtd}}</span></h1>-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-2 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> MONTH TO DATE</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-info" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="c_mtd">{{$c_mtd}}</span></h1>
									</div>
								</div>
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-success" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="d_mtd">{{$d_mtd}}</span></h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"><br /></div>
					<div class="row" style="text-align:center;">
						<div class="col-md-12 col-xs-12 col-sm-12">
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
	<div class="col-lg-3 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> YEAR TO DATE</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-info" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="c_ytd">{{number_format($c_ytd, 0, ',', '.')}}</span></h1>
									</div>
								</div>
								<div class="col-md-6 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-success" style="margin-bottom:0px;">
  										<h1 class="text-white counter"><span id="d_ytd">{{number_format($d_ytd, 0, ',', '.')}}</span></h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="clear"><br /></div>
					<div class="row" style="text-align:center;">
						<div class="col-md-12 col-xs-12 col-sm-12">
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
	<div class="col-lg-5 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> MONTH TO MONTH - {{$year}}</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div id="graph_mts" style="max-width:100%; max-height:220px !important;"></div>
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
	<div class="col-md-2 col-md-2 col-sm-12">
		<div class="white-box">
  			<div class="row row-in">
    				<div class="col-lg-12 col-sm-12">
      				<div class="col-in row" style="text-align:center;">
      					{!! Html::image('plugins/images/logo.png', '', array('class'=>'thumb-lg', 'alt'=>'user-img', 'style'=>'width:100% !important; height:105px;')) !!}
      				</div>
    				</div>
  			</div>
		</div>
	</div>
	<div class="col-md-10 col-lg-10 col-sm-12">
		<div class="white-box">
  			<div class="row row-in">
  				<div class="col-lg-1 col-sm-1"></div>
  				@foreach ($info as $row)
    				<div class="col-lg-2 col-sm-2">
    					<div class="col-in row">
        					<div class="col-md-5 col-sm-5 col-xs-5"> 
        						<i data-icon="{{$row['icon']}}" class="linea-icon linea-basic"></i>
    							<h5 class="text-muted vb">{{$row['name']}}</h5>
      					</div>
        					<div class="col-md-7 col-sm-7 col-xs-7">
          						<h3 class="counter text-right m-t-15 text-{{$row['color']}}">{{$row['value']}}</h3>
        					</div>
        					<div class="col-md-12 col-sm-12 col-xs-12">
          						<div class="progress">
          							<div class="progress-bar progress-bar-{{$row['color']}}" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        						</div>
        					</div>
      				</div>
    				</div>
    				@endforeach
    				<div class="col-lg-1 col-sm-1"></div>
  			</div>
		</div>
	</div>
</div>

@stop