@extends('template')
@section('content')
@if(Auth::check())

  <div class="parent container">
      <span class="right">Checked By: <span class="red-text">{{Auth::User()->name}}</span></span>
      <input type="hidden" value="{{Auth::User()->id}}" class="stock_userID">


      <input type="hidden" name="" value="">
      <input type="text" v-model="search">


      <div v-if="stocks.length > 0" v-for="(stock,index) in filterStocks" v-bind:style="{'border':'1px dotted teal'}">
        <form class="test_1">
            <div @style="styleObj" class="row">
              <h5 class="col s8 m8 l8 indigo-text">@{{stock.name}}</h5>
              <input type="number" class="col s4 m4 l4" v-model="stock.updateQty" placeholder="Updated Qty can not be empty">

              <h5 class="col s6 m6 l6 orange-text" >@{{stock.reference}}</h5>

              <button type="button" v-bind:class="['btn-large','a'+stock.web_stock_id,'blue darken-4 col s6 m6 l6 right']"
              v-on:click.prevent="tt(index,stock.reference,stock.web_stock_id,stock.updateQty,$event)"
              v-bind:style="{'marginBottom':'10px'}">
                Update
              </button>
              </div>
        </form>
    </div>
    </div>


@stop
@push('inventory')
  <script type="text/javascript" src="{{URL::asset('js/inventory.vue.js')}}"></script>
@endpush
@endif
