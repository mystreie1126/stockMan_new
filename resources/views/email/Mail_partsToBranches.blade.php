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
        @if(count($part_lists) > 0)
        <p>Hi,</p>
        <p>Please see the parts delivery list to <span class="bold">{{$shopname}}</span>.</p>
        <p>Delivery time: <span class="bold">{{date('Y-m-d')}}</span>.</p>

            <table>
                <tr>
                    <th>Name</th>
                    <th>Send</th>
                </tr>
                @foreach($part_lists as $part)
                    @if(intval($part->send_quantity) > 0)
                        <tr>
                            <td>{{$part->parts_name}}</td>
                            <td>{{$part->send_quantity}}</td>
                        </tr>
                    @endif
                @endforeach
            </table>
            <p></p>
            <p>If you have any query, please do not hesitate to contact us.</p>
            <p>Best Regards,</p>
            <p>warehouse@funtech.ie</p>
         @endif
    </body>
</html>
