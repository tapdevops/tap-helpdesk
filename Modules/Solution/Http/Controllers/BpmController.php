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

class BpmController extends Controller
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
    return view('solution::bpm.index');
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

  public function search(Request $req)
  {
    $param = preg_replace('/\s+/', '', $req->nodoc);;
    $param = str_replace(",", "','", $param); 

    $q = "SELECT document_code, bpm_code from tap_flow.tr_assignment where document_code in ('".$param."')";
    $data = $this->db->select($q);

    try {
      $response_data = ['b_status' => true];
      if(count($data) > 0 ) {
        foreach ($data as $key) {
          $rs['no_doc']  = $key['document_code'];
          $rs['no_bpm']       = $key['bpm_code'];
          $response_data['data'][$key['bpm_code']] = $rs;
        }
      } else {
        $response_data = ['b_status' => false, 's_message' => 'No data found', 'param' => $param];
      }

      return $response_data;

    } catch (Exception $e) {
      return ['b_status' => false, 's_message' => 'No data found', 'param' => $param];
    }
  }

}