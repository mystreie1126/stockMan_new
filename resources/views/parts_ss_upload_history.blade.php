@extends('template')
@if(Auth::check())
@section('content')

<div class="container">

<ul class="collapsible" data-collapsible="accordion" style="font-familty:sans-serif">
   
    <li>
        <div class="collapsible-header">Who uploaded Parts SS sheet</div>
        <div class="collapsible-body">
            @foreach($upload_history as $history)
            <p>{{$history->name}} parts uploaded at {{$history->created_at}}</p>
            @endforeach
        </div>
    </li>

    <li>
        <div class="collapsible-header">Merge History</div>
        <div class="collapsible-body">
            @foreach($merge_history as $merge)
                <div class="row">
                    <span class="col s2">{{$merge->shopname}}</span>
                    <span class="col s2 indigo-text">{{$merge->partsname}}</span>
                    <span class="col s4">{{$merge->reason}}</span>
                    <span class="col s4">{{$merge->update_at}}</span>
                </div>
            @endforeach
        </div>
    </li>
   
  </ul>
 
</div>


@stop
@endif
