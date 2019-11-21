@extends('layouts.layout')

@section('content')
<div class="row">
	<div class="col-lg-6 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-pencil-alt"></i> Mean time to Response</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-4 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-danger" style="margin-bottom:0px;">
  										<h1 class="text-white counter">{{$response['red']}}</h1>
									</div>
									<div class="white-box text-center bg-inverse" style="font-size:13px;">
										<strong class="text-white">> 60 min</strong>
									</div>
								</div>
								<div class="col-md-4 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-warning" style="margin-bottom:0px;">
  										<h1 class="text-white counter">{{$response['orange']}}</h1>
									</div>
									<div class="white-box text-center bg-inverse" style="font-size:13px;">
										<strong class="text-white">> 30 sd < 60 min</strong>
									</div>
								</div>
								<div class="col-md-4 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-success" style="margin-bottom:0px;">
  										<h1 class="text-white counter">{{$response['green']}}</h1>
									</div>
									<div class="white-box text-center bg-inverse" style="font-size:13px;">
										<strong class="text-white">< 30 min</strong>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="progress progress-lg">
						<div role="progressbar" aria-valuenow="{{$response['average']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$response['average']}}%;" class="progress-bar progress-bar-info progress-bar">{{$response['average']}}%</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-6 col-lg-6 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-bookmark-alt"></i> Mean time to Solving</div>
			<div class="panel-wrapper collapse in" aria-expanded="true">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-md-4 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-danger" style="margin-bottom:0px;">
  										<h1 class="text-white counter">{{$solving['red']}}</h1>
									</div>
									<div class="white-box text-center bg-inverse" style="font-size:13px;">
										<strong class="text-white">> 8 hours</strong>
									</div>
								</div>
								<div class="col-md-4 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-warning" style="margin-bottom:0px;">
  										<h1 class="text-white counter">{{$solving['orange']}}</h1>
									</div>
									<div class="white-box text-center bg-inverse" style="font-size:13px;">
										<strong class="text-white">> 4 sd <= 8 hours</strong>
									</div>
								</div>
								<div class="col-md-4 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-success" style="margin-bottom:0px;">
  										<h1 class="text-white counter">{{$solving['green']}}</h1>
									</div>
									<div class="white-box text-center bg-inverse" style="font-size:13px;">
										<strong class="text-white">> 1 sd <= 4 hours</strong>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="progress progress-lg">
						<div role="progressbar" aria-valuenow="{{$solving['average']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$solving['average']}}%;" class="progress-bar progress-bar-info progress-bar">{{$solving['average']}}%</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-7 col-lg-7 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-user"></i> Monitoring Task</div>
			<div class="panel-wrapper collapse in" aria-expanded="true">
				<div class="panel-body">
					<div class="table-responsive">
						<table class="table table-striped color-table info-table">
							<thead>
								<tr>
									<th>Assigned</th>
									<th>Last Total Ticket</th>
									<th>Today Ticket</th>
									<th>Max Duration Ticket</th>
									<th>Ticket Number</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($monitor as $row)
								<tr>
									<td class="txt-oflo">{{$row['nama']}}</td>
									<td class="txt-oflo">{{$row['last_ticket']}}</td>
									<td class="txt-oflo">{{$row['ticket_today']}}</td>
									<td class="txt-oflo">{{$row['waktu']}}</td>
									<td class="txt-oflo">{{$row['id']}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-md-5 col-lg-5 col-sm-12">
		<div class="white-box">
  			<div class="row row-in">
  				@foreach ($add as $row)
    				<div class="col-lg-6 col-sm-6">
    					<div class="col-in row">
        					<div class="col-md-5 col-sm-5 col-xs-5"> 
        						<i data-icon="{{$row['icon']}}" class="linea-icon linea-basic"></i>
    							<h5 class="text-muted vb">{{$row['text']}}</h5>
      					</div>
        					<div class="col-md-7 col-sm-7 col-xs-7">
          						<h3 class="counter text-right m-t-15 text-{{$row['color']}}">{{$row['counter']}}</h3>
        					</div>
        					<div class="col-md-12 col-sm-12 col-xs-12">
          						<div class="progress">
          							<div class="progress-bar progress-bar-{{$row['color']}}" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
        						</div>
        					</div>
      				</div>
    				</div>
    				@endforeach
  			</div>
		</div>
		<div class="white-box">
  			<div class="row row-in">
    				<div class="col-lg-12 col-sm-12">
      				<div class="col-in row" style="text-align:center;">
      					{!! Html::image('plugins/images/' . $smilies, '', array('class' => 'thumb-lg')) !!}
      				</div>
    				</div>
  			</div>
		</div>
	</div>
	<!-- /.row -->
</div>
@stop