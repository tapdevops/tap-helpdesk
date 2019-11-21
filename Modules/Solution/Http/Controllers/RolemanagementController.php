<?php

namespace Modules\Solution\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Yajra\Pdo\Oci8;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Session;
use URL;
use Modules\Solution\Http\Models\Roles;


class RolemanagementController extends Controller
{
  private $db;
  private $tree;
  private $tree_generator;
  private $tree_list;

  function __construct() {
    $this->middleware('ceklogin');
    $this->db = DB::connection('app_db');
  }

  public function index(Request $req) {
    $tm_menu = $this->db->select('select * from tm_menu where published = 1 order by parent_id, seq_id');
    $menu_tree = $this->tree($tm_menu);
    $tree_list = $this->tree_list($menu_tree);
    return view('solution::user.role', ['data' => $tree_list]);
  }

  public function save(Request $req) {
    $input = $req->input();
    if(isset($input["role_id"])) {
      // update records
      try {
        $role = Roles::find($input["role_id"]);
        $role->role_name   = $req->input('role_name');
        $role->description = $req->input('role_description');
        $role->save();

        $this->db->delete('delete from tm_role_detail where role_id = ?', [$input["role_id"]]);

        $tm_role_detail = [];
        foreach ($req->input('roles_id') as $ds) {
          $data = [
            'role_id'    => $input["role_id"],
            'menu_id'    => $ds,
            'acl_create' => intval(1),
            'acl_update' => intval(1),
            'acl_delete' => intval(1),
            'acl_view'   => intval(1)
          ];
          array_push($tm_role_detail, $data);
        }
        $this->db->table('tm_role_detail')->insert($tm_role_detail);
      } catch (\PDOException $e) {
        return ['b_status' => false, 's_message' => 'Gagal menyimpan data hasil update', 'd_message' => $e->getMessage()];
      } finally {
        return ['b_status' => true, 's_message' => 'Data berhasil diupdate'];
      }
    } else {
      // insert new
      try {
        $role = new Roles();
        $role->role_name   = $req->input('role_name');
        $role->description = $req->input('role_description');
        $role->created_by  = $req->session()->get('email_address');
        $role->save();

        $tm_role_detail = [];
        foreach ($req->input('roles_id') as $ds) {
          $data = [
            'role_id'    => $role->seq_id,
            'menu_id'    => $ds,
            'acl_create' => intval(1),
            'acl_update' => intval(1),
            'acl_delete' => intval(1),
            'acl_view'   => intval(1)
          ];
          array_push($tm_role_detail, $data);
        }
        $this->db->table('tm_role_detail')->insert($tm_role_detail);

      } catch (\PDOException $e) {
        return ['b_status' => false, 's_message' => 'Gagal menyimpan data baru', 'd_message' => $e->getMessage()];
      } finally {
        return ['b_status' => true, 's_message' => 'Data berhasil disimpan'];
      }
    }
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
    $DT_columns = ['ROLE_NAME', 'DESCRIPTION'];
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

    $q = "SELECT * FROM TM_ROLE $DT_where $order_by OFFSET $DT_offset ROWS FETCH NEXT $DT_limit ROWS ONLY";
    $data = $this->db->select($q);

    $count_query = "SELECT * FROM TM_ROLE";
    $rResult2 = $this->db->select($count_query);

    $output = array(
      "sEcho" => intval($req->input('draw')),
      "recordsFiltered" => count($rResult2),
      "data" => array(),
    );

    foreach ($data as $aRow){
      $row = $aRow;
      $row['button'] = '<button class="btn btn-xs btn-success" data-toggle="modal" data-target="#role_modal" onClick="popupUserRole(\''.$aRow['seq_id'].'\',\''.$aRow['role_name'].'\',\''.$aRow['description'].'\')">Update</button>';
      $output['data'][] = $row;
    }
    return $output ;
  }

  public function getRoleAccess(Request $req)
  {
    if($req->input('role_id')) {
      $roles = $this->db->table("TM_ROLE_DETAIL")->where('role_id', $req->input('role_id'));
      $menu_id = array();
      if($roles->count() > 0) {
        foreach ($roles->get() as $role) {
          array_push($menu_id, $role['menu_id']);
        }
      }
      return response()->json(['b_status' => true, 'data' => $menu_id]);
    } else {
      return response()->json(['b_status' => false, 's_message' => 'Invalid request']);
    }
  }
}
