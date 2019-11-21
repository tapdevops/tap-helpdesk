<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Tickets;

class DashboardzController extends Controller
{
	public function __construct() {
		date_default_timezone_set('Asia/Jakarta');
		$this->oci = DB::connection('oracle');
		$this->gt = 'glpi_tickets';
		$this->gtu = 'glpi_tickets_users';
		$this->gu = 'glpi_users';
	}
	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
	public function index()
	{
		$title = 'Dashboard Early Warning System (EWS) Helpdesk';
		$date = date('d M Y');

		DB::enableQueryLog();
		DB::connection('oracle')->enableQueryLog();


		
		// ******************** GET LAST HOLIDAY DATE ******************** //
		$nowDate = strtoupper(date('d-M-y'));
		//$nowDate = '13-FEB-17';
		//echo $nowDate; echo '<br />';
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

		$dateBefore = strtoupper(date('j', strtotime($output . '-1 day')));
		$dateBefore = (int) $dateBefore;

		$finalDate = strtoupper(date('Y-m-d', strtotime($output . ' -1 day')));

		$currentTime = date('Y-m-d H:i:s');
		$convertCurrentTime = strtotime($currentTime);

		$beforeMidnight = $finalDate . ' 23:59:59';
		$startMidnight = date('Y-m-d 00:00:00');
		$endMidnight = date('Y-m-d 07:59:59');
		$convertBeforeMidnight = strtotime($beforeMidnight);
		$convertStartMidnight = strtotime($startMidnight);
		$convertEndMidnight = strtotime($endMidnight);

		if ($dateBefore >= 1 && $dateBefore <= 5) {
			/* < Yesterday 16:00:00 or 22:00:00 */
			$beforeYesterday = $finalDate . ' 22:00:00';

			/* Yesterday from 16:00:01 - 16:00:59 or 22:00:01 - 22:00:59 */
			$startClosingYesterday = $finalDate . ' 22:00:01';
			$endClosingYesterday = $finalDate . ' 22:00:59';
			$convertStartClosingYesterday = strtotime($startClosingYesterday);
			$convertEndClosingYesterday = strtotime($endClosingYesterday);

			/* From Yesterday 16:01:00 - Today 07:59:59 or Yesterday 22:01:00 - Today 07:59:59 */
			$startAfterWork = $finalDate . ' 22:01:00';
			$endAfterWork = date('Y-m-d 07:59:59');

			/* Today from 08:00:00 - 16:00:00 */
			$startToday = date('Y-m-d 08:00:00');
			$endToday = date('Y-m-d 22:00:00');
			$convertStartToday = strtotime($startToday);
			$convertEndToday = strtotime($endToday);

			/* Today from 16:00:01 - 23:59:59 or 22:00:01 - 23:59:59 */
			$startAfterWorkToday = date('Y-m-d 22:00:01');
			$endAfterWorkToday = date('Y-m-d 23:59:59');
			$convertStartAfterWorkToday = strtotime($startAfterWorkToday);
			$convertEndAfterWorkToday = strtotime($endAfterWorkToday);
		} else {
			/* < Yesterday 16:00:00 or 22:00:00 */
			$beforeYesterday = $finalDate . ' 16:00:00';

			/* Yesterday from 16:00:01 - 16:00:59 or 22:00:01 - 22:00:59 */
			$startClosingYesterday = $finalDate . ' 16:00:01';
			$endClosingYesterday = $finalDate . ' 16:00:59';
			$convertStartClosingYesterday = strtotime($startClosingYesterday);
			$convertEndClosingYesterday = strtotime($endClosingYesterday);

			/* From Yesterday 16:01:00 - Today 07:59:59 or Yesterday 22:01:00 - Today 07:59:59 */
			$startAfterWork = $finalDate . ' 16:01:00';
			$endAfterWork = date('Y-m-d 07:59:59');

			/* Today from 08:00:00 - 16:00:00 */
			$startToday = date('Y-m-d 08:00:00');
			$endToday = date('Y-m-d 16:00:00');
			$convertStartToday = strtotime($startToday);
			$convertEndToday = strtotime($endToday);

			/* Today from 16:00:01 - 23:59:59 or 22:00:01 - 23:59:59 */
			$startAfterWorkToday = date('Y-m-d 16:00:01');
			$endAfterWorkToday = date('Y-m-d 23:59:59');
			$convertStartAfterWorkToday = strtotime($startAfterWorkToday);
			$convertEndAfterWorkToday = strtotime($endAfterWorkToday);
		}

		/* Status Data */
		$new = 'new';
		$assign = 'assign';

		/* Total Before Yesterday => < Yesterday 16:00:00 */
		$totalBeforeYesterday = DB::select("
			SELECT 
				(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date < '{$beforeYesterday}') + 
				(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod < '{$beforeYesterday}')
				AS total
			FROM dual
		");
		$totalBeforeYesterday = $totalBeforeYesterday[0]['total'];
		//echo $totalBeforeYesterday;

		/* Total Range 16:00:01 - 16:00:59 or 22:00:01 - 22:00:59 */
		$totalYesterday = DB::select("
			SELECT 
				(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startClosingYesterday' AND '$endClosingYesterday') + 
				(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startClosingYesterday' AND '$endClosingYesterday')
				AS total
			FROM dual
		");
		$totalYesterday = $totalYesterday[0]['total'];

		/* Total After Work => Yesterday 16:01:00 - Today 07:59:59 or Yesterday 22:01:00 - Today 07:59:59 */
		$totalAfterWork = DB::select("
			SELECT
				(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startAfterWork' AND '$endAfterWork') + 
				(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startAfterWork' AND '$endAfterWork') 
				AS total
			FROM dual
		");
		$totalAfterWork = $totalAfterWork[0]['total'];


		// ========== MTR ========== //

		$startHalfHour = strtotime(date('Y-m-d 08:00:00'));
		$endHalfHour = strtotime(date('Y-m-d 08:30:00'));
		$startMiddleHour = strtotime(date('Y-m-d 08:30:01'));
		$endMiddleHour = strtotime(date('Y-m-d 09:00:00'));
		$upAnHour = strtotime(date('Y-m-d 09:00:01'));

		$afterWorkGreen = $afterWorkOrange = $afterWorkRed = 0;
		if ($convertCurrentTime >= $startHalfHour && $convertCurrentTime <= $endHalfHour) {
			$afterWorkGreen = $totalAfterWork;
		} else if ($convertCurrentTime >= $startMiddleHour && $convertCurrentTime <= $endMiddleHour) {
			$afterWorkOrange = $totalAfterWork;
		} else if ($convertCurrentTime >= $upAnHour) {
			$afterWorkRed = $totalAfterWork;
		}

		// current time antara 00:00:00 - 07:59:59
		if ($convertCurrentTime >= $convertStartMidnight && $convertCurrentTime <= $convertEndMidnight) {
			//echo 'a';
			if ($dateBefore >= 1 && $dateBefore <= 5) {
				$anHour = date($finalDate . ' 21:00:00');
				$halfHour = date($finalDate . ' 21:30:00');
				$upHalf = date($finalDate . ' 21:30:01');
			} else {
				$anHour = date($finalDate . ' 15:00:00');
				$halfHour = date($finalDate . ' 15:30:00');
				$upHalf = date($finalDate . ' 15:30:01');
			}

			$totalResGreen = DB::select("
				SELECT 
					(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$upHalf' AND '$beforeYesterday') + 
					(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$upHalf' AND '$beforeYesterday')
					AS total
				FROM dual
			");

			$totalResOrange = DB::select("
				SELECT 
					(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$anHour' AND '$halfHour') + 
					(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$anHour' AND '$halfHour') 
					AS total
				FROM dual
			");

			$totalResRed = DB::select("
				SELECT 
					(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date < '$anHour') + 
					(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod < '$anHour') 
					AS total 
				FROM dual
			");

			$totalResGreen = $totalResGreen[0]['total'] + $totalAfterWork;
			$totalResOrange = $totalResOrange[0]['total'] + $afterWorkOrange;
			$totalResRed = $totalResRed[0]['total'] + $afterWorkRed;

		// current time antara 08:00:00 - 16:00:00
		} else if ($convertCurrentTime >= $convertStartToday && $convertCurrentTime <= $convertEndToday) {
			// current time antara 08:00:00 - 08:30:00
			if ($convertCurrentTime > $startHalfHour && $convertCurrentTime < $endHalfHour) {
				$totalResGreen = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startToday' AND '$currentTime') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startToday' AND '$currentTime') 
						AS total
					FROM dual
				");

				$totalResGreen = $totalResGreen[0]['total'] + $afterWorkGreen;
				$totalResOrange = 0 + $afterWorkOrange;
				$totalResRed = $totalBeforeYesterday + $totalYesterday + $afterWorkRed;

			// current time antara 08:30:00 - 09:00:00
			} else if ($convertCurrentTime > $startMiddleHour && $convertCurrentTime < $endMiddleHour) {
				$halfHour = date('Y-m-d H:i:s', strtotime(' -30 minute'));
				$anHour = date('Y-m-d H:i:s', strtotime(' -60 minute'));

				$totalResGreen = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$halfHour' AND '$currentTime') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$halfHour' AND '$currentTime') 
						AS total
					FROM dual
				");

				$totalResOrange = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startToday' AND '$halfHour') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startToday' AND '$halfHour') 
						AS total 
					FROM dual
				");

				$totalResGreen = $totalResGreen[0]['total'] + $afterWorkGreen;
				$totalResOrange = $totalResOrange[0]['total'] + $afterWorkOrange;
				$totalResRed = $totalBeforeYesterday + $totalYesterday + $afterWorkRed;

			// curren ttime diatas 09:00:00
			} else if ($convertCurrentTime >= $upAnHour) {
				$halfHour = date('Y-m-d H:i:s', strtotime(' -30 minute'));
				$anHour = date('Y-m-d H:i:s', strtotime(' -60 minute'));

				$totalResGreen = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$halfHour' AND '$currentTime') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$halfHour' AND '$currentTime') 
						AS total 
					FROM dual
				");

				$totalResOrange = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$anHour' AND '$halfHour') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$anHour' AND '$halfHour') 
						AS total 
					FROM dual
				");

				$totalResRed = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startToday' AND '$anHour') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startToday' AND '$anHour') 
						AS total 
					FROM dual
				");

				$totalResGreen = $totalResGreen[0]['total'] + $afterWorkGreen;
				$totalResOrange = $totalResOrange[0]['total'] + $afterWorkOrange;
				$totalResRed = $totalResRed[0]['total'] + $totalBeforeYesterday + $totalYesterday + $afterWorkRed;
			}

		// current time antara 16:00:01 - 23:59:59
		} else if ($convertCurrentTime >= $convertStartAfterWorkToday && $convertCurrentTime <= $convertEndAfterWorkToday) {
			if ($dateBefore >= 1 && $dateBefore <= 5) {
				$anHour = date('Y-m-d 21:00:00');
				$upHour = date('Y-m-d 21:00:01');
				$halfHour = date('Y-m-d 21:30:00');
				$upHalf = date('Y-m-d 21:30:01');
			} else {
				$anHour = date('Y-m-d 15:00:00');
				$upHour = date('Y-m-d 15:00:01');
				$halfHour = date('Y-m-d 15:30:00');
				$upHalf = date('Y-m-d 15:30:01');
			}

			$totalResGreen = DB::select("
				SELECT 
					(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$upHalf' AND '$endToday') + 
					(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$upHalf' AND '$endToday') 
					AS total
				FROM dual
			");

			$totalResOrange = DB::select("
				SELECT 
					(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$upHour' AND '$halfHour') + 
					(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$upHour' AND '$halfHour') 
					AS total
				FROM dual
			");

			$totalResRed = DB::select("
				SELECT 
					(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startToday' AND '$anHour') + 
					(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startToday' AND '$anHour') 
					AS total 
				FROM dual
			");

			$totalResGreen = $totalResGreen[0]['total'] + $afterWorkGreen;
			$totalResOrange = $totalResOrange[0]['total'] + $afterWorkOrange;
			$totalResRed = $totalResRed[0]['total'] + $totalBeforeYesterday + $totalYesterday + $afterWorkRed;
		}


		// MTS
		if ($dateBefore >= 1 && $dateBefore <= 5) {
			$startMTS = strtotime(date('Y-m-d 09:00:00'));
			$startSeperempat = strtotime(date('Y-m-d 14:00:00'));
			$endSeperempat = strtotime(date('Y-m-d 15:00:00'));
			$startDuaperempat = strtotime(date('Y-m-d 15:00:01'));
			$endDuaperempat = strtotime(date('Y-m-d 18:00:00'));
			$startTigaperempat = strtotime(date('Y-m-d 18:00:01'));
			$endTigaperempat = strtotime(date('Y-m-d 22:00:00'));
			$upPerempat = strtotime(date('Y-m-d 22:00:01'));
		} else {
			$startMTS = strtotime(date('Y-m-d 09:00:00'));
			$startSeperempat = strtotime(date('Y-m-d 08:00:00'));
			$endSeperempat = strtotime(date('Y-m-d 09:00:00'));
			$startDuaperempat = strtotime(date('Y-m-d 09:00:01'));
			$endDuaperempat = strtotime(date('Y-m-d 12:00:00'));
			$startTigaperempat = strtotime(date('Y-m-d 12:00:01'));
			$endTigaperempat = strtotime(date('Y-m-d 16:00:00'));
			$upPerempat = strtotime(date('Y-m-d 16:00:01'));
		}

		$solAfterWorkGreen = $solAfterWorkOrange = $solAfterWorkRed = 0;
		if ($convertCurrentTime >= $startSeperempat && $convertCurrentTime <= $endSeperempat) {
			$solAfterWorkGreen = $solAfterWorkOrange = $solAfterWorkRed = 0;
		} else if ($convertCurrentTime >= $startDuaperempat && $convertCurrentTime <= $endDuaperempat) {
			$solAfterWorkGreen = $totalAfterWork;
		} else if ($convertCurrentTime >= $startTigaperempat && $convertCurrentTime <= $endTigaperempat) {
			$solAfterWorkOrange = $totalAfterWork;
		} else if ($convertCurrentTime >= $upPerempat) {
			$solAfterWorkRed = $totalAfterWork;
		}

		if ($dateBefore >= 1 && $dateBefore <= 5) {
			// current time antara 22:00:01 - 23:59:59
			if ($convertCurrentTime >= $convertStartClosingYesterday && $convertCurrentTime <= $convertBeforeMidnight) {
				$satuPerempat = date('Y-m-d 21:00:00');
				$duaPerempat = date('Y-m-d 18:00:00');
				$tigaPerempat = date('Y-m-d 14:00:00');

				$totalSolGreen = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$duaPerempat' AND '$satuPerempat') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$duaPerempat' AND '$satuPerempat') 
						AS total 
					FROM dual
				");

				$totalSolOrange = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$tigaPerempat' AND '$duaPerempat') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$tigaPerempat' AND '$duaPerempat') 
						AS total 
					FROM dual
				");

				$totalSolRed = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startToday' AND '$tigaPerempat') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startToday' AND '$tigaPerempat') 
						AS total 
					FROM dual
				");

				$totalSolGreen = $totalSolGreen[0]['total'] + $solAfterWorkGreen;
				$totalSolOrange = $totalSolOrange[0]['total'] + $solAfterWorkOrange;
				$totalSolRed = $totalSolRed[0]['total'] + $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;

			// current time antara 08:00:00 - 22:00:00
			} else if ($convertCurrentTime >= $convertStartToday && $convertCurrentTime <= $endTigaperempat) {
				$satuPerempat = date('Y-m-d H:i:s', strtotime(' -1 hour'));
				$duaPerempat = date('Y-m-d H:i:s', strtotime(' -4 hour'));
				$tigaPerempat = date('Y-m-d H:i:s', strtotime(' -8 hour'));

				$totalSolGreen = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$duaPerempat' AND '$satuPerempat') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$duaPerempat' AND '$satuPerempat') 
						AS total 
					FROM dual
				");

				if (strtotime($tigaPerempat) < $convertStartToday) $tigaPerempat = $startToday;
				$totalSolOrange = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$tigaPerempat' AND '$duaPerempat') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$tigaPerempat' AND '$duaPerempat') 
						AS total 
					FROM dual
				");

				if ($tigaPerempat == $startToday) {
					$totalSolRed = $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;
				} else {
					$totalSolRed = DB::select("
						SELECT 
							(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startToday' AND '$tigaPerempat') + 
							(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startToday' AND '$tigaPerempat') 
							AS total 
						FROM dual
					");
					$totalSolRed = $totalSolRed[0]['total'] + $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;
				}

				$totalSolGreen = $totalSolGreen[0]['total'] + $solAfterWorkGreen;
				$totalSolOrange = $totalSolOrange[0]['total'] + $solAfterWorkOrange;
				$totalSolRed = $totalSolRed;
			// current time antara 00:00:00 - 07:59:59
			} else if ($convertCurrentTime >= $convertStartMidnight && $convertCurrentTime <= $convertEndMidnight) {
				$satuPerempat = date($finalDate . ' 21:00:00');
				$duaPerempat = date($finalDate . ' 18:00:00');
				$tigaPerempat = date($finalDate . ' 14:00:00');
				$start = date($finalDate . ' 08:00:00');

				$totalSolGreen = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$duaPerempat' AND '$satuPerempat') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$duaPerempat' AND '$satuPerempat') 
						AS total 
					FROM dual
				");

				$totalSolOrange = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$tigaPerempat' AND '$duaPerempat') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$tigaPerempat' AND '$duaPerempat') 
						AS total 
					FROM dual
				");

				$totalSolRed = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date < '$tigaPerempat') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod < '$tigaPerempat') 
						AS total
					FROM dual
				");

				$totalSolGreen = $totalSolGreen[0]['total'] + $solAfterWorkGreen;
				$totalSolOrange = $totalSolOrange[0]['total'] + $solAfterWorkOrange;
				$totalSolRed = $totalSolRed[0]['total'] + $solAfterWorkRed;
			}
		} else {
			// current time antara 00:00:00 - 07:59:59
			if ($convertCurrentTime >= $convertStartMidnight && $convertCurrentTime <= $convertEndMidnight) {
				if ($dateBefore >= 1 && $dateBefore <= 5) {
					$oneHour = date($finalDate . ' 21:00:00');
					$startFourHour = date($finalDate . ' 18:00:01');
					$endFourHour = date($finalDate . ' 18:00:00');
					$startEightHour = date($finalDate . ' 14:00:01');
					$upEightHour = date($finalDate . ' 14:00:00');
				} else {
					$oneHour = date($finalDate . ' 15:00:00');
					$startFourHour = date($finalDate . ' 12:00:01');
					$endFourHour = date($finalDate . ' 12:00:00');
					$startEightHour = date($finalDate . ' 08:00:01');
					$upEightHour = date($finalDate . ' 08:00:00');
				}

				$totalSolGreen = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startFourHour' AND '$oneHour') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startFourHour' AND '$oneHour') 
						AS total 
					FROM dual
				");

				$totalSolOrange = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startEightHour' AND '$endFourHour') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startEightHour' AND '$endFourHour') 
						AS total 
					FROM dual
				");

				$totalSolRed = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date < '$upEightHour') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod < '$upEightHour') 
						AS total 
					FROM dual
				");

				$totalSolGreen = $totalSolGreen[0]['total'] + $solAfterWorkGreen;
				$totalSolOrange = $totalSolOrange[0]['total'] + $solAfterWorkOrange;
				$totalSolRed = $totalSolRed[0]['total'];

			// current time antara 08:00:00 - 09:00:00
			} else if ($convertCurrentTime >= $startSeperempat && $convertCurrentTime <= $endSeperempat) {
				$totalSolGreen = $solAfterWorkGreen;
				$totalSolOrange = $solAfterWorkOrange;
				$totalSolRed = $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;

			// current time antara 09:00:01 - 12:00:00
			} else if ($convertCurrentTime >= $startDuaperempat && $convertCurrentTime <= $endDuaperempat) {
				$startHour = date('Y-m-d H:i:s', strtotime(' -1 hour'));

				$totalSolGreen = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startToday' AND '$startHour') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startToday' AND '$startHour') 
						AS total 
					FROM dual
				");

				$totalSolGreen = $totalSolGreen[0]['total'] + $solAfterWorkGreen;
				$totalSolOrange = 0 + $solAfterWorkOrange;
				$totalSolRed = $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;

			// current time antara 12:00:01 - 16:00:00
			} else if ($convertCurrentTime >= $convertStartToday && $convertCurrentTime <= $convertEndToday) {
				$startHour = date('Y-m-d H:i:s', strtotime(' -1 hour'));
				$endHour = date('Y-m-d H:i:s', strtotime(' -4 hour'));
				$endsHour = date('Y-m-d H:i:s', strtotime(' -8 hour'));
				
				$totalSolGreen = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$endHour' AND '$startHour') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$endHour' AND '$startHour') 
						AS total 
					FROM dual
				");

				if ($dateBefore >= 1 && $dateBefore <= 5) {
					$totalSolOrange = DB::select("
						SELECT 
							(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$endsHour' AND '$endHour') + 
							(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$endsHour' AND '$endHour') 
							AS total 
						FROM dual
					");

					$totalSolRed = DB::select("
						SELECT 
							(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startToday' AND '$endsHour') + 
							(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startToday' AND '$endsHour') 
							AS total 
						FROM dual
					");
				} else {
					$totalSolOrange = DB::select("
						SELECT 
							(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startToday' AND '$endHour') + 
							(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startToday' AND '$endHour') 
							AS total 
						FROM dual
					");
				}

				//echo $totalBeforeYesterday; echo $totalYesterday; echo $solAfterWorkRed;
				$totalSolGreen = $totalSolGreen[0]['total'] + $solAfterWorkGreen;
				$totalSolOrange = $totalSolOrange[0]['total'] + $solAfterWorkOrange;
				if ($dateBefore >= 1 && $dateBefore <= 5) {
					$totalSolRed = $totalSolRed[0]['total'] + $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;
				} else {
					$totalSolRed = $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;
				}

			// current time antara 16:00:01 - 20:00:00

			// current time antara 16:00:01 - 23:59:59
			} else if ($convertCurrentTime >= $convertStartAfterWorkToday && $convertCurrentTime <= $convertEndAfterWorkToday) {
				if ($dateBefore >= 1 && $dateBefore <= 5) {
					$oneHour = date('Y-m-d 21:00:00');
					$startFourHour = date('Y-m-d 18:00:01');
					$endFourHour = date('Y-m-d 18:00:00');
					$startEightHour = date('Y-m-d 14:00:01');
					$upEightHour = date('Y-m-d 14:00:00');
				} else {
					$oneHour = date('Y-m-d 15:00:00');
					$startFourHour = date('Y-m-d 12:00:01');
					$endFourHour = date('Y-m-d 12:00:00');
					$startEightHour = date('Y-m-d 08:00:01');
					$upEightHour = date('Y-m-d 08:00:00');
				}

				$totalSolGreen = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startFourHour' AND '$oneHour') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startFourHour' AND '$oneHour') 
						AS total 
					FROM dual
				");

				$totalSolOrange = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startEightHour' AND '$endFourHour') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startEightHour' AND '$endFourHour') 
						AS total 
					FROM dual
				");

				$totalSolRed = DB::select("
					SELECT 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$new' AND date BETWEEN '$startToday' AND '$upEightHour') + 
						(SELECT COUNT(*) FROM $this->gt WHERE status = '$assign' AND date_mod BETWEEN '$startToday' AND '$upEightHour') 
						AS total 
					FROM dual
				");

				$totalSolGreen = $totalSolGreen[0]['total'] + $solAfterWorkGreen;
				$totalSolOrange = $totalSolOrange[0]['total'] + $solAfterWorkOrange;
				$totalSolRed = $totalSolRed[0]['total'] + $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;
			}
		}


		// totalYesterday + today
		$today = Tickets::whereBetween('date', [$startToday, $currentTime])->count();
		$totalToday = $today + $totalResGreen;
		//echo 'Today : ' . $totalToday; echo '<br />';

		$info  = array(
			'0' => array(
				'icon' => 'E',
				'text' => 'OUTSTANDING TICKET',
				'color' => 'danger',
				'counter' => $totalSolRed
			),
			'1' => array(
				'icon' => '',
				'text' => 'TICKET TODAY',
				'color' => 'megna',
				'counter' => $totalToday
			),
			'2' => array(
				'icon' => '',
				'text' => 'ASSIGNED',
				'color' => 'primary',
				'counter' => Tickets::where('status', $assign)->whereBetween('date_mod', [$startToday, $endToday])->count()
			),
			'3' => array(
				'icon' => '',
				'text' => 'SOLVED',
				'color' => 'success',
				'counter' => Tickets::where('status', 'solved')->whereBetween('solvedate', [$startToday, $endToday])->count()
			)
		);

		//echo '<pre>'; print_r ($info); echo '</pre>';


		// ******************** ADDITIONAL ******************** //
		$add  = array(
			'0' => array(
				'icon' => 'E',
				'text' => 'PENDING TICKET',
				'color' => 'danger',
				'counter' => Tickets::where('status', '=', 'waiting')->count()
			),
			'1' => array(
				'icon' => '',
				'text' => 'TICKET NOT CLOSED',
				'color' => 'success',
				'counter' => Tickets::where('status', '=', 'solved')->count()
			)
		);
		


		$label = 'assign';
		$mon = DB::select("
			SELECT id, date, users_id, firstname, realname, selisih FROM (
				SELECT gt.id, gt.date, gtu.users_id, gu.firstname, gu.realname, 
					timestampdiff(second, gt.date, now()) AS selisih
				FROM $this->gt gt
				LEFT JOIN $this->gtu gtu
					ON gt.id = gtu.tickets_id
				LEFT JOIN $this->gu gu
					ON gtu.users_id = gu.id
				WHERE gt.status = '$assign'
					AND gtu.type = '2'
				GROUP BY gt.id
				ORDER BY selisih DESC
			) t
			GROUP BY users_id
			ORDER BY selisih DESC
			LIMIT 10;
		");
		//$queries = DB::getQueryLog();
		//print_r (end($queries));

		$monitor = array();
		foreach ($mon as $k=>$v) {
			$last = Tickets::leftJoin('glpi_tickets_users', 'glpi_tickets_users.tickets_id', '=', 'glpi_tickets.id')
					->where('glpi_tickets_users.users_id', '=', $v['users_id'])
					->where('glpi_tickets.status', '=', $assign)
					->where('date', '<', $startToday)
					->get();
		//$queries = DB::getQueryLog();
		//print_r (end($queries));

			$today = Tickets::leftJoin('glpi_tickets_users', 'glpi_tickets_users.tickets_id', '=', 'glpi_tickets.id')
					->where('glpi_tickets_users.users_id', '=', $v['users_id'])
					->where('glpi_tickets.status', '=', $assign)
					->whereBetween('date', array($startToday, $endToday))
					->get();
		//$queries = DB::getQueryLog();
		//print_r (end($queries));

			$ss = $v['selisih'];
			$m = floor(($ss%3600)/60);
			$h = floor(($ss%86400)/3600);
			$d = floor(($ss%2592000)/86400);
			$M = floor($ss/2592000);

			$monitor[$k]['nama'] = ltrim($v['firstname'] . ' ' . $v['realname']);
			$monitor[$k]['last'] = count($last);
			$monitor[$k]['today'] = count($today);
			$monitor[$k]['max'] = $M . ' : ' . $d . ' : ' . $h . ' : ' . $m;
			$monitor[$k]['nomor'] = $v['id'];
		}
		//echo '<pre>'; print_r ($monitor); echo '</pre>';

		// Jumlah Tiket Hari Ini //
		// Dibawah 1 jam
		$totalOneHour = $totalResGreen + $totalResOrange;
		//echo 'Didalam 1 jam : ' . $totalOneHour; echo '<br />';

		// Diatas 1 jam
		$totalUpHour = $totalResRed;
		//echo 'Diatas 1 jam : ' . $totalUpHour; echo '<br />';

		$totalToday = $totalOneHour + $totalUpHour;
		//echo 'Jumlah Hari Ini : ' . $totalToday; echo '<br />';

		$response = [
			'danger' => [
				'angka' => $totalResRed,
				'persen' => 0
			],
			'warning' => [
				'angka' => $totalResOrange,
				'persen' => 0
			],
			'success' => [
				'angka' => $totalResGreen,
				'persen' => 0
			]
		];

		$solving = [
			'danger' => [
				'angka' => $totalSolRed,
				'persen' => 0
			],
			'warning' => [
				'angka' => $totalSolOrange,
				'persen' => 0
			],
			'success' => [
				'angka' => $totalSolGreen,
				'persen' => 0
			]
		];

		// Persentase MTR //
		if ($totalToday == 0) {
			$persenMTR = 0;
		} else {
			$count1 = $totalUpHour / $totalToday;
			$count2 = $count1 * 100;
			$persenMTR = number_format($count2, 0);
		}
		//echo 'MTR : ' . $persenMTR; echo '<br />';
		$response['average'] = $persenMTR;

		// Kurang 1 hari
		$oneDay = $totalResRed + $afterWorkRed;
		//echo 'One Day : ' . $oneDay; echo '<br />';
		// Lebih 1 hari
		$upDay = $totalYesterday + $totalBeforeYesterday;
		//echo 'Up One Day : ' . $upDay; echo '<br />';

		// Persentase MTS //
		if ($upDay == 0) {
			$persenMTS = 0;
		} else {
			$mts1 = $oneDay / $upDay;
			$mts2 = $mts1 * 100;
			$persenMTS = number_format($mts2, 0);
		}
		//echo 'MTS : ' . $persenMTS; echo '<br />';
		$solving['average'] = $persenMTS;

		$nilaiEmoticon = (($persenMTR * 1) + ($persenMTS * 3)) / 4;
		//echo 'Nilai Emoticon : ' . ceil($nilaiEmoticon); echo '<br />';

		if ($nilaiEmoticon >= 70 && $nilaiEmoticon <= 100) {
			//echo 'Menangis'; echo '<br />';
			$smilies = 'cry.png';
		} else if ($nilaiEmoticon >= 50 && $nilaiEmoticon <= 69) {
			//echo 'Sedih'; echo '<br />';
			$smilies = 'sad.png';
		} else {
			//echo 'Gembira'; echo '<br />';
			$smilies = 'fun.png';
		}

		return view('layouts.content', compact('title', 'date', 'info', 'response', 'solving', 'monitor', 'add', 'smilies'));
	}
}
