<?php
header("content-type: application/x-javascript");
?>


/**
 *	BubbleSort
 * 
 *	Used to sort elements in the DOM based on the CardName
 * 
 *	This method takes in the ID of the container element, and the type ( li, p, etc ) of
 *	the element to sort
 * 
 * :KLUDGE: 
 *  It may be best at some point to rewrite this function so that 
 *  the DOM is not manipulated each time there is a swap
 *  this operation is expencive and causes the browser to 'repaint'
 * 	the page each time. 
 */
function selectionSort(id, type){
	var $el =  $(id),
		$list = $el.children(type),
		len = $list.length;
		//$buffer = $el.clone();
	
	// Clear the dom 
	for( i = 0; i < len; i++ ){
		$min = $($list[i]);
		indx = i;
		for ( j = i+1; j < len; j++ ){
			oneName = $min.attr("cardname");
			twoName = $($list[j]).attr("cardname");
			oneName = oneName.split(" ")[1];
			twoName = twoName.split(" ")[1];;
			if (twoName < oneName){
				$min = $($list[j]);
				indx = j;
			}
		}	
		
		if ( i != indx ){
			console.log('swap ',i,' ~ ',indx);
			// copy_one = $($list[i]).clone(true);
			// copy_two = $($list[indx]).clone(true);
			// $($list[i]).replaceWith(copy_two);
			// $($list[indx]).replaceWith(copy_one);
		
			swp = $list[i];
			$list[i] = $list[indx];
			$list[indx] = swp;
			
			console.log($list);
		}
	}
}

// $("#sortable").change(function (){
// 	alert("change");
// });


function hoverOverCard(id){
	$("#"+id).children().each(function() {
		$(this).css({"display":"", "float":"right", "background-color":"white"});
	});

}

function hoverOffCard(id){

	$("#"+id).children().each(function() {
		$(this).hide();
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
						echo "$(\"#".$card->id.".farc\").css('background-image', 'url(\"card_imgs/".addslashes($card->picture)."\")');";
						echo "\n";
					}
				}
				?>


	        }
	    });		
	});
