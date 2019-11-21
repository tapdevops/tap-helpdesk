<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use App\LDAP;
use Redirect;
use URL, DateTime;
use App\Tickets;
use AccessRight;

class MonitoringController extends Controller {

	public function __construct() {
		date_default_timezone_set('Asia/Jakarta');
		$this->gt = 'glpi_tickets';
		$this->gtu = 'glpi_tickets_users';
		$this->gu = 'glpi_users';
	}

	public function index() 
	{
		$url = $_SERVER['REQUEST_URI'];
		header("Refresh: 300; URL=\"" . $url . "\"");

		$title = 'DASHBOARD WORKSHOP - GBSM';
		$date = date('d F Y');
		$year = date('Y');
		$day = '';

		// TODAY
		$countToday = "SELECT count(*) as TOTAL FROM TREPORT_A1";
		$sqlToday = "
			SELECT AUFNR, EQUNR, KTEXT, TXT04, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, TXT04, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, TXT04 FROM TREPORT_A1 WHERE TXT04 = 'CRTD' OR TXT04 = 'REL' ORDER BY AUFNR
				) WHERE rownum <= 6
			) WHERE  rnum > 0
		";
		$totalToday = DB::connection('workshop')->select($countToday);
		$totalToday = $totalToday[0]['total'];
		$dataToday = DB::connection('workshop')->select($sqlToday);

		// TOMORROW
		$countTomorrow = "SELECT count(*) as TOTAL FROM TREPORT_A2";
		$sqlTomorrow = "
			SELECT AUFNR, EQUNR, KTEXT, TXT04, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, TXT04, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, TXT04 FROM TREPORT_A2 WHERE TXT04 = 'CRTD' OR TXT04 = 'REL' ORDER BY AUFNR
				) WHERE rownum <= 6
			) WHERE  rnum > 0
		";

		$totalTomorrow = DB::connection('workshop')->select($countTomorrow);
		$totalTomorrow = $totalTomorrow[0]['total'];
		$dataTomorrow = DB::connection('workshop')->select($sqlTomorrow);

		// APPROVAL
		$countApproval = "SELECT COUNT(*) AS TOTAL FROM REPORT_WAIT_APPROVAL";
		$sqlApproval = "
			SELECT AUFNR, EQUNR, NAMA, rnum FROM (
				SELECT AUFNR, EQUNR, NAMA, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, NAMA FROM REPORT_WAIT_APPROVAL ORDER BY AUFNR
				) WHERE rownum <= 3
			) WHERE  rnum > 0
		";

		$totalApproval = DB::connection('workshop')->select($countApproval);
		$totalApproval = $totalApproval[0]['total'];
		$dataApproval = DB::connection('workshop')->select($sqlApproval);

		// MATERIAL
		$countMaterial = "SELECT COUNT(*) AS TOTAL FROM REPORT_WAIT_FOR_MATERIAL";
		$sqlMaterial = "
			SELECT AUFNR, EQUNR, KTEXT, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT FROM REPORT_WAIT_FOR_MATERIAL ORDER BY AUFNR
				) WHERE rownum <= 3
			) WHERE  rnum > 0
		";

		$totalMaterial = DB::connection('workshop')->select($countMaterial);
		$totalMaterial = $totalMaterial[0]['total'];
		$dataMaterial = DB::connection('workshop')->select($sqlMaterial);

		// RELEASE
		$countRelease = "SELECT COUNT(*) AS TOTAL FROM REPORT_READY_TO_RELEASE";
		$sqlRelease = "
			SELECT AUFNR, EQUNR, KTEXT, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT FROM REPORT_READY_TO_RELEASE ORDER BY AUFNR
				) WHERE rownum <= 3
			) WHERE  rnum > 0
		";

		$totalRelease = DB::connection('workshop')->select($countRelease);
		$totalRelease = $totalRelease[0]['total'];
		$dataRelease = DB::connection('workshop')->select($sqlRelease);

		// PROGRESS
		$totalProgress = "SELECT COUNT(*) AS TOTAL FROM REPORT_WORK_IN_PROGRESS";
		$sqlProgress = "
			SELECT AUFNR, EQUNR, KTEXT, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT FROM REPORT_WORK_IN_PROGRESS ORDER BY AUFNR
				) WHERE rownum <= 3
			) WHERE  rnum > 0
		";

		$totalProgress = DB::connection('workshop')->select($totalProgress);
		$totalProgress = $totalProgress[0]['total'];
		$dataProgress = DB::connection('workshop')->select($sqlProgress);

		// REPORT READY TO TECO
		$totalTeco = "SELECT COUNT(*) AS TOTAL FROM REPORT_READY_TO_TECO";
		$sqlTeco = "
			SELECT AUFNR, EQUNR, KTEXT, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT FROM REPORT_READY_TO_TECO ORDER BY AUFNR
				) WHERE rownum <= 3
			) WHERE  rnum > 0
		";

		$totalTeco = DB::connection('workshop')->select($totalTeco);
		$totalTeco = $totalTeco[0]['total'];
		$dataTeco = DB::connection('workshop')->select($sqlTeco);
		
		// REPORT CHART
		$date = "";
		$day = '';
		$date_today = date('d');
		for($i=1;$i<=$date_today;$i++)
		{
			$day .= $i.',';
		}
		
		$sql_chart = " SELECT GSTRP,JML_ORDER,TXT04,RNUM FROM 
			(
				SELECT GSTRP,JML_ORDER,TXT04, ROWNUM AS RNUM FROM
				(
					SELECT * FROM REPORT_CHART
				) WHERE ROWNUM <= 10000
			) WHERE RNUM > 0 ";
		
		$dataChart = DB::connection('workshop')->select($sql_chart);
		$plan = array();
		$actual = array();

		if(!empty($dataChart))
		{
			foreach($dataChart as $k => $v)
			{				
				$tanggal = substr($v['gstrp'],6);
				
				if( $v['txt04'] == 'CRTD' )
				{
					$plan[] = array
					(
						'DATE' => $tanggal,
						'JML_ORDER'=>$v['jml_order']
					);
				}
				
				if( $v['txt04'] == 'TECO' )
				{
					$actual[] = array
					(
						'DATE' => $tanggal,
						'JML_ORDER' => $v['jml_order']
					);
				}
			}
		}
		$listday = rtrim($day,',');
		$plan = $this->get_plan_chart($listday, $plan);
		$actual = $this->get_actual_chart($listday, $actual);
		
		// REPORT C (OUTSTANDING NOTIFICATION)
		$totalOutstanding = " SELECT ACTION, QTY, RNUM FROM (    
						SELECT ACTION, QTY, rownum AS RNUM FROM(
							SELECT TXT30 AS ACTION, COUNT(TXT30) AS QTY 
								FROM REPORT_C 
							GROUP BY TXT30 ORDER BY TXT30
						) WHERE rownum <= 3 
					) WHERE RNUM > 0 ";
		$sqlOn = " SELECT NO_NOTIFICATION, EQUIPMENT, DESCRIPTION, rownum AS RNUM FROM(             
			SELECT NO_NOTIFICATION, EQUIPMENT, DESCRIPTION, rownum AS RNUM FROM(        
				SELECT QMNUM AS NO_NOTIFICATION, EQUNR AS EQUIPMENT, QMTXT AS DESCRIPTION
				FROM REPORT_C WHERE TXT30 = 'Outstanding notification' ORDER BY TXT30
			) WHERE rownum <= 3
		) WHERE RNUM > 0 ";
		$totalOutstanding = DB::connection('workshop')->select($totalOutstanding);
		$totalOutstanding = @$totalOutstanding[2]['qty'];
		$dataOutstanding = DB::connection('workshop')->select($sqlOn); 
		
		// REPORT E (SUB WO)
		$countSubwo = "SELECT count(*) as TOTAL FROM REPORT_E";
		$sql_subwo = "
			SELECT ORDERS, ROWNUM AS RNUM FROM
			(
				SELECT ORDERS, ROWNUM AS RNUM FROM
				(
					SELECT MAUFNR AS ORDERS FROM REPORT_E GROUP BY MAUFNR
				)  WHERE ROWNUM <= 1
			) WHERE RNUM > 0
		";
		$totalSubwo = DB::connection('workshop')->select($countSubwo);
		//echo "<pre>"; print_r($totalSubwo); die();
		$totalSubwo = @$totalSubwo[0]['total'];
		$data_subwo = DB::connection('workshop')->select($sql_subwo);
		$data_new_subwo = array();
		
		if($data_subwo)
		{
			foreach( $data_subwo as $k => $v )
			{
				$data_new_subwo[] = array(
					'NO' => $v['rnum'],
					'ORDERS'=> $v['orders'],
					'DATA' => $this->get_detail_subwo($v)
				); 
			}
		}

		return view('layouts.monitoringcontent', compact('dataOutstanding','totalOutstanding','totalSubwo','data_new_subwo','actual','plan','listday','title', 'date', 'totalToday', 'dataToday', 'totalTomorrow', 'dataTomorrow', 'totalApproval', 'dataApproval', 'totalMaterial', 'dataMaterial', 'totalRelease', 'dataRelease', 'totalProgress', 'dataProgress', 'totalTeco', 'dataTeco'));
	}

	public function getToday(Request $request) {
		$req = $request->all();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 4;
		$nextOffset = $req['offset'] + 4;

		$output = array();

		$count = "SELECT count(*) as TOTAL FROM TREPORT_A1 WHERE TXT04 = 'CRTD' OR TXT04 = 'REL'";

		$total = DB::connection('workshop')->select($count);
		$total = $total[0]['total'];

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 4;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 4;
			$nextOffset = 0;
		}

		$sql = "
			SELECT AUFNR, EQUNR, KTEXT, TXT04, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, TXT04, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, TXT04 FROM TREPORT_A1 WHERE TXT04 = 'CRTD' OR TXT04 = 'REL' ORDER BY AUFNR
				) WHERE rownum <= {$nextLimit}
			) WHERE  rnum > {$nextOffset}
		";

		//$sql = "SELECT * FROM auth_modules LIMIT {$nextLimit} OFFSET {$nextOffset}";

		$data = DB::connection('workshop')->select($sql);

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '';
		$i = $nextOffset + 1;
		if (isset($data) && !empty($data)) {
			foreach ($data as $row) {
				$tabel .= '
					<tr>
						<td>'.$row['aufnr'].'</td>
						<td>'.$row['equnr'].'</td>
						<td>'.$row['ktext'].'</td>
						<td>'.$row['txt04'].'</td>
					</tr>
				';
				$i++;
			}
		}
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}

	public function getTomorrow(Request $request) 
	{
		$req = $request->all();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 4;
		$nextOffset = $req['offset'] + 4;

		$output = array();

		$count = "SELECT count(*) as TOTAL FROM TREPORT_A2 WHERE TXT04 = 'CRTD' OR TXT04 = 'REL'";

		$total = DB::connection('workshop')->select($count);
		$total = $total[0]['total'];

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 4;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 4;
			$nextOffset = 0;
		}

		$sql = "
			SELECT AUFNR, EQUNR, KTEXT, GSTRP, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, GSTRP, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, GSTRP FROM TREPORT_A2 WHERE TXT04 = 'CRTD' OR TXT04 = 'REL' ORDER BY AUFNR
				) WHERE rownum <= {$nextLimit}
			) WHERE  rnum > {$nextOffset}
		";
		//$sql = "SELECT * FROM mail_queues LIMIT {$nextLimit} OFFSET {$nextOffset}";

		$data = DB::connection('workshop')->select($sql);

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '';
		$i = $nextOffset + 1;
		if (isset($data) && !empty($data)) 
		{
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
			
			foreach ($data as $row) 
			{
				$tgl = substr($row['gstrp'],6);
				$bln = @$month[substr($row['gstrp'],4,2)];
				$year = substr($row['gstrp'],0,4);
				$udate = $tgl." ".$bln." ".$year;
				
				$tabel .= '
					<tr>
						<td>'.@$row['aufnr'].'</td>
						<td>'.@$row['equnr'].'</td>
						<td>'.@$row['ktext'].'</td>
						<td nowrap="nowrap">'.@$udate.'</td>
					</tr>
				';
				$i++;
			}
		}
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}

	public function getApproval(Request $request) {
		$req = $request->all();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 3;
		$nextOffset = $req['offset'] + 3;

		$output = array();

		$count = "SELECT COUNT(*) AS TOTAL FROM REPORT_WAIT_APPROVAL";

		$total = DB::connection('workshop')->select($count);
		$total = $total[0]['total'];

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 3;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 3;
			$nextOffset = 0;
		}

		$sql = "
			SELECT AUFNR, EQUNR, NAMA, rnum FROM (
				SELECT AUFNR, EQUNR, NAMA, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, NAMA FROM REPORT_WAIT_APPROVAL ORDER BY AUFNR
				) WHERE rownum <= {$nextLimit}
			) WHERE  rnum > {$nextOffset}
		";

		//$sql = "SELECT * FROM auth_modules LIMIT {$nextLimit} OFFSET {$nextOffset}";

		$data = DB::connection('workshop')->select($sql);

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '';
		$i = $nextOffset + 1;
		if (isset($data) && !empty($data)) {
			$tabel .= '
				<tr>
					<td rowspan="4" style="vertical-align : middle;text-align:center;">Waiting Approval</td>
					<td rowspan="4" style="vertical-align : middle;text-align:center;">'.$$total.'</td>
				</tr>
			';
			foreach ($data as $row) {
				$tabel .= '
					<tr>
						<td>'.$row['aufnr'].'</td>
						<td>'.$row['equnr'].'</td>
						<td>'.$row['nama'].'</td>
					</tr>
				';
				$i++;
			}
		}
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}

	public function getMaterial(Request $request) {
		$req = $request->all();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 3;
		$nextOffset = $req['offset'] + 3;

		$output = array();

		$count = "SELECT COUNT(*) AS TOTAL FROM REPORT_WAIT_FOR_MATERIAL";

		$total = DB::connection('workshop')->select($count);
		$total = $total[0]['total'];

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 3;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 3;
			$nextOffset = 0;
		}

		$sql = "
			SELECT AUFNR, EQUNR, KTEXT, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT FROM REPORT_WAIT_FOR_MATERIAL ORDER BY AUFNR
				) WHERE rownum <= {$nextLimit}
			) WHERE  rnum > {$nextOffset}
		";

		$data = DB::connection('workshop')->select($sql);

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '';
		$i = $nextOffset + 1;
		if (isset($data) && !empty($data)) {
			$tabel .= '
				<tr>
					<td rowspan="4" style="vertical-align : middle;text-align:center;">Waiting for Material</td>
					<td rowspan="4" style="vertical-align : middle;text-align:center;">'.$$total.'</td>
				</tr>
			';
			foreach ($data as $row) {
				$tabel .= '
					<tr>
						<td>'.$row['aufnr'].'</td>
						<td>'.$row['equnr'].'</td>
						<td>'.$row['ktext'].'</td>
					</tr>
				';
				$i++;
			}
		}
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}

	public function getRelease(Request $request) {
		$req = $request->all();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 3;
		$nextOffset = $req['offset'] + 3;

		$output = array();

		$count = "SELECT COUNT(*) AS TOTAL FROM REPORT_READY_TO_RELEASE";

		$total = DB::connection('workshop')->select($count);
		$total = $total[0]['total'];

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 3;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 3;
			$nextOffset = 0;
		}

		$sql = "
			SELECT AUFNR, EQUNR, KTEXT, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT FROM REPORT_READY_TO_RELEASE ORDER BY AUFNR
				) WHERE rownum <= {$nextLimit}
			) WHERE  rnum > {$nextOffset}
		";

		$data = DB::connection('workshop')->select($sql);

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '';
		$i = $nextOffset + 1;
		if (isset($data) && !empty($data)) {
			$tabel .= '
				<tr>
					<td rowspan="4" style="vertical-align : middle;text-align:center;">Ready to Release</td>
					<td rowspan="4" style="vertical-align : middle;text-align:center;">'.$$total.'</td>
				</tr>
			';
			foreach ($data as $row) {
				$tabel .= '
					<tr>
						<td>'.$row['aufnr'].'</td>
						<td>'.$row['equnr'].'</td>
						<td>'.$row['ktext'].'</td>
					</tr>
				';
				$i++;
			}
		}
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}

	public function getProgress(Request $request) {
		$req = $request->all();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 3;
		$nextOffset = $req['offset'] + 3;

		$output = array();

		$count = "SELECT COUNT(*) AS TOTAL FROM REPORT_WORK_IN_PROGRESS";

		$total = DB::connection('workshop')->select($count);
		$total = $total[0]['total'];

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 3;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 3;
			$nextOffset = 0;
		}

		$sql = "
			SELECT AUFNR, EQUNR, KTEXT, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT FROM REPORT_WORK_IN_PROGRESS ORDER BY AUFNR
				) WHERE rownum <= {$nextLimit}
			) WHERE  rnum > {$nextOffset}
		";

		$data = DB::connection('workshop')->select($sql);

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '';
		$i = $nextOffset + 1;
		if (isset($data) && !empty($data)) {
			$tabel .= '
				<tr>
					<td rowspan="4" style="vertical-align : middle;text-align:center;">Work in Progress</td>
					<td rowspan="4" style="vertical-align : middle;text-align:center;">'.$$total.'</td>
				</tr>
			';
			foreach ($data as $row) {
				$tabel .= '
					<tr>
						<td>'.$row['aufnr'].'</td>
						<td>'.$row['equnr'].'</td>
						<td>'.$row['ktext'].'</td>
					</tr>
				';
				$i++;
			}
		}
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}

	public function getTeco(Request $request) {
		$req = $request->all();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 3;
		$nextOffset = $req['offset'] + 3;

		$output = array();

		$count = "SELECT COUNT(*) AS TOTAL FROM REPORT_READY_TO_TECO";

		$total = DB::connection('workshop')->select($count);
		$total = $total[0]['total'];

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 3;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 3;
			$nextOffset = 0;
		}

		$sql = "
			SELECT AUFNR, EQUNR, KTEXT, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT FROM REPORT_READY_TO_TECO ORDER BY AUFNR
				) WHERE rownum <= {$nextLimit}
			) WHERE  rnum > {$nextOffset}
		";

		$data = DB::connection('workshop')->select($sql);

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '';
		$i = $nextOffset + 1;
		if (isset($data) && !empty($data)) {
			$tabel .= '
				<tr>
					<td rowspan="4" style="vertical-align : middle;text-align:center;">Ready to TECO</td>
					<td rowspan="4" style="vertical-align : middle;text-align:center;">'.$$total.'</td>
				</tr>
			';
			foreach ($data as $row) {
				$tabel .= '
					<tr>
						<td>'.$row['aufnr'].'</td>
						<td>'.$row['equnr'].'</td>
						<td>'.$row['ktext'].'</td>
					</tr>
				';
				$i++;
			}
		}
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}
	
	public function getSubwo(Request $request) 
	{
		//echo "hello"; die();
		//echo "<pre>"; print_r($request); die();
		
		$req = $request->all();
		//echo "<pre>"; print_r($req); die();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 1;
		$nextOffset = $req['offset'] + 1;

		$output = array();

		$count = "SELECT count(*) as TOTAL FROM REPORT_E";

		$total = DB::connection('workshop')->select($count);
		//echo "<pre>"; print_r($total[0]['total']); die();
		$total = $total[0]['total'];
		
		//echo $total; die();

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 1;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 1;
			$nextOffset = 0;
		}

		/*
		$sql1 = "
			SELECT AUFNR, EQUNR, KTEXT, TXT04, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, TXT04, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, TXT04 FROM REPORT_A1 ORDER BY AUFNR
				) WHERE rownum <= {$nextLimit}
			) WHERE  rnum > {$nextOffset}
		";
		*/
		
		$sql = "
			SELECT ORDERS, ROWNUM AS RNUM FROM
			(
				SELECT ORDERS, ROWNUM AS RNUM FROM
				(
					SELECT MAUFNR AS ORDERS FROM REPORT_E GROUP BY MAUFNR
				)  WHERE ROWNUM <= {$nextLimit}
			) WHERE RNUM > {$nextOffset}
		";
		//echo $sql; die();

		//$sql = "SELECT * FROM auth_modules LIMIT {$nextLimit} OFFSET {$nextOffset}";

		$data = DB::connection('workshop')->select($sql);

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '';
		$i = $nextOffset + 1;
		$table_detil = '';
		if (isset($data) && !empty($data)) 
		{
			foreach ($data as $row) 
			{
				//echo "<pre>"; print_r($row['orders']); die();
				
				$order = $row['orders']; 
				$sql_detil_subwo = "SELECT DISTINCT(AUFNR) AS SUB_WO FROM REPORT_E WHERE MAUFNR = '".$order."'";
				//echo $sql_detil_subwo; die();
				$data_detil_subwo = DB::connection('workshop')->select($sql_detil_subwo); 
				//echo "<pre>"; print_r($data_detil_subwo); die();
				if($data_detil_subwo){
					foreach( $data_detil_subwo as $k => $v )
					{
						$table_detil .= " ".$v['sub_wo']." ";
					}
				}
				
				$tabel .= '
					<tr>
						<td>NO</td>
						<td>'.$order.'</td>
					</tr>
					<tr>
						<td>SUB</td>
						<td>'.$table_detil.'</td>
					</tr>
				';
				$i++;
			}
		}
		
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}
	
	function get_plan_chart($listday, $data)
	{	
		$data = array();
		$sql = " SELECT distinct(tanggal) as tanggal FROM REPORT_CHART_ALL_WO_MTD ";
		$datax = DB::connection('workshop')->select($sql);
		
		if($datax)
		{
			foreach($datax as $kk => $vv)
			{
				$data[] = array(
					'tanggal' => $vv['tanggal'],
					'total_wo' => $this->get_total_wo($vv['tanggal'])
				);
			}
		}
		
		$dt = array();
		$dataPlan = '';
		if(!empty($data))
		{
			foreach($data as $k => $v)
			{
				$dt[$v['tanggal']] = $v;
			}
		}
		
		
		$total_hari = count(explode(',',$listday));
		$last_plan = 0;
		$plan_plus = 0;
		
		for($i=1; $i<=$total_hari; $i++)
		{
			$tgl = str_pad($i, 2, 0, STR_PAD_LEFT);
			$month = date('m');
			$year = date('Y');

			if( !empty($dt) )
			{
				$tanggal = $year.$month.$tgl; 
				if($tanggal == intval(@$dt[$tanggal]['tanggal']) )
				{
					$plan = $dt[$tanggal]['total_wo'];
					$last_plan = $plan;
				}
				else
				{
					//$plan = $last_plan;
					$plan = 0;
				}
			}
			else { $plan = 0; }
			$plan_plus = $plan_plus + $plan;
			
			//$dataPlan .= $plan.',';
			$dataPlan .= $plan_plus.',';
		}
		
		return rtrim($dataPlan,',');
	}
	
	function get_actual_chart($listday,$data)
	{
		$data = array();
		$sql = " SELECT distinct(tanggal) as tanggal FROM REPORT_CHART_WO_TECO_MTD ";
		$datax = DB::connection('workshop')->select($sql);
		
		
		if($datax)
		{
			foreach($datax as $kk => $vv)
			{
				$data[] = array(
					'tanggal' => $vv['tanggal'],
					'total_wo_teco' => $this->get_total_wo_teco($vv['tanggal'])
				);
			}
		}
		//echo "<pre>"; print_r($data); die();
		
		$dt = array();
		$dataActual = "";
		if(!empty($data))
		{
			foreach($data as $k => $v)
			{
				$dt[$v['tanggal']] = $v;
			}
		}
		
		$total_hari = count(explode(',',$listday));
		$last_actual = 0;
		$actual_plus = 0;
		
		for($i=1; $i<=$total_hari; $i++)
		{
			$tgl = str_pad($i, 2, 0, STR_PAD_LEFT);
			$month = date('m');
			$year = date('Y');
			
			if( !empty($dt) )
			{
				$tanggal = $year.$month.$tgl; 
				if($tanggal == intval(@$dt[$tanggal]['tanggal']) )
				{
					$actual = $dt[$tanggal]['total_wo_teco'];
					$last_actual = $actual;
				}
				else
				{
					$actual = 0; //$last_actual;
				}
			}
			else 
			{ 
				$actual = 0; 
			}
			
			$actual_plus = $actual_plus + $actual;
			
			//$dataActual .= $actual.',';
			$dataActual .= $actual_plus.',';
			
			
		}
		
		return rtrim($dataActual,',');
		
	}
	
	function get_detail_subwo($order)
	{
		$sql_detil_subwo = "SELECT DISTINCT(AUFNR) AS SUB_WO FROM REPORT_E WHERE MAUFNR = '".$order['orders']."'";
		$data_detil_subwo = DB::connection('workshop')->select($sql_detil_subwo); 
		return $data_detil_subwo;
	}
	
	public function page_one()
	{
		//echo "Monitoring Workshop All Company"; die();
		
		$url = $_SERVER['REQUEST_URI'];
		header("Refresh: 180; URL=\"" . $url . "\""); //60 = 60 sec | 300 = 5 jam

		$title = "Dashboard Monitoring Plant Maintenance";
		$date = date('d M Y');
		
		
		// TODAY
		$countToday = "SELECT count(*) as TOTAL FROM TREPORT_A1 WHERE TXT04 = 'CRTD' OR TXT04 = 'REL' ";
		$sqlToday = "
			SELECT AUFNR, EQUNR, KTEXT, TXT04, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, TXT04, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, TXT04 FROM TREPORT_A1 WHERE TXT04 = 'CRTD' OR TXT04 = 'REL' ORDER BY AUFNR
				) WHERE rownum <= 4
			) WHERE  rnum > 0
		";
		$totalToday = DB::connection('workshop')->select($countToday);
		$totalToday = $totalToday[0]['total'];
		$dataToday = DB::connection('workshop')->select($sqlToday);

		// TOMORROW
		$countTomorrow = "SELECT count(*) as TOTAL FROM TREPORT_A2 WHERE TXT04 = 'CRTD' OR TXT04 = 'REL' ";
		$sqlTomorrow = "
			SELECT AUFNR, EQUNR, KTEXT, GSTRP, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, GSTRP, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, GSTRP FROM TREPORT_A2 WHERE TXT04 = 'CRTD' OR TXT04 = 'REL' ORDER BY AUFNR
				) WHERE rownum <= 4
			) WHERE  rnum > 0
		";
		$totalTomorrow = DB::connection('workshop')->select($countTomorrow);
		$totalTomorrow = $totalTomorrow[0]['total'];
		$dataTomorrow = DB::connection('workshop')->select($sqlTomorrow);
		
		// CORRECTIVE MAINTENANCE
		$countCorrective = "SELECT count(*) as TOTAL FROM REPORT_CORRECTIVE_MNTC  ";
		$sqlCorrective = "
			SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30, rnum FROM (
				SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30, rownum AS rnum FROM (
					SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30 FROM REPORT_CORRECTIVE_MNTC ORDER BY TXT04, EQUNR
				) WHERE rownum <= 22
			) WHERE  rnum > 0
		";
		$totalCorrective = DB::connection('workshop')->select($countCorrective);
		$totalCorrective = $totalCorrective[0]['total'];
		$dataCorrective = DB::connection('workshop')->select($sqlCorrective);
		
		//REPORT CHART
		$day = '';
		$date_today = date('d');
		for($i=1;$i<=$date_today;$i++)
		{
			$day .= $i.',';
		}		
		$plan = array();
		$actual = array();
		$listday = rtrim($day,',');
		$plan = $this->get_plan_chart($listday, $plan);
		$actual = $this->get_actual_chart($listday, $actual);
		
		return view('layouts.monitoringcontent-one', compact('totalCorrective', 'dataCorrective','totalToday', 'dataToday', 'totalTomorrow', 'dataTomorrow','actual','plan','listday','title','date'));
	}
	
	function getDetailOutstandingNotification($params)
	{
		//echo "<pre>"; print_r($params);die();
		$sql = " 	SELECT NO_NOTIFICATION, EQUIPMENT, DESCRIPTION, rownum AS RNUM FROM(             
						SELECT NO_NOTIFICATION, EQUIPMENT, DESCRIPTION, rownum AS RNUM FROM(        
							SELECT QMNUM AS NO_NOTIFICATION, EQUNR AS EQUIPMENT, QMTXT AS DESCRIPTION
							FROM REPORT_C WHERE TXT30 = '".$params['action']."' ORDER BY TXT30
						) WHERE rownum <= 3
					) WHERE RNUM > 0 
				";
		$data = DB::connection('workshop')->select($sql); 
		return $data;
	}
	
	public function getOutstanding(Request $request) 
	{	
		$req = $request->all();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 3;
		$nextOffset = $req['offset'] + 3;

		$output = array();

		//$count = " SELECT count(*) as TOTAL FROM REPORT_E ";
		$count = " SELECT TXT30 AS ACTION, COUNT(TXT30) AS QTY FROM REPORT_C GROUP BY TXT30 ORDER BY TXT30 ";

		$total = DB::connection('workshop')->select($count);
		$total = $total[2]['qty'];//$total[0]['total'];

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 3;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 3;
			$nextOffset = 0;
		}
		
		$sql = "
			SELECT NO_NOTIFICATION, EQUIPMENT, DESCRIPTION, ROWNUM AS RNUM FROM(
				SELECT NO_NOTIFICATION, EQUIPMENT, DESCRIPTION, ROWNUM AS RNUM FROM(
					SELECT QMNUM AS NO_NOTIFICATION, EQUNR AS EQUIPMENT, QMTXT AS DESCRIPTION
						FROM REPORT_C 
					WHERE TXT30 = 'Outstanding notification' ORDER BY TXT30
				)  WHERE ROWNUM <= {$nextLimit}
			) WHERE RNUM > {$nextOffset}
		"; //echo $sql; die();

		$data = DB::connection('workshop')->select($sql);
		//echo "<pre>"; print_r($data); die();

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '<tr>
						<td width="150px" style="vertical-align:middle" rowspan="3" align="center">OUTSTANDING NOTIFICATION</td>
						<td width="50px" style="vertical-align:middle" rowspan="3" align="center">'.$total.'</td>';
		$i = $nextOffset + 1;
		if (isset($data) && !empty($data)) 
		{
			foreach ($data as $row) 
			{				
				//echo "<pre>"; print_r($row); 
				/*
					[no_notification] => 100007361
					[equipment] => O4122DT058
					[description] => SERVIS BERING RODA KIRI BELAKANG DT 58
					[rnum] => 1
				*/
				
				$no_notification = $row['no_notification']; 
				$equipment = $row['equipment']; 
				$description = $row['description']; 
				
				$tabel .= '
						<td>'.$no_notification.'</td>
						<td>'.$equipment.'</td>
						<td>'.$description.'</td>
					</tr>
				';
				$i++;
			}
		}
		
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}
	
	public function page_two()
	{
		$url = $_SERVER['REQUEST_URI'];
		header("Refresh: 300; URL=\"" . $url . "\""); //10 = 10 sec | 300 = 5 jam

		$title = "Dashboard Monitoring 2";
		$date = date('d M Y');
		
		return view('layouts.monitoringcontent-two', compact('date','title'));
	}
	
	public function getCorrective(Request $request) 
	{
		$req = $request->all();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 22;
		$nextOffset = $req['offset'] + 22;

		$output = array();

		$count = "SELECT count(*) as TOTAL FROM REPORT_CORRECTIVE_MNTC ";

		$total = DB::connection('workshop')->select($count);
		$total = $total[0]['total'];

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 22;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 22;
			$nextOffset = 0;
		}

		$sql = "
			SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30, rnum FROM (
				SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30, rownum AS rnum FROM (
					SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30 FROM REPORT_CORRECTIVE_MNTC ORDER BY TXT04, EQUNR
				) WHERE rownum <= {$nextLimit}
			) WHERE  rnum > {$nextOffset}
		";
		//$sql = "SELECT * FROM mail_queues LIMIT {$nextLimit} OFFSET {$nextOffset}";

		$data = DB::connection('workshop')->select($sql);

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '';
		$i = $nextOffset + 1;
		$status_wo = '';
		$ket_wo = '';
		
		if (isset($data) && !empty($data)) 
		{	
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
			
			foreach ($data as $row) 
			{
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
												
				$tabel .= '
					<tr>
						<td>'.@$row['equnr'].'</td>
						<td>'.@$row['aufnr'].'</td>
						<td>'.@$status_wo.'</td>
						<td>'.@$ket_wo.'</td>
						<td>'.@$udate.'</td>
					</tr>
				';
				$i++;
			}
		}
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}
	
	public function monitoring_workshop(Request $request, $id=null)
	{
		//echo " monitoring workshop $id "; die();
		/*if ( !$request->session()->get('users') )  
		{
			$title = 'Login - Monitoring Workshop 2019';
			$id_company = $id;
			return view('layouts.monitoring-workshop-login', compact('title','id_company'));
		}*/
		
		$url = $_SERVER['REQUEST_URI'];
		header("Refresh: 180; URL=\"" . $url . "\""); //60 = 60 sec | 300 = 5 jam

		$title = "Dashboard Monitoring Plant Maintenance";
		$date = date('d M Y');
		$compid = $id;
		
		// TODAY
		$countToday = "SELECT count(*) as TOTAL FROM TREPORT_A1 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') "; //echo $countToday; die();
		$sqlToday = "
			SELECT AUFNR, EQUNR, KTEXT, TXT04, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, TXT04, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, TXT04 FROM TREPORT_A1 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') ORDER BY AUFNR
				) WHERE rownum <= 4
			) WHERE  rnum > 0
		"; //echo $sqlToday; die();
		$totalToday = DB::connection('workshop')->select($countToday);
		$totalToday = $totalToday[0]['total'];
		$dataToday = DB::connection('workshop')->select($sqlToday);

		// TOMORROW
		$countTomorrow = "SELECT count(*) as TOTAL FROM TREPORT_A2 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') ";//echo $countTomorrow; die();
		$sqlTomorrow = "
			SELECT AUFNR, EQUNR, KTEXT, GSTRP, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, GSTRP, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, GSTRP FROM TREPORT_A2 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') ORDER BY AUFNR
				) WHERE rownum <= 4
			) WHERE  rnum > 0
		";//echo $sqlTomorrow;die();
		$totalTomorrow = DB::connection('workshop')->select($countTomorrow);
		$totalTomorrow = $totalTomorrow[0]['total'];
		$dataTomorrow = DB::connection('workshop')->select($sqlTomorrow);
		
		// CORRECTIVE MAINTENANCE
		$countCorrective = "SELECT count(*) as TOTAL FROM REPORT_CORRECTIVE_MNTC WHERE AUFNR LIKE '0$id%'  ";
		$sqlCorrective = "
			SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30, rnum FROM (
				SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30, rownum AS rnum FROM (
					SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30 FROM REPORT_CORRECTIVE_MNTC WHERE AUFNR LIKE '0$id%' ORDER BY TXT04, EQUNR
				) WHERE rownum <= 22
			) WHERE  rnum > 0
		";
		$totalCorrective = DB::connection('workshop')->select($countCorrective);
		$totalCorrective = $totalCorrective[0]['total'];
		$dataCorrective = DB::connection('workshop')->select($sqlCorrective);
		
		//REPORT CHART
		$day = '';
		$date_today = date('d');
		for($i=1;$i<=$date_today;$i++)
		{
			$day .= $i.',';
		}		
		$plan = array();
		$actual = array();
		$listday = rtrim($day,',');
		$plan = $this->get_plan_wo($listday, $plan, $id);
		$actual = $this->get_actual_teco($listday, $actual, $id);
		
		return view('layouts.monitoringcontent-workshop', compact('compid','totalCorrective', 'dataCorrective','totalToday', 'dataToday', 'totalTomorrow', 'dataTomorrow','actual','plan','listday','title','date'));
	}
	
	public function getTodayWorkshop(Request $request,$id) 
	{
		//echo $id; die();
		$req = $request->all();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 4;
		$nextOffset = $req['offset'] + 4;

		$output = array();

		$count = "SELECT count(*) as TOTAL FROM TREPORT_A1 WHERE AUFNR like '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL')";

		$total = DB::connection('workshop')->select($count);
		$total = $total[0]['total'];

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 4;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 4;
			$nextOffset = 0;
		}

		$sql = "
			SELECT AUFNR, EQUNR, KTEXT, TXT04, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, TXT04, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, TXT04 FROM TREPORT_A1 WHERE AUFNR like '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') ORDER BY AUFNR
				) WHERE rownum <= {$nextLimit}
			) WHERE  rnum > {$nextOffset}
		";

		//$sql = "SELECT * FROM auth_modules LIMIT {$nextLimit} OFFSET {$nextOffset}";

		$data = DB::connection('workshop')->select($sql);

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '';
		$i = $nextOffset + 1;
		if (isset($data) && !empty($data)) {
			foreach ($data as $row) {
				$tabel .= '
					<tr>
						<td>'.$row['aufnr'].'</td>
						<td>'.$row['equnr'].'</td>
						<td>'.$row['ktext'].'</td>
						<td>'.$row['txt04'].'</td>
					</tr>
				';
				$i++;
			}
		}
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}
	
	public function getTomorrowWorkshop(Request $request,$id) 
	{
		$req = $request->all();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 4;
		$nextOffset = $req['offset'] + 4;

		$output = array();

		$count = "SELECT count(*) as TOTAL FROM TREPORT_A2 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL')";

		$total = DB::connection('workshop')->select($count);
		$total = $total[0]['total'];

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 4;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 4;
			$nextOffset = 0;
		}

		$sql = "
			SELECT AUFNR, EQUNR, KTEXT, GSTRP, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, GSTRP, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, GSTRP FROM TREPORT_A2 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') ORDER BY AUFNR
				) WHERE rownum <= {$nextLimit}
			) WHERE  rnum > {$nextOffset}
		";
		//$sql = "SELECT * FROM mail_queues LIMIT {$nextLimit} OFFSET {$nextOffset}";

		$data = DB::connection('workshop')->select($sql);

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '';
		$i = $nextOffset + 1;
		if (isset($data) && !empty($data)) 
		{
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
	
			foreach ($data as $row) 
			{
				$tgl = substr($row['gstrp'],6);
				$bln = @$month[substr($row['gstrp'],4,2)];
				$year = substr($row['gstrp'],0,4);
				$udate = $tgl." ".$bln." ".$year;
			
				$tabel .= '
					<tr>
						<td>'.@$row['aufnr'].'</td>
						<td>'.@$row['equnr'].'</td>
						<td>'.@$row['ktext'].'</td>
						<td nowrap="nowrap">'.@$udate.'</td>
					</tr>
				';
				$i++;
			}
		}
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}
	
	public function getCorrectiveWorkshop(Request $request,$id) 
	{
		$req = $request->all();

		$currTotal = $req['total'];
		$currLimit = $req['limit'];
		$currOffset = $req['offset'];

		$nextLimit = $req['limit'] + 22;
		$nextOffset = $req['offset'] + 22;

		$output = array();

		$count = "SELECT count(*) as TOTAL FROM REPORT_CORRECTIVE_MNTC WHERE AUFNR LIKE '0$id%' AND TXT04 = 'CRTD' ";

		$total = DB::connection('workshop')->select($count);
		$total = $total[0]['total'];

		if ($nextOffset < $total) {
			$nextLimit = $nextLimit;
			$nextOffset = $nextOffset;
		} else if ($nextOffset == $total) {
			$nextLimit = 22;
			$nextOffset = 0;
		} else if ($nextOffset > $total) {
			$nextLimit = 22;
			$nextOffset = 0;
		}

		$sql = "
			SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30, rnum FROM (
				SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30, rownum AS rnum FROM (
					SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30 FROM REPORT_CORRECTIVE_MNTC WHERE AUFNR LIKE '0$id%' AND TXT04 = 'CRTD' ORDER BY TXT04, EQUNR
				) WHERE rownum <= {$nextLimit}
			) WHERE  rnum > {$nextOffset}
		";
		//$sql = "SELECT * FROM mail_queues LIMIT {$nextLimit} OFFSET {$nextOffset}";

		$data = DB::connection('workshop')->select($sql);

		$output['limit'] = $nextLimit;
		$output['offset'] = $nextOffset;
		$output['total'] = $total;

		$tabel = '';
		$i = $nextOffset + 1;
		$status_wo = '';
		$ket_wo = '';
		
		if (isset($data) && !empty($data)) 
		{	
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
			
			foreach ($data as $row) 
			{
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
												
				$tabel .= '
					<tr>
						<td>'.@$row['equnr'].'</td>
						<td>'.@$row['aufnr'].'</td>
						<td>'.@$status_wo.'</td>
						<td>'.@$ket_wo.'</td>
						<td>'.@$udate.'</td>
					</tr>
				';
				$i++;
			}
		}
		$output['data'] = $tabel;

		echo json_encode($output);

		die;
	}
	
	function get_plan_wo($listday, $data, $id)
	{	
		$sql = " SELECT * FROM REPORT_CHART_ALL_WO_MTD WHERE COMPANY_CODE = {$id} ";
		$data = DB::connection('workshop')->select($sql);
		
		$dt = array();
		$dataPlan = '';
		if(!empty($data))
		{
			foreach($data as $k => $v)
			{
				$dt[$v['tanggal']] = $v;
			}
		}
		
		
		$total_hari = count(explode(',',$listday));
		$last_plan = 0;
		
		for($i=1; $i<=$total_hari; $i++)
		{
			$tgl = str_pad($i, 2, 0, STR_PAD_LEFT);
			$month = date('m');
			$year = date('Y');

			if( !empty($dt) )
			{
				$tanggal = $year.$month.$tgl; 
				if($tanggal == intval(@$dt[$tanggal]['tanggal']) )
				{
					$plan = $dt[$tanggal]['total_wo'];
					$last_plan = $plan;
				}
				else
				{
					$plan = $last_plan;
				}
			}
			else { $plan = 0; }
			$dataPlan .= $plan.',';
		}
		
		return rtrim($dataPlan,',');
	}
	
	function get_actual_teco($listday,$data,$id)
	{
		$sql = " SELECT * FROM REPORT_CHART_WO_TECO_MTD WHERE COMPANY_CODE = {$id} ";
		$data = DB::connection('workshop')->select($sql);
		//echo "<pre>"; print_r($data); die();
		
		$dt = array();
		$dataActual = "";
		if(!empty($data))
		{
			foreach($data as $k => $v)
			{
				$dt[$v['tanggal']] = $v;
			}
		}
		
		$total_hari = count(explode(',',$listday));
		$last_actual = 0;
		
		for($i=1; $i<=$total_hari; $i++)
		{
			$tgl = str_pad($i, 2, 0, STR_PAD_LEFT);
			$month = date('m');
			$year = date('Y');
			
			if( !empty($dt) )
			{
				$tanggal = $year.$month.$tgl; 
				if($tanggal == intval(@$dt[$tanggal]['tanggal']) )
				{
					$actual = $dt[$tanggal]['total_wo_teco'];
					$last_actual = $actual;
				}
				else
				{
					$actual = $last_actual;
				}
			}
			else { $actual = 0; }
			$dataActual .= $actual.',';
		}
		
		return rtrim($dataActual,',');
		
	}
	
	function get_total_wo($tgl)
	{
		$sql = " SELECT SUM(wo_per_hari) AS total FROM REPORT_CHART_ALL_WO_MTD WHERE tanggal = '{$tgl}' ";
		$data = DB::connection('workshop')->select($sql);
		return $data[0]['total'];
	}
	
	function get_total_wo_teco($tgl)
	{
		$sql = " SELECT SUM(wo_teco_per_hari) AS total FROM REPORT_CHART_WO_TECO_MTD WHERE tanggal = '{$tgl}' ";
		$data = DB::connection('workshop')->select($sql);
		return $data[0]['total'];
	}
	
	public function monitoring_workshop_login(Request $request, $id=null)
	{
		$userlogin = @$request->session()->get('users');
		$idcomp = @$request->session()->get('id_company');
		//echo $userlogin; die();
		
		if (  $idcomp != $id )  
		{
			$title = 'Login - Monitoring Workshop 2019';
			$id_company = $id;
			return view('layouts.monitoring-workshop-login', compact('title','id_company'));
		}
		
		//echo " monitoring workshop $id "; die();
		$url = $_SERVER['REQUEST_URI'];
		header("Refresh: 180; URL=\"" . $url . "\""); //60 = 60 sec | 300 = 5 jam

		$title = "Dashboard Monitoring Plant Maintenance";
		$date = date('d M Y');
		$compid = $id;
		
		// TODAY
		$countToday = "SELECT count(*) as TOTAL FROM TREPORT_A1 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') "; //echo $countToday; die();
		$sqlToday = "
			SELECT AUFNR, EQUNR, KTEXT, TXT04, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, TXT04, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, TXT04 FROM TREPORT_A1 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') ORDER BY AUFNR
				) WHERE rownum <= 4
			) WHERE  rnum > 0
		"; //echo $sqlToday; die();
		$totalToday = DB::connection('workshop')->select($countToday);
		$totalToday = $totalToday[0]['total'];
		$dataToday = DB::connection('workshop')->select($sqlToday);

		// TOMORROW
		$countTomorrow = "SELECT count(*) as TOTAL FROM TREPORT_A2 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') ";//echo $countTomorrow; die();
		$sqlTomorrow = "
			SELECT AUFNR, EQUNR, KTEXT, GSTRP, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, GSTRP, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, GSTRP FROM TREPORT_A2 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') ORDER BY AUFNR
				) WHERE rownum <= 4
			) WHERE  rnum > 0
		";//echo $sqlTomorrow;die();
		$totalTomorrow = DB::connection('workshop')->select($countTomorrow);
		$totalTomorrow = $totalTomorrow[0]['total'];
		$dataTomorrow = DB::connection('workshop')->select($sqlTomorrow);
		
		// CORRECTIVE MAINTENANCE
		$countCorrective = "SELECT count(*) as TOTAL FROM REPORT_CORRECTIVE_MNTC WHERE AUFNR LIKE '0$id%'  ";
		$sqlCorrective = "
			SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30, rnum FROM (
				SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30, rownum AS rnum FROM (
					SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30 FROM REPORT_CORRECTIVE_MNTC WHERE AUFNR LIKE '0$id%' ORDER BY TXT04, EQUNR
				) WHERE rownum <= 22
			) WHERE  rnum > 0
		";
		$totalCorrective = DB::connection('workshop')->select($countCorrective);
		$totalCorrective = $totalCorrective[0]['total'];
		$dataCorrective = DB::connection('workshop')->select($sqlCorrective);
		
		//REPORT CHART
		$day = '';
		$date_today = date('d');
		for($i=1;$i<=$date_today;$i++)
		{
			$day .= $i.',';
		}		
		$plan = array();
		$actual = array();
		$listday = rtrim($day,',');
		$plan = $this->get_plan_wo($listday, $plan, $id);
		$actual = $this->get_actual_teco($listday, $actual, $id);
		
		return view('layouts.monitoringcontent-workshop-login', compact('compid','totalCorrective', 'dataCorrective','totalToday', 'dataToday', 'totalTomorrow', 'dataTomorrow','actual','plan','listday','title','date'));
	}
	
	/*
	function monitoring_workshop_dologin_1(Request $request, $id=null)
	{		
		//echo "<pre>"; print_r($_POST); //die();
		//$user = $_POST['iuser'];
		//$pass = $_POST['ipass'];
		
		$user = $request->iuser;
        $pass = $request->ipass;
		
		if( $user == 'admin' && $pass == 'admin' )
		{
			//echo $user."success login ===> user : ".$user." && pass : ".$pass; die();
			$request->session()->forget('users');
			$request->session()->forget('id_company');
			
			$request->session()->put('users','admin');
			$request->session()->put('id_company',$id);
			return redirect('monitoring-workshop-login/'.$id);
			//return redirect('monitoring-workshop/'.$id);
		}
		else
		{
			//echo "failed login"; die();
			return redirect('monitoring-workshop-login/'.$id)->with('status', 'Login Failed!');
		}
		
	}
	*/
	
	function monitoring_workshop_dologin(Request $request, $id=null)
	{
		$username = $request->iuser;
		$password = $request->ipass;
		
		$ldap = new LDAP();
		$data = $ldap->login($username,$password,$id);

		if ( $data['count'] > 0 ) 
		{
			$request->session()->forget('users');
			$request->session()->forget('id_company');
			
			$request->session()->put('users',$username);
			$request->session()->put('id_company',$id);
			return redirect('monitoring-workshop-login/'.$id);
		}
		else
		{
			return redirect('monitoring-workshop-login/'.$id)->with('status', 'Login Failed!');
		}
		die();
	}
	
	function monitoring_workshop_logout(Request $request, $id=null)
	{
		//echo $id; die();
		$request->session()->forget('users');
		$request->session()->forget('id_company');
		
		return redirect('monitoring-workshop-login/'.$id)->with('status', 'Logout Success!');
	}
	
	function monitoring_workshop_download_today(Request $request, $id=null)
	{
		//echo $id; die();
		$data = array();
		
		date_default_timezone_set("Asia/Bangkok");
		$date = date("dmY_Hi");
		
		$var_1 = "monitoring_workshop_today_".$date.".csv";
		$dir = ""; // trailing slash is important
		$file = $dir.$var_1;
		
		
		#### EXPORT TO CSV VIA BROWSER ####
		header("Content-type: text/csv");
		//header("Content-Disposition: attachment; filename=production_daily_".$date."_".count($data)."_data.csv");
		header("Content-Disposition: attachment; filename=".basename($file)."");
		header("Pragma: no-cache");
		header("Expires: 0");

		// TODAY
		$countToday = "SELECT count(*) as TOTAL FROM TREPORT_A1 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') "; //echo $countToday; die();
		$sqlToday = "
			SELECT AUFNR, EQUNR, KTEXT, TXT04, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, TXT04, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, TXT04 FROM TREPORT_A1 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') ORDER BY AUFNR
				) WHERE 1=1
			) WHERE rnum > 0
		"; 
		
		$totalToday = DB::connection('workshop')->select($countToday);
		$totalToday = $totalToday[0]['total'];
		$datax = DB::connection('workshop')->select($sqlToday);
		//echo "<pre>"; print_r($datax); die();
		
		$output = fopen("php://output", "wb");
		$data2 = array("NO WO","EQUIPTMENT","JENIS PAKET","STATUS WO");
		
		fputcsv($output, $data2);
		
		if(!empty($datax))
		{
			
			
			foreach( $datax as $k => $v )
			{
				//echo "<pre>"; print_r($v);
				/*
					[aufnr] => 041210006316
					[equnr] => O4121DT083
					[ktext] => PERAWATAN BERKALA DT DYNA/DUTRO
					[txt04] => CRTD
					[rnum] => 1
				*/
				
				$data3 = array(
					'aufnr' => $v['aufnr'],
					'equnr' => $v['equnr'],
					'ktext' => $v['ktext'],
					'txt04' => $v['txt04']
				);
				 
				fputcsv($output, $data3);
				
			}
			//die();
			
			fclose($output);
		}
		#### END EXPORT TO CSV VIA BROWSER ####
	
		die;
	}
	
	function monitoring_workshop_download_tomorrow(Request $request, $id=null)
	{
		//echo $id; die();
		$data = array();
		
		date_default_timezone_set("Asia/Bangkok");
		$date = date("dmY_Hi");
		
		$var_1 = "monitoring_workshop_tomorrow_".$date.".csv";
		$dir = ""; // trailing slash is important
		$file = $dir.$var_1;
		
		
		#### EXPORT TO CSV VIA BROWSER ####
		header("Content-type: text/csv");
		//header("Content-Disposition: attachment; filename=production_daily_".$date."_".count($data)."_data.csv");
		header("Content-Disposition: attachment; filename=".basename($file)."");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$output = fopen("php://output", "wb");
		$data2 = array("NO WO","EQUIPTMENT","JENIS PAKET","BASIC START");
		
		fputcsv($output, $data2);
		
		// TOMORROW
		$sqlTomorrow = "
			SELECT AUFNR, EQUNR, KTEXT, GSTRP, rnum FROM (
				SELECT AUFNR, EQUNR, KTEXT, GSTRP, rownum AS rnum FROM (
					SELECT AUFNR, EQUNR, KTEXT, GSTRP FROM TREPORT_A2 WHERE AUFNR LIKE '0$id%' AND (TXT04 = 'CRTD' OR TXT04 = 'REL') ORDER BY AUFNR
				) WHERE 1=1
			) WHERE  rnum > 0
		";//echo $sqlTomorrow;die();
		$datax = DB::connection('workshop')->select($sqlTomorrow);
		//echo "<pre>"; print_r($datax); die();
		
		if(!empty($datax))
		{
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
	
			foreach( $datax as $k => $v )
			{				
				$tgl = substr($v['gstrp'],6);
				$bln = @$month[substr($v['gstrp'],4,2)];
				$year = substr($v['gstrp'],0,4);
				$udate = $tgl." ".$bln." ".$year;
				
				$data3 = array(
					'aufnr' => $v['aufnr'],
					'equnr' => $v['equnr'],
					'ktext' => $v['ktext'],
					'gstrp' => $udate
				);
				 
				fputcsv($output, $data3);
				
			}
			//die();
			
			fclose($output);
		}
		#### END EXPORT TO CSV VIA BROWSER ####
	
		die;
	}
	
	function monitoring_workshop_download_corrective(Request $request, $id=null)
	{
		//echo $id; die();
		$data = array();
		
		date_default_timezone_set("Asia/Bangkok");
		$date = date("dmY_Hi");
		
		$var_1 = "monitoring_workshop_corrective_".$date.".csv";
		$dir = ""; // trailing slash is important
		$file = $dir.$var_1;
		
		
		#### EXPORT TO CSV VIA BROWSER ####
		header("Content-type: text/csv");
		//header("Content-Disposition: attachment; filename=production_daily_".$date."_".count($data)."_data.csv");
		header("Content-Disposition: attachment; filename=".basename($file)."");
		header("Pragma: no-cache");
		header("Expires: 0");
		
		$output = fopen("php://output", "wb");
		$data2 = array("EQUIPMENT","WORK ORDER","STATUS WO","DESCRIPTION", "PER TANGGAL");
		
		fputcsv($output, $data2);
		
		// CORRECTIVE MAINTENANCE
		$sqlCorrective = "
			SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30, rnum FROM (
				SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30, rownum AS rnum FROM (
					SELECT STAT, EQUNR, AUFNR, TXT04, UDATE, TXT30 FROM REPORT_CORRECTIVE_MNTC WHERE AUFNR LIKE '0$id%' ORDER BY TXT04, EQUNR
				) WHERE 1=1
			) WHERE  rnum > 0
		";
		$datax = DB::connection('workshop')->select($sqlCorrective);
		//echo "<pre>"; print_r($datax); die();
		
		if(!empty($datax))
		{
			$status_wo = '';
			$ket_wo = '';
			
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
											
			foreach( $datax as $k => $v )
			{				
				$tgl = substr($v['udate'],6);
				$bln = @$month[substr($v['udate'],4,2)];
				$year = substr($v['udate'],0,4);
				$udate = $tgl." ".$bln." ".$year;
				$stat = @$list_stat[''.$v['stat'].''];
				
				if( !empty($stat) )
				{
					$status_wo = $list_stat[$v['stat']][0];
					$ket_wo = $list_stat[$v['stat']][1];
				}
				else
				{
					$status_wo = $v['txt04'];
					$ket_wo = $v['txt30'];
				}
												
				$data3 = array(
					'equnr' => @$v['equnr'],
					'aufnr' => @$v['aufnr'],
					'status_wo' => @$status_wo,
					'ket_wo' => @$ket_wo,
					'udate' => @$udate
				);
				 
				fputcsv($output, $data3);
				
			}
			//die();
			
			fclose($output);
		}
		#### END EXPORT TO CSV VIA BROWSER ####
	
		die;
	}
	
}