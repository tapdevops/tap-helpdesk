@extends('solution::layouts.master')
@extends('solution::layouts.navbartop')
@extends('solution::layouts.navmain')

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
          <div class="col-sm-6">
            <table class="table table-hover table-condensed">
              <tr>
                <td><strong>Execute By</strong></td>
                <td>:</td>
                <td>{{ $summary['execute_by'] }}</td>
              </tr>
              <tr>
                <td><strong>Execute On</strong></td>
                <td>:</td>
                <td>{{ $summary['execute_on'] }}</td>
              </tr>
            </table>
          </div>
          <div class="col-sm-6">
            <p><strong>Parameters</strong></p>
            <table class="table table-hover table-condensed">
              @foreach ($summary['parameters'] as $param)
                <tr>
                  <td width="25%"><strong>{{$param['parameter_field']}}</strong></td>
                  <td>:</td>
                  <td>{{$param['parameter_value']}}</td>
                </tr>
              @endforeach
            </table>
          </div>
          <div class="col-sm-12">
            <p><strong>Revert Query</strong></p>
            <p class="text-yellow">Query ini hanya untuk membantu mempermudah merestore data yang sudah terupdate, tapi belum tentu merupukan solusi yang paling tepat.</p>
            <pre>{{ $summary['revert_query_string'] }}</pre>
          </div>
        </div>
      </div>

      <div class="box box-solid box-success">
        <div class="box-header with-border">
          <h3 class="box-title">Data before update</h3>
        </div>
        <div class="box-body">
          <div class="table-wrapper">
          <?php foreach ($report as $table_key => $table_name) { ?>
            <p class="text-blue"><strong><?php echo $table_key ?></strong> (<?php echo count($table_name); ?> data)</p>
            <table class="table table-bordered table-hover table-striped table-condensed">
              <thead>
                <tr>
                <?php 
                  foreach ($fields as $f) {
                    if($f['table_name'] == $table_key) {
                      echo '<th nowrap>'.strtoupper($f['parameter_field']).'</th>';
                    }
                  }
                ?>
                </tr>
              </thead>
            <?php
              foreach ($table_name as $records) {
                echo '<tr>';
                  foreach ($fields as $f) {
                    if($f['table_name'] == $table_key) {
                      echo '<td nowrap>'.$records[$f['parameter_field']].'</td>';
                    }
                  }
                echo '</tr>';
              }
            ?>
            </table>
          <?php } ?>

          </div>
        </div>
      </div>
    </section>

  </div>

<script type="text/javascript">

</script>

@stop
