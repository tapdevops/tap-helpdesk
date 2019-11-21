@extends('solution::layouts.master')
@extends('solution::layouts.navbartop')
@extends('solution::layouts.navmain')

{!! Html::style('assets/adminlte/plugins/datatables/dataTables.bootstrap.css') !!}
@section('content')

  <div class="content-wrapper">
      
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>Resolution Center</h1>
      </section>

      <!-- Main content -->
      <section class="content">

        <div class="box box-solid box-success">
          <div class="box-body">
              <table id="issues" class="table table-bordered table-striped">
                <thead>
                </thead>
                <tbody></tbody>
              </table>
          </div>
        </div>
      </section>
      <!-- /.content -->
  </div>

{!! Html::script('assets/adminlte/plugins/datatables/jquery.dataTables.min.js') !!}
{!! Html::script('assets/adminlte/plugins/datatables/dataTables.bootstrap.min.js') !!}

<script type="text/javascript">
  $(document).ready(function(){
    $("#issues").DataTable({
      serverSide: true,
      ajax: {
        url: "{{URL::to('solution/data').'/'.$menu_id}}",
        type: 'POST',
        data: function(d) {
          d.url = "{{Request::path()}}"
          d.menu_id = "{{$menu_id}}"
        },
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      },
      "columnDefs": [
        {"orderable" : false, "searchable": false, "targets":[3]},
      ],
      "columns": [
        {"data": "application_name", "title" : "Nama Aplikasi"},
        {"data": "issue_name", "title" : "Issue"},
        {"data": "description", "title" : "Keterangan"},
        {"data": "button", "title" : ""},
      ],
    });
    
  });
</script>

@stop
