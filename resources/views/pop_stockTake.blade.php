@extends('template')
@if(Auth::check())
@section('content')
<div class="container row" id="pop_upload">
    <div class="input-field col s8">
        <p>1. Select a Shop first:</p>
        <select class="shops">
            <option value="" disabled selected>Choose a shop</option>
            @foreach($shops as $shop)
                <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
            @endforeach
        </select>
    </div>       
    <div class="file-field input-field col s8">
        <p>2. Then Upload the shop pre own stock take sheet from your PC:</p>
        <div class="btn">
            <input type="file" id="file" v-on:change="popfileUpload()" >
            <span>Upload</span>
        </div>
        <div class="file-path-wrapper">
            <input class="file-path validate" type="text" >
            <p class="center">@{{message}}</p>
        </div>        
    </div>
    <div class="col s12" v-if="success_upload">
        <div style="display:flex; justify-content:space-between">
            <div>
                <p class="flow-text">Total Available Devices in RockPos: <span class="teal-text">@{{result.rockpos_phones}}</span></p>
                <p style="font-style:italic" class="lime-text text-darken-1">Device IMEIs Available from rockpos but not shows in stock on sheet:</p>
                <ul v-for="item in result.inPosNotInSheet">
                    <li>@{{item}}</li>
                </ul>
            </div>

            <div>
                <p class="flow-text">Total In-Stock Devices from Sheet: <span class="indigo-text">@{{result.sheet_phones}}</span></p>
                <p style="font-style:italic" class="blue-text text-lighten-2">Device IMEIs shows in stock from sheet but not Available in Rock pos:</p>
                <ul v-for="item in result.inSheetNotInPos">
                    <li>@{{item}}</li>
                </ul>
            </div>
        </div>
       
        
    </div>
   
</div>
@stop

@push('pop_stocktake')
<script>
	var pop_stockTake = new Vue({
		el:'#pop_upload',
		data:{
			file:'',
			message:'',
			success_upload:false,
            result:{
                rockpos_phones:'',
                sheet_phones:'',
                inSheetNotInPos:'',
                inPosNotInSheet:''
            }
			
		},
		methods:{
            popfileUpload:function(){
                
                var shop_id = $('.shops :selected').val();
                console.log(shop_id)
				var input;
                // (Can't use `typeof FileReader === "function"` because apparently
                // it comes back as "object" on some browsers. So just see if it's there
                // at all.)
                if (!window.FileReader) {
                    alert("The file API isn't supported on this browser yet.");
                    return;
                }
                input = document.getElementById('file');
                if (!input) {
                    alert("Um, couldn't find the uploaded file element.");
                }
                else if (!input.files) {
                    alert("This browser doesn't seem to support the `files` property of file inputs.");
                }
                else if (!input.files[0]) {
                    alert("Please select a file before clicking 'Load'");
                }else{
                    if(shop_id > 0){
                        this.file = input.files[0];
                        let formData = new FormData();
                        formData.append('pop_stockTake_sheet',this.file);
                        axios({
                            method:'post',
                            url:api_endpoint+'check-pop-stocktake-sheet/'+shop_id,
                            data:formData,
                            headers:{
                                'Content-Type': 'multipart/form-data',
                            }
                        }).then((e)=>{
                            console.log(e.data)
                            if(e.data.status == 'success'){
                                this.success_upload = true;
                                this.result.rockpos_phones = e.data.data.how_many_in_stock_from_rockpos;
                                this.result.sheet_phones = e.data.data.how_many_in_stock_from_sheet;
                                this.result.inPosNotInSheet = e.data.data.inPosNotInSheet;
                                this.result.inSheetNotInPos = e.data.data.inSheetNotInPos;
                            }
                        })
                    }else{
                        alert('please select valid shop id');
                        location.reload()
                    }
                }
			}
		}

	})
</script>
@endpush
@endif