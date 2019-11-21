<!DOCTYPE html>
<html>
    <head>
        {!! Html::style('assets/lib/font-awesome/css/font-awesome.min.css') !!}

        <title>Oops!</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:400" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }
            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 400;
                font-family: 'Lato', sans-serif;
            }
            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }
            .content {
                text-align: center;
                display: inline-block;
            }
            .title {
                font-size: 72px;
                margin-bottom: 40px;
                font-weight: bold;
            }
            .text-yellow {
                color: #f39c12 !important;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Oops <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i></div>
                <p>Halaman yang Anda klik, tidak tersedia</p>
            </div>
        </div>
    </body>
</html>
