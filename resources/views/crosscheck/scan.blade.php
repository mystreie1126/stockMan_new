@extends('template')
@if(Auth::check())
@section('content')


<div class="scanner-input container">

    <h5 class="col s12 cyan-text text-darken-3 center">Scan the Product(Task Number{{$task_id}})</h5>
    <div class="row">
        <div v-if="flag == true" class="col s12 center green-text">
            <h5>Scan for Adding Product</h5>
        </div>
        <div v-if="flag === false" class="col s12 center red-text">
            <h5>Scan for Removing Product</h5>
        </div>
        
        <!-- barcode input !-->
        <input type="text" id="barcode_scanned" v-on:keypress="pressMe($event,flag)" class="center col s12" v-model="scanned_input" placeholder="Hightlight barcode scanner">
        
        <div class="col s12 m12 l12" style="display:flex; justify-content: space-between; align-items: center">
            <!-- if barcode cannot be scanned !-->
            <div class="input-field row">
                <input placeholder="Barcode" id="canotscanned" type="text" class="validate  indigo-text col s7" v-model="cannot_scanned.barcode" style="margin-right: 20px">
                <label for="canotscanned">Can not scanned?</label>

                {{-- <input v-if="flag == true" placeholder="quantity" type="number" class="validate center col s5" v-model="cannot_scanned.qty"> --}}

                <button class="btn" v-if="flag == true" @click.prevent="add_ifnotscanned" :disabled="disabled"><i class="material-icons left">note_add</i>Add</button>
                
                <button class="btn red" v-if="flag == false" @click.prevent="remove_ifnotscanned" :disabled="disabled"><i class="large material-icons left">delete_forever</i>Delete</button> 
            </div>

            <!-- toggle add or remove !-->
            <div class="switch">
                <label style="display: flex; justify-content: space-between; align-items: center; transform: translateX(-50%);">
                    <span style="font-size: 20px">Remove</span>
                        <input type="checkbox" v-model="flag" v-on:change="check($event)">
                        <span class="lever"></span>
                    <span style="font-size: 20px">Add</span>
                </label>
            </div>            
            <!-- compare !-->
            <button class="white-text btn-large right pink accent-3" v-on:click="compare">COMPARE</button>
        </div>

            <!-- compare results-->
        <div class="col s12 center" v-if="missmatches.length > 0">
            <button v-on:click="exportPDF" class="light-green accent-4 btn">export pdf</button>
            <table id="missmatchTable">
                <thead>
                    <tr>
                        <th>Missmatch</th>
                        <th>Name</th>
                        <th>Barcode</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(product,index) in missmatches" v-bind:class="[product.missmatch > 0 ? 'green-text' : 'red-text']">
                        <th>@{{product.missmatch}}</th>
                        <th>@{{product.name}}</th>
                        <th>@{{product.barcode}}</th>
                    </tr>
                </tbody>
            </table>
            <button class="btn-large red accent-4" @click.prevent="package" :disabled="disabled">Ready to Package</button>
        </div>

        
</div>

@stop
@push('scan_product')


<script>
var task_id = Number(window.location.href.split('/').slice(-1)[0]);
var chars = [];     


