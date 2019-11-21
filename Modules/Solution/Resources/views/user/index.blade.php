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
      <h1>User Management</h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Default box -->
      <div class="box box-solid box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Search User based on LDAP</h3>
        </div>
        <div class="box-body">
          <form action="" id="ldap_search" autocomplete="off">
            {{ csrf_field() }}
              <label class="control-label" for="ldap_email"><i class="fa fa-at"></i> Kata kunci hanya berdasarkan email address</label>
            <div class="input-group input-group-sm">
              <input type="text" id="ldap_email" class="form-control" name="email">
                <span class="input-group-btn"> <button type="submit" class="btn btn-info btn-flat">Search!</button> </span>
            </div>
          </form>
          <table class="table table-bordered" id="userlist">
            <thead>
              <tr>
                <th>Email Address</th>
                <th>Fullname</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      <!-- /.box -->

      <div class="box box-solid box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Registered User</h3>
        </div>
        <div class="box-body">
          <table class="table table-bordered" id="registered_user">
            <thead>
              <tr>
                <th>Email Address</th>
                <th>Fullname</th>
                <th>Role</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
      
      <div class="modal" id="user_add_modal">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Default Modal</h4>
            </div>
            <form action="" id="ldap_add_user">
            <div class="modal-body">
              <p>Assign <strong><span class="ldap_email"></span></strong> to roles:</p>
                <input type="hidden" name="ldap_name" class="ldap_name">
                <input type="hidden" name="ldap_email" class="ldap_email">
                <input type="hidden" name="ldap_dn" class="ldap_dn">
                <div class="form-group">
                @foreach ($roles as $role)
                  <div class="checkbox">
                    <label>
                      <input type="checkbox" class="user_role" name="role_id[]" value="{{ $role['seq_id'] }}"> {{ $role["role_name"] }}
                    </label>
                  </div>
                @endforeach
                </div>                  
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-primary" onclick="submit_form()">Save changes</button>
            </div>
            </form>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
      </div>

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
    window.ldap_user;
    $("form#ldap_search").submit(function(e){
      e.preventDefault();
      tableLoading();

      var tuples = [];
      $.post(
        "{{URL::to('/solution/ldapsearch')}}",
        $("form#ldap_search").serialize(),
        function(data) {
          $('table#userlist tbody').empty();
          ldap_user = data['data'];
          var rows = data['data'];
          $.each(rows, function(k,v){

            var tr = "<tr>"+
                     "<td>"+v.email_address+"</td>"+
                     "<td>"+v.fullname+"</td>"+
                     "<td>"+v.button+"</td>"+
                     "</tr>";
            // tuples.push( tr );
            $('table#userlist tbody').append(tr);
          });
        }
      )
    });

    window.user_datatable = $("#registered_user").DataTable({
      processing: true,
      serverSide: true,
      autoWidth: false,
      ajax: {
        url: "{{URL::to('solution/userlist')}}",
        type: 'POST',
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      },
      columnDefs: [
        {"searchable": false, "targets":[3]},
        {"orderable" : false, "targets":[2,3]},

      ],
      columns: [
        {"data": "email_address", "title" : "Email"},
        {"data": "fullname", "title" : "Fullname"},
        {"data": "user_roles", "title" : "Role"},
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

  });

  function popupUserAdd(email_address, fullname, dn, role_id) {
    $(':checkbox.user_role').prop("checked", false);
    $('h4.modal-title').html(fullname);
    $('span.ldap_email').html(email_address);
    $('.ldap_name').val(fullname);
    $('.ldap_email').val(email_address);
    $('.ldap_dn').val(dn);
    $.ajax({
      method: 'post',
      url: '{{route("get_role")}}',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: {email_address: email_address},
      success: function(data){
        var user_role = data.data;
        $.each(user_role, function(k,v){
          $(':checkbox.user_role').each(function() {
            if($(this).val() == v) {
              $(this).prop('checked', true);
            }
          });
        });
      }
    });
  }

  function submit_form() {
    var noty = new Noty({
      type: 'warning', layout: 'center', theme: 'bootstrap-v3', text: 'Interactive example', progressBar: true,
      closeWith: ['click', 'button'], animation: {open: 'noty_effects_open', close: 'animated zoomOutDown'},
      id: false, force: false, killer: false, queue: 'global', container: false, modal: true,
      buttons: [
        Noty.button('Close', 'btn btn-xs btn-primary pull-right', function () {
          noty.close();
        })
      ],
    });
    $.ajax({
      type: "POST",
      url: "{{URL::to('/solution/user/save')}}",
      dataType: 'json',
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      data: $('#ldap_add_user').serialize(),
      success: function(data){
        $('#user_add_modal').modal('hide');
        noty.on('onClose', function(){user_datatable.ajax.reload()}).show();
        noty.setText('<p>'+data["s_message"]+'</p>');
        if(data.b_status) {
          noty.setType('success');
        } else {
          noty.setType('error');
        }
      }
    })
  }

  function tableLoading() {
    $('table#userlist tbody').empty();
    $('table#userlist tbody').append('<tr><td colspan="5"><div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></td></tr>');
  }
</script>
@stop
