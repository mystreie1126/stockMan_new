<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <style>
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
        <p>Hi,</p>
        <p>Please view the warehouse delivery details for <span class="bold">{{$shopname}}</span> branch.</p>
        <p>Delivery time: <span class="bold">{{$date}}</span>.</p>

        <p style="font-weight:bold">Delivery Lists:</p>


        @if(count($lists) > 0)
        {{-- {{$lists}} --}}

        <table>
            <tr>
                <th>Barcode</th>
                <th>Name</th>
                <th>Send</th>
                <th>Retail Price</th>
                <th>Wholesale Price</th>
            </tr>
        @foreach($lists as $list)

            <tr>
                <td>{{$list->reference}}</td>
                <td>{{$list->product_name}}</td>
                <td>{{$list->updated_quantity}}</td>
                <td>{{$list->retail}}</td>
                <td>{{$list->wholesale}}</td>
            </tr>

        @endforeach
        </table>
        <p>Total <span class="bold">Retail</span> is: <span class="bold">{{$total_retail}} &euro;</span></p>
        <p>Total <span class="bold">Wholesale price</span> is: {{round($total_wholesale)}} &euro;</p>
        <p>Best Regards,</p>
        <p>warehouse@funtech.ie</p>
        @endif

    </body>
</html>
