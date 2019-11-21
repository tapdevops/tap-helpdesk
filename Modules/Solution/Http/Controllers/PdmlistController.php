<?php

namespace Modules\Solution\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Yajra\Pdo\Oci8;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Session;
use URL;
use Modules\Solution\Http\Models\Issues;
use Modules\Solution\Http\Models\Solutions;

class PdmlistController extends Controller
{

  private $db;
  private $apiurl;
  private $tree;
  private $tree_generator;
  private $tree_list;


  function __construct()
  {
    $this->middleware('ceklogin');
    $this->db = DB::connection('app_db');
    $this->apiurl = config('other.api_url');
  }

  public function index(Request $req)
  {
    $tm_menu = $this->db->select('select * from tm_menu where published = 1 order by parent_id, seq_id');
    $menu_tree = $this->tree($tm_menu);
    $tree_list = $this->tree_list($menu_tree);
    return view('solution::pdmlist.pdmlist', ['data' => $tree_list]);
  }

    private function tree(&$list) {
    $tree = array();
    foreach ($list as $key ) {
      if($key['parent_id'] == 0) {
        $tree[$key['seq_id']] = $key;
        $sub_menu = $this->tree_generator($list, $key);
        if($sub_menu) {
          $tree[$key['seq_id']]['sub_menu'] = $sub_menu;
        }
      }
    }
    return $tree;
  }

  private function tree_generator(&$list, $parent) {
    $tree = array();
    foreach ($list as $k => $l) {
      if($parent['seq_id'] == $l['parent_id']){
        $x = $this->tree_generator($list, $l);
        if($x) {
          $l['sub_menu'] = $x;
        }
        $tree[] = $l;
      }
    } 
    return $tree;
  }

  private function tree_list(&$nested_list) {
    $list = '';
    foreach ($nested_list as $lists) {
      if(isset($lists['sub_menu'])) {
        $list .= '<div class="checkbox"><label><input type="checkbox" class="menu_role" name="roles_id[]" value="'.$lists['seq_id'].'">';
        $list .= $lists['menu_name'];
        $list .= '</label></div>';
        $list .= $this->tree_list($lists['sub_menu']);
      } else {
        $list .= '<div class="checkbox"><label><input type="checkbox" class="menu_role" name="roles_id[]" value="'.$lists['seq_id'].'">';
        $list .= $lists['menu_name'];
        $list .= '</label></div>';
      }
    }
    return $list;
  }

  private function additionalField() {
    return '<td></td><td></td>';
  }

  public function grid(Request $req) {
    $DT_search = $req->input('search');
    $DT_start  = $req->input('start');
    $DT_limit  = $req->input('length');
    $DT_offset = $req->input('start');
    $DT_order  = $req->input('order');
    $DT_where  = "";
    $DT_columns = ['DOC_CODE', 'MESSAGE', 'NIK', 'NAMA', 'USERNAME', 'AREA_CODE'];
    $order_by = 'order by ';

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

    if($DT_search['value']) {
      $DT_where = "WHERE ";

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
          { $DT_where .= 'UPPER('.$alias.") LIKE '%".strtoupper($DT_search['value'])."%' OR "; }
      }
      $DT_where = substr_replace( $DT_where, "", -3 );
    }

    $q = "SELECT * FROM tap_flow.V_PDM_FAILED_SYNCH_SAP_MSG $DT_where $order_by OFFSET $DT_offset ROWS FETCH NEXT $DT_limit ROWS ONLY";

    $data = $this->db->select($q);

    $count_query = "SELECT * FROM tap_flow.V_PDM_FAILED_SYNCH_SAP_MSG";
    $rResult2 = $this->db->select($count_query);

    $output = array(
      "sEcho" => intval($req->input('draw')),
      "recordsFiltered" => count($rResult2),
      "data" => $data,
    );
    return $output ;

//    return view('solution::pdmlist.test', ['data' => $data]);
  }

 
 

}
