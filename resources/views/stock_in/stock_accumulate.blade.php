@extends('template')

@section('content')
    @if(Auth::check())


          <div id="avaialble_stockIn" class="container">
              <h3 class="center">Accumulate Stocks quantity</h3>
              <span class="right" style="font-size:1.2rem;">Staff: <span class="teal-text">{{Auth::User()->name}}</span></span>
              <input type="hidden" value="{{Auth::User()->id}}" class="stock_userID">

              <input type="text" v-model="search" placeholder="Search by refernce or name" class="searchable">

              <div class="preloader-wrapper big active pre-loader" v-if="loading">
                <div class="spinner-layer spinner-blue-only">
                  <div class="circle-clipper left">
                    <div class="circle"></div>
                  </div><div class="gap-patch">
                    <div class="circle"></div>
                  </div><div class="circle-clipper right">
                    <div class="circle"></div>
                  </div>
                </div>
              </div>

              <div v-if="stocks.length > 0" v-for="(stock,index) in filterStocks" v-bind:style="{'border-bottom':'1px solid black'}">
                <div class="test_1">
                    <div @style="styleObj" class="row">
                      <h5 class="col s8 m8 l8 blue-grey-text text-lighten-1">@{{stock.name}}</h5>
                      <h5 class="col s2 m2 l2 pink-text text-accent-3">@{{stock.warehouse_qty}}</h5>
                      <input type="number" class="col s2 m2 l2 input_qty" v-model="stock.updateQty" placeholder="stockIn Quantity">

                      <h5 class="col s6 m6 l6 yellow-text text-darken-3" >@{{stock.reference}}</h5>

                      <button type="button" v-bind:class="['btn-large','a'+stock.web_stock_id,'brown darken-2 col s6 m6 l6 right']"
                      v-on:click.prevent="tt(index,stock.name,stock.reference,stock.web_stock_id,stock.updateQty,$event)"
                      v-bind:style="{'marginBottom':'10px'}" v-bind:disabled="btn_disable">
                        Accumulate and Update
                      </button>
                      </div>
                </div>

            </div>

            </div>



    @endif
    @push('stock_in_js')
        <script type="text/javascript" src="{{URL::asset('js/stockIn/stock_in.js')}}"></script>

    @endpush

@stop
