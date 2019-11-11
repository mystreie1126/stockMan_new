@extends('template')
@if(Auth::check())
@section('content')
<div class="container" id="pop_upload">
   
       
        <div class="file-field input-field">
            <div class="btn">
                <span>Upload</span>
                <input type="file" id="file" v-on:change="popfileUpload()" >
            </div>
            <div class="file-path-wrapper">
                <input class="file-path validate" type="text" >
                <p class="center">@{{message}}</p>
            </div>
           	
        </div>
        <div v-if="success_upload" class="row">
        	<p class="flow-text">Total<span class="indigo-text">@{{parse_result.how_many_in_stock_from_sheet}} showing In stock from pop Sheet</span></p>
            <p class="flow-text">Total <span class="indigo-text">@{{parse_result.how_many_in_stock_from_rockpos}} showing In stock from Rockpos</span></p>
           
            <p v-if="parse_result.inSheetNotInPos > 0" >
            	<p class="amber-text flow-text">IMEIs show instock from sheet but not in rockpos:</p>
            	<ul v-for="item in parse_result.inSheetNotInPos">
            		<li>@{{item}}</li>
            	</ul>
            </p>
            <p v-if="parse_result.inPosNotInSheet > 0" >
            	<p class="teal-text flow-text">IMEIs show instock from rockpos but not in sheet:</p>
            	<ul v-for="item in parse_result.inPosNotInSheet">
            		<li>@{{item}}</li>
            	</ul>
            </p>
            
        </div>
   
</div>
@stop

@push('pop_stockTake')
<script>
	var pop_stockTake = new Vue({
		el:'#pop_upload',
		data:{
			file:'',
			message:'',
			success_upload:false,
			parse_result:{
				how_many_in_stock_from_sheet:'',
				how_many_in_stock_from_rockpos:'',
				inSheetNotInPos:'',
				inPosNotInSheet:''
			},
			
		},
		methods:{
			popfileUpload:function(){
				console.log(1232)
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
                	this.disabled = true;
                	this.file = input.files[0];
                	let formData = new FormData();
                    formData.append('pop_stockTake_sheet',this.file);
                    formData.append('shop_id',document.querySelector('.shop_id').value)
                    axios({
                        method:'post',
                        url:stockMan_api+'check-pop-stocktake-sheet/'+document.querySelector('.shop_id').value,
                        data:formData,
                        headers:{
                            'Content-Type': 'multipart/form-data',
                        }
                    }).then((e)=>{
                    	if(e.data.status == 'failed'){
                    		this.message = e.data.data.msg;
                    		this.disabled = false;
                    	}else if(e.data.status == 'success'){
                    		console.log(e.data)
                    		this.success_upload = true;
                    		this.parse_result.how_many_in_stock_from_sheet = e.data.data.how_many_in_stock_from_sheet;
                    		this.parse_result.how_many_in_stock_from_rockpos = e.data.data.how_many_in_stock_from_rockpos;
                    		this.parse_result.inSheetNotInPos = e.data.data.inSheetNotInPos;
                    		this.parse_result.inPosNotInSheet = e.data.data.inPosNotInSheet;
                    		this.disabled = false;
                    	}else{
                    		this.disabled = false;
                    	}
                    	
                    })
                }
			}
		}

	})
</script>
@endpush
@endif