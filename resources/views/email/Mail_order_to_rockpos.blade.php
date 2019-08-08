<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <style media="screen">
            table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
            }

            td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 4px;
            box-sizing: border-box;
            }

            .bold{
                font-weight: 800;
            }
        </style>
    </head>
    <body>
        @if(count($order_details) > 0)
        <p>Hi,</p>
        <p>Your Order items have been dispatched and delivered to <span class="bold">{{$shopname}}</span> branch.</p>
        <p>Delivery time: <span class="bold">{{date('Y-m-d')}}</span>.</p>
        <p>Order reference: <span class="bold">{{$order_ref}}</span></p>

            <table>
                <tr>
                    <th>Product Name</th>
                    <th>Barcode/ref</th>
                    <th>Quantity</th>
                    <th>Retail Price</th>
                    <th>Wholesale Price</th>
                </tr>
                @foreach($order_details as $detail)
                <tr>
                    <td>{{$detail->product_name}}</td>
                    <td>{{$detail->ref}}</td>
                    <td>{{$detail->product_quantity}}</td>
                    <td>{{$detail->retail_price}}</td>
                    <td>{{$detail->wholesale_price}}</td>
                </tr>
                @endforeach
            </table>
            <p>Total <span class="bold">Total Retail sum</span>: {{$total_retail}} &euro;</p>
            <p>Total <span class="bold">Total Wholesale sum</span>: {{($total_wholesale)}} &euro;</p>
            <p></p>
            <p>If you have any query, please do not hesitate to contact us.</p>
            <p>Best Regards,</p>
            <p>warehouse@funtech.ie</p>
         @endif
    </body>
</html>
