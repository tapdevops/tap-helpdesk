<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;

//use DB;
use App\Janjang_kirim; //MODEL
use Carbon\Carbon;

class Janjang_kirimController extends Controller
{	
    public function index ()
	{
		$k = '.';
		$data = array();
		
		$sql = "      SELECT data.comp_code company_code,
       data.est_code estate_code,
       data.block_code,
       sub_block_name block_name,
       dt,
       janjang_kirim
  FROM    (  SELECT comp_code,
                    est_code,
                    block_code,
                    date_created,
                    TO_CHAR (date_created, 'mm/dd/yyyy') dt,
                    SUM (tandan) janjang_kirim
               FROM staging.zest_blockc@proddb_link
              WHERE date_created BETWEEN CASE
                                            WHEN TO_CHAR (SYSDATE, 'dd') BETWEEN '01' AND '05' THEN TRUNC (ADD_MONTHS (SYSDATE, -1), 'mon')
                                            ELSE TRUNC (SYSDATE, 'mon')
                                         END
                                     AND  SYSDATE - 1
           GROUP BY comp_code,
                    est_code,
                    block_code,
                    date_created,
                    TO_CHAR (date_created, 'mm/dd/yyyy')) data
       LEFT JOIN
          tap_dw.tm_sub_block blok
       ON     data.comp_code || data.est_code = blok.werks
          AND data.block_code = blok.sub_block_code
          AND date_created BETWEEN start_valid AND end_valid";
						   /*CASE
														   WHEN TO_CHAR (SYSDATE, 'dd') BETWEEN '01' AND '05' THEN TRUNC (ADD_MONTHS (SYSDATE, -1), 'mon')
														   ELSE TRUNC (SYSDATE, 'mon')
														END
													AND  TRUNC (SYSDATE)*/
		$datax = DB::connection('irs')->select($sql);
		
		if( $datax )
		{
			foreach($datax as $k => $v)
			{
				//echo "<pre>"; print_r($v);
				
				$data[] = array(
					'company_code' => $v['company_code'],
					'estate_code' => $v['estate_code'],
					'block_code' => $v['block_code'],
					'block_name' => $v['block_name'],
					'date' => $v['dt'],
					'janjang_kirim' => $v['janjang_kirim']
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
		//$date = date("dmY_Hi");
		$date = date("dmY");
		header("Content-type: text/csv");
		header("Content-Disposition: attachment; filename=Janjang_kirim.csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		//$l = '';
		if($datax)
		{
			$output = fopen("php://output", "wb");
			$data2 = array("Company Code","Estate Code","Block Code","Block Name","Date","janjang Kirim");
			
			fputcsv($output, $data2);
			
			foreach( $datax as $k => $v )
			{
				//echo "<pre>"; print_r($v);
				
				$data3 = array(
					'company_code' => $v['company_code'],
					'estate_code' => $v['estate_code'],
					'block_code' => $v['block_code'],
					'block_name' => $v['block_name'],
					'date' => $v['dt'],
					'janjang_kirim' => $v['janjang_kirim']
				);
				 
				fputcsv($output, $data3);
				
			}
			
			fclose($output);
		}
	
		die;
		
		//return Response::json($result,200);
	}
	
	public function cron ()
	{	
		$pathfile = "/etc/csv_share/Janjang_kirim.csv";
		
		
		//HEADER
		//$output = fopen("php://output", "wb");
		$data2 = array("Company Code","Estate Code","Block Code","Block Name","Date","janjang Kirim");

		//CONTENT DATA		
		$sql = "  
					SELECT data.comp_code company_code,
       data.est_code estate_code,
       data.block_code,
       sub_block_name block_name,
       dt,
       janjang_kirim
  FROM    (  SELECT comp_code,
                    est_code,
                    block_code,
                    date_created,
                    TO_CHAR (date_created, 'mm/dd/yyyy') dt,
                    SUM (tandan) janjang_kirim
               FROM staging.zest_blockc@proddb_link
              WHERE date_created BETWEEN CASE
                                            WHEN TO_CHAR (SYSDATE, 'dd') BETWEEN '01' AND '05' THEN TRUNC (ADD_MONTHS (SYSDATE, -1), 'mon')
                                            ELSE TRUNC (SYSDATE, 'mon')
                                         END
                                     AND  SYSDATE - 1
           GROUP BY comp_code,
                    est_code,
                    block_code,
                    date_created,
                    TO_CHAR (date_created, 'mm/dd/yyyy')) data
       LEFT JOIN
          tap_dw.tm_sub_block blok
       ON     data.comp_code || data.est_code = blok.werks
          AND data.block_code = blok.sub_block_code
          AND date_created BETWEEN start_valid AND end_valid";
						   /*CASE
														   WHEN TO_CHAR (SYSDATE, 'dd') BETWEEN '01' AND '05' THEN TRUNC (ADD_MONTHS (SYSDATE, -1), 'mon')
														   ELSE TRUNC (SYSDATE, 'mon')
														END AND  TRUNC (SYSDATE)*/
		
		$datax = DB::connection('irs')->select($sql);
		
		if($datax)
		{   $file = fopen($pathfile,"w");
			fputcsv($file, $data2);
			foreach( $datax as $k => $v )
			{
				//echo "<pre>"; print_r($v);
				
				$data3 = array(
					'company_code' => $v['company_code'],
					'estate_code' => $v['estate_code'],
					'block_code' => $v['block_code'],
					'block_name' => $v['block_name'],
					'date' => $v['dt'],
					'janjang_kirim' => $v['janjang_kirim']
				);
				 
				fputcsv($file, $data3);
				
			}
			fclose($file);
		}
	}
	
}

?>