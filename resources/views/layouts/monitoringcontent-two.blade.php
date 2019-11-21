@extends('layouts.monitoring-two')

@section('content')

<script type="text/javascript">

//var timeoutToday = setInterval(getToday, 5000);
//var timeoutTomorrow = setInterval(getTomorrow, 3000);
//var timeoutSubwo = setInterval(getSubwo, 10000); //3000 = 3 sec

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
		
			<div class="panel-heading"> COL 1</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								
								
							</div>
						</div>
					</div>
				</div>
			</div>
		
		</div>
		
	</div>
	<div class="col-lg-6 col-sm-6">
	
		<div class="panel panel-default">
			<div class="panel-heading"> COL 2</div>
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>
	
</div>

@stop
