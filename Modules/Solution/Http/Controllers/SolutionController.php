<?php

namespace Modules\Solution\Http\Controllers;

use Illuminate\Support\Facades\DB;
// use Yajra\Pdo\Oci8;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use URL;
use Session;
use Modules\Solution\Http\Models\Logexecution;

class SolutionController extends Controller
{

  private $db;

  function __construct()
  {
    $this->middleware('ceklogin');
    $this->db = DB::connection('app_db');
  }
  
  /**
   * Display a listing of the resource.
   * @return Response
   */
  public function index(Request $req)
  {
    return view('solution::dashboard.index');
  }
  
  public function frontend(Request $req, $menu_id)
  {
    return view('solution::dashboard.data', ['menu_id' => $menu_id]);
  }

  public function grid(Request $req)
  {
    $datasource = $req->input('url');
    $DT_search  = $req->input('search');
    $DT_start   = $req->input('start');
    $DT_limit   = $req->input('length');
    $DT_offset  = $req->input('start');
    $DT_order   = $req->input('order');
    $DT_where   = "";
    $DT_columns = ['APPLICATION_NAME', 'ISSUE_NAME', 'DESCRIPTION'];
    $order_by   = 'order by ';

    // foreach ($DT_order as $i_order) {
    for ( $i=0 ; $i<count($DT_order) ; $i++ ) {
      $cx = explode(' as ', $DT_columns[$DT_order[$i]['column']]);
      if(count($cx) > 1) {
        $alias = $cx[1];
      }
      else {
        $alias = $DT_columns[$DT_order[$i]['column']];
      }
      $order_by .= $alias.' '.$DT_order[$i]['dir'].' ';
    }

    if($req->input('menu_id')) {
      $DT_where = "WHERE PUBLISHED = 1 AND MENU_ID_CATEGORY = ".$req->input('menu_id');
    } else {
      $DT_where = "WHERE PUBLISHED = 1 ";
    }
    if($DT_search['value']) {
      $DT_where .= " AND (";
      for ( $i=0 ; $i<count($DT_columns) ; $i++ ) {
        $cx = explode(' as ', $DT_columns[$i]);
        if(count($cx) > 1) {
          $alias = $cx[0];
        }
        else {
          $alias = $DT_columns[$i];
        }
        $columns = $req->input('columns');
        if ($columns[$i]['searchable'] == "true" )
          { $DT_where .= "UPPER(".$alias.") LIKE '%".strtoupper( $DT_search['value'] )."%' OR "; }
      }
      $DT_where = substr_replace( $DT_where, "", -3 );
      $DT_where .= ")";
    }

    $sQuery = "SELECT SEQ_ID, APPLICATION_NAME, ISSUE_NAME, DESCRIPTION FROM TM_ISSUES $DT_where
               $order_by OFFSET $DT_offset ROWS FETCH NEXT $DT_limit ROWS ONLY";
    $q = $sQuery;

    $pdo = DB::connection('app_db')->getPdo();
    $aa = $pdo->prepare($sQuery);
    $aa->setFetchMode($pdo::FETCH_ASSOC);
    $aa->execute();
    $rResult = $aa->fetchAll();

    $sQuery = "SELECT APPLICATION_NAME, ISSUE_NAME, DESCRIPTION FROM TM_ISSUES $DT_where";

    $bb = $pdo->prepare($sQuery);
    $bb->setFetchMode($pdo::FETCH_NUM);
    $bb->execute(); $bb->fetchAll();
    $rResult2 = $bb->rowCount();

    // Generating outputs
    $output = array(
      "sEcho" => intval($req->input('draw')),
      "recordsTotal" => $rResult2,
      "recordsFiltered" => $rResult2,
      "data" => array()
    );

    // $j=$_GET['iDisplayStart'] ;
    foreach ($rResult as $aRow){
      $row = array();
      for ( $i=0 ; $i<count($DT_columns) ; $i++ ){
        $cx = explode(' as ', $DT_columns[$i]);
        if(count($cx) > 1) {
          $alias = $cx[1];
        } else {
          $alias = $DT_columns[$i];
        }

        if ( $DT_columns[$i] != null ){
          $row[strtolower($alias)] = $aRow[strtolower($alias)];
          $row['button'] = $this->_generateButton($aRow, $datasource);
        }
      }

      $output['data'][] = $row;
    }
    return $output ;
  }

  public function _generateButton($datarow, $url)
  {
    if($url != 'solution/resolution') {
      return '<a href="'.route('run_solution', $datarow['seq_id']).'" class="btn btn-xs btn-block btn-primary">Detail</a>';
    } else {
      $tombol  = '<div class="btn-group">';
      $tombol .= '  <button type="button" class="btn btn-flat btn-xs btn-primary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">';
      $tombol .= '    Action <span class="caret"></span>';
      $tombol .= '  </button>';
      $tombol .= '  <ul class="dropdown-menu pull-right" style="padding: 3px; margin: 0px; border-radius: 0px; box-shadow: 1px 1px 1px #ddd">';
      $tombol .= '    <li style="margin-bottom: 3px"><a href="'.route("update_issue").'" onclick="updateIssue('.$datarow["seq_id"].')" class="btn btn-xs btn-block text-green update_issue"><strong>Edit</strong></a></li>';
      $tombol .= '    <li><button type="button" class="btn btn-xs btn-block text-red" onclick="popUpDeleteIssue('.$datarow["seq_id"].')"><strong>Delete</strong></button></li>';
      $tombol .= '  </ul>';
      $tombol .= '</div>';
      return $tombol;
    }
  }

