function initElements(){
	$('<div id="dialog-overlay"></div>\
		<div id="dialog-wrapper">\
			<div id="dialog-container">\
				<div id="dialog-header"><div id="close-button">x</div></div>\
				<section id="dialog-content"></section>\
			</div>\
		</div>').appendTo('body').hide();

}

$(document).delegate("#close-button","click",function(){
	closeDialog();
});

$(document).delegate("#dialog-wrapper","click",function(){
	closeDialog();
});



function openDialog(url){
	if ( $('#dynamic-dialog').length == 0 ){
		initElements();
	}
	$('#dialog-wrapper').hide();
	$('#dialog-overlay').fadeIn();
	$('#dialog-wrapper #dialog-container #dialog-content').html($('#testing').html());
	$('#dialog-wrapper').fadeIn();
	
	//$('#dialog-overlay #dialog-container #dialog-content').load(url, function(){$(this).fadeIn();window.dialog_showing=true;});
}

function closeDialog(){
	
	$('#dialog-wrapper').hide();
	$('#dialog-overlay').fadeOut();
}