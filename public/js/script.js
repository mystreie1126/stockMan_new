console.log('r/myplace');
const api = 'http://localhost/project/laravel/stockManager_api/public/api';

//init
$(document).ready(function(){

 $('.section').fadeIn();

        // Hide preloader
         //$('.loader').fadeOut();

         //Init Side nav
         $('.button-collapse').sideNav();
         $('.modal').modal();


         // Init Select
       $('select').material_select();

       $('.order_details_table_modal').fancyTable({
         activeColor: 'blue',
         pagination: true,
         pagClosest: 3,
         perPage: 10,

         });

});

//main js
