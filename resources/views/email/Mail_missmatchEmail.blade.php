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
        @if(count($missmatch_parts) > 0)
        <p>Good Day,</p>
        <p>Please see below missmatches between RockPos and your parts stockTake.</p>
            <p><span class="bold">{{$shopname}}</span> Branch Parts Missmatches:</p>
            <table>
                <tr>
                    <th>Parts ID</th>
                    <th>Parts Name</th>
                    <th>StockTake Quantity</th>
                    <th>RockPos Quantity</th>
                    <th>Reason</th>
                </tr>
                @foreach($missmatch_parts as $part)
                <tr>
                    <td>{{$part->parts_id}}</td>
                    <td>{{$part->parts_name}}</td>
                    <td>{{$part->sheet_stock}}</td>
                    <td>{{$part->pos_stock}}</td>
                    <td><textarea rows="4" cols="50"></textarea></td>
                </tr>
                @endforeach
            </table>
            <p>Please Email to manager@funtech.ie adding a reason for each miss match mentioned above</p>
            <p>FunTech HQ</p>
         @endif
    </body>
</html>
