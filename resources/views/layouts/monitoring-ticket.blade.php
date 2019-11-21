@extends('layouts.layout-monitoring-ticket')

@section('content')

<script type="text/javascript">
	$(document).ready(function() {
		//$('#dailymtstitle, #dailymtrtitle').css('cursor', 'pointer');
	});
</script>

<div class="row">
	<div class="col-lg-12 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<center><span style="font-weight:bold;font-size:15px" class="">MEAN TIME TO RESPONSE ( PROSES TIKET DIBAWAH 1 JAM )</span></center>
								
								<table class="table table-responsive table-striped">
									<tr align="center">
										<th>NO</th>
										<th>STATUS</th>
										<TH>ID TICKETS</TH>
										<TH>REQUEST</TH>
										<TH>TITLE/ISSUE</TH>
										<TH>WATCHER</TH>
										<TH>ASSIGN</TH>
										<th>COUNT DOWN TIMER</TH>
									</tr>
									<?php 
										//echo "<pre>"; print_r($ticket_today); 
										if(!empty( $pdsj ))
										{
											$l = "";
											$no = 1;
											foreach( $pdsj as $k => $v )
											{
												//echo "<pre>"; print_r($v); 
												$l .= "<tr>";
												$l .= "<td>$no</td>";
												$l .= "<td>".strtoupper($v['status'])."</td>";
												$l .= "<td>{$v['id']}</td>";
												$l .= "<td>{$v['request_name']}<hr/>{$v['date']}</td>";
												$l .= "<td><u>{$v['name']}</u><br/><br/>{$v['content']}</td>";
												$l .= "<td>{$v['watcher']}</td>";
												$l .= "<td>{$v['assign']}<hr/>{$v['date_mod']}</td>";
												$l .= "<td align='center'>{$v['selisih_waktu']}</td>";
												$l .= "</tr>";
												$no++;
											}
											echo $l;
										}
										else
										{
											echo "<tr><td colspan='8' align='center' style='color:red'><i>Belum Ada Tiket</i></td></tr>";
										}
									?>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
							<div class="row">
								<center><span style="font-weight:bold;font-size:15px">ASSIGN TIKET PERHARI (<?php echo $total_ticket.' TOTAL TIKET '; ?>)</span></center>
								<table class="table table-responsive table-striped">
									<tr>
										<th>NO</th>
										<TH>ASSIGNED</TH>
										<TH>JUMLAH TIKET ASSIGN</TH>
										<TH>JUMLAH TIKET SOLVED</TH>
										<TH>OUTSTANDING TICKET</TH>
									</tr>
									<?php 
										//echo "<pre>"; print_r($atp); 
										if( !empty($atp) )
										{
											$l = '';
											$no = 1;
											foreach( $atp as $k => $v )
											{
												//echo "<pre>"; print_r($v);
												/*
													[users_id] => 1764
													[assign] => helpdesk.3
													[jumlah_tiket_assign] => 1
													[jumlah_tiket_solved] => 1
													[outstanding_tiket] => 0
												*/
												$l .= '
													<tr>
														<td>'.$no.'</td>
														<td>'.$v['assign'].'</td>
														<td>'.$v['jumlah_tiket_assign'].'</td>
														<td>'.$v['jumlah_tiket_solved'].'</td>
														<td>'.$v['outstanding_tiket'].'</td>
													</tr>
												';
												
												$no++;
											}
											echo $l; 
										}
									?>
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