/***
 * Setup Functions
 */
function initElements(){
	$('<div id="dialog-overlay"></div>\
		<div id="dialog-wrapper">\
			<div id="dialog-container">\
				<div id="dialog-header"><div id="close-button">x</div></div>\
				<section id="dialog-content"></section>\
			</div>\
		</div>').appendTo('body').hide();

}
function initFooter(){
	$('#dialog-wrapper #dialog-container').append('<div id="dialog-footer"><button id="dialog-close">close</button></div>');
}

/*** 
 * Functions to handle default events 
 */
$(document).delegate("#close-button, #dialog-wrapper, #dialog-close","click",function(){
	closeDialog();
});
$(document).delegate("#dialog-container","click",function(){
	e.cancelBubble = true;
	e.stopPropagation();
});


/***
 * Callable functions 
 */
function openDialog(url, specialClass){
	
	// If we dont have a dialog yet, make one
	if ( $('#dynamic-dialog').length == 0 ){
		initElements();
	}

	// Prepare to show the dialog ( hide the dialog and show the fadeout background )
	$('#dialog-wrapper').hide();
	$('#dialog-overlay').fadeIn();

	$('#dialog-container').attr('class', specialClass);

	// Load either the template, or the URL passed
	if ( $('script[type="text/template"]#_'+url).length != 0 ){  // If we were passed an id of a template
		content = $('script[type="text/template"]#_'+url).html();
	}else{
		content = "[ Error loading content ]";
		jQuery.ajax({url:url, success:function(html){content = html;}, async:false});
	}
	// Set the content into the dialog container
	$('#dialog-wrapper #dialog-container #dialog-content').html(content);

	// Make sure there is a footer ( where the action buttons are )
	if ( $('#dialog-wrapper #dialog-container #dialog-footer').length == 0 ){
		initFooter();
	}
	// Show the dialog itself
	$('#dialog-wrapper').fadeIn();
}

function closeDialog(){
	$('#dialog-wrapper').hide();
	$('#dialog-overlay').fadeOut();
	$('#dialog-container').removeAttr('class');
	
}