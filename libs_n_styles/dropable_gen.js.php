<?php
header("content-type: application/x-javascript");
?>



function bubbleSort(id, type){
	console.log("sorting...");
	var len = $(id).children().length;
	var i = 1;
	var j = 1;
	for (i = 1; i < len; i++){
		for (j=i+1; j <=len; j++){
			var one = $(id+" "+type+":nth-child("+i+")");
			var two = $(id+" "+type+":nth-child("+j+")");
			var oneName = $(one).attr("cardname");
			var twoName = $(two).attr("cardname");
			oneName = oneName.substring(oneName.indexOf(" ")+1);
			twoName = twoName.substring(twoName.indexOf(" ")+1);
			if (twoName < oneName){
				var copy_one = $(one).clone(true);
				var copy_two = $(two).clone(true);
				$(one).replaceWith(copy_two);
				$(two).replaceWith(copy_one);
			}			
		}
	}
}



$("#sortable").change(function (){
	alert("change");
});


function hoverOverCard(id){

//	console.log("hover on: "+id);
/*	console.log("test: "+ $("#"+id+" #closeSpan"));
	console.log("style: "+ $("#"+id+" #closeSpan").attr("style"));
	$("#"+id+" #closeSpan").attr("style","display: align:right");
	$("#"+id+" #zoomSpan").attr("style","display: align:right");
*/

	$("#"+id).children().each(function() {
		this.setAttribute("style","display:; float:right;background-color:white;");
	});


}

function hoverOffCard(id){

//	console.log("hover off: "+id);
/*	$("#"+id+" #closeSpan").attr("style","display:none");
	$("#"+id+" #zoomSpan").attr("style","display:none");
*/


	$("#"+id).children().each(function() {
		this.setAttribute("style","display:none;");
	});
	
}

