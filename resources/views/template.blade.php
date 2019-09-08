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


    @include('_includes/navbar')
    <div id="main">
      @yield('content')
    </div>

    @stack('product_editing')
    @stack('export_topSale')
    <script type="text/javascript">
        var stockMan = 'http://stockangryman.funtech.ie/api/';
      //var stockMan = 'http://localhost/project/laravel/stockManager_api/public/api/';

      var submit_once = function(e,btn_text){
          $(e).attr('disabled','disabled');
          $(e).text(btn_text);
      }

      var reset_button = function(e,btn_text){
          $(e).removeAttr('disabled');
          $(e).text(btn_text);
      }

      var removeHide = function(e){
          if($(e).hasClass('hide')){
              $(e).removeClass('hide');
          }
      }

      var addHide = function(e){
          if(!$(e).hasClass('hide')){
              $(e).addClass('hide');
          }
      }

      var objectToCSV = function(data){
          var csvRows = [];
          var headers = Object.keys(data[0]);
          csvRows.push(headers.join(','));

         for(var row of data){
             var values = headers.map(header =>{
                 var escaped = (''+row[header]).replace(/"/g, '\\"');
                 return `"${escaped}"`;
             })
             csvRows.push(values.join(','));
         }
         return  csvRows.join('\n');
      }

      var downloadList = function(data){
          var blob = new Blob([data],{type:'text/csv'});
          var url  = window.URL.createObjectURL(blob);
          var  a   = document.createElement('a');
          a.setAttribute('hidden','');
          a.setAttribute('href',url);
          a.setAttribute('download','download.csv')
          document.body.appendChild(a);
          a.click();
          document.body.removeChild(a);
      }

    </script>
    <script src="https://cdn.jsdelivr.net/npm/vue"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js
"></script>
<script type="text/javascript" src="https://cdn.rawgit.com/pinzon1992/materialize_table_pagination/f9a8478f/js/pagination.js"></script>


    <script type="text/javascript" src="{{URL::asset('js/script.js')}}"></script>

    @stack('product_js')
    @stack('sale_js')
    @stack('order_js')
    @stack('replishment_js')
    @stack('customRep_js')
    @stack('inventory')
    @stack('mystocktake_js')
    @stack('stockTake_analysis_js')
    @stack('update_to_branch')
    @stack('device_newDevice_js')
    @stack('stock_in_js')

    {{-- device js... --}}
    @stack('device_pool_js')
    @stack('deviceTest_js')
    {{--barcode js--}}
    @stack('barcode_js')
    @stack('partner_order_price')
    {{--barcode js--}}
    @stack('invoice')
    </body>
</html>
