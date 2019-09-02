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
           <li>
             <div class="collapsible-header">{{$missmatch->name}} has {{$missmatch->count}} missmatches</div>
             <div class="collapsible-body">
                 <table>
                     <thead>
                         <tr>
                             <th>Name</th>
                             <th>Item Name</th>
                          </tr>
                     </thead>
                     <tbody>
                         @foreach($missmatch->devices as $device)
                             <tr>
                                 <td>{{$device->name}}</td>
                                 <td>{{$device->imei}}</td>
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


   @if($message = Session::get('success'))
           <strong>{{ $message }}</strong>
   @endif

</div>



@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
    @endforeach
@endif
@push('barcode_js')
    <script src="{{URL::asset('js/phoneCheck/debroa_check.js')}}"></script>
@endpush
@stop
@else
    <h5 class="center">Please login</h5>
@endif
