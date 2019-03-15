//console.log('r/myplace');


jQuery.ajaxPrefilter(function(options) {
    if (options.crossDomain && jQuery.support.cors) {
        options.url = 'https://cors-anywhere.herokuapp.com/' + options.url;
    }
});
const api ='http://localhost/project/laravel/stockManager_api/public/api';

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

         $('.datepicker').pickadate({
            selectMonths: true, // Creates a dropdown to control month
            selectYears: 2, // Creates a dropdown of 15 years to control year,
            today: 'Today',
            clear: 'Clear',
            close: 'Ok',
            closeOnSelect: true, // Close upon selecting a date,
            container: undefined // ex. 'body' will append picker to body
          });

});

//main js
