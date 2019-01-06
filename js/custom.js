 	document.querySelector( "input" ).addEventListener( "invalid",
		function( event ) {
			event.preventDefault();
		});
    jQuery(document).ready(function(){
	   jQuery(document).on("click",'#submit',function(){
		   jQuery(".input_error").hide();
			if (jQuery('#pf_edit_name').val() == ""){
				jQuery(".input_error").show();
			jQuery(".input_error").html("Please Enter the value");
			}
		});
	});
	
	function fnedit(user_id){
		alert(user_id);
		var r = confirm("Are you sure want to edit this record!");
            if (r == true) {	
                window.location.href="?page=sub-peoples-form&edit_id="+user_id;
            }
	}
	function fndelete(user_id){
		alert(user_id)
		var r = confirm("Are you sure want to delete this record!");
            if (r == true) {
                jQuery.ajax({
				url: ajaxurl,
				type: "post",
				data: {
					action : 'delete_action',
					user_id : user_id,
					},
				success: function(data) {
					if(data == 'success'){
						window.location.href="?page=peoples-form&user_id="+user_id;
					}
			}
	    });
      }
	}
	
	jQuery(document).ready(function(){
	   jQuery(document).on("click",'#edit_submit',function(){
		   alert();
			var edit_name =jQuery('#pf_edit_name').val();
			var edit_id =jQuery('#edit_id').val();
			jQuery.ajax({
				url: ajaxurl,
				type: "post",
				data: {
					action : 'edit_action',
					edit_name : edit_name,
					edit_id : edit_id
					},
				success: function(data) {
					if(data == 'success'){
						window.location.href="?page=peoples-form";
					}
			}
	    });			
	  });
	});
	