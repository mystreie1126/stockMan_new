@extends('template')
@section('content')
@if(Auth::check())

<div class=" row">
    <div id="branches_general_sales">
        <div class="row" style="margin-top:10px">
            <div class="input-field col s4">
                <select class="teal-text" style="display:block" v-model="selectedDays" @change="onChange()" id="select_days">
                    <option value="0">Today</option>
                    <option value="1">Yesterday</option>
                    <option value="7">Last 7 Days</option>
                    <option value="14">Last 2 Weeks</option>
                    <option value="30">Last Month</option>
                </select>
                <label>Select Date</label>
            </div>
        </div>
    </div>
    <div class="col s12">
        <canvas id="product_sales_chart"></canvas>
    </div>
</div>
@endif
@stop
@push('sale_js')
	<script type="text/javascript" src="{{URL::asset('js/sale.js')}}"></script>
@endpush