console.log('awaiting check devices');

var awaiting_check_devices = new Vue({
    el:'#awaiting_check_devices',
    data:{
        devices:[],
        search:"",
        loading:true
    },
    created:function(){
        axios({
            method:'get',
            url:stockMan+'check_awaiting_update_devices'
        }).then(function(res){
            awaiting_check_devices.loading = false;
            console.log(res.data);
            res.data.forEach((e)=>{
                e.search = (e.brand_name.toString() + e.model_name.toString()+e.device_id.toString()).toLowerCase().concat(e.serial_number);
            });
            awaiting_check_devices.devices = res.data;

        });
    },
    computed:{
        searchLower:function(){
            return this.search.toLowerCase();
        },
        filterDevices:function(){
            return this.devices.filter((e)=>{
                return e.search.match(this.searchLower);
            })
        }
    },
    methods:{
        test_device_page:function(device_id){
            window.location.href = "test_device/"+device_id;
        }
    }
});

var technical_test = new Vue({
    el:'#technical_test',
    data:{
        device_id:$('#technical_device_id').val(),
        issues:[],
        cant_turn_on:{
            description:'',
            checked:''
        },
        isDisabled:false,
        user_id:$('.user_id').val()
    },
    created:function(){
        axios({
            method:'get',
            url:stockMan+'get_device_issues'
        }).then(function(res){
            res.data.issues.forEach((e)=>{
                e.checked = true
            });
            technical_test.issues  = res.data.issues;
            // technical_test.turn_on.description = res.data.turn_on[0].description;
        });
    },
    methods:{
        isTurnOn:function(turn){
            if(turn == true){
                this.issues.forEach((e)=>{
                    e.checked = false
                })
                this.isDisabled = true;

            }else if(turn == false){
                this.issues.forEach((e)=>{
                    e.checked = true
                })
                this.isDisabled = false;
            }
        },
        submit_issues:function(){
            let faulty = this.issues.filter((e)=>{
                return e.checked == false;
            }).map((e)=>{
                return e.description;
            });
            console.log(faulty.join(','));

            axios({
                method:'post',
                url:stockMan+'save_device_issues',
                data:{
                    user_id:this.user_id,
                    issues:(faulty.length > 0) ? faulty.join(',') : 'all good'
                }
            })
        }
    }
});
