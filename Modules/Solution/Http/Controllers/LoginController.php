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
use Redirect;
use URL;

class LoginController extends Controller
{
  private $apiurl;
  private $menu_generator;
  private $tree_generator;
  private $bootstrap_menu_generator;

  function __construct()
  {
    $this->apiurl = config('other.api_url');
    $this->db = DB::connection('app_db');
  }
  
  public function index(Request $req)
  {
    return view('solution::layouts.login');
  }

  public function doLogin(Request $req)
  {
    $username = $req->input('username');
    $password = $req->input('password');

    if($req->isMethod('post')) {
      $get_param = 'username='.$username.'&password='.$password;

      $data = base64_encode('/login?'.$get_param);
      $encoded_url = str_replace(array('+','/','='),array('-','_',''),$data);
      $final_url = $this->apiurl.$encoded_url;

      $client = new Client();

        $result = $client->request('GET',$final_url);
        $auth = json_decode($result->getBody());



      //if($auth->valid) {
        $user_exist = $this->db->table('tm_user')->where('email_address', 'like', $req->input('username').'%')->count();

        if($user_exist  != 0) {
          $user = $this->db->table('tm_user')->where('email_address', 'like', $req->input('username').'%')->first();
          $roles = $this->db->select('select rr.role_name, ur.role_id from tm_user_role ur join tm_role rr on rr.seq_id = ur.role_id where ur.email_address = ?', [$user["email_address"]]);

          $role = [];
          foreach ($roles as $r) {
            $role[] = $r;
          }

          Session::set('is_logged_in', true);
          Session::set('role', $role);
          Session::set('email_address', $user["email_address"]);
          Session::set('fullname', $user["fullname"]);

          $menu_role = $this->db->select('select ur.email_address, rr.role_name, mm.parent_id, mm.menu_category, mm.menu_name, mm.menu_url, mm.menu_icon, rd.* from tm_role_detail rd join tm_menu mm on mm.seq_id = rd.menu_id join tm_role rr on rr.seq_id = rd.role_id join tm_user_role ur on ur.role_id = rr.seq_id where ur.email_address = ? order by mm.menu_category desc, mm.parent_id, mm.seq_id asc', [$user["email_address"]]);

          $menu = $this->menu_generator($menu_role);
          $menu_string  = $this->bootstrap_menu_generator($menu);
          Session::set('menu', $menu_string);

          $redirect_to = Session::get('redirect_to');
          if ($redirect_to != null) {
            return Redirect($redirect_to);
          }else{
            return Redirect('/solution');
          }
        } else {
            return Redirect('/solution/login')->with('warning_message', $this->warning_message('Login not Authorized'));
        }
        // return redirect()->route('solution/');
    //  } else {
    //    return Redirect('/solution/login')->with('warning_message', $this->warning_message('Login not Authorized'));
    //  }
    } else {
      return Redirect('/solution/login')->with('warning_message', $this->warning_message('Login not Authorized'));
    }
  }

  public function logout(Request $req)
  {
    Session::flush();
    Session::regenerate();
    $url = URL::to('/');
    Session::put('redirect_to', $url);
    return Redirect($url);
  }

  private function menu_generator(&$list){
    $tree = array();
    foreach ($list as $key ) {
      if($key['parent_id'] == 0) {
        $tree[$key['menu_id']] = $key;
        $tree[$key['menu_id']]['sub_menu'] = $this->tree_generator($list, $key);
      }
    }
    return $tree;
  }

  private function tree_generator(&$list, $parent){
    $tree = array();
    foreach ($list as $k => $l) {
      if($parent['menu_id'] == $l['parent_id']){
        $x = $this->tree_generator($list, $l);
        if($x) {
          $l['sub_menu'] = $x;
        } else {
          unset($l['sub_menu']);
        }
        $tree[] = $l;
      }
    } 
    return $tree;
  }

  private function bootstrap_menu_generator(&$menu) {
    $xmenu      = '';
    $menu_class = '';
    $e_style    = '';
    foreach ($menu as $key) {
      ($key["menu_category"] == 'FRONTEND')? $menu_url = URL::to($key["menu_url"]).'/'.$key["menu_id"] : $menu_url = URL::to($key["menu_url"]);
      if($key['menu_icon']) {
        $menu_icon = '<i class="'.$key['menu_icon'].'"></i> ';
      }
      else {
        $menu_icon = '<i class="fa fa-circle-o"></i> '; 
      }

      if(isset( $key['sub_menu'] ) ) {
        if(count($key['sub_menu']) > 0) {
          if($key['parent_id'] == '0') {
            $xmenu .= '<li class="treeview">';
            $xmenu .= '<a href="#">'.$menu_icon.' <span>'.$key["menu_name"].'</span>';
            $xmenu .= '  <span class="pull-right-container">';
            $xmenu .= '    <i class="fa fa-angle-left pull-right"></i>';
            $xmenu .= '  </span>';
            $xmenu .= '</a>';
            $xmenu .= '<ul class="treeview-menu menu-open" style="display: block;">';
            $xmenu .= $this->bootstrap_menu_generator($key['sub_menu']);
            $xmenu .= '</ul></li>';
          }
          else {
            $xmenu .= '<li class="">';
            $xmenu .= '  <a href="'.$menu_url.'">'.$menu_icon.$key["menu_name"];
            $xmenu .= '  <span class="pull-right-container">';
            $xmenu .= '    <i class="fa fa-angle-left pull-right"></i>';
            $xmenu .= '  </span>';
            $xmenu .= '</a>';
            $xmenu .= '<ul class="treeview-menu menu-open" style="display: block;">';
            $xmenu .= $this->bootstrap_menu_generator($key['sub_menu']);
            $xmenu .= '</ul></li>';
          }
        }
        else {
          $xmenu .= '<li class=""><a href="'.$menu_url.'">'.$menu_icon.$key["menu_name"].'</a></li>';
        }
      }
      else {
        $xmenu .= '<li class=""><a href="'.$menu_url.'">'.$menu_icon.$key["menu_name"].'</a></li>';
      }
    }
    return $xmenu;
  }

  function warning_message($error_message){
    $message = "<div class='alert alert-warning alert-dismissible' role='alert'>
        <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button>
        <strong>Warning!</strong> ".$error_message." .
      </div>";
    return $message;
  }
}
