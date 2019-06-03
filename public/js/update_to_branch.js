console.log(1234444);

$(document).ready(function(){
    $('.upload_qty_to_branch').click(function(e){
        e.preventDefault();
        submit_once($('.upload_qty_to_branch'),'loading...');

        let form =$(this).parent();
        form.submit();
    })

    $('.delete_before_update_to_branch').click(function(e){
        e.preventDefault();
        if(confirm(`Do you wanna delete ${$(this).prev().val()} shop upload list?`)){
            submit_once($('.delete_before_update_to_branch'),'deleting..');
            console.log($(this).prev().val());
            let form = $(this).parent();
            form.submit();
        }
    })
});
