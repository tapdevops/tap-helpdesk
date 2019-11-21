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
      <h1>Role Management</h1>
    </section>

    <section class="content">
      <div class="box box-solid box-success">

        <div class="box-header with-border">
          <h3 class="box-title">Roles</h3>
          <div class="box-tools pull-right">
            <button class="btn btn-xs bg-maroon btn-flat" data-toggle="modal" data-target="#role_modal"><i class="fa fa-asterisk"></i> New Role</button>
          </div><!-- /.box-tools -->
        </div><!-- /.box-header -->

        <div class="box-body">
          <table class="table table-condensed table-striped" id="grid"></table>
        </div>
      </div>
    </section>

    <div class="modal" id="role_modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span></button>
            <h4 class="modal-title">Default Modal</h4>
          </div>
          <form class="form-horizontal">
          <div class="modal-body">
            <div class="form-group">
              <input type="hidden" id="role_id" name="role_id">
              <label for="role_name" class="col-sm-2 control-label">Role Name</label>
              <div class="col-sm-10">
                <input type="text" class="form-control" id="role_name" name="role_name" placeholder="Role Name" required>
              </div>
            </div>
            <div class="form-group">
              <label for="role_description" class="col-sm-2 control-label">Description</label>
              <div class="col-sm-10">
                <textarea class="form-control" id="role_description" name="role_description" rows="3" placeholder="Description" required></textarea>
              </div>
            </div>
            <div class="form-group">
              <div class="col-sm-2 control-label"><strong>User Role</strong></div>
              <div class="col-sm-10">
                <?php echo $data ?>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-sm btn-primary pull-right" onclick="submitForm()"><strong>Save</strong></button>
          </div>
          </form>
        </div>
        <!-- /.modal-content -->
      </div>
      <!-- /.modal-dialog -->
    </div>

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
        url: "{{URL::to('solution/role/list')}}",
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      },
      columnDefs: [
        {"searchable": false, "targets":[2]},
        {"orderable" : false, "targets":[2]},

      ],
      columns: [
        {"data": "role_name", "title" : "Role"},
        {"data": "description", "title" : "Description"},
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

    $("form").submit(function(event){
      event.preventDefault();
    });
  });

  function popupUserRole(role_id, role_name, description) {
    $(':checkbox.menu_role').prop("checked", false);
    $('#role_id').val(role_id);
    $('#role_name').val(role_name);
    $('#role_description').val(description);
    $.ajax({
      method: 'post',
      url: '{{route("get_menu_access")}}',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: {role_id: role_id},
      success: function(data){
        var user_role = data.data;
        $.each(user_role, function(k,v){
          $(':checkbox.menu_role').each(function() {
            if($(this).val() == v) {
              $(this).prop('checked', true);
            }
          });
        });
      }
    });
  }

  function submitForm() {
    var noty = new Noty({
      layout: 'center', theme: 'bootstrap-v3', progressBar: true,
      closeWith: ['click', 'button'], animation: {open: 'noty_effects_open', close: 'animated zoomOutDown'},
      id: false, force: false, killer: false, queue: 'global', container: false, modal: true,
      buttons: [
        Noty.button('Close', 'btn btn-xs btn-primary pull-right', function () {
          noty.close();
        })
      ],
    }).on('onClose', function(){role_datatable.ajax.reload(); $('#role_modal').modal('hide')});
      
    $("form").validate({
      errorClass: "has-error text-danger",
      validClass: "has-success",
      errorElement: "em",
      highlight: function(element, errorClass, validClass) {
        $(element).parent().addClass(errorClass).removeClass(validClass);
        $(element).addClass(errorClass).removeClass(validClass);
        $(element.form).closest("label[for=" + element.id + "]").addClass(errorClass);
      },
      unhighlight: function(element, errorClass, validClass) {
        $(element).parent().removeClass(errorClass).addClass(validClass);
        $(element).removeClass(errorClass).addClass(validClass);
        $(element.form).closest("label[for=" + element.id + "]").removeClass(errorClass);
      }
    });
    if($("form").valid()) {

      $.ajax({
        method:"post",
        url: '{{route("save_role")}}',
        data: $('form').serialize(),
        headers: {
          "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
        },
        success: function(data) {
          noty.show();
          noty.setText('<p>'+data["s_message"]+'</p>');
          if(data.b_status) {
            noty.setType('success');
          } else {
            noty.setType('error');
          }
        }
      });
    }
  }


</script>
@stop
