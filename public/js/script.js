//console.log('r/myplace');


// jQuery.ajaxPrefilter(function(options) {
//     if (options.crossDomain && jQuery.support.cors) {
//         options.url = 'https://cors-anywhere.herokuapp.com/' + options.url;
//     }
// });
// window.confirm = function(){return true;};


//const stockMan = 'http://localhost/project/laravel/newStockApi/public/api/';
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
         sortColumn:0,
 					pagination: true,
 					perPage:5,
 					globalSearch:true,
 					paginationClass: "btn green"

         });

         $('.top_sale_product_table').fancyTable({
           sortColumn:0,
            pagination: true,
            perPage:5,
            globalSearch:true,
            paginationClass: "btn green"

           });

       $('.rep_sale_table').fancyTable({
         activeColor: 'teal',
         sortColumn:0,
         pagination: true,
         pagClosest: 3,
         perPage: 5,
         globalSearch:true

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

          $('.timepicker').pickatime({
            default: 'now', // Set default time: 'now', '1:30AM', '16:30'
            fromnow: 0,       // set default time to * milliseconds from now (using with default = 'now')
            twelvehour: false, // Use AM/PM or 24-hour format
            donetext: 'OK', // text for done-button
            cleartext: 'Clear', // text for clear-button
            canceltext: 'Cancel', // Text for cancel-button,
            container: undefined, // ex. 'body' will append picker to body
            autoclose: false, // automatic close timepicker
            ampmclickable: true, // make AM PM clickable
            aftershow: function(){} //Function for after opening timepicker
          });

});

//main js
