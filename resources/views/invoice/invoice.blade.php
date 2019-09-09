@extends('template')
@section('content')
@if(Auth::check())
<div class="row container" id="invoice" style="font-family:sans-serif; margin-top:20px">
    <div class="row">
        <div class="col s12" style="display:flex; justify-content:space-between">
            <div class="datetime">
                <span class="indigo-text text-darken-3">Choose Order date:(must have)</span>
                <input type="date" id="selected_start_date" v-model="date">
            </div>

            {{-- <div class="input-field" style="transform:translateY(-20%)">
                <span class="indigo-text text-darken-3">Input Order reference (can be empty):</span>
                <input type="text" v-model="order_reference">
            </div> --}}
        </div>

        <div class="input-field col s4">
          <input id="fullname" type="text" class="validate" v-model="fullname">
          <label for="fullname">Customer Full name:</label>
        </div>


        <div class="input-field col s9">
          <input id="email" type="email" class="validate" v-model="email">
          <label for="email">Customer Email (must have):</label>
        </div>

        <div class="input-field col s9">
          <input id="last_name" type="text" class="validate" v-model="billing_address">
          <label for="last_name">Billing Address (can be empty):</label>
        </div>

        <div class="input-field col s9">
          <input id="password" type="text" class="validate" v-model="shipping_address">
          <label for="password">Shipping Address (can be empty):</label>
        </div>
    </div>
    <a class="btn-floating btn waves-effect waves-light teal" @click="add"><i class="material-icons">add</i></a>

    <div v-if="showList" class="row">
        <table>
            <thead>
              <tr>
                  <th>item</th>
                  <th>Description</th>
                  <th>Quantity</th>
                  <th>
                      Unit price <br>
                      (tax excluded)
                  </th>
                  <th>Tax Rate &#37;</th>
              </tr>
            </thead>

            <tbody v-for="(list,index) in lists">
                <tr>
                    <td>
                        <input type="text" v-model="list.name" class="center">
                    </td>
                    <td>
                        <input type="text" v-model="list.desc" class="center">
                    </td>
                    <td>
                        <input type="number" v-model="list.qty" class="center">
                    </td>
                    <td>
                        <input type="number" v-model="list.price" class="center">
                    </td>
                    <td>
                        <input type="number" v-model="list.tax" class="center">
                    </td>
                    <td>
                        <button class="red btn" @click.prevent="remove(index)">remove</button>
                    </td>
                </tr>
            </tbody>


        </table>
        {{-- <button class="btn blue" @click.prevent="generateInvoice">generate invoice</button> --}}
    </div>

    <div class="invoice_template" style="margin-top:20px">
        <div class="" style="display:flex; justify-content:space-between">
            <div class="left-logo-text">
                <div class="center logo grey" style="padding:.3rem .8rem">
                    <h3 class="bold" style="font-family:'Russo One'">
                        <span class="red-text">Fun</span><span class="white-text">Tech</span>
                    </h3>
                </div>
                <div class="">
                    <span class="bold" style="margin-bottm:3px">Fun Tech LTD</span><br>
                    <span>VAT NO.9795223M</span><br>
                    <span>Unit 8 Old Sawmills Industrial Est</span><br>
                    <span>Lr Ballymount Road, Walkinstown 12</span><br>
                    <span>D12 K022</span><br>
                    <span>Ireland</span>
                </div>
            </div>


            <div class="right-invoice-date" style="transform:translate(-50%,20%)">
                <h4>Invoice</h4>
                <div class="">
                    <input type="hidden" class="last_id" value="{{$last_id}}">
                    <span>Invoice Number: <span class="invoice_id">{{'#0000'.$last_id}}</span></span><br>
                    <span>Date: @{{date}}</span><br>
                    <span v-if="fullname !== ''"><span class="bold">@{{fullname}}</span></span><br>
                    <span class="bold">@{{email}}</span><br>
                    <span v-if="order_reference">Order Ref. - <span class="bold">@{{order_reference}}</span></span>
                </div>
            </div>
        </div>

        <div class="address" style="display:flex; justify-content:space-around">
            <div class="address_left" style="width:30%;"  v-if="billing_address !== ''">
                <h6 class="bold">Bill to:</h6>
                <span style="word-break: break-all;">@{{billing_address}}</span>
            </div>

            <div class="address_right" style="width:20%" v-if="shipping_address !== ''">
                <h6 class="bold">Ship to:</h6>
                <span style="word-break: break-all;">@{{shipping_address}}</span>
            </div>
        </div>

        <div class="order_list" style="border:1px solid black; margin-top:20px">
            <table>
                <thead>
                  <tr>
                       <th>Quantity</th>
                      <th>Item</th>
                      <th>Description</th>
                      <th>
                          Unit Price<br>
                          (tax incl.)
                      </th>
                      <th>Tax Rate &percnt;</th>
                      <th>Taxes</th>
                      <th style="display:flex; flex-direction: column; align-items:center" class="center">
                          <span>Total</span>
                          <span>(tax incl.)</span>
                      </th>
                  </tr>
                </thead>

                <tbody v-for="list in lists">
                    <tr>
                        <td>@{{list.qty}}</td>
                        <td>@{{list.name}}</td>
                        <td>@{{list.desc}}</td>
                        <td>@{{price_tax_inc(list.price,list.tax)}} &euro;</td>
                        <td>@{{list.tax}} &percnt;</td>
                        <td>@{{Number(Number(list.price) * Number(list.tax/100)).toFixed(2)}} &euro;</td>
                        <td class="center">@{{Number(price_tax_inc(list.price,list.tax) * list.qty).toFixed(2) }}&euro;</td>
                    </tr>
                </tbody>
            </table>
            <div class="right" v-if="lists.length > 0" style="display:flex; flex-direction:column">
                <p>Total Tax:<span class="bold">@{{total_tax}}</span>&euro;</p>
                <p>Total:<span class="bold">@{{total_price}}</span>&euro;</p>
            </div>

        </div>
    </div>
    <button class="btn blue left send_invoice_button"style="margin-top:30px" @click.prevent='send'>Send Invoice</button>
</div>



@endif
@stop
@push('invoice')
    <script type="text/javascript" src="{{URL::asset('js/invoice.js')}}"></script>
@endpush
