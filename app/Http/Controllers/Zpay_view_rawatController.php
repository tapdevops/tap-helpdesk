<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Input;

//use DB;
use App\Zpay_view_rawat; //MODEL
use Carbon\Carbon;

class Zpay_view_rawatController extends Controller
{	
    public function index( $comp_ba )
	{
		$k = '.';
		$data = array();
		
		$sql = "SELECT prfnr profile_name,
					   bukrs company_code,
					   TO_CHAR (TO_DATE (budat, 'yyyymmdd'), 'mm/dd/yyyy') dt,
					   empnr employee_code,
					   zemp.employee_name employee_name,
					   jbcde job_code,
					   jbtyp job_type,
					   estnr estate,
					   divnr afdeling,
					   zwork.phase phase,
					   NULL item,
					   NULL total_item,
					   actvt_no activity,
					   actvt_name activity_name,
					   block block,
					   block_name old_block,
					   empnr_m mandor_code,
					   empnr_k1 helper1_code,
					   ename_k1 helper1_name,
					   empnr_k2 helper2_code,
					   ename_k2 helper2_name,
					   empnr_k3 helper3_code,
					   ename_k3 helper3_name,
					   per per,
					   zwork.kunnr customer,
					   amein activity_uom,
					   aufnr vehicle_license_no,
					   start_mileage start_mileage,
					   end_mileage end_mileage,
					   mileage mileage,
					   mileage_uom uom,
					   anln1 asset,
					   anlhtxt asset_maint_no,
					   kostl cost_center,
					   zwork.waerk doc_currency,
					   prate premi_rate,
					   otime over_time,
					   matnr_1 mat_1,
					   maktx_1 mat_desc1,
					   mat_per_1 mat_qty1,
					   mat_meins_1 mat_uom1,
					   matnr_2 mat_2,
					   maktx_2 mat_desc2,
					   mat_per_2 mat_qty2,
					   mat_meins_2 mat_uom2,
					   matnr_3 mat_3,
					   maktx_3 mat_desc3,
					   mat_per_3 mat_qty3,
					   mat_meins_3 mat_uom3,
					   zwork.werks plant,
					   reasn reason,
					   ernam created_by,
					   TO_CHAR (TO_DATE (erdat, 'YYYYMMDD'), 'mm/dd/yyyy') created_on,
					   TO_DATE (erzet, 'HH24MISS') time,
					   aenam changed_by,
					   CASE WHEN aedat != '00000000' THEN TO_DATE (aedat, 'YYYYMMDD') END changed_on,
					   CASE WHEN aedat != '000000' THEN TO_DATE (aezet, 'HH24MISS') END time_of_changed
				  FROM rizki.zpay_work_sap_mv zwork
					   LEFT JOIN staging.zpay_employee@proddb_link zemp
						  ON zwork.empnr = zemp.nik AND TO_DATE (budat, 'yyyymmdd') BETWEEN start_valid AND end_valid
					   LEFT JOIN tap_dw.tm_block blk
						  ON zwork.werks = blk.werks AND zwork.divnr = blk.afd_code AND zwork.block = blk.block_code
				 WHERE TRUNC (TO_DATE (budat, 'yyyymmdd')) BETWEEN CASE
																	  WHEN TO_CHAR (SYSDATE, 'dd') BETWEEN '01' AND '05'
																	  THEN
																		 TRUNC (ADD_MONTHS (SYSDATE, -1), 'mon')
																	  ELSE
																		 TRUNC (SYSDATE, 'mon')
																   END
															   AND  TRUNC (SYSDATE)
					   AND zwork.jbtyp IN ('WD', 'HV')
					   AND zwork.werks = '".$comp_ba."'";

		$datax = DB::connection('irs')->select($sql);
		
		if( $datax )
		{
			foreach($datax as $k => $v)
			{
				//echo "<pre>"; print_r($v);
				
				$data[] = array(
					'profile_name' => $v['profile_name'],
					'company_code' => $v['company_code'],
					'date' => $v['dt'],
					'employee_code' => $v['employee_code'],
					'employee_name' => $v['employee_name'],
					'job_code' => $v['job_code'],   
					'job_type' => $v['job_type'],   
					'estate' => $v['estate'],   
					'afdeling' => $v['afdeling'],   
					'phase' => $v['phase'],  
					'item' => $v['item'],
					'total_item' => $v['total_item'],
					'activity' => $v['activity'],  
					'activity_name' => $v['activity_name'],  
					'block' => $v['block'],   
					'old_block' => $v['old_block'],
					'mandor_code' => $v['mandor_code'],
					'helper1_code' => $v['helper1_code'],
					'helper1_name' => $v['helper1_name'],
					'helper2_code' => $v['helper2_code'],
					'helper2_name' => $v['helper2_name'],
					'helper3_code' => $v['helper3_code'],
					'helper3_name' => $v['helper3_name'],
					'per' => $v['per'],
					'customer' => $v['customer'],
					'activity_uom' => $v['activity_uom'],					
					'vehicle_license_no' => $v['vehicle_license_no'],
					'start_mileage' => $v['start_mileage'],
					'end_mileage' => $v['end_mileage'],
					'mileage' => $v['mileage'],
					'uom' => $v['uom'],
					'asset' => $v['asset'],
					'asset_maint_no' => $v['asset_maint_no'],
					'cost_center' => $v['cost_center'],
					'doc_currency' => $v['doc_currency'],
					'premi_rate' => $v['premi_rate'],
					'over_time' => $v['over_time'],
					'mat_1' => $v['mat_1'],
					'mat_desc1' => $v['mat_desc1'],
					'mat_qty1' => $v['mat_qty1'],
					'mat_uom1' => $v['mat_uom1'],
					'mat_2' => $v['mat_2'],
					'mat_desc2' => $v['mat_desc2'],
					'mat_qty2' => $v['mat_qty2'],
					'mat_uom2' => $v['mat_uom2'],
					'mat_3' => $v['mat_3'],
					'mat_desc3' => $v['mat_desc3'],
					'mat_qty3' => $v['mat_qty3'],
					'mat_uom3' => $v['mat_uom3'],
					'plant' => $v['plant'],
					'reason' => $v['reason'],
					'created_by' => $v['created_by'],
					'created_on' => $v['created_on'],
					'time' => $v['time'],
					'changed_by' => $v['changed_by'],
					'changed_on' => $v['changed_on'],
					'time_of_changed' => $v['time_of_changed']
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
		header("Content-Disposition: attachment; filename=ZPAY_VIEW_RAWAT_".$comp_ba.".csv");
		header("Pragma: no-cache");
		header("Expires: 0");

		//$l = '';
		if($datax)
		{
			$output = fopen("php://output", "wb");
			$data2 = array("Profile Name", "Company Code", "Date", "Employee Code", "Employee Name", "Job Code", "Job Type", "Estate", "Afdeling", "Phase", "Item", "Total Item", "Activity", "Activity Name", "Block Code", "Old Block", "Mandor Code", "Helper1 Code", "Helper1 Name", "Helper2 Code", "Helper2 Name", "Helper3 Code", "Helper3 Name", "PER", "Customer", "Activity UoM", "Vehicle License Number", "Start Point of Vehicle Mileage", "End Point of Vehicle Mileage", "Vehicle Mileage", "UOM", "Asset", "Asset main no. text", "Cost Center", "Document Currency", "Premi Rate", "Over Time", "Material 1", "Material Description 1", "Quantity Material 1", "UOM Material 1", "Material 2", "Material Description 2", "Quantity Material 2", "UOM Material 2", "Material 3", "Material Description 3", "Quantity Material 3", "UOM Material 3", "Plant", "Reason", "Created by", "Created on", "Time", "Changed by", "Changed on", "Time of change");
			
			fputcsv($output, $data2);
			
			foreach( $datax as $k => $v )
			{
				//echo "<pre>"; print_r($v);
				
				$data3 = array(
					'profile_name' => $v['profile_name'],
					'company_code' => $v['company_code'],
					'date' => $v['dt'],
					'employee_code' => $v['employee_code'],
					'employee_name' => $v['employee_name'],
					'job_code' => $v['job_code'],   
					'job_type' => $v['job_type'],   
					'estate' => $v['estate'],   
					'afdeling' => $v['afdeling'],   
					'phase' => $v['phase'],  
					'item' => $v['item'],
					'total_item' => $v['total_item'],
					'activity' => $v['activity'],  
					'activity_name' => $v['activity_name'],  
					'block' => $v['block'],   
					'old_block' => $v['old_block'],
					'mandor_code' => $v['mandor_code'],
					'helper1_code' => $v['helper1_code'],
					'helper1_name' => $v['helper1_name'],
					'helper2_code' => $v['helper2_code'],
					'helper2_name' => $v['helper2_name'],
					'helper3_code' => $v['helper3_code'],
					'helper3_name' => $v['helper3_name'],
					'per' => $v['per'],
					'customer' => $v['customer'],
					'activity_uom' => $v['activity_uom'],					
					'vehicle_license_no' => $v['vehicle_license_no'],
					'start_mileage' => $v['start_mileage'],
					'end_mileage' => $v['end_mileage'],
					'mileage' => $v['mileage'],
					'uom' => $v['uom'],
					'asset' => $v['asset'],
					'asset_maint_no' => $v['asset_maint_no'],
					'cost_center' => $v['cost_center'],
					'doc_currency' => $v['doc_currency'],
					'premi_rate' => $v['premi_rate'],
					'over_time' => $v['over_time'],
					'mat_1' => $v['mat_1'],
					'mat_desc1' => $v['mat_desc1'],
					'mat_qty1' => $v['mat_qty1'],
					'mat_uom1' => $v['mat_uom1'],
					'mat_2' => $v['mat_2'],
					'mat_desc2' => $v['mat_desc2'],
					'mat_qty2' => $v['mat_qty2'],
					'mat_uom2' => $v['mat_uom2'],
					'mat_3' => $v['mat_3'],
					'mat_desc3' => $v['mat_desc3'],
					'mat_qty3' => $v['mat_qty3'],
					'mat_uom3' => $v['mat_uom3'],
					'plant' => $v['plant'],
					'reason' => $v['reason'],
					'created_by' => $v['created_by'],
					'created_on' => $v['created_on'],
					'time' => $v['time'],
					'changed_by' => $v['changed_by'],
					'changed_on' => $v['changed_on'],
					'time_of_changed' => $v['time_of_changed']
				);
				 
				fputcsv($output, $data3);
				
			}
			
			fclose($output);
		}
	
		die;
		
		//return Response::json($result,200);
	}
	
	public function cron($comp_ba)
	{	
		$pathfile = "/etc/csv_share/Zpay_view_rawat_".$comp_ba.".csv";
		
		//HEADER
		//$output = fopen("php://output", "wb");
		$data2 = array("Profile Name", "Company Code", "Date", "Employee Code", "Employee Name", "Job Code", "Job Type", "Estate", "Afdeling", "Phase", "Item", "Total Item", "Activity", "Activity Name", "Block Code", "Old Block", "Mandor Code", "Helper1 Code", "Helper1 Name", "Helper2 Code", "Helper2 Name", "Helper3 Code", "Helper3 Name", "PER", "Customer", "Activity UoM", "Vehicle License Number", "Start Point of Vehicle Mileage", "End Point of Vehicle Mileage", "Vehicle Mileage", "UOM", "Asset", "Asset main no. text", "Cost Center", "Document Currency", "Premi Rate", "Over Time", "Material 1", "Material Description 1", "Quantity Material 1", "UOM Material 1", "Material 2", "Material Description 2", "Quantity Material 2", "UOM Material 2", "Material 3", "Material Description 3", "Quantity Material 3", "UOM Material 3", "Plant", "Reason", "Created by", "Created on", "Time", "Changed by", "Changed on", "Time of change");
		

		//CONTENT DATA		
		$sql = "SELECT prfnr profile_name,
				   bukrs company_code,
				   TO_CHAR (TO_DATE (budat, 'yyyymmdd'), 'mm/dd/yyyy') dt,
				   empnr employee_code,
				   zemp.employee_name employee_name,
				   jbcde job_code,
				   jbtyp job_type,
				   estnr estate,
				   NVL (TRIM (divnr), blk.afd_code) afdeling,
				   zwork.phase phase,
				   NULL item,
				   NULL total_item,
				   actvt_no activity,
				   actvt_name activity_name,
				   CASE WHEN TRIM (block) IS NULL THEN SUBSTR (anln1, LENGTH (anln1) - 2, 3) ELSE block END block,
				   sub_block_name old_block,
				   empnr_m mandor_code,
				   empnr_k1 helper1_code,
				   ename_k1 helper1_name,
				   empnr_k2 helper2_code,
				   ename_k2 helper2_name,
				   empnr_k3 helper3_code,
				   ename_k3 helper3_name,
				   per per,
				   zwork.kunnr customer,
				   amein activity_uom,
				   aufnr vehicle_license_no,
				   start_mileage start_mileage,
				   end_mileage end_mileage,
				   mileage mileage,
				   mileage_uom uom,
				   anln1 asset,
				   anlhtxt asset_maint_no,
				   kostl cost_center,
				   zwork.waerk doc_currency,
				   prate premi_rate,
				   otime over_time,
				   matnr_1 mat_1,
				   maktx_1 mat_desc1,
				   mat_per_1 mat_qty1,
				   mat_meins_1 mat_uom1,
				   matnr_2 mat_2,
				   maktx_2 mat_desc2,
				   mat_per_2 mat_qty2,
				   mat_meins_2 mat_uom2,
				   matnr_3 mat_3,
				   maktx_3 mat_desc3,
				   mat_per_3 mat_qty3,
				   mat_meins_3 mat_uom3,
				   zwork.werks plant,
				   reasn reason,
				   ernam created_by,
				   TO_CHAR (TO_DATE (erdat, 'YYYYMMDD'), 'mm/dd/yyyy') created_on,
				   TO_CHAR (TO_DATE (erzet, 'HH24MISS'), 'hh:mi:ss AM') time,
				   aenam changed_by,
				   CASE WHEN aedat != '00000000' THEN TO_CHAR (TO_DATE (aedat, 'YYYYMMDD'), 'mm/dd/yyyy') END changed_on,
				   CASE WHEN aedat != '000000' AND aedat IS NOT NULL THEN TO_CHAR (TO_DATE (aezet, 'HH24MISS'), 'hh:mi:ss AM') END time_of_changed
			  FROM rizki.zpay_work_sap_mv zwork
				   LEFT JOIN staging.zpay_employee@proddb_link zemp
					  ON zwork.empnr = zemp.nik AND TO_DATE (budat, 'yyyymmdd') BETWEEN start_valid AND end_valid
				   LEFT JOIN tap_dw.tm_sub_block blk
					  ON     zwork.werks = blk.werks
						 AND CASE WHEN TRIM (block) IS NULL THEN SUBSTR (anln1, LENGTH (anln1) - 2, 3) ELSE block END = blk.sub_block_code
						 AND TO_DATE (budat, 'yyyymmdd') BETWEEN blk.start_valid AND blk.end_valid
			 WHERE TRUNC (TO_DATE (budat, 'yyyymmdd')) BETWEEN  CASE
																  WHEN TO_CHAR (SYSDATE, 'dd') BETWEEN '01' AND '05'
																  THEN
																	 TRUNC (ADD_MONTHS (SYSDATE, -1), 'mon')
																  ELSE
																	 TRUNC (SYSDATE, 'mon')
															   END
																AND  CASE
																   WHEN TO_CHAR (SYSDATE, 'dd') BETWEEN '01' AND '05'
																   THEN
																	  TRUNC (LAST_DAY (ADD_MONTHS (SYSDATE, -1)))
																   ELSE
																	  TRUNC (SYSDATE)
																END
				   /*AND zwork.jbtyp IN ('WD', 'HV')*/
				   AND prfnr IN (SELECT prof_name
								   FROM staging.zpay_profile@proddb_link
								  WHERE plant_code = '".$comp_ba."')";
			/*CASE
																  WHEN TO_CHAR (SYSDATE, 'dd') BETWEEN '01' AND '05'
																  THEN
																	 TRUNC (ADD_MONTHS (SYSDATE, -1), 'mon')
																  ELSE
																	 TRUNC (SYSDATE, 'mon')
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
					'profile_name' => $v['profile_name'],
					'company_code' => $v['company_code'],
					'date' => $v['dt'],
					'employee_code' => $v['employee_code'],
					'employee_name' => $v['employee_name'],
					'job_code' => $v['job_code'],   
					'job_type' => $v['job_type'],   
					'estate' => $v['estate'],   
					'afdeling' => $v['afdeling'],   
					'phase' => $v['phase'],  
					'item' => $v['item'],
					'total_item' => $v['total_item'],
					'activity' => $v['activity'],  
					'activity_name' => $v['activity_name'],  
					'block' => $v['block'],   
					'old_block' => $v['old_block'],
					'mandor_code' => $v['mandor_code'],
					'helper1_code' => $v['helper1_code'],
					'helper1_name' => $v['helper1_name'],
					'helper2_code' => $v['helper2_code'],
					'helper2_name' => $v['helper2_name'],
					'helper3_code' => $v['helper3_code'],
					'helper3_name' => $v['helper3_name'],
					'per' => $v['per'],
					'customer' => $v['customer'],
					'activity_uom' => $v['activity_uom'],					
					'vehicle_license_no' => $v['vehicle_license_no'],
					'start_mileage' => $v['start_mileage'],
					'end_mileage' => $v['end_mileage'],
					'mileage' => $v['mileage'],
					'uom' => $v['uom'],
					'asset' => $v['asset'],
					'asset_maint_no' => $v['asset_maint_no'],
					'cost_center' => $v['cost_center'],
					'doc_currency' => $v['doc_currency'],
					'premi_rate' => $v['premi_rate'],
					'over_time' => $v['over_time'],
					'mat_1' => $v['mat_1'],
					'mat_desc1' => $v['mat_desc1'],
					'mat_qty1' => $v['mat_qty1'],
					'mat_uom1' => $v['mat_uom1'],
					'mat_2' => $v['mat_2'],
					'mat_desc2' => $v['mat_desc2'],
					'mat_qty2' => $v['mat_qty2'],
					'mat_uom2' => $v['mat_uom2'],
					'mat_3' => $v['mat_3'],
					'mat_desc3' => $v['mat_desc3'],
					'mat_qty3' => $v['mat_qty3'],
					'mat_uom3' => $v['mat_uom3'],
					'plant' => $v['plant'],
					'reason' => $v['reason'],
					'created_by' => $v['created_by'],
					'created_on' => $v['created_on'],
					'time' => $v['time'],
					'changed_by' => $v['changed_by'],
					'changed_on' => $v['changed_on'],
					'time_of_changed' => $v['time_of_changed']
				);
				 
				fputcsv($file, $data3);
				
			}
			fclose($file);
		}
		
	}
	
}

?>