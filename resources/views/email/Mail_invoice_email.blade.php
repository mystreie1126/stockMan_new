<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        {{-- <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"> --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
        <link rel="stylesheet" href="{{URL::asset('css/style/style.css')}}">
        {{-- <link href="https://fonts.googleapis.com/css?family=Russo+One&display=swap" rel="stylesheet"> --}}

    </head>
    <body>
        <div class=" container" id="invoice" style="font-family:sans-serif">

            <div class="invoice_template">
                <div class="">
                    <div class="left-logo-text">
                        <div class="center logo grey" style="padding:.3rem .8rem">
                            <h3 class="bold">
                                <span class="red-text">Fun</span><span class="white-text">Tech</span>
                            </h3>
                        </div>
                        <div>
                            <span class="bold" style="margin-bottm:3px">Fun Tech LTD</span><br>
                            <span class="bold">VAT NO.9795223M</span><br>
                            <span>Unit 8 Old Sawmills Industrial Est</span>
                            <span>Lr Ballymount Road, Walkinstown 12</span><br>
                            <span>D12 K022</span><br>
                            <span>Ireland</span>
                        </div>
                    </div>
                    <hr>
                    <div class="right-invoice-date" >
                        <div class="">
                            <span>Invoice Number: <span class="bold">#0000{{$invoice_id}}</span></span><br>
                            <span>Order Date: <span class="bold">{{$date}}</span></span><br>
                            {{-- @if($order_ref !== '')
                                <span>Order Ref: <span class="bold">{{$order_ref}}</span></span><br>
                            @endif --}}
                        </div>
                    </div>
                </div>
                <div class="address">
                    @if($name !== '')
                        <span class="bold">{{$name}}</span><br>
                    @endif
                    @if($email !== '')
                        <span class="bold">{{$email}}</span>
                    @endif
                    @if($billing_address !== '')
                        <div class="address_left">
                            Billing Address:<span class="bold" style="word-break: break-all;">{{$billing_address}}</span>
                        </div>
                    @endif
                    @if($shipping_address !== '')
                        <div class="address_right">
                            Shipping Address:<span class="bold" style="word-break: break-all;">{{$shipping_address}}</span>
                        </div>
                    @endif
                </div>
                <?php
                    $order_lists = json_decode($lists,true);
                ?>
                <p></p>
                <div class="order_list" style="border:1px solid black">

                    <table>
                        <thead>
                          <tr>

                              <th class="center">Product</th>
                              <th class="center">Quantity</th>
                              <th class="center">
                                  Unit Price
                                  (tax excl.)
                              </th>
                              <th class="center">Tax Rate %</th>
                              <th class="center">Taxes</th>
                              <th class="center">
                                  <span>Total</span>
                                  <span>(tax incl.)</span>
                              </th>
                          </tr>
                        </thead>

                        <tbody>
                            @foreach($order_lists as $list)
                                <tr>

                                    <td class="center">{{$list['name']}}</td>
                                    <td class="center">{{$list['qty']}}</td>
                                    <td class="center">{{$list['price_tax_excl']}}&euro;</td>
                                    <td class="center">{{$list['tax']}}%</td>
                                    <td class="center">{{$list['taxes']}} &euro;</td>
                                    <td class="center">{{$list['total']}} &euro;</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="right">
                        <p>Total VAT:<span class="bold">{{$total_tax}}</span>&euro;</p>
                        <p>Total Amount:<span class="bold">{{$total_price}}</span>&euro;</p>
                    </div>

                </div>
            </div>
        </div>
    </body>
</html>
