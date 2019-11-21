@extends('layouts.monitoring')

@section('content')

<script type="text/javascript">
	var timeoutToday = setInterval(getToday, 5000);
	var timeoutTomorrow = setInterval(getTomorrow, 3000);
	var timeoutApproval = setInterval(getApproval, 3000);
	var timeoutMaterial = setInterval(getMaterial, 3000);
	var timeoutRelease = setInterval(getRelease, 3000);
	var timeoutProgress = setInterval(getProgress, 3000);
	var timeoutTeco = setInterval(getTeco, 3000);
	var timeoutSubwo = setInterval(getSubwo, 10000); //3000 = 3 sec
	var timeoutstanding = setInterval(getOutstanding, 10000); 

	function getToday() {
		var limitToday = $('#limitToday').val();
		var offsetToday = $('#offsetToday').val();
		var totalToday = $('#totalToday').val();

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: url + '/monitoring/getToday',
			data: { 
				"_token": "{{ csrf_token() }}",
				limit: limitToday, 
				offset: offsetToday, 
				total: totalToday
			},
			success: function(data) {
				$('#limitToday').val(data.limit);
				$('#offsetToday').val(data.offset);
				$('#totalToday').val(data.total);
				$('#today').html(data.data);
			}
		});
	}

	function getTomorrow() {
		var limitTomorrow = $('#limitTomorrow').val();
		var offsetTomorrow = $('#offsetTomorrow').val();
		var totalTomorrow = $('#totalTomorrow').val();

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: url + '/monitoring/getTomorrow',
			data: { 
				"_token": "{{ csrf_token() }}",
				limit: limitTomorrow, 
				offset: offsetTomorrow,
				total: totalTomorrow
			},
			success: function(data) {
				$('#limitTomorrow').val(data.limit);
				$('#offsetTomorrow').val(data.offset);
				$('#totalTomorrow').val(data.total);
				$('#tomorrow').html(data.data);
			}
		});
	}

	function getApproval() {
		var limitApproval = $('#limitApproval').val();
		var offsetApproval = $('#offsetApproval').val();
		var totalApproval = $('#totalApproval').val();

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: url + '/monitoring/getApproval',
			data: { 
				"_token": "{{ csrf_token() }}",
				limit: limitApproval, 
				offset: offsetApproval,
				total: totalApproval
			},
			success: function(data) {
				$('#limitApproval').val(data.limit);
				$('#offsetApproval').val(data.offset);
				$('#totalApproval').val(data.total);
				$('#approval').html(data.data);
			}
		});
	}

	function getMaterial() {
		var limitMaterial = $('#limitMaterial').val();
		var offsetMaterial = $('#offsetMaterial').val();
		var totalMaterial = $('#totalMaterial').val();

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: url + '/monitoring/getMaterial',
			data: { 
				"_token": "{{ csrf_token() }}",
				limit: limitMaterial, 
				offset: offsetMaterial,
				total: totalMaterial
			},
			success: function(data) {
				$('#limitMaterial').val(data.limit);
				$('#offsetMaterial').val(data.offset);
				$('#totalMaterial').val(data.total);
				$('#material').html(data.data);
			}
		});
	}

	function getRelease() {
		var limitRelease = $('#limitRelease').val();
		var offsetRelease = $('#offsetRelease').val();
		var totalRelease = $('#totalRelease').val();

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: url + '/monitoring/getRelease',
			data: { 
				"_token": "{{ csrf_token() }}",
				limit: limitRelease, 
				offset: offsetRelease,
				total: totalRelease
			},
			success: function(data) {
				$('#limitRelease').val(data.limit);
				$('#offsetRelease').val(data.offset);
				$('#totalRelease').val(data.total);
				$('#release').html(data.data);
			}
		});
	}

	function getProgress() {
		var limitProgress = $('#limitProgress').val();
		var offsetProgress = $('#offsetProgress').val();
		var totalProgress = $('#totalProgress').val();

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: url + '/monitoring/getProgress',
			data: { 
				"_token": "{{ csrf_token() }}",
				limit: limitProgress, 
				offset: offsetProgress,
				total: totalProgress
			},
			success: function(data) {
				$('#limitProgress').val(data.limit);
				$('#offsetProgress').val(data.offset);
				$('#totalProgress').val(data.total);
				$('#progress').html(data.data);
			}
		});
	}

	function getTeco() {
		var limitTeco = $('#limitTeco').val();
		var offsetTeco = $('#offsetTeco').val();
		var totalTeco = $('#totalTeco').val();

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: url + '/monitoring/getTeco',
			data: { 
				"_token": "{{ csrf_token() }}",
				limit: limitTeco, 
				offset: offsetTeco,
				total: totalTeco
			},
			success: function(data) {
				$('#limitTeco').val(data.limit);
				$('#offsetTeco').val(data.offset);
				$('#totalTeco').val(data.total);
				$('#teco').html(data.data);
			}
		});
	}
	
	function getSubwo() 
	{
		var limitSubwo = $('#limitSubwo').val();
		var offsetSubwo = $('#offsetSubwo').val();
		var totalSubwo = $('#totalSubwo').val();

		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: url + '/monitoring/getSubwo',
			data: { 
				"_token": "{{ csrf_token() }}",
				limit: limitSubwo, 
				offset: offsetSubwo, 
				total: totalSubwo
			},
			success: function(data) 
			{
				$('#limitSubwo').val(data.limit);
				$('#offsetSubwo').val(data.offset);
				$('#totalSubwo').val(data.total);
				$('#subwo').html(data.data);
			},
			error: function(x) {
				//alert("Error: "+ "\r\n\r\n" + x.responseText);
			}
		});
	}
	
	function getOutstanding()
	{
		var limitOutstanding = $('#limitOutstanding').val();
		var offsetOutstanding = $('#offsetOutstanding').val();
		var totalOutstanding = $('#totalOutstanding').val();
		
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: url + '/monitoring/getOutstanding',
			data: { 
				"_token": "{{ csrf_token() }}",
				limit: limitOutstanding, 
				offset: offsetOutstanding, 
				total: totalOutstanding
			},
			success: function(data) 
			{
				//alert(data.limit);
				$('#limitOutstanding').val(data.limit);
				$('#offsetOutstanding').val(data.offset);
				$('#totalOutstanding').val(data.total);
				$('#outstanding-notification').html(data.data);
			},
			error: function(x) {
				//alert("Error: "+ "\r\n\r\n" + x.responseText);
			}
		});
	}
