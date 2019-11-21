<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use API;
use Session;
use DB;
class Select2tHelper extends ServiceProvider
{

    static public function tr_materials()
    {
        $service = API::exec(array(
            'request' => 'GET',
            'method' => "tr_materials/". ( !empty($_REQUEST['search']) ? $_REQUEST['search'] : '')
        ));
        $data = $service;

        return response()->json(array('data' => $data->data));
    }

    static public function tm_materials()
    {
        $service = API::exec(array(
            'request' => 'GET',
            'method' => "tm_materials/". ( !empty($_REQUEST['search']) ? $_REQUEST['search'] : '')
        ));
        $data = $service;

        return response()->json(array('data' => $data->data));
    }

    public function get_div()
    {
        $data = DB::table('tm_general_data')->where("general_code","div") ->get();
        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function plant()
    {
        $data = DB::table('tm_general_data')->where("general_code","plant ")->get();
        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function location()
    {
        $data = DB::table('tm_general_data')->where("general_code", "location")->get();

        $arr = array();
        foreach ($data as $row) {

            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_mrp_controller()
    {
        $data = DB::table('tm_general_data')->where("general_code", "mrp_controller")->get();
        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_valuation_class()
    {
        $data = DB::table('tm_general_data')->where("general_code", "valuation_class")->get();
        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_industry_sector()
    {
        $data = DB::table('tm_general_data')->where("general_code", "industry_sector")->get();
        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_material_type()
    {
        $data = DB::table('tm_general_data')->where("general_code", "material_type")->get();

        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_sales_org()
    {
        $data = DB::table('tm_general_data')->where("general_code", "sales_org")->get();
        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_dist_channel()
    {
        $data = DB::table('tm_general_data')->where("general_code", "dist_channel")->get();

        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_item_cat()
    {
        $data = DB::table('tm_general_data')->where("general_code", "item_cat")->get();
        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_tax_classification()
    {
        $data = DB::table('tm_general_data')->where("general_code", "tax_class")->get();
        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_account_assign()
    {
        $data = DB::table('tm_general_data')->where("general_code", "account_assign")->get();
        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_availability_check()
    {
        $data = DB::table('tm_general_data')->where("general_code", "avail_check")->get();

        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_transportation_group()
    {
        $data = DB::table('tm_general_data')->where("general_code", "trans_group")->get();

        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_loading_group()
    {
        $data = DB::table('tm_general_data')->where("general_code", "loading_group")->get();
        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_profit_center()
    {
        $data = DB::table('tm_general_data')->where("general_code", "profit_center")->get();
        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_mrp_type()
    {
        $data = DB::table('tm_general_data')->where("general_code", "mrp_type")->get();

        $arr = array();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function get_sle()
    {
        $data = DB::table('tm_general_data')->where("general_code", "sle")->get();
        foreach ($data as $row) {
            $arr[] = array(
                "id"=> $row->description_code,
                "text"=> $row->description_code ." - ".  $row ->description
            );
        }
        return response()->json(array('data' => $arr));
    }

    public function show(Request $request)
    {
        $service = API::exec(array(
            'request' => 'GET',
            'host' => 'ldap',
            'method' => "store_loc/" . $_REQUEST["id"]
        ));
        $data = $service;
        foreach ($data->data as $row) {
            $arr[] = array(
                "id" => $row->LGOR,
                "text" => $row->LGOR . " - " . str_replace("_", " ", $row->LGOBE)
            );
        }
        return response()->json(array('data' => $arr));

    }


}
