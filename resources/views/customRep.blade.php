@extends('template')

@section('content')

@if(Auth::check())

<div class="container" id="customRepLists">
  <div>
    <p class="flow-text  cyan-text text-darken-3">Select a branch to start:</p>
    <div class="row" >
       <div class="col s12 m3 l3">
        <span class="indigo-text text-lighten-3">Select From:</span>
        <select id="selected_shop">
          <option disabled selected>Choose a Shop</option>
            @foreach($shops as $shop)
              <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
            @endforeach
        </select>
      </div>
      <div class="input-field col s12 m6 l6" style="transform:translateY(10%)">
        <input placeholder="Search by Reference" id="first_name" type="text" class="validate">
        <label for="first_name">Reference</label>
      </div>
    <button type="button" v-on:click.prevent="getList" class="btn s12 m3 l3" id="createSalesList" style="transform:translateY(80%)">Search</button>
    </div>
  </div>
</div>



@endif
@stop

@push('customRep_js')
  <script type="text/javascript" src="{{URL::asset('js/customRep.vue.js')}}"></script>
@endpush