</script>

<!--<div class="row">
	<div class="col-lg-6 col-sm-6">
		<div class="panel panel-default">
			<div class="panel-heading"><i class="ti-calendar"></i> Preventative Maintenance Hari Ini</div>
		</div>
	</div>
</div>-->

<div class="row">
	<div class="col-lg-6 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading"> PREVENTIVE MAINTENANCE HARI INI</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								
								<input type="hidden" id="limitToday" value="6">
								<input type="hidden" id="offsetToday" value="0">
								<input type="hidden" id="totalToday" value="{{ $totalToday }}">
								
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>No WO</th>
											<th>Equipment</th>
											<th>Jenis Paket</th>
											<th>Status WO</th>
										</tr>
									</thead>
									<tbody id="today">
										@foreach ($dataToday as $row)
										<tr>
											<td>{{ $row['aufnr'] }}</td>
											<td>{{ $row['equnr'] }}</td>
											<td>{{ $row['ktext'] }}</td>
											<td>{{ $row['txt04'] }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-heading"> PREVENTIVE MAINTENANCE 2 HARI KEDEPAN</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<input type="hidden" id="limitTomorrow" value="6">
								<input type="hidden" id="offsetTomorrow" value="0">
								<input type="hidden" id="totalTomorrow" value="{{ $totalTomorrow }}">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>No WO</th>
											<th>Equipment</th>
											<th>Jenis Paket</th>
											<th>Basic Start</th>
										</tr>
									</thead>
									<tbody id="tomorrow">
										@foreach ($dataTomorrow as $row)
										<tr>
											<td>{{ $row['aufnr'] }}</td>
											<td>{{ $row['equnr'] }}</td>
											<td>{{ $row['ktext'] }}</td>
											<td>{{ $row['txt04'] }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
							<div class="panel-heading"> %MTD Achievement Preventive Maintenance</div>
							<!--<div id="graph_mts" style="max-width:100%; max-height:220px !important;"></div>-->
							<canvas id="canvas"></canvas>
						</div>
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
							<div class="panel-heading"> SUB WO</div>
							<div class="panel-body">
					
								<input type="hidden" id="limitSubwo" value="1">
								<input type="hidden" id="offsetSubwo" value="0">
								<input type="hidden" id="totalSubwo" value="{{ $totalSubwo }}">
								
								<table class="table table-bordered table-striped">
									<thead>
									</thead>
									<tbody id="subwo">
										<tr>
											<td>NO</td>
											<td><?php echo @$data_new_subwo[0]['ORDERS']; ?></td>
										</tr>
										<tr>
											<td>SUB</td>
											<td>
												<?php
													if( $data_new_subwo )
													{
														foreach( $data_new_subwo[0]['DATA'] as $k => $v )
														{
															//echo "<pre>"; print_r($v);
															echo " ".$v['sub_wo']." ";
														}
													}
												?>
											</td>
										</tr>
									</tbody>
								</table>
							
							</div>
							
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="col-lg-6 col-sm-12">
		<div class="panel panel-default">
			<!--<div id="dailymtstitle" class="panel-heading"><i class="ti-calendar"></i> PREVENTATIVE MAINTENANCE 2 HARI KEDEPAN</div>-->
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
							
								<input type="hidden" id="limitOutstanding" value="3">
								<input type="hidden" id="offsetOutstanding" value="0">
								<input type="hidden" id="totalOutstanding" value="{{ $totalOutstanding }}">
							
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Action</th>
											<th>Qty</th>
											<th>No Notification</th>
											<th>Equipment</th>
											<th>Description</th>
										</tr>
									</thead>
									<tbody id="outstanding-notification">
										<tr>
											<td style="vertical-align:middle" rowspan="3" align="center" width="150px">OUTSTANDING NOTIFICATION</td>
											<td style="vertical-align:middle" rowspan="3" align="center" width="50px">{{ $totalOutstanding }}</td>
											@foreach ($dataOutstanding as $row)
											<td>{{ $row['no_notification'] }}</td>
											<td>{{ $row['equipment'] }}</td>
											<td>{{ $row['description'] }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<!--<div id="dailymtstitle" class="panel-heading"><i class="ti-calendar"></i> PREVENTATIVE MAINTENANCE 2 HARI KEDEPAN</div>-->
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<input type="hidden" id="limitApproval" value="3"><input type="hidden" id="offsetApproval" value="0"><input type="hidden" id="totalApproval" value="{{ $totalApproval }}">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Action</th>
											<th>Qty</th>
											<th>No WO</th>
											<th>Equipment</th>
											<th>Next Approval</th>
										</tr>
									</thead>
									<tbody id="approval">
										<tr>
											<td rowspan="4" style="vertical-align : middle;text-align:center;" width="150px">WAITING APPROVAL</td>
											<td rowspan="4" style="vertical-align : middle;text-align:center;" width="50px">{{ $totalApproval }}</td>
										</tr>
										@foreach ($dataApproval as $row)
										<tr>
											<td>{{ $row['aufnr'] }}</td>
											<td>{{ $row['equnr'] }}</td>
											<td>{{ $row['nama'] }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<!--<div id="dailymtstitle" class="panel-heading"><i class="ti-calendar"></i> PREVENTATIVE MAINTENANCE 2 HARI KEDEPAN</div>-->
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<input type="hidden" id="limitMaterial" value="3"><input type="hidden" id="offsetMaterial" value="0"><input type="hidden" id="totalMaterial" value="{{ $totalMaterial }}">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Action</th>
											<th>Qty</th>
											<th>No WO</th>
											<th>Equipment</th>
											<th>Item Material</th>
										</tr>
									</thead>
									<tbody id="material">
										<tr>
											<td rowspan="4" style="vertical-align : middle;text-align:center;" width="150px">WAITING FORM MATERIAL</td>
											<td rowspan="4" style="vertical-align : middle;text-align:center;" width="50px">{{ $totalMaterial }}</td>
										</tr>
										@foreach ($dataMaterial as $row)
										<tr>
											<td>{{ $row['aufnr'] }}</td>
											<td>{{ $row['equnr'] }}</td>
											<td>{{ $row['ktext'] }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<!--<div id="dailymtstitle" class="panel-heading"><i class="ti-calendar"></i> PREVENTATIVE MAINTENANCE 2 HARI KEDEPAN</div>-->
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<input type="hidden" id="limitRelease" value="3"><input type="hidden" id="offsetRelease" value="0"><input type="hidden" id="totalRelease" value="{{ $totalRelease }}">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Action</th>
											<th>Qty</th>
											<th>No WO</th>
											<th>Equipment</th>
											<th>Operation</th>
										</tr>
									</thead>
									<tbody id="release">
										<tr>
											<td rowspan="4" style="vertical-align : middle;text-align:center;" width="150px">READY TO RELEASE</td>
											<td rowspan="4" style="vertical-align : middle;text-align:center;" width="50px">{{ $totalRelease }}</td>
										</tr>
										@foreach ($dataRelease as $row)
										<tr>
											<td>{{ $row['aufnr'] }}</td>
											<td>{{ $row['equnr'] }}</td>
											<td>{{ $row['ktext'] }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<!--<div id="dailymtstitle" class="panel-heading"><i class="ti-calendar"></i> PREVENTATIVE MAINTENANCE 2 HARI KEDEPAN</div>-->
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<input type="hidden" id="limitProgress" value="3"><input type="hidden" id="offsetProgress" value="0"><input type="hidden" id="totalProgress" value="{{ $totalProgress }}">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Action</th>
											<th>Qty</th>
											<th>No WO</th>
											<th>Equipment</th>
											<th>Operation</th>
										</tr>
									</thead>
									<tbody id="progress">
										<tr>
											<td rowspan="4" style="vertical-align : middle;text-align:center;" width="150px">WORK IN PROGRESS</td>
											<td rowspan="4" style="vertical-align : middle;text-align:center;" width="50px">{{ $totalProgress }}</td>
										</tr>
										@foreach ($dataProgress as $row)
										<tr>
											<td>{{ $row['aufnr'] }}</td>
											<td>{{ $row['equnr'] }}</td>
											<td>{{ $row['ktext'] }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="panel panel-default">
			<!--<div id="dailymtstitle" class="panel-heading"><i class="ti-calendar"></i> PREVENTATIVE MAINTENANCE 2 HARI KEDEPAN</div>-->
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<input type="hidden" id="limitTeco" value="3"><input type="hidden" id="offsetTeco" value="0"><input type="hidden" id="totalTeco" value="{{ $totalTeco }}">
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>Action</th>
											<th>Qty</th>
											<th>No WO</th>
											<th>Equipment</th>
											<th>Operation</th>
										</tr>
									</thead>
									<tbody id="teco">
										<tr>
											<td rowspan="4" style="vertical-align : middle;text-align:center;" width="150px">READY TO TECO</td>
											<td rowspan="4" style="vertical-align : middle;text-align:center;" width="50px">{{ $totalTeco }}</td>
										</tr>
										@foreach ($dataTeco as $row)
										<tr>
											<td>{{ $row['aufnr'] }}</td>
											<td>{{ $row['equnr'] }}</td>
											<td>{{ $row['ktext'] }}</td>
										</tr>
										@endforeach
									</tbody>
								</table>
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
	<div class="col-md-2 col-md-2 col-sm-12">
		<div class="white-box">
  			<div class="row row-in">
    				<div class="col-lg-12 col-sm-12">
      				<div class="col-in row" style="text-align:center;">
      					{!! Html::image('plugins/images/puninar.png', '', array('class'=>'thumb-lg', 'alt'=>'user-img', 'style'=>'width:100% !important; height:105px;')) !!}
      				</div>
    				</div>
  			</div>
		</div>
	</div>
	<div class="col-md-10 col-lg-10 col-sm-12">
		<div class="white-box">
  			<div class="row row-in">
  				<div class="col-lg-1 col-sm-1"></div>
    				<div class="col-lg-2 col-sm-2">
    					<div class="col-in row">
        					<div class="col-md-5 col-sm-5 col-xs-5"> 
        						<i data-icon="" class="linea-icon linea-basic"></i>
    							<h5 class="text-muted vb"></h5>
      					</div>
        					<div class="col-md-7 col-sm-7 col-xs-7">
          						<h3 class="counter text-right m-t-15 text-red"></h3>
        					</div>
        					<div class="col-md-12 col-sm-12 col-xs-12">
          						<div class="progress">
          							<div class="progress-bar progress-bar-red" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 40%"></div>
        						</div>
        					</div>
      				</div>
    				</div>
    				<div class="col-lg-1 col-sm-1"></div>
  			</div>
		</div>
	</div>
</div>


@stop