var scan_check = new Vue({
    el:'.scanner-input',
    data:{
        scanned_input:'',
        flag:true,
        missmatches:[],
        cannot_scanned:{
            barcode:'',
            qty:''
        },
        disabled:false
    },
    methods:{
        pressMe:function(e,flag){ 
            if (e.which >= 48 && e.which <= 200) {
                console.log(e.which)
                chars.push(String.fromCharCode(e.which));
            }
            setTimeout(function(){
                if (chars.length >= 3) {
                    var _barcode = chars.join("");
                    console.log(_barcode,flag)
                    if(flag == true){
                        addtheScannerInput(_barcode)
                    }else if(flag == false){
                        removeFromScan(_barcode)
                    }
                }
                chars = [];               
            },500);
        },
        check:function(e){
            this.scanned_input = '';
            this.disabled = false;
            
        },
        compare:function(){
            axios({
                method:'get',
                url:api_endpoint+'scanner-products/missmatches/'+task_id
            }).then((e)=>{
                console.log(e.data)
                this.missmatches = e.data.data
            })
        },
        exportPDF:function(){
            var doc = new jsPDF();
            var time = (new Date().getMonth()+1)+'-'+new Date().getDate()+'-'+new Date().getMinutes();
            //doc.text('Theme "grid"', 14, doc.autoTable.previous.finalY + 10);   
            doc.autoTable({html:"#missmatchTable",theme: 'grid',
                            columnStyles:{
                                0: {cellWidth:50,fontStyle: 'bold',halign: 'center'},
                                1: {cellWidth:50,fontStyle: 'bold'},
                                2: {cellWidth:50,fontStyle: 'bold'}
                            },didDrawPage:function(data){
                                doc.text(`Task number:${task_id} missmatch`,20 ,10);
                            }
                        })
            doc.save(`Task number:${task_id} missmatch.pdf`);

        },
        add_ifnotscanned:function(){
            if(this.cannot_scanned.barcode !== ''){
                this.disabled = true
                axios({
                    method:'post',
                    url:api_endpoint+'scanner-product',
                    data:{
                        barcode:this.cannot_scanned.barcode,
                        task_id:Number(window.location.href.split('/').slice(-1)[0]),
                        updated_quantity:1
                    }
                }).then((e)=>{
                     new Audio('http://www.funtech.ie/audiofiles/notification.mp3').play();
                     this.cannot_scanned.barcode = '';
                     this.disabled = false
                })
            }
        },
        remove_ifnotscanned:function(){
            if(this.cannot_scanned.barcode !== ''){
                this.disabled = true
                axios({
                    method:'delete',
                    url:api_endpoint+'scanner-product?barcode='+this.cannot_scanned.barcode+'&task_id='+task_id,
                }).then((e)=>{
                    console.log(e.data.data)
                    if(e.data.status == "success"){
                        new Audio('http://www.funtech.ie/audiofiles/surprise-on-a-spring.mp3').play();
                        this.disabled = false;
                        this.cannot_scanned.barcode = '';

                    }else if(e.data.status == "failed"){
                        alert('Can not deleted, item not found!')

                        new Audio('http://www.funtech.ie/audiofiles/are-you-kidding.mp3').play();
                        this.disabled = false;
                        this.cannot_scanned.barcode = '';
                    }
                })
            }
        },
        package:function(){
            this.compare();
            if(this.missmatches.length > 0){
                this.disabled = true;
                let r = confirm('Are you sure wanna package with outstanding missmatches items left?')
                if(r == true){
                    axios({
                        method:'put',
                        url:api_endpoint+'scanner-products/'+task_id
                    }).then((e)=>{
                        if(e.data.status == 'success'){
                            this.whatIhaveScanned();
                        }else{
                            alert('can not be updated!');
                            window.location.href = '../';
                        }
                        
                    })
                }
            }else if(this.missmatches.length == 0){
                this.disabled = true;
                axios({
                    method:'put',
                    url:api_endpoint+'scanner-products/'+task_id
                }).then((e)=>{
                    this.whatIhaveScanned();
                })
            }
        },
        whatIhaveScanned:function(){
            axios({
                method:'get',
                url:api_endpoint+'scanner-products/'+task_id
            }).then((e)=>{
                var doc = new jsPDF();
                var columns = ['name','barcode','send']
                var rows = [];
                var products = e.data.data.products;
                var shopname = e.data.data.shopname;
                var date = [];
                console.log(date)
                products.forEach((e)=>{
                    rows.push({name:e.name,barcode:e.barcode,send:e.total})
                    date.push({from:e.selected_from,to:e.selected_to})
                })
                console.log(date)
                var from = date[0].from.slice(0, 19).replace('T', ' '),
                    to   = date[0].to.slice(0, 19).replace('T', ' ');
                var header = function(data){
                    doc.setFontSize(12);
                    doc.setTextColor(40);
                    doc.setFontStyle('normal');
                    doc.text(`Job Number:${task_id} ${shopname} delivery sheet from ${from} to ${to}`, 10, 10);
                }
          
                doc.autoTable({body:rows,didDrawPage:header})
                doc.save(`Job Number:${task_id} ${shopname} delivery sheet`);
                window.location.href = '../';
            })
        }

    }

})


function addtheScannerInput(barcode){
    $.ajax({
        type:'post',
        dataType:'json',
        data:{
            barcode:barcode,
            task_id:Number(window.location.href.split('/').slice(-1)[0]),
            updated_quantity:1
        },
        url:api_endpoint+'scanner-product',
        success:function(res){
            new Audio('http://www.funtech.ie/audiofiles/notification.mp3').play()
            $('#barcode_scanned').val('')
        }
    })
}


function removeFromScan(barcode){
    $.ajax({
        type:'delete',
        dataType:'json',
        url:api_endpoint+'scanner-product?barcode='+barcode+'&task_id='+task_id,
        success:function(res){
            
            if(res.status == "success"){
                new Audio('http://www.funtech.ie/audiofiles/surprise-on-a-spring.mp3').play();
                $('#barcode_scanned').val('')

            }else if(res.status == "failed"){
                alert('Can not deleted, item not found!')
                new Audio('http://www.funtech.ie/audiofiles/are-you-kidding.mp3').play();
                $('#barcode_scanned').val('')
                       
            }
        }
    })
}

</script>
@endpush
@endif
