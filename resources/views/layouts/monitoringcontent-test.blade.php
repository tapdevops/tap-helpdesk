@extends('layouts.monitoring-test')

@section('content')

<script type="text/javascript">

var timeoutSubwo = setInterval(getSubwo, 10000); //3000 = 3 sec
var timeoutstanding = setInterval(getOutstanding, 10000); //3000 = 3 sec

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
			
			<div class="col-lg-8 col-sm-8">
				<div class="panel-heading"><i class="ti-calendar"></i> % MTD Achievement Preventive Maintenance</div>
				<div class="panel-body"><canvas id="canvas"></canvas></div>
			</div>
			
			<div class="col-lg-4 col-sm-4">
				<div class="panel-heading">SUB WO</div>
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
	<div class="col-lg-6 col-sm-6">
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
											<th width="100px">Action</th>
											<th>Qty</th>
											<th>No Notification</th>
											<th>Equipment</th>
											<th>Description</th>
										</tr>
									</thead>
									<tbody id="outstanding-notification">
										<tr>
											<td width="100px" style="vertical-align:middle" rowspan="3" align="center">OUTSTANDING NOTIFICATION</td>
											<td style="vertical-align:middle" rowspan="3" align="center">{{ $totalOutstanding }}</td>
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
	</div>
</div>

@stop
