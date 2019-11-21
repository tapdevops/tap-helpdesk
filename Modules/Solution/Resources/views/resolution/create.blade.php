@extends('solution::layouts.master')
@extends('solution::layouts.navbartop')
@extends('solution::layouts.navmain')

{!! Html::style('assets/lib/noty/lib/noty.css') !!}
{!! Html::style('assets/lib/noty/demo/animate.css') !!}

@section('content')

  <div class="content-wrapper">

      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>New Issue Resolution</h1>
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="box box-solid box-success">
          <div class="box-header with-border">
            <h3 class="box-title">Issue Summary</h3>
            <div class="box-tools pull-right">
              <button type="button" class="btn btn-box-tool" data-widget="collapse" data-toggle="tooltip" title="Collapse">
                <i class="fa fa-minus"></i></button>
            </div>
          </div>

          <form class="form-horizontal">
            <div class="box-body">
              <div class="form-group">
                <label for="application_name" class="col-sm-3 control-label">Issue Category</label>
                <div class="col-sm-9">
                  <select class="form-control" name="menu_id_category">
                    <option></option>
                    @foreach ($category as $cat)
                      <option value="{{$cat['seq_id']}}">{{$cat['menu_name']}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="form-group">
                <label for="application_name" class="col-sm-3 control-label">Application Name</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="application_name" name="application_name" placeholder="Application Name" required>
                </div>
              </div>
              <div class="form-group">
                <label for="issue_summary" class="col-sm-3 control-label">Issue Summary</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="issue_summary" name="issue_summary" placeholder="Issue Summary" required>
                </div>
              </div>
              <div class="form-group">
                <label for="issue_detail" class="col-sm-3 control-label">Detail Description</label>
                <div class="col-sm-9">
                  <textarea class="form-control" rows="3" id="issue_detail" name="issue_detail" placeholder="Description ..." required></textarea>
                </div>
              </div>
            </div>

            <hr style="margin-top: 0px; box-shadow: 1px 1px 0px #eee; border-top: 1px solid #ccc;" />

            <div class="box-body">
              <div class="form-group">
                <label for="solution_name" class="col-sm-3 control-label">Solution</label>
                <div class="col-sm-9">
                  <input type="text" class="form-control" id="solution_name" name="solution_name" placeholder="Solution Name" required>
                </div>
              </div>
              
              <p><strong>Datasource</strong></p>
              <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                  <li role="presentation" class="active"><a href="#table_0" aria-controls="table_0" role="tab" data-toggle="tab">Table 1</a></li>
                  <li class="pull-right"><a href="#" class="add-contact" data-toggle="tab"><i class="fa fa-plus-circle text-success" aria-hidden="true"></i></a></li>
                </ul>
                <div class="tab-content">
                  <div role="tabpanel" class="tab-pane active" id="table_0">
                    <div class="form-group">
                      <label for="table_datasource" class="col-sm-3 control-label">Table Name</label>
                      <div class="col-sm-9">
                        <input type="text" class="form-control" id="table_datasource" name="datasource[0][table_name]" placeholder="Solution Name" required>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="solution_datasource" class="col-sm-3 control-label">Data Query</label>
                      <div class="col-sm-9">
                        <textarea class="form-control query_editor" rows="8" id="solution_datasource" name="datasource[0][data_source]" placeholder="SELECT * from..." required></textarea>
                      </div>
                    </div>
                  </div>
                </div>
              </div>


              <div class="form-group">
                <label class="col-sm-3 control-label">Parameter</label>
                <div class="col-sm-9">
                  <table class="table table-condensed table-bordered" id="paramater_data">
                    <thead>
                      <tr>
                        <th>Parameter Name</th>
                        <th>Parameter Field</th>
                        <th>Description</th>
                        <th width="50px">Action</th>
                      </tr>
                    </thead>
                    <tfoot>
                      <tr><td colspan="3">
                        <a class="btn btn-xs btn-success pull-right" id="add_params" onclick="addParams()">
                          <i class="fa fa-plus-circle" aria-hidden="true"></i> Add
                        </a>
                      </td></tr>
                    </tfoot>
                    <tbody>
                      <tr id="parameter_0" class="paramater">
                        <td><input class="form-control input-sm" name="parameters[0][parameter_label]" type="text" required></td>
                        <td><input class="form-control input-sm" name="parameters[0][parameter_field]" type="text" required></td>
                        <td><input class="form-control input-sm" name="parameters[0][placeholder]" type="text"></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <div class="form-group">
                <label for="resolution" class="col-sm-3 control-label">Resolution Query</label>
                <div class="col-sm-9">
                  <textarea class="form-control query_editor" rows="10" id="resolution" name="resolution" placeholder="" required></textarea>
                </div>
              </div>
            </div>

            <!-- /.box-body -->
            <div class="box-footer">
              <a class="btn btn-default" href="{{route('issue_list')}}"><i class="fa fa-fw fa-chevron-left"></i> Cancel</a>
              <button type="submit" class="btn btn-info pull-right" onclick="submitForm()">Save</button>
            </div>
            <!-- /.box-footer -->
          </form>

        </div>
        <!-- /.box -->

      </section>
      <!-- /.content -->
  </div>

  {!! Html::script('assets/lib/noty/lib/noty.js') !!}
  {!! Html::script('assets/lib/validation/dist/jquery.validate.min.js') !!}

  <script type="text/javascript">

    var currval = 0, tab_counter = 0;

    $(".nav-tabs").on("click", "a", function(e){
      e.preventDefault();
      $(this).tab('show');
    })
    .on("click", ".close-tab", function () {
        var anchor = $(this).siblings('a');
        $(anchor.attr('href')).remove();
        $(this).parent().remove();
        $(".nav-tabs li").children('a').first().click();
    });

    $('.add-contact').click(function(e) {
      e.preventDefault();
      tab_counter++;     
      $(this).closest('li').before('<li><a href="#table_'+(tab_counter + 1)+'">Table '+(tab_counter + 1)+'</a> <i class="fa fa-times-circle text-danger close-tab" aria-hidden="true"></i></li>');         
      $('.tab-content').append('<div class="tab-pane" id="table_'+(tab_counter + 1)+'">'+
        '<div class="form-group">'+
        '  <label for="table_datasource_'+tab_counter+'" class="col-sm-3 control-label">Table Name</label>'+
        '  <div class="col-sm-9">'+
        '    <input type="text" class="form-control" id="table_datasource_'+tab_counter+'" name="datasource['+tab_counter+'][table_name]" placeholder="Solution Name" required>'+
        '  </div>'+
        '</div>'+
        '<div class="form-group">'+
        '  <label for="solution_datasource_'+tab_counter+'" class="col-sm-3 control-label">Data Query</label>'+
        '  <div class="col-sm-9">'+
        '    <textarea class="form-control query_editor" rows="8" id="solution_datasource_'+tab_counter+'" name="datasource['+tab_counter+'][data_source]" placeholder="SELECT * from..." required></textarea>'+
        '  </div>'+
        '</div>'+
        '</div>');
    });

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

    $("form").submit(function(event){
      event.preventDefault();
    });

    function submitForm() {
        $("form").validate({
          errorClass: "has-error text-danger",
          validClass: "has-success",
          errorElement: "em",
          highlight: function(element, errorClass, validClass) {
            console.log(element);
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
          url: "<?php echo url('/solution/resolution/save'); ?>",
          data: $('form').serialize(),
          headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
            noty.on('onClose', function(){
              window.location.href = "{{route('issue_list')}}";
            }).show();
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

    function addParams() {
      currval++;
      var tuples = '<tr id="parameter_'+currval+'" class="paramater">'+
                    '  <td><input class="form-control input-sm" name="parameters['+currval+'][parameter_label]" type="text" required></td>'+
                    '  <td><input class="form-control input-sm" name="parameters['+currval+'][parameter_field]" type="text" required></td>'+
                    '  <td><input class="form-control input-sm" name="parameters['+currval+'][placeholder]" type="text" ></td>'+
                    '  <td class="text-center"><i class="fa fa-minus-circle fa-2x text-danger" aria-hidden="true" onclick="removeParam('+currval+')"></i></td>'+
                    '</tr>';
      $('#paramater_data').append(tuples);
    }

    function removeParam(param_id) {
      $("tr#parameter_"+param_id).remove();
    }

  </script>

@stop
