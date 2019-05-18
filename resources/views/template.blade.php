<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>StockManager</title>
        <!-- Latest compiled and minified CSS -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <!-- fonts -->
        <link href="https://fonts.googleapis.com/css?family=Staatliches" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Russo+One&display=swap" rel="stylesheet">
        <link href="https://unpkg.com/tabulator-tables@4.2.7/dist/css/tabulator.min.css" rel="stylesheet">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
        <link rel="stylesheet" href="{{URL::asset('css/style/style.css')}}">
        <style media="screen">
          #chartContainer{
            height: 300px;
            width:100%;
          }
        </style>
    <body>
      @section('ss')

      @show

    @include('_includes/navbar')
    <div id="main">
      @yield('content')
    </div>

    @stack('product_editing')
    @stack('export_topSale')
    <script type="text/javascript">
      //const stockMan = 'http://localhost/project/laravel/newStockApi/public/api/';
      //const api = 'http://localhost/project/laravel/stockManager_api/public/api';
      //var stockMan = 'http://stockangryman.funtech.ie/api/';
      var stockMan = 'https://calm-anchorage-96610.herokuapp.com/http://stockmangagerapi.funtech.ie/api/';
      //var stockMan = 'http://localhost/project/laravel/stockManager_api/public/api/';
    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>

    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css"> --}}

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js
"></script>
{{-- <script type="text/javascript" src="https://cdn.rawgit.com/pinzon1992/materialize_table_pagination/f9a8478f/js/pagination.js"></script> --}}
 <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>


    <script type="text/javascript" src="{{URL::asset('js/plugin/fancyTable.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/plugin/csvExport.min.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/script.js')}}"></script>

    @stack('product_js')
    @stack('sale_js')
    @stack('order_js')
    @stack('replishment_js')
    @stack('customRep_js')
    @stack('inventory')
    </body>
</html>
