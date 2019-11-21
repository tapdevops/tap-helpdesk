@extends('layouts.layout-monitoring-ticket')

@section('content')

<script type="text/javascript">
	$(document).ready(function() {
		//$('#dailymtstitle, #dailymtrtitle').css('cursor', 'pointer');
	});
</script>

<style>
body{
font-size:10px;
}

.id-tiket{
	color:#00C853;
}
</style>

<div class="row">
	<div class="col-lg-12 col-sm-12">
		<div class="panel panel-default">
			<div class="panel-wrapper collapse in">
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
							<div class="row">
								<center><span style="font-weight:bold;font-size:15px" class="">MEAN TIME TO RESPONSE - <?php echo count(@$pdsj); ?> TIKET</span></center>
								
								<table class="table table-responsive table-striped table-condensed table-bordered">
									<tr align="center">
										<!--th>STATUS</th-->
										<TH>TICKET</TH>
										<TH>REQUEST</TH>
										<!--TH>TITLE/ISSUE</TH-->
										<!--TH>WATCHER</TH-->
										<TH>ASSIGN / WATCHER</TH>
										<th>COUNT DOWN TIMER</TH>
									</tr>
									<?php 
										//echo "<pre>"; print_r($ticket_today); 
										if(!empty( $pdsj ))
										{
											$l = "";
											$no = 1;
											$status = "";
											foreach( $pdsj as $k => $v )
											{
												if( $v['status'] == 'ASSIGN' ){ $status .= '<span style="text-decoration: blink">'.strtoupper($v['status']).'</span>'; }
												
												//echo "<pre>"; print_r($v); 
												$l .= "<tr>";
												//$l .= "<td class='warning'></td>";
												$l .= "<td><span class='id-tiket'>{$v['id']}</span> <br/><span style='text-decoration: blink'>".strtoupper($v['status'])."</span></td>";
												$l .= "<td>{$v['request_name']} <br/> <a href='http://helpdesk.tap-agri.com/front/ticket.form.php?id={$v['id']}' target='_blank'><u>{$v['name']}</u></a></td>";
												//$l .= "<td><u>{$v['name']}</u><br/><br/>{$v['content']}</td>";
												//$l .= "<td>{$v['watcher']}</td>";
												$l .= "<td>{$v['assign']} watcher: <br/> {$v['watcher']}</td>";
												$l .= "<td align='center'>{$v['selisih_waktu']} Minute</td>";
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
								<br/>
								
								<center><span style="font-weight:bold;font-size:15px" class="">OUTSTANDING TICKET (TODAY) - <?php echo count(@$ott); ?> TIKET</span></center>
								
								<table class="table table-responsive table-striped table-condensed table-bordered">
									<tr align="center">
										<TH>TICKET</TH>
										<TH>REQUEST</TH>
										<TH>ASSIGN</TH>
									</tr>
									<?php 
										//echo "<pre>"; print_r($ticket_today); 
										if(!empty( $ott ))
										{
											$l = "";
											$no = 1;
											$status = "";
											foreach( $ott as $k => $v )
											{
												if( $v['status'] == 'ASSIGN' ){ $status .= '<span style="text-decoration: blink">'.strtoupper($v['status']).'</span>'; }
												
												$l .= "<tr>";
												$l .= "<td><span class='id-tiket'>{$v['id']}</span> <br/><span style='text-decoration: blink'>".strtoupper($v['status'])."</span></td>";
												$l .= "<td>{$v['request_name']} <br/> <a href='http://helpdesk.tap-agri.com/front/ticket.form.php?id={$v['id']}' target='_blank'><u>{$v['name']}</u></a></td>";
												$l .= "<td>{$v['assign']}</td>";
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
						<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" >
							<div class="row" style="xbackground-color:red">
								<center><span style="font-weight:bold;font-size:15px" class="">MEAN TIME TO RESOLVE - <?php echo count(@$dibawah_satu_jam); ?> TIKET</span></center>
								<table class="table table-responsive table-striped table-bordered small">
									<tr align="center">
										<!--th>STATUS</th-->
										<TH>TICKET</TH>
										<TH>REQUEST</TH>
										<TH>ASSIGN</TH>
									</tr>
									<?php 
										if(!empty( $dibawah_satu_jam ))
										{
											$l = "";
											$no = 1;
											foreach( $dibawah_satu_jam as $k => $v )
											{
												//echo "<pre>"; print_r($v); 
												$l .= "<tr class='success'>";
												//$l .= "<td>".strtoupper($v['status'])."</td>";
												$l .= "<td><span class='id-tiket'>{$v['id']}</span> </td>";
												$l .= "<td>{$v['request_name']} <br/> <a href='http://helpdesk.tap-agri.com/front/ticket.form.php?id={$v['id']}' target='_blank'><u>{$v['name']}</u></a></td>";
												$l .= "<td>{$v['assign']}</td>";
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
								</table><br/>
								<center>
								<H2>TOTAL TIKET HARI INI</h2>
								<h1 style="color:#DD2C00;font-weight:bold"><?php echo $total_ticket; ?></h1>
								</center>
							</div>
						</div>
						<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="max-height: 600px;overflow-y: scroll">
							<div class="row" style="overflow:auto">
								<center><span style="font-weight:bold;font-size:15px" class="">OUTSTANDING TICKET - <?php echo count(@$lebih_satu_hari); ?> TIKET</span></center>
								<table class="table table-responsive table-striped table-bordered" >
									<tr align="center">
										<!--th>STATUS</th-->
										<TH>TICKET</TH>
										<TH>REQUEST</TH>
										<!--TH>ASSIGN</TH-->
										<th>TIME</TH>
									</tr>
									<?php 
										//echo "<pre>"; print_r($lebih_satu_hari); die();
										if(!empty( $lebih_satu_hari ))
										{
											$l = "";
											$no = 1;
											foreach( $lebih_satu_hari as $k => $v )
											{
												//echo "<pre>"; print_r($v); 
												$l .= "<tr>";
												//$l .= "<td>$no</td>";
												//$l .= "<td>".strtoupper($v['status'])."</td>";
												$l .= "<td><span class='id-tiket'>{$v['id']}</span> </td>";
												$l .= "<td>{$v['request_name']} <br/> <a href='http://helpdesk.tap-agri.com/front/ticket.form.php?id={$v['id']}' target='_blank'><u>{$v['name']}</u></a> <hr/>{$v['date']}</td>";
												//$l .= "<td><u>{$v['name']}</u><br/><br/>{$v['content']}</td>";
												//$l .= "<td>{$v['watcher']}</td>";
												//$l .= "<td>{$v['assign']}<hr/>{$v['date_mod']}</td>";
												$l .= "<td align='center'>{$v['selisih_waktu']} Day</td>";
												$l .= "</tr>";
												$no++;
											}
											echo $l;
										}
										else
										{
											echo "<tr><td colspan='3' align='center' style='color:red'><i>Belum Ada Tiket</i></td></tr>";
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
								<table class="table table-responsive table-striped" >
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