$(document).ready(function() {
		var dropped = false;
	    var draggable_sibling;
	    var current;
		var selfrom;
		var selto;

	    $( "#sortable, #buffer" ).sortable({
			connectWith: ".connectedSortable"
		}).disableSelection();

	    $("#sortable").sortable({
	        start: function(event, ui) {
	            draggable_sibling = $(ui.item).prev();
	            current =  $(ui.item);
	        },
	        stop: function(event, ui) {
				selfrom = event.target.id;
				selto = $(ui.item).parent().attr("id");
				if(selfrom == 'sortable' && selfrom == selto) {
					//Then stayed in the equipped cards.
				}
				else if(selfrom == 'sortable' && selto == 'buffer') {
					//Then was removed from the equipped cards.
					$.post("char_controller.php?type=remove_card", { cardid: $(ui.item).attr("id") }, function(data) { 
						if(data.length > 0) {
							//Then the card wasn't removed.
							$("#sortable").append($(ui.item).clone());
							$(ui.item).remove();
						}
						else {
							//The card was removed, let's refresh.
							refreshAllData();
							//And if is a race card, then populate the race area.
							$.post("card_controller.php?type=get_cardtype", { cardid: $(ui.item).attr("id") }, function(data) {
								if(JSON.parse(data) == "race") {
									$("#race").html('<img src="components/race.png" class="constrained" />');
								}
								});
						}
						//$.post("char_controller.php?type=save_character", {  });
						refreshAllData();
bubbleSort("#buffer","li");
						});
				}

	            if (dropped) {
	                if (draggable_sibling.length == 0)
	                    $('#sortable').prepend(ui.item);

	                draggable_sibling.after(ui.item);
	                dropped = false;
	            }
	        }
	    });

	    $("#buffer").sortable({
	        start: function(event, ui) {
	            draggable_sibling = $(ui.item).prev();
	            current =  $(ui.item);
	        },
	        stop: function(event, ui) {
				selfrom = event.target.id;
				selto = $(ui.item).parent().attr("id");
				if(selfrom == 'buffer' && selfrom == selto) {
					//Then stayed in the buffer cards.
				}
				else if(selfrom == 'buffer' && selto == 'sortable') {
					//Then was added to the equipped cards.
					$.post("char_controller.php?type=add_card", { cardid: $(ui.item).attr("id") }, function(data) {
						if(data.length > 0) {
							//Then the card wasn't added.
							if (data.indexOf("Sorry, you don't have enough available UP to add this card")>=0)
								alert(data+".");
							$("#buffer").append($(ui.item).clone());
							$(ui.item).remove();
						}
						else {
							//The card was added, let's refresh.
							refreshAllData();
							//And if it's a race card, then populate the race area.
							$.post("card_controller.php?type=get_typeimgsname", { cardid: $(ui.item).attr("id") }, function(data) {
								if(data["cardtype"] == "race") {
									var TheRaceCardID = data["backimg"];
									var index = TheRaceCardID.indexOf("_");
									TheRaceCardID = data["backimg"].substring(0,index);
									$("#race").html('<img ondblclick=dispCardDetails("'+TheRaceCardID+'")  src="card_imgs/' + data["backimg"] + '" style="width: 45%;">');
									$("#race").html($("#race").html() + '<img ondblclick=dispCardDetails("'+TheRaceCardID+'") src="card_imgs/' + data["frontimg"] + '" style="width: 45%;">');
                                    $("#race").css('text-align', 'center');
								}
								}, "json");
						}

bubbleSort("#sortable","li");

						});
				}

	            if (dropped) {
	                if (draggable_sibling.length == 0)
	                    $('#buffer').prepend(ui.item);

	                draggable_sibling.after(ui.item);
	                dropped = false;
	            }
	        }
	    });

	    $(".droppable").droppable({
	    	accept: ".item",
	        drop:function(event,ui){
//			console.log("ui.item: "+current.attr("id"));
			var duplicate = 0;
			$("#canvas").children().each(function(){
//				console.log("this: "+this.getAttribute("id"));

				if (this.getAttribute("id").indexOf(current.attr("id"))>=0){
					alert("Sorry, cannot add duplicate card.");
					duplicate = 1;
					return;	
				}

			});
			if (duplicate===1) return;























	            dropped = true;
	            $(event.target).addClass('dropped');






//	            $(this).append("<div style=\"margin:10px;\" onmouseover=hoverOverCard(\""+current.attr("id")+"\") onmouseout=hoverOffCard(\""+current.attr("id")+"\")  ondblclick=dispCardDetails(\""+current.attr("id")+"\") class=\"farc\" id=\""+current.attr("id")+"\"><span style=\"display:;\" id=\"zoomSpan\" onclick=dispCardDetails(\""+current.attr("id")+"\")><img style=\"cursor:pointer;\" width=\"25px\" height=\"25px\" src=\"components/buttons/zoom.png\"/></span><span style=\"display:;\" id=\"closeSpan\" onclick=$(this).parent().remove()><img style=\"cursor:pointer;\" width=\"25px\" height=\"25px\" src=\"components/buttons/XButton.png\"/></span></div>");



$(this).append("<div style=\"margin:10px;\" onmouseover=hoverOverCard(\""+current.attr("id")+"\") onmouseout=hoverOffCard(\""+current.attr("id")+"\")  ondblclick=dispCardDetails(\""+current.attr("id")+"\") class=\"farc\" id=\""+current.attr("id")+"\"><span style=\"display:none;float:right;\" id=\"closeSpan\" onclick=$(this).parent().remove()><img style=\"cursor:pointer;\" width=\"25px\" height=\"25px\" src=\"components/buttons/XButton.png\"/></span><span style=\"display:none;float:right;\" id=\"zoomSpan\" onclick=dispCardDetails(\""+current.attr("id")+"\")><img style=\"cursor:pointer;\" width=\"25px\" height=\"25px\" src=\"components/buttons/zoom.png\"/></span></div>");






	            $(".farc").draggable({ containment: "#canvas" });
	            $(".farc").css('position', 'absolute');
	            $(".farc").css('width', '152px');
	            $(".farc").css('height', '203px');
                var ypos = event.pageY - $("#title").height();
                if(ypos > ($("#canvas").height() - $("#" + current.attr("id")).height())) {
                    $("#" + current.attr("id")).css('top', ($("#canvas").height() + $("#title").height()) - $("#" + current.attr("id")).height());
                }
                else {
                    $("#" + current.attr("id")).css('top', event.pageY);
                }
                var xpos = event.pageX - $("#subCtnInfo").width() - (document.documentElement.clientWidth - $("#content").width())/2;
                if(xpos > ($("#canvas").width() - $("#" + current.attr("id")).width())) {
                    $("#" + current.attr("id")).css('left', $("#subCtnInfo").width() + (document.documentElement.clientWidth - $("#content").width())/2 + ($("#canvas").width() - $("#" + current.attr("id")).width()));
                }
                else {
                    $("#" + current.attr("id")).css('left', event.pageX);
                }
	            $(".farc").css('background-size', '185px 235px');
				$(".farc").css('display', 'block');
				$(".farc").css('background-position', '-17px -16px');
				$(".farc").css('background-repeat', 'no-repeat');
	            $("#one.farc").css('background-image', 'url(1.jpg)');
	            $("#two.farc").css('background-image', 'url(2.jpg)');
	            $("#three.farc").css('background-image', 'url(3.jpg)');
	            $("#four.farc").css('background-image', 'url(4.jpg)');




<?php
//Sets the $userid variable.
require_once('../get_userid.php');

require_once('../cb_backend/deck.php');
require_once('../cb_backend/card.php');

$deck = new Deck($userid);

if(!$deck) {
	echo json_encode("failure");
}
else {
	$deckarr = $deck->getDeckArr();
	$toretarr = array();
	foreach($deckarr as $card) {
		echo "	            $(\"#".$card->id.".farc\").css('background-image', 'url(\"card_imgs/".addslashes($card->picture)."\")');";
		echo "\n";
	}
}
?>






		//ui.draggable.append('<a id="'+current.attr("id")+'" class="remove_link" onClick="$(\'#'+current.attr("id")+'.farc\').remove(); $(this).remove();">Remove From Table</a>');














	        }
	    });		
	});
