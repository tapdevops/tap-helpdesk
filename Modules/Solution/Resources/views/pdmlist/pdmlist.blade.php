@extends('solution::layouts.master')
@extends('solution::layouts.navbartop')
@extends('solution::layouts.navmain')

{!! Html::style('assets/lib/noty/lib/noty.css') !!}
{!! Html::style('assets/lib/noty/demo/animate.css') !!}
{!! Html::style('assets/adminlte/plugins/datatables/dataTables.bootstrap.css') !!}

@section('content')
  <style type="text/css">
    ul.checkbox { padding: 1.5em; }
    ul.checkbox ul { padding:0px; margin-top: 4px; }
    ul.checkbox li {
      padding: 4px 0px;
      border-bottom: solid 1px #34edfe;
    }
    ul.checkbox li:last-child {
      border-bottom:none;
      padding-bottom: 0px;
    }
    ul.checkbox ul li{ padding-left: 1.5em; }
    ul.checkbox ul li:first-child{ border-top: solid 1px #34edfe; }
  </style>
  
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>PDM List</h1>
    </section>

    <section class="content">
      <div class="box box-solid box-success">

        <div class="box-header with-border">
          <h3 class="box-title">PDM</h3>
        </div><!-- /.box-header -->

        <div class="box-body">
          <table class="table table-condensed table-striped" id="grid"></table>
        </div>
      </div>
    </section>

  </div>

  {!! Html::script('assets/adminlte/plugins/datatables/jquery.dataTables.min.js') !!}
  {!! Html::script('assets/adminlte/plugins/datatables/dataTables.bootstrap.min.js') !!}
  {!! Html::script('assets/lib/isjs/is.js') !!}
  {!! Html::script('assets/lib/noty/lib/noty.js') !!}
  {!! Html::script('assets/lib/validation/dist/jquery.validate.min.js') !!}

<script type="text/javascript">
  $(document).ready(function(){

    window.role_datatable = $("#grid").DataTable({
      processing: true,
      serverSide: true,
      autoWidth: false,
      ajax: {
        url: "{{URL::to('solution/pdm-list/list')}}",
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      },
      columnDefs: [
        {"searchable": false, "targets":[1]},
        {"orderable" : false, "targets":[1]},

      ],
      columns: [
        {"data": "doc_code", "title" : "Document Code"},
        {"data": "message", "title" : "Message"},
        {"data": "nik", "title" : "Nik"},
        {"data": "nama", "title" : "Nama"},
        {"data": "username", "title" : "Username"},
        {"data": "area_code", "title" : "Area Code"},
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

    $("form").submit(function(event){
      event.preventDefault();
    });
  });

//['DOC_CODE', 'MESSAGE', 'NIK', 'NAMA', 'USERNAME', 'AREA_CODE']


</script>
@stop
