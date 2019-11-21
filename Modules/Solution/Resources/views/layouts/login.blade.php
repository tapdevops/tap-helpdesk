<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Module Solution</title>
  {!! Html::style('assets/bootstrap/css/bootstrap.css') !!}
  {!! Html::style('assets/css/style.css') !!}
  {!! Html::style('assets/adminlte/dist/css/AdminLTE.css') !!}
  {!! Html::style('assets/adminlte/dist/css/skins/skin-blue.css') !!}
  {!! Html::style('assets/lib/font-awesome/css/font-awesome.min.css') !!}
  {!! Html::script('assets/js/jquery.min.js') !!}
  {!! Html::script('assets/bootstrap/js/bootstrap.min.js') !!}
  {!! Html::script('assets/adminlte/dist/js/app.js') !!}

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="../../index2.html">Helpdesk Tools</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">

    @if(Session::has('warning_message'))
      {!! Session::get('warning_message') !!}
    @else 
      <p class="login-box-msg">
        Authorized personel only
      </p>
    @endif


    <form action="<?php echo URL::current()?>" method="post">
      {{ csrf_field() }}
      <div class="form-group has-feedback">
        <input type="text" name="username" class="form-control" placeholder="Username" required>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="row">
        <!-- /.col -->
        <div class="col-xs-4">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>


  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="../../bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="../../plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
