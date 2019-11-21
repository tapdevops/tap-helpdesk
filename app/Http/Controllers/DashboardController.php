<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use App\Tickets;

class DashboardzzController extends Controller
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

		$startClosing = 15;
		$endClosing = 20;


		
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

		$dateBefore = strtoupper(date('j', strtotime($output . ' -1 day')));
		$dateBefore = (int) $dateBefore;

		$finalDate = strtoupper(date('Y-m-d', strtotime($output . ' -1 day')));

		$currentTime = date('Y-m-d H:i:s');
		$convertCurrentTime = strtotime($currentTime);
		$startCountToday = date('Y-m-d 00:00:00');
		$endCountToday = date('Y-m-d 23:59:59');

		$beforeMidnight = $finalDate . ' 23:59:59';
		$startMidnight = date('Y-m-d 00:00:00');
		$endMidnight = date('Y-m-d 07:59:59');
		$convertBeforeMidnight = strtotime($beforeMidnight);
		$convertStartMidnight = strtotime($startMidnight);
		$convertEndMidnight = strtotime($endMidnight);

		if ($dateBefore >= $startClosing && $dateBefore <= $endClosing) {
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
		$status = ['new', 'assign'];
		$new = 'new';
		$assign = 'assign';

		/* Total Before Yesterday => < Yesterday 16:00:00 */
		$totalBeforeYesterday = Tickets::whereIn('status', $status)->where('date', '<', $beforeYesterday)->count();

		/* Total Range 16:00:01 - 16:00:59 or 22:00:01 - 22:00:59 */
		$totalYesterday = Tickets::whereIn('status', $status)->whereBetween('date', [$startClosingYesterday, $endClosingYesterday])->count();

		/* Total After Work => Yesterday 16:01:00 - Today 07:59:59 or Yesterday 22:01:00 - Today 07:59:59 */
		$totalAfterWork = Tickets::whereIn('status', $status)->whereBetween('date', [$startAfterWork, $endAfterWork])->count();


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
			if ($dateBefore >= $startClosing && $dateBefore <= $endClosing) {
				$anHour = date($finalDate . ' 21:00:00');
				$halfHour = date($finalDate . ' 21:30:00');
				$upHalf = date($finalDate . ' 21:30:01');
			} else {
				$anHour = date($finalDate . ' 15:00:00');
				$halfHour = date($finalDate . ' 15:30:00');
				$upHalf = date($finalDate . ' 15:30:01');
			}

			$totalResGreen = Tickets::whereIn('status', $status)->whereBetween('date', [$upHalf, $beforeYesterday])->count() + $totalAfterWork;
			$totalResOrange = Tickets::whereIn('status', $status)->whereBetween('date', [$anHour, $halfHour])->count() + $afterWorkOrange;
			$totalResRed = Tickets::whereIn('status', $status)->where('date', '<', $anHour)->count() + $afterWorkRed;

		// current time antara 08:00:00 - 16:00:00
		} else if ($convertCurrentTime >= $convertStartToday && $convertCurrentTime <= $convertEndToday) {
			// current time antara 08:00:00 - 08:30:00
			if ($convertCurrentTime > $startHalfHour && $convertCurrentTime < $endHalfHour) {
				$totalResGreen = Tickets::whereIn('status', $status)->whereBetween('date', [$startToday, $currentTime])->count() + $afterWorkGreen;
				$totalResOrange = 0 + $afterWorkOrange;
				$totalResRed = $totalBeforeYesterday + $totalYesterday + $afterWorkRed;

			// current time antara 08:30:00 - 09:00:00
			} else if ($convertCurrentTime > $startMiddleHour && $convertCurrentTime < $endMiddleHour) {
				$halfHour = date('Y-m-d H:i:s', strtotime(' -30 minute'));
				$anHour = date('Y-m-d H:i:s', strtotime(' -60 minute'));

				$totalResGreen = Tickets::whereIn('status', $status)->whereBetween('date', [$halfHour, $currentTime])->count() + $afterWorkGreen;
				$totalResOrange = Tickets::whereIn('status', $status)->whereBetween('date', [$startToday, $halfHour])->count() + $afterWorkOrange;
				$totalResRed = $totalBeforeYesterday + $totalYesterday + $afterWorkRed;

			// current time diatas 09:00:00
			} else if ($convertCurrentTime >= $upAnHour) {
				$halfHour = date('Y-m-d H:i:s', strtotime(' -30 minute'));
				$anHour = date('Y-m-d H:i:s', strtotime(' -60 minute'));

				$totalResGreen = Tickets::whereIn('status', $status)->whereBetween('date', [$halfHour, $currentTime])->count() + $afterWorkGreen;
				$totalResOrange = Tickets::whereIn('status', $status)->whereBetween('date', [$anHour, $halfHour])->count() + $afterWorkOrange;
				$totalResRed = Tickets::whereIn('status', $status)->whereBetween('date', [$startToday, $anHour])->count() + $totalBeforeYesterday + $totalYesterday + $afterWorkRed;
			}

		// current time antara 16:00:01 - 23:59:59
		} else if ($convertCurrentTime >= $convertStartAfterWorkToday && $convertCurrentTime <= $convertEndAfterWorkToday) {
			if ($dateBefore >= $startClosing && $dateBefore <= $endClosing) {
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

			$totalTonight = Tickets::whereIn('status', $status)->whereBetween('date', [$endToday, $currentTime])->count();
			$totalResGreen = Tickets::whereIn('status', $status)->whereBetween('date', [$upHalf, $endToday])->count() + $totalTonight;
			$totalResOrange = Tickets::whereIn('status', $status)->whereBetween('date', [$upHour, $halfHour])->count() + $afterWorkOrange;
			$totalResRed = Tickets::whereIn('status', $status)->whereBetween('date', [$startToday, $anHour])->count() + $totalBeforeYesterday + $totalYesterday + $afterWorkRed;
		}


		// MTS
		if ($dateBefore >= $startClosing && $dateBefore <= $endClosing) {
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

		if ($dateBefore >= $startClosing && $dateBefore <= $endClosing) {
			// current time antara 22:00:01 - 23:59:59
			if ($convertCurrentTime >= $convertStartAfterWorkToday && $convertCurrentTime <= $convertEndAfterWorkToday) {
				$startMTS = strtotime(date('Y-m-d 09:00:00'));
				$startSeperempat = strtotime(date('Y-m-d 14:00:00'));
				$endSeperempat = strtotime(date('Y-m-d 15:00:00'));
				$startDuaperempat = strtotime(date('Y-m-d 15:00:01'));
				$endDuaperempat = strtotime(date('Y-m-d 18:00:00'));
				$startTigaperempat = strtotime(date('Y-m-d 18:00:01'));
				$endTigaperempat = strtotime(date('Y-m-d 22:00:00'));
				$upPerempat = strtotime(date('Y-m-d 22:00:01'));

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

				$satuPerempat = date('Y-m-d 21:00:00');
				$duaPerempat = date('Y-m-d 18:00:00');
				$tigaPerempat = date('Y-m-d 14:00:00');

				$totalSolGreen = Tickets::whereIn('status', $status)->whereBetween('date', [$duaPerempat, $satuPerempat])->count() + $solAfterWorkGreen;
				$totalSolOrange = Tickets::whereIn('status', $status)->whereBetween('date', [$tigaPerempat, $duaPerempat])->count() + $solAfterWorkOrange;
				$totalSolRed = Tickets::whereIn('status', $status)->whereBetween('date', [$startToday, $tigaPerempat])->count() + $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;

			// current time antara 08:00:00 - 22:00:00
			} else if ($convertCurrentTime >= $convertStartToday && $convertCurrentTime <= $endTigaperempat) {
				$startMTS = strtotime(date('Y-m-d 09:00:00'));
				$startSeperempat = strtotime(date('Y-m-d 08:00:00'));
				$endSeperempat = strtotime(date('Y-m-d 09:00:00'));
				$startDuaperempat = strtotime(date('Y-m-d 09:00:01'));
				$endDuaperempat = strtotime(date('Y-m-d 12:00:00'));
				$startTigaperempat = strtotime(date('Y-m-d 12:00:01'));
				$endTigaperempat = strtotime(date('Y-m-d 16:00:00'));
				$upPerempat = strtotime(date('Y-m-d 16:00:01'));

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

				$satuPerempat = date('Y-m-d H:i:s', strtotime(' -1 hour'));
				$duaPerempat = date('Y-m-d H:i:s', strtotime(' -4 hour'));
				$tigaPerempat = date('Y-m-d H:i:s', strtotime(' -8 hour'));

				if (strtotime($duaPerempat) < $convertStartToday) $duaPerempat = $startToday;
				$totalSolGreen = Tickets::whereIn('status', $status)->whereBetween('date', [$duaPerempat, $satuPerempat])->count();

				if (strtotime($tigaPerempat) < $convertStartToday) $tigaPerempat = $startToday;
				$totalSolOrange = Tickets::whereIn('status', $status)->whereBetween('date', [$tigaPerempat, $duaPerempat])->count();

				if ($tigaPerempat == $startToday) {
					$totalSolRed = $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;
				} else {
					if (strtotime($tigaPerempat) < $convertStartToday) $tigaPerempat = $startToday;
					$totalSolRed = Tickets::whereIn('status', $status)->whereBetween('date', [$startToday, $tigaPerempat])->count();
					$totalSolRed = $totalSolRed + $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;
				}

				$totalSolGreen = $totalSolGreen + $solAfterWorkGreen;
				$totalSolOrange = $totalSolOrange + $solAfterWorkOrange;
				$totalSolRed = $totalSolRed;

			// current time antara 00:00:00 - 07:59:59
			} else if ($convertCurrentTime >= $convertStartMidnight && $convertCurrentTime <= $convertEndMidnight) {
				$satuPerempat = date($finalDate . ' 21:00:00');
				$duaPerempat = date($finalDate . ' 18:00:00');
				$tigaPerempat = date($finalDate . ' 14:00:00');
				$start = date($finalDate . ' 08:00:00');

				$totalSolGreen = Tickets::whereIn('status', $status)->whereBetween('date', [$duaPerempat, $satuPerempat])->count() + $solAfterWorkGreen;
				$totalSolOrange = Tickets::whereIn('status', $status)->whereBetween('date', [$tigaPerempat, $duaPerempat])->count() + $solAfterWorkOrange;
				$totalSolRed = Tickets::whereIn('status', $status)->where('date', '<', $tigaPerempat)->count() + $solAfterWorkRed;
			}
		} else {
			// current time antara 00:00:00 - 07:59:59
			if ($convertCurrentTime >= $convertStartMidnight && $convertCurrentTime <= $convertEndMidnight) {
				if ($dateBefore >= $startClosing && $dateBefore <= $endClosing) {
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

				$totalSolGreen = Tickets::whereIn('status', $status)->whereBetween('date', [$startFourHour, $oneHour])->count() + $solAfterWorkGreen;
				$totalSolOrange = Tickets::whereIn('status', $status)->whereBetween('date', [$startEightHour, $endFourHour])->count() + $solAfterWorkOrange;
				$totalSolRed = Tickets::whereIn('status', $status)->where('date', '<', $upEightHour)->count();

			// current time antara 08:00:00 - 09:00:00
			} else if ($convertCurrentTime >= $startSeperempat && $convertCurrentTime <= $endSeperempat) {
				$totalSolGreen = $solAfterWorkGreen;
				$totalSolOrange = $solAfterWorkOrange;
				$totalSolRed = $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;

			// current time antara 09:00:01 - 12:00:00
			} else if ($convertCurrentTime >= $startDuaperempat && $convertCurrentTime <= $endDuaperempat) {
				$startHour = date('Y-m-d H:i:s', strtotime(' -1 hour'));

				$totalSolGreen = Tickets::whereIn('status', $status)->whereBetween('date', [$startToday, $startHour])->count() + $solAfterWorkGreen;
				$totalSolOrange = 0 + $solAfterWorkOrange;
				$totalSolRed = $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;

			// current time antara 12:00:01 - 16:00:00
			} else if ($convertCurrentTime >= $convertStartToday && $convertCurrentTime <= $convertEndToday) {
				$startHour = date('Y-m-d H:i:s', strtotime(' -1 hour'));
				$endHour = date('Y-m-d H:i:s', strtotime(' -4 hour'));
				$endsHour = date('Y-m-d H:i:s', strtotime(' -8 hour'));
				
				$totalSolGreen = Tickets::whereIn('status', $status)->whereBetween('date', [$endHour, $startHour])->count();

				if ($dateBefore >= $startClosing && $dateBefore <= $endClosing) {
					$totalSolOrange = Tickets::whereIn('status', $status)->whereBetween('date', [$endsHour, $endHour])->count();
					$totalSolRed = Tickets::whereIn('status', $status)->whereBetween('date', [$startToday, $endsHour])->count();
				} else {
					$totalSolOrange = Tickets::whereIn('status', $status)->whereBetween('date', [$startToday, $endHour])->count();
				}

				$totalSolGreen = $totalSolGreen + $solAfterWorkGreen;
				$totalSolOrange = $totalSolOrange + $solAfterWorkOrange;
				if ($dateBefore >= $startClosing && $dateBefore <= $endClosing) {
					$totalSolRed = $totalSolRed + $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;
				} else {
					$totalSolRed = $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;
				}

			// current time antara 16:00:01 - 23:59:59
			} else if ($convertCurrentTime >= $convertStartAfterWorkToday && $convertCurrentTime <= $convertEndAfterWorkToday) {
				if ($dateBefore >= $startClosing && $dateBefore <= $endClosing) {
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

				$totalSolGreen = Tickets::whereIn('status', $status)->whereBetween('date', [$startFourHour, $oneHour])->count() + $solAfterWorkGreen;
				$totalSolOrange = Tickets::whereIn('status', $status)->whereBetween('date', [$startEightHour, $endFourHour])->count() + $solAfterWorkOrange;
				$totalSolRed = Tickets::whereIn('status', $status)->whereBetween('date', [$startToday, $upEightHour])->count() + $totalBeforeYesterday + $totalYesterday + $solAfterWorkRed;
			}
		}


		$info  = array(
			'0' => array(
				'icon' => 'E',
				'text' => 'OUTSTANDING TICKET',
				'color' => 'danger',
				'counter' => Tickets::whereIn('status', $status)->where('date', '<', $finalDate . ' 23:59:59')->count()
			),
			'1' => array(
				'icon' => '',
				'text' => 'TICKET TODAY',
				'color' => 'megna',
				'counter' => Tickets::whereIn('status', $status)->whereBetween('date', [$startCountToday, $currentTime])->count()
			),
			'2' => array(
				'icon' => '',
				'text' => 'ASSIGNED',
				'color' => 'primary',
				'counter' => Tickets::where('status', $assign)->whereBetween('date', [$startToday, $currentTime])->count()
			),
			'3' => array(
				'icon' => '',
				'text' => 'SOLVED',
				'color' => 'success',
				'counter' => Tickets::where('status', 'solved')->whereBetween('solvedate', [$startToday, $currentTime])->count()
			)
		);


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
		

		$monitor = DB::select("
			SELECT id, nama, COUNT(b.users_id) AS last_ticket, ticket_today, waktu FROM (
				SELECT a.* FROM (
					SELECT DISTINCT gt.id, 
							gtu.users_id, 
							concat_ws(' ', gu.firstname, gu.realname) AS nama, 
							gt.date, 
							timestampdiff(second, gt.date, now()) AS selisih, 
							CONCAT(
								FLOOR(HOUR(TIMEDIFF(gt.date, now()) ) / 24), ' : ',
								MOD(HOUR(TIMEDIFF(gt.date, now()) ), 24), ' : ',
								MINUTE(TIMEDIFF(gt.date, now()) ), ''
							) AS waktu,
							(SELECT COUNT(*) FROM $this->gt LEFT JOIN $this->gtu ON $this->gtu.tickets_id = $this->gt.id WHERE $this->gtu.users_id = gu.id AND $this->gt.status = 'assign' AND $this->gt.date BETWEEN '$startCountToday' and '$currentTime') AS ticket_today 
					FROM $this->gt gt, $this->gtu gtu, $this->gu gu 
					WHERE 
						gt.id = gtu.tickets_id 
						AND gt.status='assign' 
						AND gtu.type = '2' 
						AND gt.date <= '$beforeMidnight' 
						AND gtu.users_id = gu.id
				) a GROUP BY id ORDER BY date ASC
			) b GROUP BY users_id ORDER BY selisih DESC LIMIT 10
		");

		// Jumlah Tiket Hari Ini //
		$totalOneHour = $totalResGreen + $totalResOrange;		// dibawah 1 jam
		$totalUpHour = $totalResRed;								// diatas 1 jam
		$totalToday = $totalOneHour + $totalUpHour;				// total

		$response = [
			'red' => $totalResRed,
			'orange' => $totalResOrange,
			'green' => $totalResGreen
		];

		$solving = [
			'red' => $totalSolRed,
			'orange' => $totalSolOrange,
			'green' => $totalSolGreen
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

		$oneDay = $totalResRed + $afterWorkRed;			// kurang 1 hari
		$upDay = $totalYesterday + $totalBeforeYesterday;	// lebih 1 hari
		$totalMTS = $oneDay + $upDay;						// total

		// Persentase MTS //
		if ($upDay == 0) {
			$persenMTS = 0;
		} else {
			$mts1 = $oneDay / $totalMTS;
			$mts2 = $mts1 * 100;
			$persenMTS = number_format($mts2, 0);
		}
		$solving['average'] = $persenMTS;

		$nilaiEmoticon = (($persenMTR * 1) + ($persenMTS * 3)) / 4;

		if ($nilaiEmoticon >= 70 && $nilaiEmoticon <= 100) {
			$smilies = 'cry.png';
		} else if ($nilaiEmoticon >= 50 && $nilaiEmoticon <= 69) {
			$smilies = 'sad.png';
		} else {
			$smilies = 'fun.png';
		}

		return view('layouts.content', compact('title', 'date', 'info', 'response', 'solving', 'monitor', 'add', 'smilies'));
	}
}
