@extends('solution::layouts.master')
@extends('solution::layouts.navbartop')
@extends('solution::layouts.navmain')

{!! Html::style('assets/adminlte/plugins/datatables/dataTables.bootstrap.css') !!}
{!! Html::style('assets/lib/noty/lib/noty.css') !!}
{!! Html::style('assets/lib/noty/demo/animate.css') !!}

@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Issue Management</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <!-- Default box -->
      <div class="box box-solid box-success">
        <div class="box-header with-border">
          <h3 class="box-title">&nbsp;</h3>
          <div class="box-tools pull-right">
            <a href="{{URL::to('solution/resolution/create')}}" class="btn btn-xs bg-maroon btn-flat"><i class="fa fa-asterisk"></i> New Solution</a>
          </div>
        </div>
        <div class="box-body">
          <table id="issues" class="table table-bordered table-striped">
            <thead>
            </thead>
            <tbody></tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>
      <!-- /.box -->
    </section>
    <!-- /.content -->
  </div>
{!! Html::script('assets/adminlte/plugins/datatables/jquery.dataTables.min.js') !!}
{!! Html::script('assets/adminlte/plugins/datatables/dataTables.bootstrap.min.js') !!}
{!! Html::script('assets/lib/noty/lib/noty.js') !!}

<script type="text/javascript">
  $(document).ready(function(){
    window.issue_datatable = $("#issues").DataTable({
      serverSide: true,
      ajax: {
        url: "{{URL::to('solution/data')}}",
        type: 'POST',
        data: function(d) {
          d.url = "{{Request::path()}}"
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
      "drawCallback": function( settings ) {
        $('a.update_issue').click(function(e){
          e.preventDefault();
        });
      }
    });
  });

  function updateIssue(issue_id) {
    var form = document.createElement("form");
    form.setAttribute("method", "post");
    form.setAttribute("action", "{{route('update_issue')}}");

    var f_1 = document.createElement("input");
    f_1.setAttribute("type", "hidden");
    f_1.setAttribute("name", "issue_id");
    f_1.setAttribute("value", issue_id);
    form.appendChild(f_1);

    var f_2 = document.createElement("input");
    f_2.setAttribute("type", "hidden");
    f_2.setAttribute("name", "_token");
    f_2.setAttribute("value", $('meta[name="csrf-token"]').attr('content'));
    form.appendChild(f_2);

    document.body.appendChild(form);
    form.submit();
  }

  function popUpDeleteIssue(issue_id) {
    var noty = new Noty({
      layout: 'center', theme: 'bootstrap-v3', text: 'Interactive example', progressBar: true,
      closeWith: ['button'], animation: {open: 'noty_effects_open', close: null},
      id: false, force: false, killer: false, queue: 'global', container: false, modal: true,
      text: '<p><strong>Warning !</strong></p><p>Once deleted, data can not be restore. Continue deleting data?</p>',
      buttons: [
        Noty.button('Cancel', 'btn btn-xs btn-primary', function () {
          noty.close();
        }),
        Noty.button('<strong>Delete</strong>', 'btn btn-xs btn-danger pull-right', function () {
          deleteIssue(issue_id);
          noty.close();
        })
      ],
    });

    noty.show();

  }

  function deleteIssue(issue_id) {
    var noty = new Noty({
      layout: 'center', theme: 'bootstrap-v3', text: 'Interactive example', progressBar: true,
      closeWith: ['click', 'button'], animation: {open: 'noty_effects_open', close: 'animated zoomOutDown'},
      id: false, force: false, killer: false, queue: 'global', container: false, modal: true,
    }).on('onClose', function(){issue_datatable.ajax.reload()});

    noty.show();
    noty.setText("<div class='spinner' style='position:relative; top:50%'><div class='bounce1'></div><div class='bounce2'></div><div class='bounce3'></div></div>");

    $.ajax({
      type: "post",
      url: "{{route('delete_issue')}}",
      headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content') },
      data: {issue_id: issue_id},
      success: function(data){
        noty.setText(data.s_message);
      }
    })
  }
</script>

@stop
