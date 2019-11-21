@extends('solution::layouts.master')
@extends('solution::layouts.navbartop')
@extends('solution::layouts.navmain')

{!! Html::style('assets/lib/noty/lib/noty.css') !!}
{!! Html::style('assets/lib/noty/demo/animate.css') !!}
  
@section('content')

  <div class="content-wrapper">
      
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h1>{{$issue["application_name"]}}</h1>
      </section>

      <!-- Main content -->
      <section class="content">

        <!-- Default box -->
        <div class="box box-solid box-success">
          <div class="box-header with-border">
            <h3 class="box-title">{{$issue["issue_name"]}}</h3>
          </div>
          
          <div class="box-body">
            <form action="">
            <div class="row">
              <div class="col-sm-2 text-right"><p><strong>solution</strong></p></div>
              <div class="col-sm-10">
                <p>{{$issue["solution_description"]}}</p>
                <input type="hidden" name='issue' value="{{$issue['seq_id']}}">
              </div>
            </div>

            @foreach ($params as $param)
				<?php //echo "<pre>"; print_r($param); die(); ?>
            <div class="row">
              <div class="col-sm-2 text-right">
                <label for='{{$param["param_field"]}}'>{{$param["param_label"]}}</label>
              </div>
              <div class="col-sm-10">
                <p>
                  <input type="text" id='{{$param["param_field"]}}' class="input-lg form-control" name='param[{{$param["param_field"]}}]' placeholder='{{$param["placeholder"]}}' required>
                </p>
              </div>
            </div>
            @endforeach

            <div class="row">
              <div class="col-sm-10 col-sm-offset-2">
                <p><button type="button" class="btn bg-maroon btn-flat" data-toggle="collapse" data-target="#solution_resolver">View Query</button></p>
                <div class="collapse" id="solution_resolver">
                  <pre>{{$issue["resolution_query"]}}</pre>
                </div>
              </div>
            </div>
          </div>
          </form>

          <!-- /.box-body -->
          <div class="box-footer">
            <button type="submit" id="resolve" class="btn btn-info pull-right"><strong>Execute</strong></button>
            <div style="padding-top: 6px; display: none" id="loading-spinner">
              <div class="spinner">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
              </div>
            </div>
          </div>

        </div>
        <!-- /.box -->
      </section>


  </div>

  <!-- {!! Html::script('assets/lib/noty/src/index.js') !!} -->
  {!! Html::script('assets/lib/noty/lib/noty.js') !!}
  {!! Html::script('assets/lib/validation/dist/jquery.validate.min.js') !!}

  <script type="text/javascript">
    $(document).ready(function(){
      $('#solution_resolver').collapse({
        toggle: false
      });

      $('form').keyup(function(ev){
        if ( ev.which == 13 ) {
          submitForm();
        }
      });

      $('form').submit(function(ev){
        ev.preventDefault();
      });

      $('#resolve').on('click', function () {
        submitForm();
        $(this).button('complete'); // button text will be "finished!"
      });
	  
	  $('#tanggal_bcc').datetimepicker({
      format: "YYYY-MM-DD",
      useCurrent: false,
      showClear: true,
      maxDate: moment().format('YYYY-MM-DD'),
	  
    });

    });

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
      });

      $('form').validate({
        errorClass: "has-error text-danger",
        validClass: "has-success",
        errorElement: "em",
        highlight: function(element, errorClass, validClass) {
          $(element).parent().addClass(errorClass).removeClass(validClass);
          $(element).addClass(errorClass).removeClass(validClass);
          $(element.form).find("label[for=" + element.id + "]").addClass(errorClass);
        },
        unhighlight: function(element, errorClass, validClass) {
          $(element).parent().removeClass(errorClass).addClass(validClass);
          $(element).removeClass(errorClass).addClass(validClass);
          $(element.form).find("label[for=" + element.id + "]").removeClass(errorClass);
        }
      });
      
      $form_valid = $('form').valid();
      if($form_valid) {
        // noty.show();
        noty.setText('<p>Processing ...</p><p><div class="spinner"></div></p>');
        noty.setType('success');
        $('#loading-spinner').show();

        $.ajax({
          method:"post",
          url: "<?php echo url('/solution/resolve'); ?>",
          data: $('form').serialize(),
          headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content')
          },
          success: function(data) {
                    $('#loading-spinner').hide();
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
