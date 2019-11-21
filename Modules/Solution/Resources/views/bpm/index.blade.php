@extends('solution::layouts.master')
@extends('solution::layouts.navbartop')
@extends('solution::layouts.navmain')

{!! Html::style('assets/lib/noty/lib/noty.css') !!}
{!! Html::style('assets/lib/noty/demo/animate.css') !!}
{!! Html::style('assets/adminlte/plugins/datatables/dataTables.bootstrap.css') !!}

@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>BPM Search</h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-solid box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Search BPM No based on No. Document</h3>
        </div>
        <div class="box-body">
          <form action="" id="bpm_search" autocomplete="off">
            {{ csrf_field() }}
              <label class="control-label" for="bpm_doc"><i class="fa fa-at"></i> Kata kunci hanya berdasarkan No. Document</label>
            <div class="input-group input-group-sm">
              <input type="text" id="bpm_doc" class="form-control" name="nodoc">
                <span class="input-group-btn"> <button type="submit" class="btn btn-info btn-flat">Search!</button> </span>
            </div>
          </form>
          <table class="table table-bordered" id="bpmlist">
            <thead>
              <tr>
                <th>No. Document</th>
                <th>No. BPM</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <!-- /.box -->

    </section>
    <!-- /.content -->
  </div>
{!! Html::script('assets/adminlte/plugins/datatables/jquery.dataTables.min.js') !!}
{!! Html::script('assets/adminlte/plugins/datatables/dataTables.bootstrap.min.js') !!}
{!! Html::script('assets/lib/noty/lib/noty.js') !!}
{!! Html::script('assets/lib/isjs/is.js') !!}
{!! Html::script('assets/lib/JSON-js/json2.js') !!}
{!! Html::script('assets/lib/JSON-js/json_parse.js') !!}

<script type="text/javascript">
  $(document).ready(function(){
    window.bpm_no;
    $("form#bpm_search").submit(function(e){
      e.preventDefault();
      tableLoading();

      var tuples = [];
      $.post(
        "{{URL::to('/solution/bpmsearch')}}",
        $("form#bpm_search").serialize(),
        function(data) {
          $('table#bpmlist tbody').empty();
          bpm_no = data['data'];
          var rows = data['data'];
          $.each(rows, function(k,v){

            var tr = "<tr>"+
                     "<td>"+v.no_doc+"</td>"+
                     "<td>"+v.no_bpm+"</td>"+
                     "</tr>";
            // tuples.push( tr );
            $('table#bpmlist tbody').append(tr);
          });
        }
      )
    });


  });

  function tableLoading() {
    $('table#bpmlist tbody').empty();
    $('table#bpmlist tbody').append('<tr><td colspan="5"><div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></td></tr>');
  }
</script>
@stop
