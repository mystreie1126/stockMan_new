console.log('sales report');
var color = {
    retail:'#34aeeb',
    wholesale:'#eb5634',
    profit:'#46eb34'
};

var ctx  = document.getElementById('chart').getContext('2d');
var chart = new Chart(ctx,{
    type: 'bar',
    data: {
      labels: [],
      datasets: [
        {
          label: "General Product Sales (tax excl.)",
          backgroundColor: color.retail,
          data: []
        },
        {
          label:'General Product (tax excl.)',
          backgroundColor: color.wholesale,
          data:[]
        },
        {
          label:'Net Profit',
          backgroundColor:color.profit,
          data:[]
        }
      ]
    },
    options: {
      legend: { display: true },
      title: {
        display: true,
        text: 'Rockpos today general products sale'
      }
    }
});


var allBranchSalesChart = new Vue({
    el:'#branches_general_sales',
    data:{
        selectedDays:''
    },
    methods:{
        onChange:function(){
            //console.log(this.selectedDays)
            this.getChartByDate(this.selectedDays);
            //console.log($( ".select_days option:selected" ).val());
        },
        getChartByDate:function(date){
            axios({
                method:'post',
                url:stockMan+'all_shop_sales_charts',
                data:{
                    date:date
                }
            }).then((e)=>{
                console.log(e.data.shop_data);
                var shopData = e.data.shop_data;
                 chart.data.labels = shopData.map((e)=>e.name);
                 chart.data.datasets[0].data = shopData.map((e)=>e.retail);
                 chart.data.datasets[1].data = shopData.map((e)=>e.wholesale);
                 chart.data.datasets[2].data = shopData.map((e)=>e.profit);
                 chart.options.title.text ='Rockpos products sales from ' + $( "#select_days option:selected" ).text();
                 chart.update();
            })
        }
    },
    created:function(){
       this.getChartByDate(0);
    }
})


