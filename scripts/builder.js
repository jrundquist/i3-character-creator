
/*************************
 * Interface Functions  
 * 
 * Functions that are 
 * directly called from 
 * buttons, actions, etc
 * in the interface
*************************/

function showTrash(){
	$('#trash-overlay').hide().fadeIn('fast');
}
function hideTrash(){
	$('#trash-overlay').fadeOut('fast');
}

function viewCard(cardId){
	openDialog('ajax/card_controller.php?type=get_typeimgsname&cardId='+cardId, 'wide');
}

function save(){
	openDialog('save');
}

function newChar(){
	alert("New Stuff is happening!");
}

function addCard(deck){
	alert(deck);
}


/*************************
 * Helper Functions  
 * 
 * Functions that are 
 * called from other functions
*************************/

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
	$.post("char_controller.php?type=get_soulall",{}, function(data){
		$('#soulatk').html(data["atk"]);
        $('#souldef').html(data["def"]);
        $('#soulbst').html(data["bst"]);
		}, "json");
}
