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

class ResolutionController extends Controller
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
    return view("solution::resolution.index");
  }

  public function create(Request $req)
  {
    $data = $this->db->select("select distinct menu_name, seq_id from tm_menu where menu_category = 'FRONTEND'");
    return view("solution::resolution.create", ['category' => $data]);
  }

  public function issueDetail(Request $req, $issue_id = null)
  {
    $issue_id = $req->input('issue_id');
    $data['category'] = $this->db->select("select distinct menu_name, seq_id from tm_menu where menu_category = 'FRONTEND'");
    $data['issue_header'] = $this->db->table("tm_issues")->where('seq_id', $issue_id)->first();
    $data['issue_datasource'] = $this->db->table("tm_log_datasource")->where('issue_id', $issue_id)->get();
    $data['issue_parameters'] = $this->db->table("tm_parameters")->where('issue_id', $issue_id)->orderBy('order_number', 'asc')->get();

    return view("solution::resolution.update", $data);
  }

  public function save(Request $req)
  {
    $input = $req->input();

   if(isset($input["issue_id"])) {
      // update last records
      try {
        $this->db->beginTransaction();

        $exception = false;

        $issue = Issues::find($input["issue_id"]);
        $issue->application_name     = $req->input('application_name');
        $issue->issue_name           = $req->input('issue_summary');
        $issue->description          = $req->input('issue_detail');
        $issue->solution_description = $req->input('solution_name');
        $issue->resolution_query     = $req->input('resolution');
        $issue->menu_id_category     = $req->input('menu_id_category');
        $issue->created_by           = $req->session()->get('email_address');
        $issue->save();

        $this->db->delete('delete from tm_log_datasource where issue_id = ?', [$input["issue_id"]]);
        $this->db->delete('delete from tm_parameters where issue_id = ?', [$input["issue_id"]]);

        $tm_log_datasource = []; $i = 1;
        foreach ($req->input('datasource') as $ds) {
          $data = [
            'issue_id'       => $issue->seq_id,
            'table_name'     => $ds['table_name'],
            'data_log_query' => $ds['data_source'],
            'created_by'     => $req->session()->get('email_address'),
            'order_number'   => $i++
          ];
          array_push($tm_log_datasource, $data);
        }
        $this->db->table('tm_log_datasource')->insert($tm_log_datasource);

        $tm_parameters = []; $i = 1;
        foreach ($req->input('parameters') as $param) {
          $data = [
            'issue_id'   => $issue->seq_id,
            'param_label' => $param["parameter_label"],
            'param_field' => $param["parameter_field"],
            'placeholder' => $param["placeholder"],
            'created_by'  => $req->session()->get('email_address'),
            'order_number'   => $i++
          ];
          array_push($tm_parameters, $data);
        }
        $this->db->table('tm_parameters')->insert($tm_parameters);
      } catch (\PDOException $e) {
        $exception = true;
        $this->db->rollback();
      } finally {
        if(!$exception) {
          $this->db->commit();
          return ['b_status' => true, 's_message' => 'Data berhasil diupdate'];
        } else {
          return ['b_status' => false, 's_message' => 'Gagal menyimpan data hasil update', 'd_message' => $e->getMessage()];
        }
      }
   } else {
      // new records
      try {
        $this->db->beginTransaction();

        $exception = false;

        $issue = new Issues();
        $issue->application_name     = $req->input('application_name');
        $issue->issue_name           = $req->input('issue_summary');
        $issue->description          = $req->input('issue_detail');
        $issue->solution_description = $req->input('solution_name');
        $issue->resolution_query     = $req->input('resolution');
        $issue->menu_id_category     = $req->input('menu_id_category');
        $issue->created_by           = $req->session()->get('email_address');
        $issue->save();

        $tm_log_datasource = []; $i = 1;
        foreach ($req->input('datasource') as $ds) {
          $data = [
            'issue_id'       => $issue->seq_id,
            'table_name'     => $ds['table_name'],
            'data_log_query' => $ds['data_source'],
            'created_by'     => $req->session()->get('email_address'),
            'order_number'   => $i++
          ];
          array_push($tm_log_datasource, $data);
        }
        $this->db->table('tm_log_datasource')->insert($tm_log_datasource);

        $tm_parameters = [];
        foreach ($req->input('parameters') as $param) {
          $data = [
            'issue_id'   => $issue->seq_id,
            'param_label' => $param["parameter_label"],
            'param_field' => $param["parameter_field"],
            'created_by'  => $req->session()->get('email_address')
          ];
          array_push($tm_parameters, $data);
        }
        $this->db->table('tm_parameters')->insert($tm_parameters);
      } catch (\PDOException $e) {
        $exception = true;
        $this->db->rollback();
      } finally {
        if(!$exception) {
          $this->db->commit();
          return ['b_status' => true, 's_message' => 'Data berhasil disimpan'];
        } else {
          return ['b_status' => false, 's_message' => 'Gagal menyimpan data baru', 'd_message' => $e->getMessage()];
        }
      }
   }
  }

  public function delete(Request $req)
  {
    $issue_id = $req->input('issue_id');
    try {
      $this->db->beginTransaction();

      $exception = false;

      $issue = Issues::find($issue_id);
      $issue->published = intval(0);
      $issue->save();
    } catch (\PDOException $e) {
      $exception = true;
      $this->db->rollback();
    } finally {
      if(!$exception) {
        $this->db->commit();
        return response()->json(['b_status' => true, 's_message' => 'Data is deleted']);
      } else {
        return response()->json(['b_status' => false, 's_message' => 'Deleting data <strong>FAILED</strong>']);
      }
    }
  } 

}
