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
								<div class="col-md-3 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-danger">
  										<h1 class="text-white counter">{{$response['danger']['angka']}}</h1>
									</div>
								</div>
								<div class="col-md-3 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-warning">
  										<h1 class="text-white counter">{{$response['warning']['angka']}}</h1>
									</div>
								</div>
								<div class="col-md-3 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-success">
  										<h1 class="text-white counter">{{$response['success']['angka']}}</h1>
									</div>
								</div>
								<div class="col-md-3 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-default">
  										<strong>AVG (H) : </strong><h1 class="text-black counter">{{$response['average']}}</h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="progress progress-lg">
						<div role="progressbar" aria-valuenow="{{$response['danger']['persen']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$response['danger']['persen']}}%;" class="progress-bar progress-bar-danger progress-bar">{{$response['danger']['persen']}}%</div>
						<div role="progressbar" aria-valuenow="{{$response['warning']['persen']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$response['warning']['persen']}}%;" class="progress-bar progress-bar-warning">{{$response['warning']['persen']}}%</div>
						<div role="progressbar" aria-valuenow="{{$response['success']['persen']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$response['success']['persen']}}%;" class="progress-bar progress-bar-success progress-bar">{{$response['success']['persen']}}%</div>
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
								<div class="col-md-3 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-danger">
  										<h1 class="text-white counter">{{$solving['danger']['angka']}}</h1>
									</div>
								</div>
								<div class="col-md-3 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-warning">
  										<h1 class="text-white counter">{{$solving['warning']['angka']}}</h1>
									</div>
								</div>
								<div class="col-md-3 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-success">
  										<h1 class="text-white counter">{{$solving['success']['angka']}}</h1>
									</div>
								</div>
								<div class="col-md-3 col-xs-12 col-sm-6">
									<div class="white-box text-center bg-default">
  										<strong>AVG (D) : </strong><h1 class="text-black counter">{{$solving['average']}}</h1>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="progress progress-lg">
						<div role="progressbar" aria-valuenow="{{$solving['danger']['persen']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$solving['danger']['persen']}}%;" class="progress-bar progress-bar-danger progress-bar">{{$solving['danger']['persen']}}%</div>
						<div role="progressbar" aria-valuenow="{{$solving['warning']['persen']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$solving['warning']['persen']}}%;" class="progress-bar progress-bar-warning">{{$solving['warning']['persen']}}%</div>
						<div role="progressbar" aria-valuenow="{{$solving['success']['persen']}}" aria-valuemin="0" aria-valuemax="100" style="width: {{$solving['success']['persen']}}%;" class="progress-bar progress-bar-success progress-bar">{{$solving['success']['persen']}}%</div>
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
									<th>Nomor Ticket</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($monitor as $row)
								<tr>
									<td class="txt-oflo">{{$row['nama']}}</td>
									<td class="txt-oflo">{{$row['last']}}</td>
									<td class="txt-oflo">{{$row['today']}}</td>
									<td class="txt-oflo">{{$row['max']}}</td>
									<td class="txt-oflo">{{$row['nomor']}}</td>
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