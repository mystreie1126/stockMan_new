@if(Auth::check())
@extends('template')
@section('content')
    <div class="branches_general_sales container" id="editParts" style="margin-top:30px">
        <div class="row">
            <div class="input-field col s4" style="transform:translateY(-8%)">
                <input placeholder="Input Parts ID First" id="partsID" type="number" class="validate" v-model="parts_id">
                <label for="partsID">Parts ID</label>
            </div>
            <input type="hidden" value="{{Auth::User()->id}}" id="sm_user">
            <div class="input-field col s6">
                <select class="teal-text" style="display:block" v-model="shop_id" @change="onChange">
                    @foreach($shops as $shop)
                        <option value="{{$shop->id_shop}}">{{$shop->name}}</option>
                    @endforeach
                </select>
                <label>Select the Branch</label>
            </div>

            <div class="col s2 teal-text">
                <p>Login as :{{Auth::User()->name}}</p>
            </div>

            <div v-if="show" class="row">
                <table class="centered col s12">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Shop</th>
                            <th>Rockpos</th>
                            <th>Actual Qty</th>
                        </tr>
                    </thead>
                    <tbody class="center">
                        <tr>
                            <td>@{{found_parts.part_id}}</td>
                            <td>@{{found_parts.partsname}}</td>
                            <td>@{{found_parts.shopname}}</td>
                            <th class="center">@{{found_parts.rockpos_qty}}</th>
                            <th>
                                <input placeholder="Input Parts ID" id="partsID" type="number" class="validate center red-text" v-model="actual_qty">
                            </th>
                        </tr>
                    </tbody>
                </table>
                <div class="row">
                    <div class="input-field col s5" style="transform:translateX(12%)">
                        <input placeholder="Issued Staff Name" id="staff" type="text" class="validate" v-model="issued_staff">
                        <label for="staff">Issued Staff Name</label>
                    </div>
                    <div class="input-field col s12">
                        <i class="material-icons prefix">mode_edit</i>
                        <textarea id="reason" class="materialize-textarea" v-model="reason"></textarea>
                        <label for="reason">Specify The reason</label>
                    </div>
                    <button class="btn col s2 saveReason" style="transform:translateX(35%)" @click.prevent="saveTheReason(found_parts.part_id,found_parts.id_shop,issued_staff,reason,actual_qty)">Submit</button>
                  </div>
            </div>
        </div>
        <div class="row">



            @foreach($reasons as $reason)
                <ul class="collapsible" data-collapsible="accordion">
                    <li>
                      <div class="collapsible-header row valign-wrapper">
                           <i class="material-icons col s1">build</i>
                            <span class="col s4">
                                <span>{{$reason->name}}</span><br>
                            </span>


                            <span class="col s4">
                                Request Updated Quantity:&nbsp;
                                <span class="red-text">{{$reason->updated_quantity}}</span>
                            </span>

                            <span class="col s3">
                                @if($reason->approved_by > 0)
                                    Approved By:
                                    <span class="blue-text">
                                        {{DB::table('c1ft_stock_manager.sm_users')->where('id',$reason->approved_by)->value('name')}}
                                    </span>
                                @else
                                    Needs Approval
                                @endif
                            </span>
                            <span class="brown-text">
                                {{$reason->shopname}}
                            </span>
                     </div>
                      <div class="collapsible-body">
                          issued staff:<span class="red-text"> {{$reason->issued_staff}}</span><br>
                          issued date: <span class="teal-text"> {{$reason->created_at}}</span><br>
                          <p class="flow-text grey-text text-darken-2" style="font-family:sans-serif;">{{$reason->reason}}</p>
                          @if(Auth::User()->HQ == 1 && $reason->approved_by == 0)
                              <form action="{{route('updatedPartsByApproval')}}" method="post">
                                  {{csrf_field()}}
                                  <input type="hidden" value="{{$reason->parts_id}}" name="parts_id">
                                  <input type="hidden" value="{{$reason->shop_id}}" name="shop_id">
                                  <input type="hidden" value="{{$reason->updated_quantity}}" name="request_qty">
                                  <input type="show" value="{{$reason->id}}" name="reason_id">
                                  <input type="show" value="{{Auth::User()->id}}" name="user_id">
                                  <button class="btn">Approve</button>
                              </form>
                          @endif
                      </div>
                    </li>
                </ul>
            @endforeach
            {{$reasons->links()}}
        </div>
    </div>
@endif
@stop
@push('editParts_js')
    <script type="text/javascript">
        var edit_parts = new Vue({
            el:'#editParts',
            data:{
                shop_id:'',
                parts_id:'',
                found_parts:'',
                show:false,
                reason:'',
                issued_staff:'',
                actual_qty:''
            },
            methods:{
                onChange:function(){
                    if(this.parts_id !== ''){
                        this.show = false;
                        this.findParts(this.parts_id,this.shop_id);
                    }else{
                        alert('input parts id first');
                        this.shop_id = '';
                        this.show = false;
                    }

                },
                findParts:function(parts_id,shop_id){
                    axios({
                        method:'post',
                        url:stockMan+'findParts',
                        data:{
                            parts_id:parts_id,
                            shop_id:shop_id
                        }
                    }).then((e)=>{
                        console.log(e.data);
                        this.shop_id  = '';
                        this.parts_id = '';
                        this.reason   = '';
                        this.issued_staff = '';

                        if(e.data.findPart == 0){
                            alert('can not find this part')
                        }else if(e.data.findPart == 1){
                            this.show = true;
                            this.found_parts = e.data.found_parts
                        }
                    })
                },
                saveTheReason:function(parts_id,shop_id,staff,reason,actual_qty){
                    console.log(parts_id,shop_id,staff,reason,actual_qty)
                    submit_once($('.saveReason'),'saving...');

                    if(staff !== '' && reason !== '' && Number(actual_qty) >= 0){
                        axios({
                            method:'post',
                            url:stockMan+'saveEditPartsReason',
                            data:{
                                parts_id  :parts_id,
                                shop_id   :shop_id,
                                staff     :staff,
                                reason    :reason,
                                actual_qty:actual_qty,
                                sm_user   :document.getElementById('sm_user').value
                            }
                        }).then((e)=>{
                            console.log(e.data);
                            window.location.href = "editParts";
                        })
                    }else{
                        alert('You must have issued staff with reason and quantites input!');
                        this.staff  = '';
                        this.reason = '';
                        this.actual_qty = '';
                        reset_button($('.saveReason'),'submit again');
                    }
                }
            }
        });









    </script>
@endpush
