@if(Auth::check())
@extends('template')
@section('content')

<div id="update_parts_barcode" class="container">
        <p>Add parts barcode</p>

        <input type="text" placeholder="Search parts with ID/barcode/name" class="searchable" v-model="search">


        <ul class="collection" v-for="(p,index) in filterParts">
            <li class="row">
                <span class="col s12">@{{p.name}}</span>
                <span class="col s6" v-if="p.barcode !== ''">@{{p.barcode}}</span>
                <div class="col s6 red-text" v-else>
                    Brand:
                    <select class="teal-text" style="display:block" @change="getModelByBrand($event,p)">
                          <option selected disabled>choose brand</option>
                        <option v-for="brand in brands" v-bind:value="brand.brand_id">
                            @{{brand.brandname}}
                        </option>
                    </select>


                    Model:
                    <select class="teal-text" style="display:block" @change="updateModel($event,p)">
                        <option v-for="model in p.model" v-bind:value="model.model">
                            @{{model.model_name}}
                        </option>
                    </select>

                    <div class="input-field red-text">
                      <input placeholder="Identifier" id="first_name" type="text" v-model="p.identifier">
                      <label for="first_name">识别码（最后两位）</label>
                    </div>
                    <button @click.prevent="generate_barcode(p)" class="btn">生成</button>

                    <p class="" v-model="p.newbarcode">Barcode: <span class="indigo-text">@{{p.newbarcode}}</span></p>


                </div>
                <span>ID: @{{p.id_product}}</span>



            </li>
        </ul>




        <ul class="collection">
            @foreach($parts as $part)
                <li class="collection-item row">
                    <span class="col s4">ID:{{$part->id_product}}</span>
                    @if($part->barcode == '')
                        <span class="col s4 red-text">No Barcode</span>
                    @else
                        <span class="amber-text col s4">{{$part->barcode}}</span>
                    @endif
                    <span class="col s4">{{$part->name}}</span>
                </li>
            @endforeach
        </ul>

</div>


@push('barcode_js')
    <script src="{{URL::asset('js/barcode/barcode.js')}}"></script>
@endpush
@stop
@else
    <h5 class="center">Please login</h5>
@endif
