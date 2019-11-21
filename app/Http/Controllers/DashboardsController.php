<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB, DateTime;
use App\Tickets;

class DashboardsController extends Controller {

	public function __construct() 
	{
		date_default_timezone_set('Asia/Jakarta');
		$this->oci = DB::connection('oracle');
		$this->gt = 'glpi_tickets';
		$this->gtu = 'glpi_tickets_users';
		$this->gu = 'glpi_users';
		$this->ge = 'glpi_entities';
		$this->gl = 'glpi_logs';
	}

	public function index() 
	{
		$url = $_SERVER['REQUEST_URI'];
		header("Refresh: 300; URL=\"" . $url . "\"");

		$title = 'DASHBOARD DAILY HELPDESK';
		$date = date('d M Y');
		$year = date('Y');

		/*DB::enableQueryLog();
		DB::connection('oracle')->enableQueryLog();

		$nowDate = strtoupper(date('d-M-y'));
		$befDate = strtoupper(date('d-M-y', strtotime($nowDate . ' -1 day')));

		for ($i=0; $i<365; $i++) {
			$result = $this->oci->select("SELECT COUNT(*) AS total FROM ZPOM_HL WHERE WERKS IS NULL AND HOLIDAY_DATE = TO_DATE(?, 'DD-MON-YY')", array($befDate));

			$befDay = date('D', strtotime($befDate));

			if ($befDay == 'Sun') {
				$befDate = strtoupper(date('d-M-y', strtotime($befDate . ' -2 day')));
				$hasil = $this->oci->select("SELECT COUNT(*) AS total FROM ZPOM_HL WHERE WERKS IS NULL AND HOLIDAY_DATE = TO_DATE(?, 'DD-MON-YY')", array($befDate));
				if ($hasil[0]['total'] == '1') {
					$befDate = strtoupper(date('d-M-y', strtotime($befDate . ' -1 day')));
					$output = $befDate;
				} else {
					$befDate = strtoupper(date('d-M-y', strtotime($befDate . '+1 day')));
					$output = $befDate;
					break;
				}
			} else if ($befDay == 'Sat') {
				$befDate = strtoupper(date('d-M-y', strtotime($befDate . ' -1 day')));
				$hasil = $this->oci->select("SELECT COUNT(*) AS total FROM ZPOM_HL WHERE WERKS IS NULL AND HOLIDAY_DATE = TO_DATE(?, 'DD-MON-YY')", array($befDate));
				if ($hasil[0]['total'] == '1') {
					$befDate = strtoupper(date('d-M-y', strtotime($befDate . ' -1 day')));
					$output = $befDate;
				} else {
					$befDate = strtoupper(date('d-M-y', strtotime($befDate . '+1 day')));
					$output = $befDate;
					break;
				}
			} else if (($befDay != 'Sun' || $befDay != 'Sat') && $result[0]['total'] == '1') {
				$befDate = strtoupper(date('d-M-y', strtotime($befDate . ' -1 day')));
				$output = $befDate;
			} else if (($befDay != 'Sun' || $befDay != 'Sat') && $result[0]['total'] != '1') {
				$befDate = strtoupper(date('d-M-y', strtotime($befDate . ' +1 day')));
				$output = $befDate;
				break;
			} else;
		}

		$finalDate = strtoupper(date('Y-m-d', strtotime($output . ' -1 day')));

		$yesterdayDate = new DateTime(date('Y-m-d', strtotime($nowDate)));
		$todayDate = new DateTime($finalDate);

		$selisih = $todayDate->diff($yesterdayDate);
		$selisih = $selisih->days;
		*/

		$yesterday = strtoupper(date('Y-m-d', strtotime($date . ' -1 day')));

		// tiket create hari ini
		$a_result = Tickets::where(DB::raw("date(date)"), DB::raw("curdate()"))->count();
		
		// tiket hari ini solve dalam 1 jam
		$b_result = Tickets::where(DB::raw("date(date)"), DB::raw("curdate()"))->where(DB::raw("left(timediff(solvedate, date), 2)"), '00')->count();
		
		// tiket create hari kemarin
		$x_result = Tickets::where(DB::raw("date(date)"), $yesterday)->count();
		
		// tiket kemarin solve dibawah 1 jam
		$y_result = Tickets::where(DB::raw("date(date)"), $yesterday)->where(DB::raw("left(timediff(solvedate, date), 2)"), '00')->count();
		
		// tiket solve lebih dari 1 jam dibawah 1 hari
		$c_result = $x_result - $y_result;
		
		// tiket kemarin solve lebih dari 1 jam
		$d_result = Tickets::where(DB::raw("date(date)"), $yesterday)->whereNotNull('solvedate')->where(DB::raw("left(timediff(solvedate, date), 2)"), '>', '00')->count();

		if ($a_result == 0) {
			$persen_mtr = 0;
		} else {
			$persen_a = $b_result / $a_result;
			$fin_persen_a = $persen_a * 100;
			$persen_mtr = number_format($fin_persen_a, 0);
		}

		if ($c_result == 0) {
			$persen_mts = 0;
		} else {
			$persen_c = $d_result / $c_result;
			$fin_persen_c = $persen_c * 100;
			$persen_mts = number_format($fin_persen_c, 0);
		}


		// MONTH TO DATE
		// MTD - MTR
		$a_mtd = Tickets::where(DB::raw("DATE_FORMAT(date, '%Y-%m')"), DB::raw("DATE_FORMAT(CURRENT_DATE(), '%Y-%m')"))->count();
		$b_mtd = Tickets::where(DB::raw("DATE_FORMAT(date, '%Y-%m')"), DB::raw("DATE_FORMAT(CURRENT_DATE(), '%Y-%m')"))
					->where(DB::raw("LEFT(TIMEDIFF(solvedate, date), 2)"), '00')->count();

		// MTD - MTS
		$c_mtd = DB::select("
			SELECT COUNT(*) AS total FROM glpi_tickets 
			WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE() - 1, '%Y-%m') 
			AND (LEFT(TIMEDIFF(solvedate, date), 2) > '00' OR solvedate IS NULL)
		");
		$c_mtd = $c_mtd[0]['total'];

		$d_mtd = DB::select("
			SELECT COUNT(*) AS total FROM glpi_tickets 
			WHERE DATE_FORMAT(date, '%Y-%m') = DATE_FORMAT(CURRENT_DATE() - 1, '%Y-%m') 
			AND (DATEDIFF(solvedate, date) <= '1' OR solvedate IS NULL) 
			AND LEFT(TIMEDIFF(solvedate, date), 2) > '00'
		");
		$d_mtd = $d_mtd[0]['total'];


		if ($a_mtd == 0) {
			$mtr_mtd = 0;
		} else {
			$per_mtd_a = $b_mtd / $a_mtd;
			$fin_per_mtd_a = $per_mtd_a * 100;
			$persen_mtr_mtd = number_format($fin_per_mtd_a, 0);
		}

		if ($c_mtd == 0) {
			$mts_mtd = 0;
		} else {
			$per_mtd_c = $d_mtd / $c_mtd;
			$fin_per_mtd_c = $per_mtd_c * 100;
			$persen_mts_mtd = number_format($fin_per_mtd_c, 0);
		}


		// YEAR TO DATE
		$a_ytd = Tickets::where(DB::raw("DATE_FORMAT(date, '%Y')"), DB::raw("DATE_FORMAT(CURRENT_DATE(), '%Y')"))->count();
		$b_ytd = Tickets::where(DB::raw("DATE_FORMAT(date, '%Y')"), DB::raw("DATE_FORMAT(CURRENT_DATE(), '%Y')"))
					->where(DB::raw("LEFT(TIMEDIFF(solvedate, date), 2)"), '00')->count();

		$c_ytd = DB::select("
			SELECT COUNT(*) AS total FROM glpi_tickets 
			WHERE DATE_FORMAT(date, '%Y') = DATE_FORMAT(CURRENT_DATE() - 1, '%Y') 
			AND (LEFT(TIMEDIFF(solvedate, date), 2) > '00' OR solvedate IS NULL)
		");
		$c_ytd = $c_ytd[0]['total'];

		$d_ytd = DB::select("
			SELECT COUNT(*) AS total FROM glpi_tickets 
			WHERE DATE_FORMAT(date, '%Y') = DATE_FORMAT(CURRENT_DATE() - 1, '%Y') 
			AND (DATEDIFF(solvedate, date) <= '1' OR solvedate IS NULL) 
			AND LEFT(TIMEDIFF(solvedate, date), 2) > '00'
		");
		$d_ytd = $d_ytd[0]['total'];


		if ($a_ytd == 0) {
			$mtr_ytd = 0;
		} else {
			$per_ytd_a = $b_ytd / $a_ytd;
			$fin_per_ytd_a = $per_ytd_a * 100;
			$persen_mtr_ytd = number_format($fin_per_ytd_a, 0);
		}

		if ($c_ytd == 0) {
			$mts_ytd = 0;
		} else {
			$per_ytd_c = $d_ytd / $c_ytd;
			$fin_per_ytd_c = $per_ytd_c * 100;
			$persen_mts_ytd = number_format($fin_per_ytd_c, 0);
		}


		// Additional Info
		$carryOver = DB::select("
			SELECT SUM(total) as carry_over FROM (
				SELECT COUNT(*) AS total FROM glpi_tickets WHERE status IN ('solved') AND date(date) < curdate() AND date(solvedate) = curdate()
				UNION
				SELECT COUNT(*) AS total FROM glpi_tickets WHERE status IN ('new', 'assign') AND date(date) < curdate()
			) T
		");
		$carry_over = $carryOver[0]['carry_over'];

		$ticket_today = Tickets::where(DB::raw("date(date)"), DB::raw("curdate()"))->count();
		$total_ticket = $carry_over + $ticket_today;
		$solved_ticket = Tickets::where(DB::raw("date(solvedate)"), DB::raw("curdate()"))->count();
		$outstanding_ticket = Tickets::whereIn('status', ['new', 'assign'])->count();

		$info = [
			'0' => [
				'icon' => 'E',
				'color' => 'danger',
				'name' => "CARRY OVER",
				'value' => $carry_over
			],
			'1' => [
				'icon' => '',
				'color' => 'megna',
				'name' => "TODAY'S",
				'value' => $ticket_today
			],
			'2' => [
				'icon' => '',
				'name' => "TOTAL",
				'color' => 'primary',
				'value' => $total_ticket
			],
			'3' => [
				'icon' => '',
				'color' => 'success',
				'name' => "SOLVED",
				'value' => $solved_ticket
			],
			'4' => [
				'icon' => 'E',
				'color' => 'danger',
				'name' => "OUTSTANDING",
				'value' => $outstanding_ticket
			]
		];


		/* HighCharts */
		$totalMon = ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"];
		$lastElement = end($totalMon);

		// MTR //
		$chartMTR = DB::select("
			SELECT SUBSTRING(bulan, 6) AS bulan, total, total_tiket, IFNULL(ROUND((total / total_tiket) * 100), 0) AS persentase FROM (
				SELECT *, (SELECT COUNT(*) AS total_tiket FROM glpi_tickets WHERE DATE_FORMAT(date, '%Y-%m') = bulan) total_tiket FROM (
					SELECT DATE_FORMAT(date, '%Y-%m') AS bulan, COUNT(*) AS total FROM glpi_tickets 
					WHERE DATE_FORMAT(date, '%Y') = DATE_FORMAT(CURDATE(), '%Y') 
					AND DATE_FORMAT(date, '%Y-%m') < (DATE_FORMAT(CURRENT_DATE(), '%Y-%m')) 
					AND LEFT(TIMEDIFF(solvedate, date), 2) = '00' 
					GROUP BY DATE_FORMAT(date, '%Y-%m')
				) t
			) x
		");

		$newChartMTR = [];
		foreach ($chartMTR as $row) {
			$newChartMTR[$row['bulan']] = $row;
		}

		$dataMTR = '';
		$newMon = [];
		foreach ($totalMon as $row) {
			if (isset($newChartMTR[$row])) {
				$newMon[] = $row;
				$dataMTR .= $newChartMTR[$row]['persentase'] . ', ';
			} else {
				$mon = date('m');
				if ($row == $mon) {
					$newMon[] = $row;
					$dataMTR .= $persen_mtr_mtd . ', ';
				} else;
			}
		}

		if (end($newMon) != $lastElement) {
			$newMon = array_merge($newMon, ["...", "12"]);
			$dataMTR .= 'null, null, ';
		}

		$bulan_mtr = json_encode($newMon);
		$data_mtr = substr($dataMTR, 0, -2);


		// MTS //
		$pembilang = DB::select("
			SELECT DATE_FORMAT(date, '%m') AS bulan, COUNT(*) AS total FROM glpi_tickets
			WHERE DATE_FORMAT(date, '%Y') = DATE_FORMAT(CURDATE(), '%Y') 
			AND DATE_FORMAT(date, '%Y-%m') < DATE_FORMAT(CURDATE(), '%Y-%m') 
			AND (DATEDIFF(solvedate, date) <= '1' OR solvedate IS NULL) 
			AND LEFT(TIMEDIFF(solvedate, date), 2) > '00' 
			GROUP BY DATE_FORMAT(date, '%Y-%m')
		");

		$pembagiOne = DB::select("
			SELECT DATE_FORMAT(date, '%m') AS bulan, COUNT(*) AS total FROM glpi_tickets 
			WHERE DATE_FORMAT(date, '%Y') = DATE_FORMAT(CURDATE(), '%Y') 
			AND DATE_FORMAT(date, '%Y-%m') < DATE_FORMAT(CURDATE(), '%Y-%m') 
			GROUP BY DATE_FORMAT(date, '%Y-%m')
		");

		$pembagiTwo = DB::select("
			SELECT DATE_FORMAT(date, '%m') AS bulan, COUNT(*) AS total FROM glpi_tickets 
			WHERE DATE_FORMAT(date, '%Y') = DATE_FORMAT(CURDATE(), '%Y') 
			AND DATE_FORMAT(date, '%Y-%m') < DATE_FORMAT(CURDATE(), '%Y-%m') 
			AND LEFT(TIMEDIFF(solvedate, date), 2) = '00' 
			GROUP BY DATE_FORMAT(date, '%Y-%m')
		");

		$newChartMTS = [];
		foreach ($pembagiOne as $k => $v) {
			$newChartMTS[] = $v['total'] - $pembagiTwo[$k]['total'];
		}

		$newPembilang = [];
		foreach ($pembilang as $k=>$v) {
			$newPembilang[$v['bulan']]['bulan'] = $v['bulan'];
			$newPembilang[$v['bulan']]['persentase'] = ($newChartMTS[$k] == '0') ? '0' : round(($v['total'] / $newChartMTS[$k]) * 100);
		}

		$dataMTS = '';
		$newMon = [];
		foreach ($totalMon as $row) {
			if (isset($newPembilang[$row])) {
				$newMon[] = $row;
				$dataMTS .= $newPembilang[$row]['persentase'] . ', ';
			} else {
				$mon = date('m');
				if ($row == $mon) {
					$newMon[] = $row;
					$dataMTS .= $persen_mts_mtd . ', ';
				} else;
			}
		}

		if (end($newMon) != $lastElement) {
			$plusMon  = ["...", "12"];
			$newMon = array_merge($newMon, $plusMon);
			$dataMTS .= 'null, null, ';
		}

		$bulan_mts = json_encode($newMon);
		$data_mts = substr($dataMTS, 0, -2);

		$result = Tickets::where(DB::raw("date(date)"), DB::raw("subdate(current_date, 1)"))->orderBy('date')->get()->toArray();

		return view('layouts.contents', compact('title', 'date', 'year', 'info', 'a_result', 'b_result', 'c_result', 'd_result', 'persen_mtr', 'persen_mts', 'a_mtd', 'b_mtd', 'c_mtd', 'd_mtd', 'persen_mtr_mtd', 'persen_mts_mtd', 'a_ytd', 'b_ytd', 'c_ytd', 'd_ytd', 'persen_mtr_ytd', 'persen_mts_ytd', 'bulan_mtr', 'data_mtr', 'bulan_mts', 'data_mts'));
	}


	public function mtsdaily() {
		$data = [];
		//$result = Tickets::where(DB::raw("date(date)"), DB::raw("curdate()"))->orderBy('date')->get()->toArray();
		/*$result = DB::select("
			SELECT gu.name as user, gt.*, LEFT(TIMEDIFF(solvedate, date), 2) AS selisih FROM glpi_tickets gt 
			LEFT JOIN glpi_users gu ON gu.id = gt.users_id_recipient
			WHERE date(gt.date) = curdate()
		");*/
		$result = DB::select("
			SELECT mc.Company_Name, ot.* FROM (
				SELECT 
					UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(user_dn, ',OU=B.Triputra Agro Persada', 1), ',OU=', -1)) AS c_name, 
					REPLACE(REPLACE(gu.email, '@tap-agri.com', ''), '@tap-agri.co.id', '') AS user, gt.*, 
					LEFT(TIMEDIFF(solvedate, date), 2) AS selisih FROM glpi_tickets gt 
				LEFT JOIN glpi_users gu ON gu.id = gt.users_id_recipient
				WHERE date(gt.date) = curdate()
			) ot 
			LEFT JOIN db_master.M_Company mc ON mc.Company_Description LIKE CONCAT('%', ot.c_name, '%')
		");

		$count =count($result);

		if ($count > 0) {
			$i = 1;

			$data['count'] = $count;
			$output = '';

			foreach ($result as $k=>$v) {
				$in = ($k == '0') ? ' in' : '';
				$date = date('H:i:s', strtotime($v['date']));
				$selisih = ($v['selisih'] == '00') ? 'background-color:#92d050;color:#000000;' : 'background-color:#558ed5;color:#ffffff;';
				$user = (!empty($v['user'])) ? ', (' . $v['user'] : '';
				$company = (!empty($v['Company_Name'])) ? ' - ' . $v['Company_Name'] . ') ' : ' - ' . $v['c_name'] . ') ';
				$company = ($company == ' - ) ') ? str_replace(' - ', '', $company) : $company;
				$output .= '
					<div class="panel panel-default">
						<div class="panel-heading no-border">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#dailymtscontent" href="#collapse1-'.$k.'" style="'.$selisih.'font-weight:bold;text-align:left;text-transform:none;">'. $i . '. ' . $v['id'] . ' - ' . $date . $user . $company . ' - ' . ucwords(strtolower($v['name'])) . '</a>
							</h4>
						</div>
						<div id="collapse1-'.$k.'" class="panel-collapse collapse'.$in.'">
							<div class="panel-body" style="background-color:#ffffff;">'.$v['content'].'</div>
						</div>
					</div>
				';
				$i++;
			}
			$data['output'] = $output;
		} else {
			$data['count'] = 0;
			$data['output'] = '<h2 style="text-align:center;">No data found</h2>';
		}

		echo json_encode($data);

		exit();
	}

	public function mtrdaily() {
		$data = [];
		$result = DB::select("
			SELECT gu.name as user, gt.*, LEFT(TIMEDIFF(solvedate, date), 2) AS selisih FROM glpi_tickets gt 
			LEFT JOIN glpi_users gu ON gu.id = gt.users_id_recipient 
			WHERE DATE(gt.date) = SUBDATE(CURDATE(), 1) AND (LEFT(TIMEDIFF(gt.solvedate, gt.date), 2) <> '00' OR gt.solvedate IS NULL) ORDER BY gt.date
		");
		$result = DB::select("
			SELECT mc.Company_Name, ot.* FROM (
				SELECT 
					UPPER(SUBSTRING_INDEX(SUBSTRING_INDEX(user_dn, ',OU=B.Triputra Agro Persada', 1), ',OU=', -1)) AS c_name, 
					REPLACE(REPLACE(gu.email, '@tap-agri.com', ''), '@tap-agri.co.id', '') AS user, 
					gt.*, 
					LEFT(TIMEDIFF(solvedate, date), 2) AS selisih FROM glpi_tickets gt 
					LEFT JOIN glpi_users gu ON gu.id = gt.users_id_recipient 
					WHERE DATE(gt.date) = SUBDATE(CURDATE(), 1) AND (LEFT(TIMEDIFF(gt.solvedate, gt.date), 2) <> '00' OR gt.solvedate IS NULL) ORDER BY gt.date
				) ot 
			LEFT JOIN db_master.M_Company mc ON mc.Company_Description LIKE CONCAT ('%', ot.c_name, '%')
		");
		$count = count($result);

		if ($count > 0) {
			$i = 1;

			$data['count'] = $count;
			$output = '';

			foreach ($result as $k=>$v) {
				$in = ($k == '0') ? ' in' : '';
				$date = date('H:i:s', strtotime($v['date']));
				$selisih = ($v['selisih'] == '00' || empty($v['selisih'])) ? 'background-color:#558ed5;color:#ffffff;' : 'background-color:#92d050;color:#000000;';
				$user = (!empty($v['user'])) ? ', (' . $v['user'] : '';
				$company = (!empty($v['Company_Name'])) ? ' - ' . $v['Company_Name'] . ') ' : ' - ' . $v['c_name'] . ') ';
				$company = ($company == ' - ) ') ? str_replace(' - ', '', $company) : $company;
				$output .= '
					<div class="panel panel-default">
						<div class="panel-heading no-border">
							<h4 class="panel-title">
								<a data-toggle="collapse" data-parent="#dailymtrcontent" href="#collapse2-'.$k.'" style="'.$selisih.'font-weight:bold;text-align:left;text-transform:none;">'. $i . '. ' . $v['id'] . ' - ' . $date . $user . $company . ' - ' . ucwords(strtolower($v['name'])) . '</a>
							</h4>
						</div>
						<div id="collapse2-'.$k.'" class="panel-collapse collapse'.$in.'">
							<div class="panel-body" style="background-color:#ffffff;">'.$v['content'].'</div>
						</div>
					</div>
				';
				$i++;
			}
			$data['output'] = $output;
		} else {
			$data['count'] = 0;
			$data['output'] = '<h2 style="text-align:center;">No data found</h2>';
		}

		echo json_encode($data);

		exit();
	}
	
	function monitoring_ticket()
	{
		//echo "Monitoring Ticket Page";
		$url = $_SERVER['REQUEST_URI'];
		header("Refresh: 300; URL=\"" . $url . "\"");

		$title = 'MONITORING TICKET HELPDESK';
		date_default_timezone_set("Asia/Bangkok");
		$datenow = date('Y-m-d H:i:s');
		$date = date('d M Y');
		$year = date('Y');
		$info = [];
		
		#1 PROSES TIKET HARI INI DIBAWAH SATU JAM
		//$sql = Tickets::where(DB::raw("date(date)"), $yesterday)->where(DB::raw("left(timediff(solvedate, date), 2)"), '00')->count();
		$sql = " SELECT a.*, g.name AS request_name, d.name as watcher, timediff('{$datenow}', e.date_mod) AS selisih_waktu, e.date_mod AS date_mod_first
					FROM ".$this->gt." a 
						LEFT JOIN ".$this->gtu." b ON b.tickets_id = a.id AND b.type = 1
						LEFT JOIN ".$this->gu." g ON g.id = b.users_id
						LEFT JOIN ".$this->gtu." c ON c.tickets_id = a.id AND c.type = 3
						LEFT JOIN ".$this->gu." d ON d.id = c.users_id
						LEFT JOIN ".$this->gl." e ON a.id = e.items_id AND e.id_search_option = 12 AND old_value = 'New' AND new_value = 'Processing (assigned)'
					WHERE a.status NOT IN ('SOLVED','CLOSED') AND date(a.date) = curdate()
				ORDER BY timediff('{$datenow}', e.date_mod) DESC ";
		$proses_dibawah_satu_jam = DB::SELECT($sql);
		//echo "<pre>"; print_r($proses_dibawah_satu_jam); die();
		if( $proses_dibawah_satu_jam )
		{
			$pdsj = [];
			foreach( $proses_dibawah_satu_jam as $k => $v )
			{
				$sw = explode(':',$v['selisih_waktu']);
				//echo "<pre>"; print_r($sw); 
				$sjam = @$sw[0];
				$smenit = @$sw[1];
				$selisih = 60 - @$smenit;
				
				if( $sjam == '00' )
				{
					//echo "<pre>"; print_r($v); 
					$pdsj[] = array(
						'id' => $v['id'],
						'status' => $v['status'],
						'request_name' =>$v['request_name'],
						'name' => $v['name'],
						'content' => $v['content'],
						'watcher' => $v['watcher'],
						'assign' => $this->get_assign($v['id']),
						'count_down_timer' => '',
						'date_mod' => $v['date_mod'],
						'date' => $v['date'],
						'selisih_waktu' => '<h1 style="color:red;font-weight:bold">'.$selisih.'</h1>'.$v['selisih_waktu'].'<br/>Time Assign : '.$v['date_mod_first'].''
					);
				}
				
			}
			//die();
		}
		
		#2 ASSIGN TIKET PERHARI
		$sql2 = " select distinct(a.users_id), b.name as assign, 
							count(a.tickets_id) as jumlah_tiket_assign, 
							SUM(IF(c.status = 'solved', 1, 0)) AS jumlah_tiket_solved,
							SUM(IF(c.status = 'assign', 1, 0)) AS outstanding_tiket
					from glpi_tickets_users a 
						left join glpi_users b on a.users_id = b.id 
						left join glpi_tickets c on a.tickets_id = c.id
					where c.status in ('solved','assign') and a.`type` = 2 AND date(c.date) = curdate()
					group by a.users_id order by count(a.tickets_id) desc, outstanding_tiket desc, b.name ";
		$atp = DB::SELECT($sql2);
		
		#3 TOTAL TIKET HARI INI
		$sql3 = " select count(*) as total from glpi_tickets where date(date) = date(now()) ";
		$total_ticket = DB::SELECT($sql3);
		$total_ticket = @$total_ticket[0]['total'];
		//echo "<pre>"; print_r($total_ticket); die();
		
		return view('layouts.monitoring-ticket', compact('total_ticket','atp','pdsj','title','info','date'));
		
	}
	
	function get_assign($tickets_id)
	{
		$sql = " SELECT b.name AS assign FROM {$this->gtu} a 
					LEFT JOIN {$this->gu} b ON a.users_id = b.id AND a.type = 2
					WHERE a.tickets_id = $tickets_id ORDER BY a.id desc";
		$data = DB::SELECT($sql);
		//echo "<pre>"; print_r($data); die();
		$name = '';
		if( $data )
		{
			foreach( $data as $k => $v )
			{
				//echo "<pre>"; print_r($v);
				$name .= $v['assign'].'<br/>';
			}
			//die();
		}
		
		return rtrim($name,',');;
	}
	
	function monitoring_ticket_page_two()
	{
		//echo "MONITORING TICKET PAGE 2"; die();
		$url = $_SERVER['REQUEST_URI'];
		header("Refresh: 300; URL=\"" . $url . "\"");

		$title = 'MONITORING TICKET HELPDESK';
		date_default_timezone_set("Asia/Bangkok");
		$datenow = date('Y-m-d H:i:s');
		$date = date('d M Y');
		$year = date('Y');
		$info = [];
		
		#1 PROSES TIKET HARI INI DIBAWAH SATU JAM
		//$sql = Tickets::where(DB::raw("date(date)"), $yesterday)->where(DB::raw("left(timediff(solvedate, date), 2)"), '00')->count();
		$sql = " SELECT a.*, g.name AS request_name, d.name as watcher, timediff('{$datenow}', a.date) AS selisih_waktu, '' AS date_mod_first
					FROM ".$this->gt." a 
						LEFT JOIN ".$this->gtu." b ON b.tickets_id = a.id AND b.type = 1
						LEFT JOIN ".$this->gu." g ON g.id = b.users_id
						LEFT JOIN ".$this->gtu." c ON c.tickets_id = a.id AND c.type = 3
						LEFT JOIN ".$this->gu." d ON d.id = c.users_id
					WHERE a.status NOT IN ('SOLVED','CLOSED','REJECTED','WAITING') AND DATE(a.date) = DATE(NOW() - INTERVAL 1 DAY)
				ORDER BY timediff('{$datenow}', a.date) DESC  ";
		$proses_dibawah_satu_jam = DB::SELECT($sql);
		//echo "<pre>"; print_r($proses_dibawah_satu_jam); die();
		if( $proses_dibawah_satu_jam )
		{
			$pdsj = [];
			foreach( $proses_dibawah_satu_jam as $k => $v )
			{
				$sw = explode(':',$v['selisih_waktu']);
				//echo "<pre>"; print_r($sw); 
				$sjam = @$sw[0];
				$smenit = @$sw[1];
				//$selisih = @$sw[0].':'.@$smenit;
				$selisih = @$sw[0];
				
				if( $sjam != '00' )
				{
					//echo "<pre>"; print_r($v); 
					$pdsj[] = array(
						'id' => $v['id'],
						'status' => $v['status'],
						'request_name' =>$v['request_name'],
						'name' => $v['name'],
						'content' => $v['content'],
						'watcher' => $v['watcher'],
						'assign' => $this->get_assign($v['id']),
						'count_down_timer' => '',
						'date_mod' => $v['date_mod'],
						'date' => $v['date'],
						'selisih_waktu' => '<h1 style="color:red;font-weight:bold">'.$selisih.'</h1>'
					);
				}
				
			}
			//die();
		}
		
		return view('layouts.monitoring-ticket-2', compact('pdsj','title','info','date'));
	}
	
	function monitoring_ticket_page_three()
	{
		//echo "MONITORING TICKET PAGE 3"; die();
		$url = $_SERVER['REQUEST_URI'];
		header("Refresh: 300; URL=\"" . $url . "\"");

		$title = 'MONITORING TICKET HELPDESK';
		date_default_timezone_set("Asia/Bangkok");
		$datenow = date('Y-m-d H:i:s');
		$date = date('d M Y');
		$year = date('Y');
		$info = [];
		
		#1 PROSES TIKET HARI INI DIBAWAH SATU JAM
		//$sql = Tickets::where(DB::raw("date(date)"), $yesterday)->where(DB::raw("left(timediff(solvedate, date), 2)"), '00')->count();
		$sql = " SELECT a.*, g.name AS request_name, d.name as watcher, datediff('{$datenow}', a.date) AS selisih_waktu, '' AS date_mod_first
					FROM ".$this->gt." a 
						LEFT JOIN ".$this->gtu." b ON b.tickets_id = a.id AND b.type = 1
						LEFT JOIN ".$this->gu." g ON g.id = b.users_id
						LEFT JOIN ".$this->gtu." c ON c.tickets_id = a.id AND c.type = 3
						LEFT JOIN ".$this->gu." d ON d.id = c.users_id
					WHERE a.status NOT IN ('SOLVED','CLOSED','REJECTED','WAITING') AND DATE(a.date) < DATE(NOW() - INTERVAL 1 DAY)
				ORDER BY a.id DESC ";
		$proses_dibawah_satu_jam = DB::SELECT($sql);
		//echo "<pre>"; print_r($proses_dibawah_satu_jam); die();
		if( $proses_dibawah_satu_jam )
		{
			$pdsj = [];
			foreach( $proses_dibawah_satu_jam as $k => $v )
			{
				$sw = explode(':',$v['selisih_waktu']);
				//echo "<pre>"; print_r($sw); 
				$sjam = @$sw[0];
				$smenit = @$sw[1];
				//$selisih = @$sw[0].':'.@$smenit;
				$selisih = @$sw[0];//ceil(@$sw[0]/24);
				
				if( $sjam != '00' )
				{
					//echo "<pre>"; print_r($v); 
					$pdsj[] = array(
						'id' => $v['id'],
						'status' => $v['status'],
						'request_name' =>$v['request_name'],
						'name' => $v['name'],
						'content' => $v['content'],
						'watcher' => $v['watcher'],
						'assign' => $this->get_assign($v['id']),
						'count_down_timer' => '',
						'date_mod' => $v['date_mod'],
						'date' => $v['date'],
						'selisih_waktu' => '<h1 style="color:red;font-weight:bold">'.$selisih.'</h1>'
					);
				}
				
			}
			//die();
		}
		
		return view('layouts.monitoring-ticket-3', compact('pdsj','title','info','date'));
	}
	
	function monitoring_ticket_page_one()
	{
		//echo "Monitoring Ticket Page 1"; die();
		$url = $_SERVER['REQUEST_URI'];
		header("Refresh: 300; URL=\"" . $url . "\"");

		$title = 'MONITORING TICKET HELPDESK';
		date_default_timezone_set("Asia/Bangkok");
		$datenow = date('Y-m-d H:i:s');
		$date = date('d M Y');
		$year = date('Y');
		$info = [];
		
		###### 1 PROSES TIKET HARI INI DIBAWAH SATU JAM
		/*
		$sql = " SELECT a.*, g.name AS request_name, d.name as watcher, timediff('{$datenow}', e.date_mod) AS selisih_waktu, e.date_mod AS date_mod_first
					FROM ".$this->gt." a 
						LEFT JOIN ".$this->gtu." b ON b.tickets_id = a.id AND b.type = 1
						LEFT JOIN ".$this->gu." g ON g.id = b.users_id
						LEFT JOIN ".$this->gtu." c ON c.tickets_id = a.id AND c.type = 3
						LEFT JOIN ".$this->gu." d ON d.id = c.users_id
						LEFT JOIN ".$this->gl." e ON a.id = e.items_id AND e.id_search_option = 12 AND old_value = 'New' AND new_value = 'Processing (assigned)'
					WHERE a.status NOT IN ('SOLVED','CLOSED') AND date(a.date) = curdate()
				ORDER BY timediff('{$datenow}', e.date_mod) DESC ";
		*/
		
		/* hide:#250319
		$sql = " SELECT * FROM ( SELECT a.*, g.name AS request_name, d.name as watcher, timediff('{$datenow}', e.date_mod) AS selisih_waktu, e.date_mod AS date_mod_first
					FROM ".$this->gt." a 
						LEFT JOIN ".$this->gtu." b ON b.tickets_id = a.id AND b.type = 1
						LEFT JOIN ".$this->gu." g ON g.id = b.users_id
						LEFT JOIN ".$this->gtu." c ON c.tickets_id = a.id AND c.type = 3
						LEFT JOIN ".$this->gu." d ON d.id = c.users_id
						LEFT JOIN ".$this->gl." e ON a.id = e.items_id AND e.id_search_option = 12 AND old_value = 'New' AND new_value = 'Processing (assigned)'
					WHERE a.status NOT IN ('SOLVED','CLOSED') AND date(a.date) = curdate()
				ORDER BY timediff('{$datenow}', e.date_mod) DESC ) AS K GROUP BY ID ORDER BY selisih_waktu DESC ";
		*/
		
		// SELISIH WAKTU BERDASARKAN PERTAMA KALI TIKET DIBUAT (DATE) 
		$sql = " SELECT * FROM ( SELECT a.*, g.name AS request_name, d.name as watcher, timediff('{$datenow}', a.date) AS selisih_waktu
					FROM ".$this->gt." a 
						LEFT JOIN ".$this->gtu." b ON b.tickets_id = a.id AND b.type = 1
						LEFT JOIN ".$this->gu." g ON g.id = b.users_id
						LEFT JOIN ".$this->gtu." c ON c.tickets_id = a.id AND c.type = 3
						LEFT JOIN ".$this->gu." d ON d.id = c.users_id
					WHERE a.status NOT IN ('SOLVED','CLOSED') AND date(a.date) = curdate()
				ORDER BY timediff('{$datenow}', a.date) DESC ) AS K GROUP BY ID ORDER BY selisih_waktu DESC ";
		
		//echo $sql; die();
		$proses_dibawah_satu_jam = DB::SELECT($sql);
		//echo "<pre>"; print_r($proses_dibawah_satu_jam); die();
		if( $proses_dibawah_satu_jam )
		{
			$pdsj = [];
			$ott = [];
			foreach( $proses_dibawah_satu_jam as $k => $v )
			{
				$sw = explode(':',$v['selisih_waktu']);
				//echo "<pre>"; print_r($sw); 
				$sjam = @$sw[0];
				$smenit = @$sw[1];
				$selisih = 60 - @$smenit;
				
				if( $sjam == '00' || $v['status'] == 'new' )
				{
					//echo "<pre>"; print_r($v); 
					$pdsj[] = array(
						'id' => $v['id'],
						'status' => $v['status'],
						'request_name' =>$v['request_name'],
						'name' => $v['name'],
						'content' => $v['content'],
						'watcher' => $v['watcher'],
						'assign' => $this->get_assign($v['id']),
						'count_down_timer' => '',
						'date_mod' => $v['date_mod'],
						'date' => $v['date'],
						'selisih_waktu' => '<h2 style="color:red;font-weight:bold">'.$selisih.'</h2>'
					);
				}
				else
				{
					$ott[] = array(
						'id' => $v['id'],
						'status' => $v['status'],
						'request_name' =>$v['request_name'],
						'name' => $v['name'],
						'content' => $v['content'],
						'watcher' => $v['watcher'],
						'assign' => $this->get_assign($v['id']),
						'count_down_timer' => '',
						'date_mod' => $v['date_mod'],
						'date' => $v['date'],
					);
				}
				
			}
			//die();
		}
		
		###### 2 ASSIGN TIKET PERHARI
		$sql2 = " select distinct(a.users_id), b.name as assign, 
							count(a.tickets_id) as jumlah_tiket_assign, 
							SUM(IF(c.status = 'solved', 1, 0)) AS jumlah_tiket_solved,
							SUM(IF(c.status = 'assign', 1, 0)) AS outstanding_tiket
					from glpi_tickets_users a 
						left join glpi_users b on a.users_id = b.id 
						left join glpi_tickets c on a.tickets_id = c.id
					where c.status in ('solved','assign') and a.`type` = 2 AND date(c.date) = DATE(now())
					group by a.users_id order by count(a.tickets_id) desc, outstanding_tiket desc, b.name ";
		$atp = DB::SELECT($sql2);
		
		###### 3 TOTAL TIKET HARI INI
		$sql3 = " select count(*) as total from glpi_tickets where date(date) = date(now()) ";
		$total_ticket = DB::SELECT($sql3);
		$total_ticket = @$total_ticket[0]['total'];
		//echo "<pre>"; print_r($total_ticket); die();
		
		###### 4 TIKET DIATAS SATU JAM
		/*$sql4 = " SELECT a.*, g.name AS request_name, d.name as watcher, timediff('{$datenow}', a.date) AS selisih_waktu, '' AS date_mod_first
					FROM ".$this->gt." a 
						LEFT JOIN ".$this->gtu." b ON b.tickets_id = a.id AND b.type = 1
						LEFT JOIN ".$this->gu." g ON g.id = b.users_id
						LEFT JOIN ".$this->gtu." c ON c.tickets_id = a.id AND c.type = 3
						LEFT JOIN ".$this->gu." d ON d.id = c.users_id
					WHERE a.status NOT IN ('SOLVED','CLOSED','REJECTED','WAITING') AND DATE(a.date) = DATE(NOW() - INTERVAL 1 DAY)
				ORDER BY timediff('{$datenow}', a.date) DESC  ";*/
		$sql4 = " SELECT a.*, g.name AS request_name, timediff('{$datenow}', a.date) AS selisih_waktu, '' AS date_mod_first
					FROM ".$this->gt." a 
						LEFT JOIN ".$this->gtu." b ON b.tickets_id = a.id AND b.type = 1
						LEFT JOIN ".$this->gu." g ON g.id = b.users_id
					WHERE a.status NOT IN ('SOLVED','CLOSED','REJECTED','WAITING') AND DATE(a.date) = DATE(NOW() - INTERVAL 1 DAY)
				ORDER BY timediff('{$datenow}', a.date) DESC  ";
		//echo $sql4; die();
		$proses_dibawah_satu_jam = DB::SELECT($sql4);
		if( $proses_dibawah_satu_jam )
		{
			$dibawah_satu_jam = [];
			foreach( $proses_dibawah_satu_jam as $k => $v )
			{
				$sw = explode(':',$v['selisih_waktu']);
				//echo "<pre>"; print_r($sw); 
				$sjam = @$sw[0];
				$smenit = @$sw[1];
				//$selisih = @$sw[0].':'.@$smenit;
				$selisih = @$sw[0];
				
				if( $sjam != '00' )
				{
					//echo "<pre>"; print_r($v); 
					$dibawah_satu_jam[] = array(
						'id' => $v['id'],
						'status' => $v['status'],
						'request_name' =>$v['request_name'],
						'name' => $v['name'],
						'content' => $v['content'],
						//'watcher' => $v['watcher'],
						'assign' => $this->get_assign($v['id']),
						'count_down_timer' => '',
						'date_mod' => $v['date_mod'],
						'date' => $v['date'],
						'selisih_waktu' => '<h1 style="color:red;font-weight:bold">'.$selisih.'</h1>'
					);
				}
				
			}
			//die();
		}
		
		###### 5 TIKET > 1 HARI
		$sql5 = " SELECT a.*, g.name AS request_name, d.name as watcher, datediff('{$datenow}', a.date) AS selisih_waktu, '' AS date_mod_first
					FROM ".$this->gt." a 
						LEFT JOIN ".$this->gtu." b ON b.tickets_id = a.id AND b.type = 1
						LEFT JOIN ".$this->gu." g ON g.id = b.users_id
						LEFT JOIN ".$this->gtu." c ON c.tickets_id = a.id AND c.type = 3
						LEFT JOIN ".$this->gu." d ON d.id = c.users_id
					WHERE a.status NOT IN ('SOLVED','CLOSED','REJECTED','WAITING') 
							AND DATE(a.date) < DATE(NOW() - INTERVAL 1 DAY) 
							AND MONTH(a.date) = MONTH(CURRENT_DATE()) 
							AND YEAR(a.date) = YEAR(CURRENT_DATE())
				ORDER BY a.id ASC ";
		$lsh = DB::SELECT($sql5);
		if( $lsh )
		{
			$lebih_satu_hari = [];
			foreach( $lsh as $k => $v )
			{
				$sw = explode(':',$v['selisih_waktu']);
				$sjam = @$sw[0];
				$smenit = @$sw[1];
				$selisih = @$sw[0];
				
				if( $sjam != '00' )
				{
					$lebih_satu_hari[] = array(
						'id' => $v['id'],
						'status' => $v['status'],
						'request_name' =>$v['request_name'],
						'name' => $v['name'],
						'content' => $v['content'],
						'watcher' => $v['watcher'],
						'assign' => $this->get_assign($v['id']),
						'count_down_timer' => '',
						'date_mod' => $v['date_mod'],
						'date' => $v['date'],
						'selisih_waktu' => '<h2 style="color:red;font-weight:bold">'.$selisih.'</h2>'
					);
				}
				
			}
		}
		
		return view('layouts.monitoring-ticket-1', compact('ott','lebih_satu_hari','dibawah_satu_jam','total_ticket','atp','pdsj','title','info','date'));
		
	}
	
}