console.log(123123);

$('#save_popExcel').click(function(e){
      e.preventDefault();
      submit_once($(this),'saving....');
      $(this).parent().submit();
});
