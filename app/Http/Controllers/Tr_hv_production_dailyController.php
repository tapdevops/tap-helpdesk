<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;

//use DB;
use App\Tr_hv_production_daily; //MODEL
use Carbon\Carbon;

class Tr_hv_production_dailyController extends Controller
{	
    public function index()
	{
		$k = 'KG';
		$data = array();
		
		$sql = "
						SELECT WERKS AS Plnt,
						SUB_BLOCK_CODE AS Block_Code,
						TGL_MILL AS Created_on,
						KG_PRODUKSI AS Quantity
			FROM TAP_DW.TR_HV_PRODUCTION_DAILY
			WHERE TGL_MILL BETWEEN TRUNC(SYSDATE-3) AND TRUNC(SYSDATE) ";
		
		$datax = DB::connection('irs')->select($sql);
		
		if( $datax )
		{
			foreach($datax as $k => $v)
			{
				//echo "<pre>"; print_r($v);
				
				$data[] = array(
					'plnt' => $v['plnt'],
					'block_code' => $v['block_code'],
					'created_on' => $v['created_on'],
					'quantity' => $v['quantity'],
					'bun' => 'KG'
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
		
		$var_1 = "production_daily_".$date."_".count($data)."_data.csv";
		$dir = ""; // trailing slash is important
		$file = $dir.$var_1;
		
		/* copy file ke server */
		$csv = "/var/www/html/test.txt";
		$newfile = "/var/www/html/test".rand(1,99).".txt";
		copy($csv, $newfile);
		/* end copy file ke server */
		
		
		#### EXPORT TO CSV VIA BROWSER ####
		header("Content-type: text/csv");
		//header("Content-Disposition: attachment; filename=production_daily_".$date."_".count($data)."_data.csv");
		header("Content-Disposition: attachment; filename=".basename($file)."");
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
		#### END EXPORT TO CSV VIA BROWSER ####
	
		die;
		
		//return Response::json($result,200);
	}
	
	public function production_daily_cron()
	{	
		$pathfile = "/etc/csv_share/production_daily_cron.csv";
		$file = fopen($pathfile,"w");
		
		//HEADER
		//$output = fopen("php://output", "wb");
		$data2 = array("PLNT","BLOCK CODE","CREATED ON","QUANTITY","BUN");		
		fputcsv($file, $data2);

		//CONTENT DATA		
		$sql = "
			SELECT WERKS AS Plnt,
				SUB_BLOCK_CODE AS Block_Code,
				TGL_MILL AS Created_on,
				KG_PRODUKSI AS Quantity
			FROM TAP_DW.TR_HV_PRODUCTION_DAILY
			WHERE TGL_MILL BETWEEN TRUNC(SYSDATE-3) AND TRUNC(SYSDATE) ";
		
		$datax = DB::connection('irs')->select($sql);
		
		if($datax)
		{
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
		}
		fclose($file);

		//COPY FILE VIA FTP
		/*
		$koneksi = ftp_ssl_connect('149.129.224.117',22) or die("Unable to connect to server.");  //connect to server
		$login = "";
		
		//ini_set('display_errors',1);
		//error_reporting(E_ALL|E_STRICT);
		//die();
		
		if($koneksi)
		{
			// login with username and password
			//$login_result = ftp_login($conn_id, $ftp_user_name, $ftp_user_pass);
			$login = ftp_login($koneksi, 'root', 'T4pagri123'); 
		}
	
		$copy_to_folder = 'var/www/html/production_daily_cron.csv';
		if($login) 
		{ 
			if(ftp_put($koneksi, '/var/www/html/production_daily_cron.csv',$copy_to_folder, FTP_BINARY)) 
			{ 
				echo "Success";
			} 
		} 
		ftp_close($koneksi);
		*/
	
	}
	
}

?>