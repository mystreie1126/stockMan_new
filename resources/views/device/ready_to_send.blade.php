@extends('template')

@if(Auth::check())
@section('content')
    <div class="container">

    @if(count($lists) > 0)
        @foreach($lists as $list)
            {{-- <h5 class="center">{{$list[0]->shopname->name}}</h5> --}}
                <ul class="collapsible" data-collapsible="accordion">
                    <li>
                      <div class="collapsible-header"><i class="material-icons">smartphone</i>
                          @if($list[0]->shop_id > 0)
                               {{$list[0]->shopname->name}}
                          @else
                              wholeseller
                          @endif
                      </div>
                      <div class="collapsible-body">
                          <form class="" action="{{route('sendDevice_to_branch')}}" method="post">
                              <table class="centered">
                                  <thead>
                                      <tr>
                                          <th>Device</th>
                                          <th>Storage</th>
                                          <th>Color</th>
                                          <th>IMEI</th>
                                          <th>Send To</th>
                                          <th>Notes</th>
                                          <th class="hide">transfer_id</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach($list as $send_device)
                                          <tr>

                                              <td>{{$send_device->record->brand.' '.$send_device->record->model}}</td>
                                              <td class="teal-text text-darken-3">{{$send_device->record->storage}}</td>
                                              <td>{{$send_device->record->color}}</td>
                                              <td class="indigo-text">{{$send_device->record->IMEI}}</td>
                                              @if($send_device->shop_id > 0)
                                                  <td class="red-text text-accent-3">{{$send_device->shopname->name}}</td>
                                              @else
                                                  <td class="red-text">wholesale</td>
                                              @endif
                                              <td>{{$send_device->notes}}</td>
                                              <td>
                                                  <input type="hidden" name="transfer_id[]" value="{{$send_device->transfer_id}}">
                                                  <input type="hidden" name="shop_id" value="{{$send_device->shop_id}}">
                                              </td>
                                          </tr>
                                      @endforeach
                                  </tbody>
                            </table>
                            <button class="btn amber exportList">Export List</button>
                            <button class="btn send_device">send</button>
                          </form>
                      </div>
                    </li>
                </ul>
        @endforeach
    @else
        <h5 class="center">No phones are ready to send to branches</p>
    @endif


    </div>



@stop
@push('device_newDevice_js')
    <script type="text/javascript" src="{{URL::asset('js/plugin/jquery.tabletoCSV.js')}}"></script>
    <script type="text/javascript" src="{{URL::asset('js/device/device_in_out.js')}}"></script>
@endpush
@endif
