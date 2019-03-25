<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>StockManager</title>
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
        <link rel="stylesheet" href="{{URL::asset('css/style/style.css')}}">
        <style media="screen">
          #chartContainer{
            height: 300px;
            width:100%;
          }
        </style>
    <body>

    @include('_includes/navbar')
    @yield('content')

    @stack('product_editing')
    @stack('export_topSale')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js
"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/pinzon1992/materialize_table_pagination/f9a8478f/js/pagination.js"></script>
 <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


    <script type="text/javascript" src="{{URL::asset('js/plugin/fancyTable.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/plugin/csvExport.min.js')}}"></script>

    <script type="text/javascript" src="{{URL::asset('js/script.js')}}"></script>
    @stack('product_js')
    @stack('sale_js')
    @stack('order_js')
    @stack('replishment_js')
    </body>
</html>
