@if(Auth::check())
@extends('template')
@section('content')

<div class="container" id="phone_check">
   <form method="post" enctype="multipart/form-data" action="{{route('import_pop_list')}}" class="row">
        {{ csrf_field() }}

        <p class="center">
            <p class="teal-text">1. Choose your option:</p>
            <input name="options" type="radio" id="test1" value="1"/>
            <label for="test1" class="light-green-text text-darken-3">Check Devices</label>
            <br>

            <input name="options" type="radio" id="test2" value="2"/>
            <label for="test2" class="brown-text">Check Parts</label>
            <p class="teal-text">2. Choose the sheet submit date</p>
            <input type="datetime-local" name="datetime" style="width:30%">
        </p>
        <span class="teal-text">3 .Upload the sheet</span>
        <br>
        <div class="input-field col s6">
                <select name="shop_id">
                    <option disabled selected>Choose a store</option>
                    @foreach($shops as $shop)
                        <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
                    @endforeach
                </select>

        </div>

        <div class="file-field input-field col s6">
            <div class="btn">
                <span>File</span>
                <input type="file" name="select_file">
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" placeholder="Upload one or more files">
            </div>
        </div>

      <input type="submit" class="btn center blue" value="Upload" id="save_popExcel">
   </form>


   @if(count($missmatched_shops) > 0)
        <p class="flow-text light-green-text text-darken-3">Missmatch Devices</p>
        <ul class="collapsible">
            @foreach($missmatched_shops as $missmatch)
                <form action="{{route('clear_allImported')}}" class="right" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="shop_id" value="{{$missmatch->id_shop}}">
                    <button class="btn red clear" style="transform:translate(-10%,20%)">clear</button>
                </form>

                <li>
                    <div class="collapsible-header" style="display:flex">
                        {{$missmatch->name}} has {{$missmatch->count}} missmatches
                    </div>
                    <div class="collapsible-body">
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>IMEI</th>
                                    <th>IF Checked</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($missmatch->devices as $device)
                                    <tr>
                                        <td>{{$device->name}}</td>
                                        <td>{{$device->imei}}</td>
                                        <td>
                                        <form action="{{route('checkedAndDelete')}}" method="post">
                                            {{csrf_field()}}
                                                <input type="hidden" name="delete_id" value="{{$device->id}}">
                                                <button class="btn green checked">Been Checked</button>
                                        </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </li>
            @endforeach
        </ul>
    @endif

    @if(count($wrongPart_shops) > 0)
        <p class="flow-text brown-text">Missmatch Parts</p>
        <ul class="collapsible">
                @foreach($wrongPart_shops as $missmatch)
                    <form action="{{route('clear_allImported_parts')}}" class="right" method="post">
                        {{csrf_field()}}
                        <input type="hidden" name="shop_id" value="{{$missmatch->id_shop}}">
                        <button class="btn red clear" style="transform:translate(-10%,20%)">clear</button>
                    </form>

                    <li>
                        <div class="collapsible-header" style="display:flex">
                            {{$missmatch->name}} has {{$missmatch->count}} missmatches
                        </div>
                        <div class="collapsible-body">
                            <form action="{{route('sendMissMatchPartEmail')}}" method="post">
                                <input type="hidden" name="shop_id" value="{{$missmatch->id_shop}}">
                                <button class="btn partmissmatchEmail">Email Missmatch</button>
                            </form>

                            <table>
                                <thead>
                                    <tr>
                                        <th>Parts Name</th>
                                        <th>RockPos Stock</th>
                                        <th>Sheet Stock</th>
                                        <th>IF Checked</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($missmatch->parts as $part)
                                        <tr>
                                            <td>{{$part->parts_name}}</td>
                                            <td class="indigo-text">{{$part->pos_stock}}</td>
                                            <td class="orange-text text-darken-3">{{$part->sheet_stock}}</td>
                                            <td>
                                            <form action="{{route('checkedAndDelete_parts')}}" method="post">
                                                {{csrf_field()}}
                                                    <input type="hidden" name="delete_id" value="{{$part->id}}">
                                                    <button class="btn green checked">Checked</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </li>
                @endforeach
            </ul>
    @endif




</div>



@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <ul class="center">
            <li class="red-text">{{ $error }}</li>
        </ul>
    @endforeach
@endif
@push('device_import_check')
    <script>

        $('.checked').click(function(e){
            e.preventDefault();
            submit_once($('.checked'),'loading...');
            $(this).parent().submit();
        })

        $('.clear').click(function(e){
            e.preventDefault();
            submit_once($('.clear'),'loading...');
            $(this).parent().submit();
        })


        $('.partmissmatchEmail').click(function(e){
            e.preventDefault();
            submit_once($('.partmissmatchEmail'),'sending...');
            $.ajax({
                url:window.location.origin+'/sendMissMatchPartEmail',
                type:'post',
                data:{
                    shop_id:$(this).prev().val()
                },
                success:function(e){
                    reset_button($('.partmissmatchEmail'),'Email Missmatch');
                }
            })
            console.log($(this).prev().val());
        });
    </script>
@endpush

@stop
@else
    <h5 class="center">Please login</h5>
@endif
