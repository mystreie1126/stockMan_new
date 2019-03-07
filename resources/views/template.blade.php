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
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/css/materialize.min.css">
        <link rel="stylesheet" href="{{URL::asset('css/style/style.css')}}">
    <body>


        <h2>haha</h2>

        @yield('content')


    <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js
"></script>
    <script type="text/javascript" src="{{URL::asset('js/plugin/fancyTable.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/script.js')}}"></script>

    </body>
</html>
