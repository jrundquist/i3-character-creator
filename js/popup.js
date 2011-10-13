
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
function bubbleSort(id, type){
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

function showCardsWithTypeModified(id, type){
	console.log("in showCardsWithType");
	$(id).children().each(function() {
		console.log(this);
		if (type.indexOf("all")>=0){
			var currentStyle = this.getAttribute("style");
			if (currentStyle.indexOf("display")>=0){
				currentStyle = currentStyle.substring(0,currentStyle.indexOf("display"));
			}
			currentStyle = currentStyle + "display:;";
			this.setAttribute("style", currentStyle);
		}
		else if (this.getAttribute("cardtype").indexOf(type)<0){
			var currentStyle = this.getAttribute("style");
			if (currentStyle.indexOf("display")>=0){
				currentStyle = currentStyle.substring(0,currentStyle.indexOf("display"));
			}
			currentStyle = currentStyle + "display:none;";
			this.setAttribute("style", currentStyle);
		}
		else {
			var currentStyle = this.getAttribute("style");
			if (currentStyle.indexOf("display")>=0){
				currentStyle = currentStyle.substring(0,currentStyle.indexOf("display"));
			}
			currentStyle = currentStyle + "display:;";
			this.setAttribute("style", currentStyle);
		}



	});
}

function showCardsWithTypeModifiedTwo(id, type){
	console.log("in showCardsWithType");
	$(id).children().each(function() {
		if (type.indexOf("all")>=0){
			console.log("setting display blank for all");
			$(this).css("display","");
		}
		else if ($(this).attr("cardtype").indexOf(type)<0){
			console.log("setting display none for no match");
			$(this).css("display","none");
		}
		else {
			console.log("setting display blank for match");
			$(this).css("display","");
		}
	});
}




$(document).ready(function() {
	//Load a new character in the background.
	$.post("char_controller.php?type=new_character", { }, function(data){ 
		//Want to refresh the data on the page with the new character.
		refreshAllData(); 
		});

    //Want to get the character list popup for the user. Is in a function so it 
    //can be called by the user independently of the page loading.
    get_charlistpopup();
    
    //Set it to popup when the load-new-char div is clicked.
    $("#load-new-char").click(function(){
        get_charlistpopup();
    });
    
    //Shows the about information for our team that built the character builder.
    $("#about-info-link").click(function(){
                var team1infostr = "";
	var team2infostr = "";

	// original project team
	team1infostr += "<table border=0><tr>";
	// logo makes it too wide
        //team1infostr += "<td><img src='../components/tclLogo.png' width='175' height='166' /></td><td>";
        team1infostr += "<strong><center>Project Manager</strong><br />";
        team1infostr += "<a href='http://www.linkedin.com/in/aarongoldsmith' target='_blank'>Aaron Goldsmith</a>";
        team1infostr += "<br /><br />";
        team1infostr += "<strong>Managing Editor</strong><br />";
        team1infostr += "<a href='mailto:kmasoor3@gatech.edu'>Krishna Masoor</a>";
        team1infostr += "<br /><br />";
        team1infostr += "<strong>Writer &amp; Programmer</strong><br />";
        team1infostr += "<a href='http://www.enriquesantos.net/' target='_blank'>Enrique Santos</a>";
        team1infostr += "<br /><br />";
        team1infostr += "<strong>Graphic Designers</strong><br />";
        team1infostr += "<a href='mailto:hkhokhar3@gatech.edu'>Hassan Khokhar</a> &amp; <a href='http://www.linkedin.com/pub/elizabeth-lemar/32/a61/243' target='_blank'>Elizabeth LeMar</a>";
        team1infostr += "<br /><br />";
        team1infostr += "<strong>Programmers</strong><br />";
        team1infostr += "<a href='http://www.linkedin.com/pub/robert-basilio/32/a63/792' target='_blank'>Robert Basilio</a>, <a href='mailto:gchen7@gatech.edu'>George Chen</a>,<br /> <a href='https://www.facebook.com/profile.php?id=1218266014' target='_blank'>Sean Chiem</a> &amp; <a href='mailto:rloftin3@gatech.edu'>Robert Loftin</a><br />";
        team1infostr += "</td></center></tr></table>";

	// team 2
	$("#headerPopup").html("The I3 Character Builder Team");
        team2infostr += "<table border=0><tr>";
        // logo makes it too long
	//team2infostr += "<td><img src='../components/tclLogo.png' width='175' height='166' /></td><td>";
        team2infostr += "<center><strong>Project Manager</strong><br />";
        team2infostr += "<a href='mailto:' target='_blank'>Jason Molargik</a>";
        team2infostr += "<br /><br />";
        team2infostr += "<strong>Managing Editor</strong><br />";
        team2infostr += "<a href='mailto:'>Courtney McCormick</a>";
        team2infostr += "<br /><br />";
        team2infostr += "<strong>Writers </strong><br />";
        team2infostr += "<a href='mailto:'>Christopher Trimble</a> & ";
        team2infostr += "<a href='mailto:'>Vladimir Grantcharov</a>";
        team2infostr += "<br /><br />";
        team2infostr += "<strong>Graphic Designer</strong><br />";
	team2infostr += "<a href='mailto:'>William Grier</a>";
        team2infostr += "<br /><br />";
        team2infostr += "<strong>Programmers</strong><br />";
        team2infostr += "<a href='mailto:'>Andrew Monroe</a>, <a href='mailto:'>Irfan Somani</a>, &<br /> <a href='mailto:='>Nigel Lawrence</a><br />";
        team2infostr += "</td></center></tr></table>";        
		$("#paragraphPopup").html("<table border=\"0\"><tr><th><font size=6><u>Team 1</u></font></th><th><font size=6><u>Team 2</u></font></th></tr><tr><td>"+team1infostr+"</td><td>"+team2infostr+"</td></tr></table> <br /><div id='submitpopupval'><input type='submit' value='Close Popup' /></div>");
		centerPopup();
		loadPopup();

		//When the submit button is used on the popup.
		$("#submitpopupval").click(function(){
			disablePopup();
		});
	});

	$("#edit-charname").click(function(){
		$("#headerPopup").html("Edit character name");
		$("#paragraphPopup").html("<center><input id='popupval' type='text' value='" + $("#charname").text() + "' /><input type='hidden' id='popupid' value='charname' /><br /><div id='submitpopupval'><input type='submit' value='Submit' /></div></center>");
		centerPopup();
		loadPopup();

		//When the submit button is used on the popup.
		$("#submitpopupval").click(function(){
			$("#" + $("#popupid").val()).html($("#popupval").val());
			$.post("char_controller.php?type=set_charname", { newval: $("#popupval").val() }, function(data) {
				//$.post("char_controller.php?type=save_character",{  });
				});
			disablePopup();
		});
	});
	$("#edit-chardesc").click(function(){
		$("#headerPopup").html("Edit character description");
		$("#paragraphPopup").html("<center><textarea id='popupval' cols=40 rows=6>" + $("#chardesc").text() + "</textarea><input type='hidden' id='popupid' value='chardesc' /><br /><div id='submitpopupval'><input type='submit' value='Submit' /></div></center>");
		centerPopup();
		loadPopup();

		//When the submit button is used on the popup.
		$("#submitpopupval").click(function(){
			$("#" + $("#popupid").val()).html($("#popupval").val());
			$.post("char_controller.php?type=set_chardesc",{ newval: $("#popupval").val() }, function(data) {
				//$.post("char_controller.php?type=save_character",{  });
				});
			disablePopup();
		});
	});
	$("#edit-totalup").click(function(){
		$("#headerPopup").html("Edit character's total UP");
		$("#paragraphPopup").html("<center><input id='popupval' type='text' value='" + $("#totalup").text() + "' /><input type='hidden' id='popupid' value='totalup' /><br /><div id='submitpopupval'><input type='submit' value='Submit' /></div></center>");
		centerPopup();
		loadPopup();

		//When the submit button is used on the popup.
		$("#submitpopupval").click(function(){
			$("#" + $("#popupid").val()).html($("#popupval").val());
			$.post("char_controller.php?type=set_totalup",{ newval: $("#popupval").val() }, function(data){
				refreshCharInfo();
				//$.post("char_controller.php?type=save_character",{  });
				});
			disablePopup();
		});
	});
	$("#edit-buffercards").click(function(){
		$("#headerPopup").html("Add and remove cards from the buffer");
		var htmlstr = '<table><tr><td>';
//		htmlstr += '<div class="container"><center>Deck<br /></center>';
		htmlstr += '<div class="container"><center><img style="margin-bottom:3px;" src="components/deck.png"/></center>';


		
		htmlstr += '<div id="tabs1">';
		htmlstr += '<span id="tabscontent1" onclick=showCardsWithTypeModifiedTwo("#left","all")>All</span>';
		htmlstr += '<span id="tabscontent2" class="marginLeftPositive" onclick=showCardsWithTypeModifiedTwo("#left","race")>Race</span>';
		htmlstr += '<span id="tabscontent3" class="marginLeftPositive" onclick=showCardsWithTypeModifiedTwo("#left","aspect")>Aspect</span>';
		htmlstr += '<span id="tabscontent4" class="marginLeftPositive" onclick=showCardsWithTypeModifiedTwo("#left","power")>Power</span>';
		htmlstr += '</div>';


		htmlstr += '<select onChange=showImagePreview(this.value,0) name="itemsToChoose" id="left" size="8" multiple="multiple" style="width:200px; height:135px">';
		htmlstr += '</select>';
		htmlstr += '</div>';
		htmlstr += '</td><td>';
		htmlstr += '<div class="low container"><center>';
		htmlstr += '<input name="left2right" value="&gt; &gt;" type="button" style="width: 40px;"><br />';
		htmlstr += '<input name="right2left" value="&lt; &lt;" type="button" style="width: 40px;">';
		htmlstr += '</center></div>';
		htmlstr += '</td><td>';
//		htmlstr += '<div class="container"><center>Buffer<br /></center>';
		htmlstr += '<div class="container"><center><img style="margin-bottom:3px;" src="components/buffer.png"/></center>';





		htmlstr += '<div id="tabs1">';
		htmlstr += '<span id="tabscontent1" onclick=showCardsWithTypeModifiedTwo("#right","all")>All</span>';
		htmlstr += '<span id="tabscontent2" class="marginLeftPositive" onclick=showCardsWithTypeModifiedTwo("#right","race")>Race</span>';
		htmlstr += '<span id="tabscontent3" class="marginLeftPositive" onclick=showCardsWithTypeModifiedTwo("#right","aspect")>Aspect</span>';
		htmlstr += '<span id="tabscontent4" class="marginLeftPositive" onclick=showCardsWithTypeModifiedTwo("#right","power")>Power</span>';
		htmlstr += '</div>';

		htmlstr += '<select onChange=showImagePreview(this.value,0) name="itemsToAdd" id="right" size="8" multiple="multiple" style="width:200px; height:135px">';
		htmlstr += '</select>';
		htmlstr += '</div>';
		htmlstr += '</td></tr></table>';



		htmlstr += '<div id="submitpopupval"><input type="submit" style="margin-top:15px" value="Submit" /></div>';




//	      htmlstr += '<div id="cardFace"><img class="Front" src="" width="50%"></img>';
//             htmlstr += '<img class="Back" src="" width="50%"></img> </div>';


	      htmlstr += '<div id="cardFace">';
             htmlstr += '</div>';











		$("#paragraphPopup").html(htmlstr);

		//Populate the buffer cards.
		var presbuff = new Array();
		$("#buffer").children().each(function() {
			$("#paragraphPopup #right").html($("#paragraphPopup #right").html() + '<option cardname="'+$(this).attr("cardname")+'" cardtype="'+$(this).attr("cardtype")+'"  value="' + $(this).attr("id") + '" style="color: ' + $(this).css("color") + ';        background:none repeat scroll 0 0 #CCCCCC;                   ">' + $(this).text() + '</option>');
			bubbleSort("#right","option");
			presbuff[$(this).attr("id")] = $(this).text();
			});
		var presdeck;
		var tempcard;
		$.post("deck_controller.php?type=get_deckarray", { }, function(data){
			presdeck = data;
			for(var c in presdeck) {
                tempcard = presdeck[c];
				if(!(c in presbuff)) {
                    $("#paragraphPopup #left").html($("#paragraphPopup #left").html() + '<option cardname="'+tempcard["listname"]+'"  cardtype="'+tempcard["cardtype"]+'"  value="' + c + '" style="color: ' + cardTypeToColor(tempcard["cardtype"]) + ';       background:none repeat scroll 0 0 #CCCCCC;         ">' + tempcard["listname"] + '</option>');
					bubbleSort("#left","option");
                }
			}
			}, "json");




		centerPopup();
		deckPop = 1;
		loadPopup();
		deckPop = 0;
		$(".low input[type='button']").click(function(){  
			var arr = $(this).attr("name").split("2");  
			var from = arr[0];  
			var to = arr[1];  
			$("#" + from + " option:selected").each(function(){  
				$("#" + to).append($(this).clone());
				$(this).remove();
			});
bubbleSort("#right","option");
bubbleSort("#left","option");
		});



           
		$("#right").dblclick(function(){  
			var from = "right";  
			var to = "left";  
			$("#" + from + " option:selected").each(function(){  
				$("#" + to).append($(this).clone());
				$(this).remove();
			});
bubbleSort("#right","option");
bubbleSort("#left","option");
		});
		
		$("#left").dblclick(function(){  
			var from = "left";  
			var to = "right";  
			$("#" + from + " option:selected").each(function(){  
				$("#" + to).append($(this).clone());
				$(this).remove();
			});
bubbleSort("#right","option");
bubbleSort("#left","option");
		});

		//When the submit button is used on the popup.
        var availcardids = Array();
		$("#submitpopupval").click(function(){
			$("#buffer").html("");
			$("#paragraphPopup #right").children().each(function() {
				
				


//				$("#buffer").html($("#buffer").html() + '<li id="' + $(this).val() + '" cardname="'+ this.getAttribute("cardname") +'" cardtype="'+ this.getAttribute("cardtype") +'" class="item" style="color: ' + $(this).css("color") + ';" onDblClick=\'dispCardDetails("' + $(this).val() + '");\'>' + $(this).text() + '</li>');
//				});

				$("#buffer").html($("#buffer").html() + '<li id="' + $(this).val() + '" cardname="'+ this.getAttribute("cardname") +'" cardtype="'+ this.getAttribute("cardtype") +'" class="item" style="color: ' + cardTypeToColor(this.getAttribute("cardtype")) + ';" onDblClick=\'dispCardDetails("' + $(this).val() + '");\'>' + $(this).text() + '</li>');
				});


			disablePopup();
		});
	});

	//CLOSING POPUP
	//Click the x event!
	$("#popupClose").click(function(){
		disablePopup();
	});
	//Click out event!
	$("#backgroundPopup").click(function(){
		disablePopup();
	});
	//Press Escape event!
	$(document).keypress(function(e){
		if(e.keyCode==27 && popupStatus==1){
			disablePopup();
		}
	});
});

//SETTING UP OUR POPUP
//0 means disabled; 1 means enabled;
var popupStatus = 0;
var zoomIn = 0;
var deckPop = 0;
var charSel = 0;

//loading popup with jQuery magic!
function loadPopup(){
	if (zoomIn===0){
		var currStyle = $("#foregroundPopup").attr("style");
		//var index = currStyle.indexOf("left");
		//currStyle = currStyle.substring(0,index) + "left:381.5px;";
		var index = currStyle.indexOf("width");
		currStyle = currStyle.substring(0,index);

		$("#foregroundPopup").attr("style",currStyle);
	}
	if (zoomIn===1) {
		var currStyle = $("#foregroundPopup").attr("style");
		//var index = currStyle.indexOf("left");
		//var index2 = currStyle.indexOf(";",index);
		//currStyle = currStyle.substring(0,index) + currStyle.substring(index2);
		//currStyle = currStyle + "left:231.5px;width:800px;height:575px;";
		currStyle = currStyle + "width:800px;height:575px;";

		$("#foregroundPopup").attr("style",currStyle);
	}
	if (deckPop===1){
		var currStyle = $("#foregroundPopup").attr("style");
		currStyle += "height:625px;";
		$("#foregroundPopup").attr("style",currStyle);
	}
	if (charSel===1){
		var currStyle = $("#foregroundPopup").attr("style");
		currStyle += "height:575px;";
		$("#foregroundPopup").attr("style",currStyle);
	}




centerPopup();

	//loads popup only if it is disabled
	if(popupStatus==0){
		$("#backgroundPopup").css({
			"opacity": "0.7"
		});
		$("#backgroundPopup").fadeIn("slow");
		$("#foregroundPopup").fadeIn("slow");
		popupStatus = 1;
	}

}

//disabling popup with jQuery magic!
function disablePopup(){
	//disables popup only if it is enabled
	if(popupStatus==1){
		$("#backgroundPopup").fadeOut("slow");
		$("#foregroundPopup").fadeOut("slow");
		popupStatus = 0;
	}
}

//centering popup
function centerPopup(){
	//request data for centering
	var windowWidth = document.documentElement.clientWidth;
	var windowHeight = document.documentElement.clientHeight;
	var popupHeight = $("#foregroundPopup").height();
	var popupWidth = $("#foregroundPopup").width();
	//centering
	$("#foregroundPopup").css({
		"position": "absolute",
		"top": windowHeight/2-popupHeight/2,
		"left": windowWidth/2-popupWidth/2
	});
	//only need force for IE6
	
	$("#backgroundPopup").css({
		"height": windowHeight
	});	
}


function getRaceCardId(charId){
//	console.log("charId: "+charId);
	jQuery.ajax({
         	url:    "user_chars.php?type=get_racecard&charid="+charId,
         	success: function(result) {
			//console.log("race id: "+result);
			if (result.length===0){
				$("#cardFace").css("display","none");
			}
			else {
				$("#cardFace").css("display","");
				showImagePreview(result,1)
			}
			

              },
         	async:   false
    }); 




}

function get_charlistpopup() {
    //Give the character selection popup.
	var charlist;
	$.post("user_chars.php?type=get_usercharslist", {  }, function(data){
		charlist = data;
		$("#headerPopup").html("Your Characters");
		var htmlstr = "";
		htmlstr += '<div class="container"><center><select onChange="getRaceCardId(this.value)" name="charsToChoose" id="charlist" size="8">';
		htmlstr += "</select></center></div>";







		htmlstr += '<table><tr>';
		htmlstr += '<td><div id="new-char"><a href="#"><img src="components/buttons/new-button.png" width="24" height="24" title="New Character" /></a></div></td>';
		htmlstr += '<td><div id="edit-selected-char"><a href="#"><img src="components/buttons/edit-button.jpg" width="24" height="24" title="Load Character" /></a></div></td>';
		htmlstr += '<td><div id="delete-selected-char"><a href="#"><img src="components/buttons/remove-button.png" width="24" height="24" title="Delete Character" /></a></div></td>';
		htmlstr += '</tr></table>';






		htmlstr += '<div id="cardFace"></div>';






		$("#paragraphPopup").html(htmlstr);


		var myArr=new Array();
		var count = 0;

		var count2 = 0;
		for(var ch in charlist) {
			$("#paragraphPopup #charlist").html($("#paragraphPopup #charlist").html() + '<option value="' + ch + '">' + charlist[ch] + '</option>');
		}





		$("#new-char").click(function(){

			//Don't care about what's selected. Close and refresh all with new char.
			$.post("char_controller.php?type=new_character", { }, function(data){ 
				//Want to refresh the data on the page with the new character.
				refreshAllData();
				refreshEquippedCards();
    				$("#canvas").html("");
    				$("#race").html("<img class=\"constrained\" src=\"components/race.png\" />");
				disablePopup();
				});




				$.post("char_controller.php?type=set_totalup",{ newval: 50 }, function(data){
				refreshCharInfo();
				//$.post("char_controller.php?type=save_character",{  });
				});






		});
		$("#edit-selected-char").click(function(){
			//Take what's selected, get it, and then refreshAllData() after closing.
			var selObj = $("#charlist option:selected");
			var sel = 0;
			if(selObj.length > 0) {
				sel = selObj.val();
				$.post("char_controller.php?type=get_character", { charid: sel }, function(data){ 
					//Want to refresh the data on the page with the retreived character.
					refreshAllData();
					refreshEquippedCards();
    					$("#canvas").html("");
    					$("#race").html("<img class=\"constrained\" src=\"components/race.png\" />");
					disablePopup();
					});
			}
		});
		$("#delete-selected-char").click(function(){
			//Take what's selected and call the delete function. Don't close but refresh just in case.
			var selObj = $("#charlist option:selected");
			var sel = 0;
			if(selObj.length > 0) {
				sel = selObj.val();
				$.post("char_controller.php?type=del_character", { charid: sel }, function(data){
					selObj.remove();
					//In the event that the present character was deleted.
					refreshAllData();
					refreshEquippedCards();
					$("#canvas").html("");
    					$("#race").html("<img class=\"constrained\" src=\"components/race.png\" />");
					});
			}
		});

		centerPopup();
		charSel = 1;
		loadPopup();
		charSel = 0;
		}, "json");
}

function refreshAllData() {
	refreshCharInfo();
	refreshAllStats();
}

function refreshAllStats() {
	refreshStatsBody();
	refreshStatsMind();
	refreshStatsSoul();
	$.post("char_controller.php?type=get_vitality",{  }, function(data){
		$('#vitality').html(data);
		}, "json");
}

function refreshCharInfo() {
	$.post("char_controller.php?type=get_charname",{  }, function(data){
		$('#charname').html(data);
		}, "json");
	$.post("char_controller.php?type=get_chardesc",{  }, function(data){
		$('#chardesc').html(data);
		}, "json");
	$.post("char_controller.php?type=get_totalup",{  }, function(data){
		$('#totalup').html(data);
		}, "json");
	$.post("char_controller.php?type=get_currentup",{  }, function(data){
		$('#swapbuffer').html(data);
		}, "json");
}

function refreshStatsBody() {
	$.post("char_controller.php?type=get_bodyall",{  }, function(data){
		$('#bodyatk').html(data["atk"]);
        $('#bodydef').html(data["def"]);
        $('#bodybst').html(data["bst"]);
		}, "json");
}

function refreshStatsMind() {
	$.post("char_controller.php?type=get_mindall",{  }, function(data){
		$('#mindatk').html(data["atk"]);
		$('#minddef').html(data["def"]);
		$('#mindbst').html(data["bst"]);
		}, "json");
}

function refreshStatsSoul() {
	$.post("char_controller.php?type=get_soulall",{  }, function(data){
		$('#soulatk').html(data["atk"]);
        $('#souldef').html(data["def"]);
        $('#soulbst').html(data["bst"]);
		}, "json");
}

function refreshEquippedCards() {
    var charcards;
    var tempcard;
    $("#sortable").html("");
    $.post("char_controller.php?type=get_charcards", { }, function(data){
        charcards = data;
        for(var c in charcards) {
            tempcard = charcards[c];
            $("#sortable").html($("#sortable").html() + '<li id="' + c + '" class="item" cardname="'+tempcard["listname"]+'"  cardtype="'+tempcard["cardtype"]+'"  style="color: ' + cardTypeToColor(tempcard["cardtype"]) + ';" onDblClick=\'dispCardDetails("' + c + '");\'>' + tempcard["listname"] + '</li>');
            /*$("#" + c).bind('dblclick', function() {
                alert("here");
                });*/
            if(tempcard["cardtype"] == "race") {
                /*
			$("#race").html('<img src="card_imgs/' + tempcard["backimg"] + '" style="width: 45%;">');
                $("#race").html($("#race").html() + '<img src="card_imgs/' + tempcard["frontimg"] + '" style="width: 45%;">');
                $("#race").css('text-align', 'center');
			*/

		var TheRaceCardId = tempcard["backimg"];
		var index = TheRaceCardId.indexOf("_");
		TheRaceCardId = TheRaceCardId.substring(0,index);



                $("#race").html('<img ondblclick=dispCardDetails("'+TheRaceCardId+'") src="card_imgs/' + tempcard["backimg"] + '" style="width: 49%;">');
                $("#race").html($("#race").html() + '<img ondblclick=dispCardDetails("'+TheRaceCardId+'") src="card_imgs/' + tempcard["frontimg"] + '" style="width: 49%;">');
                $("#race").css('text-align', 'center');




            }
		bubbleSort("#sortable","li");
        }
    }, "json");




}


function showImagePreview(cid, onlyFront){
   $.post("card_controller.php?type=get_typeimgsname", { cardid: cid }, function(data) {
               var frontUrl = 'card_imgs/'+data["frontimg"];
               var backUrl = 'card_imgs/'+data["backimg"];

//		$("#cardFace .Front").attr("src", backUrl);
//              $("#cardFace .Back").attr("src", frontUrl);


		var one = '<img class="Front" src="'+backUrl+'" width="50%"></img>';
		var two = '<img class="Back" src="'+frontUrl+'" width="50%"></img>';


		$("#cardFace").html(one + two);

		if (onlyFront===1){
			$(".Back").css("display","none");
		}


       }, "json");

}


function dispCardDetails(cid) {
    $.post("card_controller.php?type=get_typeimgsname", { cardid: cid }, function(data) {
        $("#headerPopup").html("Card Details");
        var cdetailhtml = "<center>";
 	 cdetailhtml += '<img src="card_imgs/' + data["backimg"] + '" style="width: 45%;" >';
        cdetailhtml += '<img src="card_imgs/' + data["frontimg"] + '" style="width: 45%;" >';
        cdetailhtml += '</center>';
        cdetailhtml += '<div id="closepopup"><input type="submit" value="Close Detail View" /></div>';
        $("#paragraphPopup").html(cdetailhtml);
        centerPopup();


	 zoomIn = 1;
        loadPopup();
 	 zoomIn = 0;


        $("#closepopup").click(function(){
            disablePopup();
            });
        }, "json");
}

function cardTypeToColor(typestr) {
    var colorarr = Array();
    colorarr['race']   = 'red';
    colorarr['aspect'] = 'yellow';
    colorarr['power']  = 'blue';
    colorarr['minion'] = 'purple';
    colorarr['bane']   = 'black';
    if(typestr in colorarr) {
        return colorarr[typestr];
    }
    //Return black as default if not a known card type.
    return 'black';
}
