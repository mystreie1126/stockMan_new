@if(Auth::check())
@extends('template')
@section('content')

<div class="container" id="phone_check">


   <form method="post" enctype="multipart/form-data" action="{{route('import_pop_list')}}" class="row">
       {{ csrf_field() }}
       <div class="input-field col s6">
            <select name="shop_id">
                  <option disabled selected>Choose your option</option>
                  @foreach($shops as $shop)
                      <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
                  @endforeach
            </select>
            <label>Choose a Store</label>
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
                             <th>Item Name</th>
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

    {{-- <li>
      <div class="collapsible-header"><i class="material-icons">place</i>Second</div>
      <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
    </li>
    <li>
      <div class="collapsible-header"><i class="material-icons">whatshot</i>Third</div>
      <div class="collapsible-body"><span>Lorem ipsum dolor sit amet.</span></div>
    </li> --}}
  </ul>



</div>



@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
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

    </script>
@endpush

@stop
@else
    <h5 class="center">Please login</h5>
@endif
