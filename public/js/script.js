console.log('r/myplace');

$(document).ready(function(){


  $('.total-sale').fancyTable({
    sortColumn:0,
    sortable: true,
    pagination: false,
    searchable: true,
    globalSearch: true,
    inputPlaceholder: "Search by reference",

  });

$('.bt').click(function(){
  $.ajaxSetup({
  	    headers: {
  	       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  	    }
  	});

  $.ajax({
    url:'http://localhost/project/laravel/stockManager_api/public/api/products',
    type:'get',
    dataType:'json',
    success:function(e){
      console.log(e.links.first);
    }

  });

});
















});
