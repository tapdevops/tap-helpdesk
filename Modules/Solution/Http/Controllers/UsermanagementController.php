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
use URL;

class UsermanagementController extends Controller
{
  private $db;
  private $apiurl;

  function __construct()
  {
    $this->middleware('ceklogin');
    $this->db = DB::connection('app_db');
    $this->apiurl = config('other.api_url');
  }

  public function index(Request $req)
  {
    $roles = $this->db->table('TM_ROLE')->get();
    return view('solution::user.index', ['roles' => $roles]);
  }

  public function grid(Request $req)
  {
    $DT_search = $req->input('search');
    $DT_start  = $req->input('start');
    $DT_limit  = $req->input('length');
    $DT_offset = $req->input('start');
    $DT_order  = $req->input('order');
    $DT_where  = "";
    $DT_columns = ['UU.EMAIL_ADDRESS', 'UU.FULLNAME', 'RR.ROLE_NAME'];
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
      $DT_where = "and (";

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
      $DT_where .= ")";
    }

    $q = "SELECT UU.EMAIL_ADDRESS, UU.FULLNAME, UU.DOMAIN_NAME, LISTAGG(RR.ROLE_NAME, ', ') WITHIN GROUP (ORDER BY UU.EMAIL_ADDRESS) AS USER_ROLES FROM TM_USER_ROLE UR JOIN TM_ROLE RR ON RR.SEQ_ID = UR.ROLE_ID  JOIN TM_USER UU ON UU.EMAIL_ADDRESS = UR.EMAIL_ADDRESS $DT_where GROUP BY UU.EMAIL_ADDRESS, UU.FULLNAME, UU.DOMAIN_NAME $order_by OFFSET $DT_offset ROWS FETCH NEXT $DT_limit ROWS ONLY";
    $data = $this->db->select($q);

    $count_query = "SELECT UU.EMAIL_ADDRESS, UU.FULLNAME, UU.DOMAIN_NAME, LISTAGG(RR.ROLE_NAME, ', ') WITHIN GROUP (ORDER BY UU.EMAIL_ADDRESS) AS USER_ROLES FROM TM_USER_ROLE UR JOIN TM_ROLE RR ON RR.SEQ_ID = UR.ROLE_ID  JOIN TM_USER UU ON UU.EMAIL_ADDRESS = UR.EMAIL_ADDRESS $DT_where GROUP BY UU.EMAIL_ADDRESS, UU.FULLNAME, UU.DOMAIN_NAME";
    $rResult2 = $this->db->select($count_query);

    $output = array(
      "sEcho" => intval($req->input('draw')),
      "recordsFiltered" => count($rResult2),
      "data" => array(),
    );

    foreach ($data as $aRow){
      $row = $aRow;
      $row['button'] = '<button class="btn btn-xs btn-success" onclick="popupUserAdd(\''.$aRow["email_address"].'\',\''.$aRow["fullname"].'\',\''.$aRow["domain_name"].'\')" data-toggle="modal" data-target="#user_add_modal">Update</button>';
      $output['data'][] = $row;
    }
    return $output ;
  }

  public function getUserRole(Request $req)
  {
    if($req->input('email_address')) {
      $roles = $this->db->table("TM_USER_ROLE")->where('email_address', $req->input('email_address'));
      $role_id = array();
      if($roles->count() > 0) {
        foreach ($roles->get() as $role) {
          array_push($role_id, $role['role_id']);
        }
      }
      return response()->json(['b_status' => true, 'data' => $role_id]);
    } else {
      return response()->json(['b_status' => false, 's_message' => 'Invalid request']);
    }
  }

  public function search(Request $req)
  {
    $param = '';
    foreach ( $req->all() as $k => $v) {
      if($k != '_token') {
        $param .= $k.'='.$v.'&';
      }
    }
    $param = substr($param, 0, -1);

    $data = base64_encode('/ldap/search?'.$param);
    $encoded_url = str_replace(array('+','/','='),array('-','_',''),$data);
    $final_url = $this->apiurl.$encoded_url;

    $client = new Client();
    $result = $client->request('GET',$final_url);

    try {
      $ldap = $result->getBody();
      $account = json_decode($ldap);
      $response_data = ['b_status' => true];
      if(count($account->data) > 0 ) {
        foreach ($account->data as $key) {
          $action_button = '<button class="btn btn-xs btn-success" onclick="popupUserAdd(\''.$key->email.'\',\''.$key->name.'\',\''.$key->dn.'\')" data-toggle="modal" data-target="#user_add_modal">Action</button>';
          $rs['email_address']  = $key->email;
          $rs['fullname']       = $key->name;
          $rs['domain_name']    = $key->dn;
          $rs['button']         = $action_button;
          $response_data['data'][$key->email] = $rs;
        }
      } else {
        $response_data = ['b_status' => false, 's_message' => 'No data found'];
      }

      return $response_data;

    } catch (Exception $e) {
      return ['b_status' => false, 's_message' => 'No data found'];
    }
  }

  public function save(Request $req)
  {
    $user_exist = $this->db->table('tm_user')->where('email_address', $req->input('ldap_email'))->count();
    if($user_exist) {
      // update user
      $this->db->table('tm_user_role')->where('email_address', $req->input('ldap_email'))->delete();
      if($req->input('role_id')) {
        // delete all records
        $role = '';
        foreach ($req->input('role_id') as $key) {
          $data['email_address'] = $req->input('ldap_email');
          $data['role_id']       = $key;
          $data['created_by']    = $req->session()->get('email_address');
          $role[] = $data;
        }
        $this->db->table('tm_user_role')->insert($role);
        return response()->json(['b_status' => true, 's_message' => 'User access updated']);
      } else {
        $this->db->table('tm_user')->where('email_address', $req->input('ldap_email'))->delete();
        return response()->json(['b_status' => true, 's_message' => 'User access have been revoke']);
      }
    } else {
      // insert new if role is note empty
      if($req->input('role_id')) {
        $user['email_address']   = $req->input('ldap_email');
        $user['fullname']        = $req->input('ldap_name');
        $user['domain_name']     = $req->input('ldap_dn');
        $user['registered_by']   = $req->session()->get('email_address');
        $this->db->table('tm_user')->insert($user);

        $role = '';
        foreach ($req->input('role_id') as $key) {
          $data['email_address'] = $req->input('ldap_email');
          $data['role_id']       = $key;
          $data['created_by']    = $req->session()->get('email_address');
          $role[] = $data;
        }
        $this->db->table('tm_user_role')->insert($role);
        return response()->json(['b_status' => true, 's_message' => 'User access granted']);
      } else {
        return response()->json(['b_status' => false, 's_message' => 'Role can\'t be null']);
      }
    }


    // print_r(json_decode($ldap));
  }
}
