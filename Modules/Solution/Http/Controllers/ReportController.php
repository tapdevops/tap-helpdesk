<?php

namespace Modules\Solution\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Yajra\Pdo\Oci8;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Session;
use Redirect;
use URL;

class ReportController extends Controller
{
  private $apiurl;
  private $menu_generator;
  private $tree_generator;
  private $bootstrap_menu_generator;

  function __construct()
  {
    $this->middleware('ceklogin');
    $this->apiurl = config('other.api_url');
    $this->db = DB::connection('app_db');
  }
  
  public function index(Request $req){
    return view('solution::report.index');
  }

  public function detail(Request $req, $execution_id = false)
  {
    if($req->is('post')){
      $execution_id = $req->input('execution_id');
    }

    try {
      $summary = $this->db->table('HELPDESK.TR_LOG_EXECUTION')->where('SEQ_ID',$execution_id);
      if($summary->count() == 0) {
        return view('errors.custom');
      } else {
        $summary = $summary->first();
      }
    } catch (\PDOException $e) {
      return view('errors.custom');
    }

    $parameters = $this->db->select('SELECT * FROM HELPDESK.TR_LOG_PARAMETER WHERE EXECUTION_ID = ?', [$execution_id]);
    $report = $this->db->select('SELECT * FROM HELPDESK.TR_LOG_DATABACKUP WHERE EXECUTION_ID = ?', [$execution_id]);
    $fields = $this->db->select('SELECT DISTINCT A.PARAMETER_FIELD, A.TABLE_NAME FROM HELPDESK.TR_LOG_DATABACKUP A 
                                         WHERE A.EXECUTION_ID = ? ORDER BY TABLE_NAME', [$execution_id]);

    $summary['parameters'] = collect($parameters)->toArray();
    $data['report'] = collect($report)->sortBy('primary_key_value')
                      ->groupBy('table_name')->map(function($data, $table_name){
                        $data_map = $data->groupBy('primary_key_value')->map(function($item, $key) {
                          $map = $item->mapWithKeys(function($item_, $key_) {
                            $maps = [$item_['parameter_field'] => $item_['parameter_value']];
                            return $maps;
                          });
                          return $map;
                        });
                        return $data_map;
                      })->toArray();

    $data['fields'] = collect($fields)->all();
    $data['summary'] = $summary;

    return view('solution::report.detail', $data);
  }

  public function grid(Request $req)
  {
    $DT_search = $req->input('search');
    $DT_start  = $req->input('start');
    $DT_limit  = $req->input('length');
    $DT_offset = $req->input('start');
    $DT_order  = $req->input('order');
    $DT_columns = $req->input('columns');
    $DT_where  = "";
    $order_by = 'order by ';

    // foreach ($DT_order as $i_order) {
    for ( $i=0 ; $i<count($DT_order) ; $i++ ) {
      $order_by .= $DT_columns[$DT_order[$i]['column']]['name'].' '.$DT_order[$i]['dir'].' ';
    }

    if($DT_search['value']) {
      $DT_where = "WHERE ";

      for ( $i=0 ; $i<count($DT_columns) ; $i++ ) {
        if ($DT_columns[$i]['searchable'] == "true" )
          { $DT_where .= 'UPPER('.$DT_columns[$i]['name'].") LIKE '%".strtoupper($DT_search['value'])."%' OR "; }
      }
      $DT_where = substr_replace( $DT_where, "", -3 );
    }

    if(trim($req->input('datefirst')) != '') {
      if($DT_where == "") {
        $DT_where = "WHERE ";
      } else {
        $DT_where .= " AND " ;
      }
      if($req->input('datelast') != '') {
        $DT_where .= 'to_char(LE.EXECUTE_ON, \'YYYY-MM-DD\') between \''.$req->input('datelast').'\' and \''.$req->input('datefirst').'\'';
      } else {
        $DT_where .= 'to_char(LE.EXECUTE_ON, \'YYYY-MM-DD\') = \''.$req->input('datefirst').'\'';
      }
    }

    $q = "SELECT SS.APPLICATION_NAME, SS.ISSUE_NAME, LE.EXECUTE_BY, LE.EXECUTE_ON, LE.SEQ_ID
          FROM TR_LOG_EXECUTION LE JOIN TM_ISSUES SS ON SS.SEQ_ID = LE.ISSUE_ID 
          $DT_where $order_by OFFSET $DT_offset ROWS FETCH NEXT $DT_limit ROWS ONLY";
    $data = $this->db->select($q);

    $count_query = "SELECT * FROM TR_LOG_EXECUTION LE JOIN TM_ISSUES SS ON SS.SEQ_ID = LE.ISSUE_ID $DT_where";
    $rResult2 = $this->db->select($count_query);

    $output = array(
      "sEcho" => intval($req->input('draw')),
      "recordsFiltered" => count($rResult2),
      "data" => array(),
      "sql" => $q
    );

    foreach ($data as $aRow){
      $row = $aRow;
      $row['button'] = '<a class="btn btn-xs btn-primary" href="'.url('solution/report/'.$aRow['seq_id']).'">Detail</a>';
      $output['data'][] = $row;
    }
    return $output ;
  }
}
