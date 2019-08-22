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
        <p style="font-weight:bold"> Miss Matching:</p>

        @if(count($lists) > 0)

            <table>
                <tr>
                    <th>Barcode</th>
                    <th>Name</th>
                    <th>Warehouse Standard</th>
                    <th>Warehouse Stock</th>
                </tr>
                @foreach($lists as $list)
                    <tr>
                        <td>{{$list->barcode}}</td>
                        <td>{{$list->name}}</td>
                        <td>{{$list->standard}}</td>
                        <td>{{$list->stock_qty}}</td>
                    </tr>
                @endforeach
            </table>

        @endif

    </body>
</html>
