$(document).ready(function(){
  $('.product_btn').click(function(e){
     e.preventDefault();
    let ref = $('#ref-input').val(),
        stock_url = api+'/ProductStockSell/'+ref,
        branchStockdataPoints = [],
        totalStockCompareDataPoints = [];


    $.ajaxSetup({headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}});

    $.ajax({
      url:stock_url,
      type:'get',
      dataType:'json',
      success:function(e){
       console.log(e)







      if(e.allShop_stock.HQ == null && e.shop_stock.branch.length == 0){
        Materialize.toast('<span class="red-text">Product not Found!</span>', 2000,'rounded');
      }


      e.shop_stock.branch.forEach((e,i)=>{
          branchStockdataPoints.push({y:Number(e.quantity),label:e.name});
      });




      let current_Branch_Stock_chart = new CanvasJS.Chart("each_store_stock_chart", {
        animationEnabled: true,

        // title:{
        //   text:"Current Stock in All Branches"
        // },
        axisX:{
          interval: 1
        },
        axisY2:{
          gridColor: "rgba(1,77,101,.1)",
          title: "Branch Stocks"
        },
        data: [{
          type: "bar",
          name: "Branches",
          axisYType: "secondary",
          color: "rgb(0, 102, 255)",
          dataPoints: branchStockdataPoints
        }]
      });


      //total stock compare chart

       totalStockCompareDataPoints.push({y:e.allShop_stock.HQ, name:'Warehouse'},
                                        {y:Number(e.allShop_stock.branches),name:'All Branches'},
                                        {y:e.allShop_stock.stockIn,name:'Total Stock in'});
      let total_stock_compare_chart = new CanvasJS.Chart('total_stock_compare_chart',{
          //exportEnabled: true,
          animationEnabled: true,
          title:{
            text: "Total Stock",
          },
          data:[{
            type:'pie',
            indexLabel:"{quantity} - {y}",
            dataPoints:totalStockCompareDataPoints
          }]
      });

      let weeklyShopSale = [],
          weeklyWebSale = [];


      for(let i = 0; i < e.sales.length; i ++){
          weeklyShopSale[i] = [],
           weeklyWebSale[i] = [];

          e.sales[i].weekShop.forEach((el,a)=>{

             weeklyShopSale[i].push({label:el.name,y:Number(el.quantity)});
             weeklyWebSale[i].push({label:el.name,y:Number(el.quantity)});
          });

          //weeklyShopSaleAll.push(weeklyShopSale)
      }


let lastFourWeekchart = new CanvasJS.Chart("last-four-week-chart", {
  animationEnabled: true,

  axisY: {
    title: "General Sale Quantity",
    titleFontColor: "#4F81BC",
    lineColor: "#4F81BC",
    labelFontColor: "#4F81BC",
    tickColor: "#4F81BC"
  },
  axisY2: {
    title: "Web Sale Quantity",
    titleFontColor: "#C0504E",
    lineColor: "#C0504E",
    labelFontColor: "#C0504E",
    tickColor: "#C0504E"
  },
  toolTip: {
    shared: true
  },
  legend: {
    cursor:"pointer",

  },
  data: [{
    type: "column",
    name: "Weekly in Shop Sale",
    //legendText: "General Sales",
    //showInLegend: true,
    dataPoints: weeklyShopSale[0]
  },
  {
    type: "column",
    name: "Weekly Web Sale",
    //legendText: "Web-Sales",
    axisYType: "secondary",
    //showInLegend: true,
    dataPoints:weeklyWebSale[0]
  }]
     });


let lastThreeWeekchart = new CanvasJS.Chart("last-three-week-chart", {
  animationEnabled: true,

  axisY: {
    title: "General Sale Quantity",
    titleFontColor: "#4F81BC",
    lineColor: "#4F81BC",
    labelFontColor: "#4F81BC",
    tickColor: "#4F81BC"
  },
  axisY2: {
    title: "Web Sale Quantity",
    titleFontColor: "#C0504E",
    lineColor: "#C0504E",
    labelFontColor: "#C0504E",
    tickColor: "#C0504E"
  },
  toolTip: {
    shared: true
  },
  legend: {
    cursor:"pointer",

  },
  data: [{
    type: "column",
    name: "Weekly in Shop Sale",
    legendText: "General Sales",
    showInLegend: true,
    dataPoints: weeklyShopSale[1]
  },
  {
    type: "column",
    name: "Weekly Web Sale",
    legendText: "Web-Sales",
    axisYType: "secondary",
    showInLegend: true,
    dataPoints:weeklyWebSale[1]
  }]
});

let lastTwoWeekchart = new CanvasJS.Chart("last-two-week-chart", {
  animationEnabled: true,

  axisY: {
    title: "General Sale Quantity",
    titleFontColor: "#4F81BC",
    lineColor: "#4F81BC",
    labelFontColor: "#4F81BC",
    tickColor: "#4F81BC"
  },
  axisY2: {
    title: "Web Sale Quantity",
    titleFontColor: "#C0504E",
    lineColor: "#C0504E",
    labelFontColor: "#C0504E",
    tickColor: "#C0504E"
  },
  toolTip: {
    shared: true
  },
  legend: {
    cursor:"pointer",

  },
  data: [{
    type: "column",
    name: "Weekly in Shop Sale",
    legendText: "General Sales",
    showInLegend: true,
    dataPoints: weeklyShopSale[1]
  },
  {
    type: "column",
    name: "Weekly Web Sale",
    legendText: "Web-Sales",
    axisYType: "secondary",
    showInLegend: true,
    dataPoints:weeklyWebSale[1]
  }]
     });

let lastOneWeekchart = new CanvasJS.Chart("last-one-week-chart", {
  animationEnabled: true,

  axisY: {
    title: "General Sale Quantity",
    titleFontColor: "#4F81BC",
    lineColor: "#4F81BC",
    labelFontColor: "#4F81BC",
    tickColor: "#4F81BC"
  },
  axisY2: {
    title: "Web Sale Quantity",
    titleFontColor: "#C0504E",
    lineColor: "#C0504E",
    labelFontColor: "#C0504E",
    tickColor: "#C0504E"
  },
  toolTip: {
    shared: true
  },
  legend: {
    cursor:"pointer",

  },
  data: [{
    type: "column",
    name: "Weekly in Shop Sale",
    legendText: "General Sales",
    showInLegend: true,
    dataPoints: weeklyShopSale[3]
  },
  {
    type: "column",
    name: "Weekly Web Sale",
    legendText: "Web-Sales",
    axisYType: "secondary",
    showInLegend: true,
    dataPoints:weeklyWebSale[3]
  }]
});

//render all the charts
current_Branch_Stock_chart.render();
total_stock_compare_chart.render();
lastFourWeekchart.render();

 } //end of success call

  }); //end of ajax call

});//end of search click event









}); //end
