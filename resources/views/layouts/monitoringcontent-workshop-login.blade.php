@extends('layouts.monitoring-one-login')

@section('content')

<?php 
	$lw = explode(',',$plan);
	$last_wo = end($lw);
	
	$lt = explode(',',$actual);
	$last_teco = end($lt);
	
	$persentase = ($last_teco / $last_wo) * 100;
	$persentase = number_format( $persentase, 2 );
	
	$list_stat = array(
		'E0001' => array('INIT', 'Initial'),
		'E0002' => array('PLAN', 'Planning Complete'),
		'E0003' => array('APVP', 'Approved'),
		'E0004' => array('WAIM', 'Waiting Material'),
		'E0005' => array('WAIR', 'Waiting Resource'),
		'E0006' => array('WAIT', 'Waiting Tools'),
		'E0007' => array('SCHD', 'Scheduled'),
		'E0008' => array('RJCT', 'Rejected')
	);
	
	$month = array(
		'01'=>'JAN',
		'02'=>'FEB',
		'03'=>'MAR',
		'04'=>'APR',
		'05'=>'MEI',
		'06'=>'JUN',
		'07'=>'JUL',
		'08'=>'AGU',
		'09'=>'SEP',
		'10'=>'OKT',
		'11'=>'NOV',
		'12'=>'DES'
	);
?>

<style>
.panel .panel-body-corrective {
  /*padding: 25px;*/
  padding: 15px 25px;
  height:947px;
  overflow:hidden;
}

.panel-heading a {text-decoration:none;}
</style>

<script type="text/javascript">

var timeoutToday = setInterval(getToday, 15000);
var timeoutTomorrow = setInterval(getTomorrow, 15000);
var timeoutCorrective = setInterval(getCorrective, 15000);
//var timeoutSubwo = setInterval(getSubwo, 10000); //3000 = 3 sec

