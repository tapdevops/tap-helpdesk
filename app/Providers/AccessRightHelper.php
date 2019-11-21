<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use API;
use Session;
class AccessRightHelper extends ServiceProvider
{
    static public function menu() {
        $username = Session::get('user');
        $service = API::exec(array(
            'request' => 'GET',
            'method' => "tr_role_accessright_menu/" . $username
        ));
        
       return $service->data;
    }

    static public function granted() {
        $current = str_replace(url('/') . '/', '', url()->current());

       if(empty(Session::get($current))) {
           return false;
       } else {
           return true;
       }
    }

    static public function access() {
        $current = str_replace(url('/') . '/', '', url()->current());
        $operation = Session::get($current);
        $access = array();
        foreach(explode('-', $operation) as $row) {
            $access[$row] = true;
        }

        return $access;
    }

    static public function grantAccess() {
        $username = Session::get('user');
        $service = API::exec(array(
            'request' => 'GET',
            'method' => "tr_role_accessright_menu/" . $username
        ));
        foreach($service->data as $row) {
            Session::put($row->url, $row->operation);
        }
    }

    static public function profile() {
        $username = Session::get('user');
        $service = API::exec(array(
            'request' => 'GET',
            'method' => "tr_user_profile/" . $username
        ));
        
        return $service->data;
    }
}
