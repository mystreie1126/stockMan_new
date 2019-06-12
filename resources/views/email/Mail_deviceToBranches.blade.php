<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <title></title>
        <style media="screen">
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
        <p>Please view the warehouse Device delivery details for <span class="bold">{{$shopname}}</span> branch.</p>
        <p>Delivery time: <span class="bold">{{date('Y-m-d')}}</span>.</p>

        <p style="font-weight:bold">Delivery Lists:</p>


        @if(count($lists) > 0)

        <table>
            <tr>
                <th>ID</th>
                <th>Device</th>
                <th>Color</th>
                <th>IMEI</th>
                <th>Extra Notes</th>
            </tr>
        @foreach($lists as $list)

            <tr>
                <td>{{$list->device_id}}</td>
                <td>{{$list->record->brand.' '.$list->record->model}}</td>
                <td>{{$list->record->color}}</td>
                <td>{{$list->record->IMEI}}</td>
                <td>{{$list->notes}}</td>
            </tr>

        @endforeach
        </table>

        <p>Best Regards,</p>
        <p>warehouse@funtech.ie</p>
        @endif

    </body>
</html>