  public function issue(Request $req, $param_id)
  {
    $data['issue']  = $this->db->table("TM_ISSUES")->where('SEQ_ID',$param_id)->first();
    $data['params'] = $this->db->select("SELECT * FROM TM_PARAMETERS WHERE ISSUE_ID = ? ORDER BY ORDER_NUMBER", [$data['issue']['seq_id']]);
    return view('solution::issue.index', $data);
  }

  public function execute(Request $req)
  {
    $issue_id = $req->input('issue');
    $params   = $req->input('param');

    $issue = $this->db->table("TM_ISSUES")->where('SEQ_ID',$issue_id)->first();

    $querySolution = $issue['resolution_query'];
    foreach ($params as $key => $value) {
      $querySolution = str_replace('{{param:'.$key.'}}', $value, $querySolution);
    }

    $datasource = $this->db->table("TM_LOG_DATASOURCE")->where('issue_id',$issue['seq_id'])->get();

    $backups = [];
    $revert_query_string = '';
    $a_idx = 0;
    foreach($datasource as $data_logs) {
    $data_query = '';

      $datasource_query = $data_logs['data_log_query'];
      foreach ($params as $key => $value) {
        $datasource_query = str_replace('{{param:'.$key.'}}', $value, $datasource_query);
      }
      $data_query .= $datasource_query."\n";
      try {
        $data = $this->db->select($datasource_query);
        if(count($data)) {
          $b_idx = 0;
          // $revert_query_string = '';
          foreach ($data as $key) {
            if($b_idx == 0) {
              $revert_query_string .= 'DELETE FROM '.$data_logs['table_name'].' WHERE '.$key['primary_key_field'].' = \''.$key['primary_key_value'].'\'';
              $revert_query_string .= "\n";
            }
            $revert_query_string.= 'INSERT INTO '.$data_logs['table_name'];
            if($key != 'primary_key_field' || $key != 'primary_key_value') {
              foreach ($key as $k => $v) {
                $backups[$a_idx]['table_name'] = $data_logs['table_name']; 
                $backups[$a_idx]['primary_key_field'] = $key['primary_key_field']; 
                $backups[$a_idx]['primary_key_value'] = $key['primary_key_value']; 
                $backups[$a_idx]['parameter_value']   = $v;
                $backups[$a_idx++]['parameter_field'] = $k;
                if($k != 'primary_key_field') {
                  if($k != 'primary_key_value') {
                    $columns[$k] = $k;
                    $values[$k]  = $v;
                  }
                }
              }
            }
            $revert_query_string .= ' ("'.implode('","', $columns).'") values (\''.implode('\',\'', $values).'\')';
            $revert_query_string .= "\n";
            $b_idx++;
          }
        }
      } catch (\PDOException $e) {
        $line = preg_split("/((\r?\n)|(\r\n?))/", $e->getMessage());

        return response()->json(['b_status' => false, 's_message' => 'Solusi gagal dijalankan', 'detail_message' => $line[1]]);
      }
    }

    $log = new Logexecution();
    $log->issue_id            = $issue['seq_id'];
    $log->data_query          = $data_query;
    $log->resolution_query    = $querySolution;
    $log->revert_query_string = $revert_query_string;
    $log->execute_by          = $req->session()->get('email_address');
    $log->save();
    try {
      // dd($querySolution);
      $this->db->beginTransaction();

      foreach ($params as $key => $val) {
        $param_logs[] = ['parameter_field' => $key, 'parameter_value' => $val, 'execution_id' => $log->seq_id];
      }
      $this->db->table('tr_log_parameter')->insert($param_logs);

      $backup = []; $x = 0;
      foreach ($backups as $key) {
        $backup[$x] = $key;
        $backup[$x++]['execution_id'] = $log->seq_id;
      }

      $this->db->table('tr_log_databackup')->insert($backup);

      // run the final resolution query
      $this->db->statement($querySolution);      

    } catch (\PDOException $e) {
      $this->db->rollback();
      $line = preg_split("/((\r?\n)|(\r\n?))/", $e->getMessage());

      return response()->json(['b_status' => false, 's_message' => 'Solusi gagal dijalankans', 'detail_message' => $line[1]]);
    } finally {
      $this->db->commit();
      return response()->json(['b_status' => true, 's_message' => 'Solusi berhasil dijalankan', 'log_id' => $log->seq_id]);
    }
  }
}
