@extends('solution::layouts.master')
@extends('solution::layouts.navbartop')
@extends('solution::layouts.navmain')

{!! Html::style('assets/adminlte/plugins/datatables/dataTables.bootstrap.css') !!}
{!! Html::style('assets/lib/bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css') !!}
@section('content')

  <style type="text/css">
    .table-wrapper{
      overflow-x: scroll;
    }
  </style>
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Execution History</h1>
    </section>

    <section class="content">
      <div class="box box-solid box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Execution Summary</h3>
        </div>
        <div class="box-body">
          <div class="col-sm-3 col-sm-offset-6">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
              <input type="text" class="form-control" id="datefirst" placeholder="YYYY-mm-dd">
            </div>  
          </div>
          <div class="col-sm-3">
            <div class="input-group">
              <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
              <input type="text" class="form-control" id="datelast" placeholder="YYYY-mm-dd">
            </div>
          </div>
          <hr>
          <div class="clearfix"></div>
          <table class="table table-bordered table-condensed" id="grid"></table>
        </div>
      </div>
    </section>

  </div>

  {!! Html::script('assets/js/moment.min.js') !!}
  {!! Html::script('assets/adminlte/plugins/datatables/jquery.dataTables.min.js') !!}
  {!! Html::script('assets/adminlte/plugins/datatables/dataTables.bootstrap.min.js') !!}
  {!! Html::script('assets/lib/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') !!}

<script type="text/javascript">
  $(document).ready(function(){
    grid = $("#grid").DataTable({
      processing: true,
      serverSide: true,
      autoWidth: false,
      ajax: {
        url: "{{URL::to('solution/report/list')}}",
        data: function(d){
          d.datefirst = $('#datefirst').val(),
          d.datelast = $('#datelast').val()
        },
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      },
      columnDefs: [
        {"searchable": false, "targets":[4]},
        {"orderable" : false, "targets":[4]},
      ],
      order: [[ 3, 'desc' ]],
      columns: [
        {"data": "application_name", "title" : "Applitaion Name", "name": "ss.application_name"},
        {"data": "issue_name", "title" : "Issue Name", "name": "ss.issue_name"},
        {"data": "execute_by", "title" : "Executed By", "name": "le.execute_by"},
        {"data": "execute_on", "title" : "Executed On", "name": "le.execute_on"},
        {"data": "button", "title" : ""},
      ],
      language: {
        "processing": "<div class='spinner' style='position:relative; top:50%'><div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div></div>"
      },
      infoCallback: function( settings, start, end, max, total, pre ) {
        var api = this.api();
        var pageInfo = api.page.info();
        return '<strong>Showing '+(1+pageInfo.start)+' to '+pageInfo.end+' of '+pageInfo.recordsDisplay +' entries</strong>';
      }
    });

    $('#datefirst').datetimepicker({
      format: "YYYY-MM-DD",
      useCurrent: false,
      showClear: true,
      maxDate: moment().format('YYYY-MM-DD'),
    });
    $('#datelast').datetimepicker({
      format: "YYYY-MM-DD",
      useCurrent: false,
      showClear: true,
    });
    $("#datefirst").on("dp.change", function (e) {
      $('#datelast').data("DateTimePicker").maxDate(e.date);
      grid.ajax.reload();
    });
    $("#datelast").on("dp.change", function (e) {
      $('#datefirst').data("DateTimePicker").minDate(e.date);
      grid.ajax.reload();
    });
  });

  function getParams() {
    var settings = $("#grid").dataTable().fnSettings();
    console.log(settings);
     
    var obj = {
      "datefirst": $('#datefirst').val(),
      "datelast": $('#datelast').val()
    };

    // var obj = {
    //   "datefirst": $('#datefirst').val(),
    //   "datelast": $('#datelast').val()
    // };
    return obj;
  }
</script>

@stop