function getToday() {
	var limitToday = $('#limitToday').val();
	var offsetToday = $('#offsetToday').val();
	var totalToday = $('#totalToday').val();

	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: url + '/monitoring/getTodayWorkshop/'+<?php echo $compid; ?>+'',
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
		url: url + '/monitoring/getTomorrowWorkshop/'+<?php echo $compid; ?>+'',
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

function getCorrective() 
{
	var limitCorrective = $('#limitCorrective').val();
	var offsetCorrective = $('#offsetCorrective').val();
	var totalCorrective = $('#totalCorrective').val();

	$.ajax({
		type: 'POST',
		dataType: 'json',
		url: url + '/monitoring/getCorrectiveWorkshop/'+<?php echo $compid; ?>+'',
		data: { 
			"_token": "{{ csrf_token() }}",
			limit: limitCorrective, 
			offset: offsetCorrective,
			total: totalCorrective
		},
		success: function(data) {
			$('#limitCorrective').val(data.limit);
			$('#offsetCorrective').val(data.offset);
			$('#totalCorrective').val(data.total);
			$('#corrective-maintenance').html(data.data);
		}
	});
}

function getSubwo() 
{
	var limitSubwo = $('#limitSubwo').val();
	var offsetSubwo = $('#offsetSubwo').val();
	var totalSubwo = $('#totalSubwo').val();
	
	//alert(limitSubwo+"/"+offsetSubwo+"/"+totalSubwo); 

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
			//alert(data.limit);
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

</script>

<div class="row">
	<div class="col-lg-6 col-sm-6">
		<div class="panel panel-default">
		
			<div class="panel-heading"> PREVENTIVE MAINTENANCE HARI INI 
				<a href="<?php echo url("monitoring-workshop-download-today/$compid"); ?>">
					<i class="fa fa-download pull-right" style="margin-top:5px;margin-right:15px;font-size:20px; text-decoration:none"></i>
				</a>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								
								<input type="hidden" id="limitToday" value="4">
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
										<?php 
											if(!empty( $dataTomorrow ))
											{ 
										?>
										@foreach ($dataToday as $row)
										<tr>
											<td>{{ $row['aufnr'] }}</td>
											<td>{{ $row['equnr'] }}</td>
											<td>{{ $row['ktext'] }}</td>
											<td>{{ $row['txt04'] }}</td>
										</tr>
										@endforeach
										<?php 
											} 
											else
											{
												echo '<tr><td colspan="5" align="center"><span style="color:red"><i>Belum ada data</i></span></td></tr>';
											}  
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading">PREVENTIVE MAINTENANCE 
				<span style="color:#2979FF">2 HARI KEDEPAN</span> 
				<a href="<?php echo url("monitoring-workshop-download-tomorrow/$compid"); ?>">
					<i class="fa fa-download pull-right" style="margin-top:5px;margin-right:15px;font-size:20px; text-decoration:none"></i>
				</a>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<input type="hidden" id="limitTomorrow" value="4">
								<input type="hidden" id="offsetTomorrow" value="0">
								<input type="hidden" id="totalTomorrow" value="{{ $totalTomorrow }}">
								<?php //echo "<pre>"; print_r($dataTomorrow); die(); ?>
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
										<?php 
											if(!empty( $dataTomorrow ))
											{ 
										?>
												@foreach ($dataTomorrow as $row)
												
												<?php 
													$tgl = substr($row['gstrp'],6);
													$bln = @$month[substr($row['gstrp'],4,2)];
													$year = substr($row['gstrp'],0,4);
													$udate = $tgl." ".$bln." ".$year;
												?>
												
												<tr>
													<td>{{ $row['aufnr'] }}</td>
													<td>{{ $row['equnr'] }}</td>
													<td>{{ $row['ktext'] }}</td>
													<td nowrap="nowrap">{{ $udate }}</td>
												</tr>
												@endforeach
										<?php 
											} 
											else
											{
												echo '<tr><td colspan="5" align="center"><span style="color:red"><i>Belum ada data</i></span></td></tr>';
											}  
										?>
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
				<div class="xpanel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<div class="col-lg-2 col-sm-2" align="center">
									<div align="center" style="margin-top:60%;margin-left:25px;font-weight:bold;font-size:20px;color:#00BFA5">
										<?php echo strtoupper(date("F")).' '.date("Y"); ?>
									</div>
								</div>
								<div class="col-lg-8 col-sm-8" align="center" >
									<div class="chart-container" style="position: relative; width:30vw; xborder:1px solid red">
										<canvas id="canvas"></canvas>
									</div>
								</div>
								<div class="col-lg-2 col-sm-2" align="center" >
									<div style="margin-top:90px;font-weight:bold;color:#F50057"><?php echo $last_wo; ?> (WO)</div>
									<div style="font-weight:bold;color:#2979FF"><?php echo $last_teco; ?> (TECO)</div>
									<div style="font-weight:bold;color:#00C853;margin-top:10px;font-size:11px">ACHIEVEMENT</div>
									<div style="font-weight:bold;color:#00C853;font-size:18px"><?php echo $persentase; ?>%</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	
	<div class="col-lg-6 col-sm-6">
	
		<div class="panel panel-default">
		
			<div class="panel-heading"> CORRECTIVE MAINTENANCE 
				<a href="<?php echo url("monitoring-workshop-download-corrective/$compid"); ?>">
					<i class="fa fa-download pull-right" style="margin-top:5px;margin-right:15px;font-size:20px; text-decoration:none"></i>
				</a>
			</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body-corrective">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								
								<input type="hidden" id="limitCorrective" value="22">
								<input type="hidden" id="offsetCorrective" value="0">
								<input type="hidden" id="totalCorrective" value="{{ $totalCorrective }}">
								
								<table class="table table-bordered table-striped">
									<thead>
										<tr>
											<th>EQUIPMENT</th>
											<th>WORK ORDER</th>
											<th>STATUS WO</th>
											<th>DESCRIPTION</th>
											<th>PER TANGGAL</th>
										</tr>
									</thead>
									<tbody id="corrective-maintenance">
									
										<?php 
								
										if(!empty($dataCorrective))
										{
											$status_wo = '';
											$ket_wo = '';
											
											foreach($dataCorrective as $row)
											{
												//echo "<pre>"; print_r($row); die();
												//echo "<pre>"; print_r($list_stat[''.$row['stat'].'']); die();
												
												$tgl = substr($row['udate'],6);
												$bln = @$month[substr($row['udate'],4,2)];
												$year = substr($row['udate'],0,4);
												$udate = $tgl." ".$bln." ".$year;
												$stat = @$list_stat[''.$row['stat'].''];
												
												if( !empty($stat) )
												{
													$status_wo = $list_stat[$row['stat']][0];
													$ket_wo = $list_stat[$row['stat']][1];
												}
												else
												{
													$status_wo = $row['txt04'];
													$ket_wo = $row['txt30'];
												}
												
												//echo "<pre>"; print_r($status_wo); //die();
												
												echo '
													<tr>
														<td>'.@$row['equnr'].'</td>
														<td>'.@$row['aufnr'].'</td>
														<td>'.@$status_wo.'</td>
														<td>'.@$ket_wo.'</td>
														<td>'.@$udate.'</td>
													</tr>
												';
											}
										}
										else
										{
											echo '<tr><td colspan="5" align="center"><span style="color:red"><i>Belum ada data</i></span></td></tr>';
										}
												
										?>
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

@stop
