@if(Auth::check())
@extends('template')
@section('content')
<p></p>
<div class="container">


    @if($results->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>barcode</th>
                    <th>Quantity</th>
                </tr>
            </thead>
            <tbody>
                @foreach($results as $result)
                    <tr>
                        <td>{{$result->name}}</td>
                        <td>{{$result->barcode}}</td>
                        <td>{{$result->total}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@stop
@endif
