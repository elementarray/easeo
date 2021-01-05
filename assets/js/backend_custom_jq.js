console.log("loading backend_custom_jq.js...");

(function( $ ) {
	$(this).on("load change",function() {
	  console.log( "Handler for .change() called." );
	});

	// serialize the form data
	let ajax_form_data = $("#easeo_form").serialize();
	
	//ajax_form_data=ajax_form_data+'&submit=Submit+Form';
	$.ajax({
	        url:    ajaxTest.ajax_url, 
	        type:   'post',                
	        data:   { action: 'save_order', ajax_form_data }
	})
	            
        .done( function( response ) { // response from the PHP action
	        $(" #ea_form_feedback ").html( "<h2>The request was successful </h2><br>" + response );
	})
	            
	// something went wrong  
	.fail( function(error) {
	       	$(" #ea_form_feedback ").html( "<h2>Something went wrong.</h2>"+JSON.stringify(error)+"<br>" );                  
	 })

})( jQuery );
