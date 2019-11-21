<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;

//use DB;
use App\Sqvi; //MODEL
use Carbon\Carbon;

class SqviController extends Controller
{	
    public function index()
	{
		$k = 'KG';
		$data = array();
		
		$sql = "
						SELECT werks AS plnt,
							   sub_block_code AS block_code,
							   tgl_mill AS created_on,
							   kg_produksi AS quantity
						  FROM tap_dw.tr_hv_production_daily
						 WHERE tgl_mill BETWEEN CASE
												   WHEN TO_CHAR (sysdate, 'dd') BETWEEN '01' AND '05' THEN TRUNC (ADD_MONTHS (sysdate, -1), 'MON')
												   ELSE TRUNC (sysdate, 'mon')
												END
											AND  TRUNC (sysdate) ";
											/*CASE
												   WHEN TO_CHAR (sysdate, 'dd') BETWEEN '01' AND '05' THEN TRUNC (ADD_MONTHS (sysdate, -1), 'MON')
												   ELSE TRUNC (sysdate, 'mon')
												END*/

		$datax = DB::connection('irs')->select($sql);
		
		if( $datax )
		{
			foreach($datax as $k => $v)
			{
				//echo "<pre>"; print_r($v);
				
				$data[] = array(
					'Plnt' => $v['plnt'],
					'Block Code' => $v['block_code'],
					'Created on' => $v['created_on'],
					'quantity' => $v['quantity'],
					'Bun' => 'KG'
				);
			}
			//die();
		}
		
		if(empty($datax))
		{
		    $result = array(
				'code' => 200,
				'status' => 'failed',
				'message' => 'data not found',
				'data' => $datax
			);
		    
			return Response::json($result,200);
		}
	 
		$result = array(
				'code' => 200,
				'status' => 'success',
				'message' => ''.count($data).' data found',
				'data' => $data
			);
		
		date_default_timezone_set("Asia/Bangkok");
		$date = date("dmY_Hi");
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=SQVI.csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		//$l = '';
		if($datax)
		{
			$output = fopen("php://output", "wb");
			$data2 = array("PLNT","BLOCK CODE","CREATED ON","QUANTITY","BUN");
			
			fputcsv($output, $data2);
			
			foreach( $datax as $k => $v )
			{
				//echo "<pre>"; print_r($v);
				
				$data3 = array(
					'plnt' => $v['plnt'],
					'block_code' => $v['block_code'],
					'created_on' => $v['created_on'],
					'quantity' => $v['quantity'],
					'bun' => 'KG'
				);
				 
				fputcsv($output, $data3);
				
			}
			
			fclose($output);
		}
	
		die;
		
		//return Response::json($result,200);
	}
	
	public function cron()
	{	
		$pathfile = "/etc/csv_share/SQVI.csv";
		
		//HEADER
		//$output = fopen("php://output", "wb");
		$data2 = array("PLNT","BLOCK CODE","CREATED ON","QUANTITY","BUN");		
		
		//CONTENT DATA		
		$sql = "
			SELECT werks AS plnt,
				   sub_block_code AS block_code,
				   TO_CHAR (tgl_mill, 'mm/dd/yyyy') AS created_on,
				   kg_produksi AS quantity
			  FROM tap_dw.tr_hv_production_daily
			 WHERE tgl_mill BETWEEN CASE
									   WHEN TO_CHAR (SYSDATE, 'dd') BETWEEN '01' AND '05' THEN TRUNC (ADD_MONTHS (SYSDATE, -1), 'MON')
									   ELSE TRUNC (SYSDATE, 'mon')
									END
								AND  TRUNC (SYSDATE)";
		
		/*CASE
									   WHEN TO_CHAR (SYSDATE, 'dd') BETWEEN '01' AND '05' THEN TRUNC (ADD_MONTHS (SYSDATE, -1), 'MON')
									   ELSE TRUNC (SYSDATE, 'mon')
									END*/
		$datax = DB::connection('irs')->select($sql);
		
		if($datax)
		{
			$file = fopen($pathfile,"w");
			fputcsv($file, $data2);
			foreach( $datax as $k => $v )
			{
				//echo "<pre>"; print_r($v);
				
				$data3 = array(
					'plnt' => $v['plnt'],
					'block_code' => $v['block_code'],
					'created_on' => $v['created_on'],
					'quantity' => $v['quantity'],
					'bun' => 'KG'
				);
				 
				fputcsv($file, $data3);
				
			}
		}	fclose($file);
	}
	
}

?>