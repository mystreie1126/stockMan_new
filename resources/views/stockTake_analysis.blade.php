@extends('template')
@section('content')

@if(Auth::check())

<h5>StockTake and StockOut till Today</h5>




@endif
@stop

@push('stockTake_analysis_js')
<script type="text/javascript" src="{{URL::asset('js/stockTake_analysis.js')}}"></script>
@endpush
