@if(Auth::check())
@extends('template')
@section('content')

<div class="container" id="partner_order_history">
    <p class="flow-text green-text text-darken-3">Partner Order History</p>
    <div class="row" >
        <div class="col s12 m3 l3">
            <span class="indigo-text text-lighten-3">Select From:</span>
            <select id="selected_partner">
              <option disabled selected>Select a partner</option>
                @foreach($partners as $partner)
                    <option value="{{$partner->id}}">{{$partner->name}}</option>
                @endforeach
            </select>
         </div>
         <div class="col s12 m3 l3" class="datetime">
             <span class="indigo-text text-lighten-3">Start datetime:</span>
             <input type="date" class="selected_start_date">
         </div>
         <div class="col s12 m3 l3" class="datetime">
             <span class="indigo-text text-lighten-3">End datetime:</span>
             <input type="date" class="selected_end_date">
         </div>
         <button class="btn col s12 m3 l3" style="transform:translateY(80%)" @click.prevent="check">Submit</button>
    </div>

    <div class="preloader-wrapper big active pre-loader hide">
        <div class="spinner-layer spinner-green-only">
          <div class="circle-clipper left">
            <div class="circle"></div>
          </div><div class="gap-patch">
            <div class="circle"></div>
          </div><div class="circle-clipper right">
            <div class="circle"></div>
          </div>
        </div>
    </div>

    <div class="row">
      <p v-if="orders.length > 0">
         <span class="cyan-text">@{{shopname}} order from @{{from}} @{{to}}</span>
      </p>
      <ul class="collapsible" data-collapsible="accordion">
        <li v-for="(order,index) in orders">
          <span v-if="missing_order_wholesale(order.order_detail).length > 0" class="flow-text red-text">
              @{{missing_order_wholesale(order.order_detail).join(' , ')}} Doesn't have correct wholesale price.
          </span>
          <br>
          <div class="collapsible-header row">
              <i class="material-icons col s3">payment</i>
              <span class="col s3">@{{order.order_ref}}</span>
              <span class="col s3">@{{order.date_add}}</span>
              <span class="col s3">Total @{{wholesale(order)}} &euro;</span>
          </div>
          <div class="collapsible-body">
              <table>
                  <thead>
                      <tr>
                          <th class="center">Name</th>
                          <th class="center">Barcode</th>
                          <th class="center">Quantity</th>
                          <th class="green-text center" style="cursor:pointer" @click.prevent="wholesale(order)">Unit Wholesale</th>
                      </tr>
                  </thead>
                  <tbody class="centered">
                      <tr v-for="(detail,i) in order.order_detail">
                          <th class="center">@{{detail.name}}</th>
                          <th class="center">@{{detail.barcode}}</th>
                          <th class="center">@{{detail.quantity}}</th>
                          <th class="center" v-bind:style="[detail.wholesale <= 0 ? {'background':'red'}:{'background':'#fff'}]">@{{detail.wholesale}} &euro;</th>
                      </tr>
                  </tbody>
              </table>
          </div>
        </li>
      </ul>
    </div>

    <div class="row">
        <p v-if="reps.length > 0" class="teal-text">
            Replishment to @{{shopname}} from:@{{from}} to:@{{to}}<br>
            Total @{{reps.length}} items, Total Cost(wholesale): <span class="blue-text">@{{rep_wholesale(reps)}}</span> &euro;
        </p>
        <p class="flow-text red-text" v-if="missing_order_wholesale(reps).length > 0">
            @{{missing_order_wholesale(reps).join(' , ')}} doesn't have correct wholesale price
        </p>
        <table v-if="reps.length > 0">
            <thead>
                <tr>
                    <th class="center">Name</th>
                    <th class="center">Barcode</th>
                    <th class="center">Quantity</th>
                    <th class="center">Unit Wholesale</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="rep in reps">
                    <th class="center">@{{rep.name}}</th>
                    <th class="center">@{{rep.barcode}}</th>
                    <th class="center">@{{rep.total_send}}</th>
                    <th class="center" v-bind:style="[rep.wholesale <= 0 ? {'background':'red'}:{'background':'#fff'}]">@{{rep.wholesale}}</th>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@stop
@push('partner_order_price')
    <script src="{{URL::asset('js/price/partner_order_price.js')}}"></script>
@endpush
@endif
