/*************************
 *
 * This file contains the actual application scripts
 *
*************************/

Character = function(name, id, totalUP, swapBuffer){
	this.name = name;
	this.id = id;
	this.totalUP = totalUP;
	this.swapBuffer = swapBuffer;

}

Character.prototype.name = null;
Character.prototype.id = null;
Character.prototype.totalUP = null;
Character.prototype.swapBuffer = null;
Character.prototype.description = null;
Character.prototype.deck = [];
Character.prototype.swapDeck = [];
Character.prototype.stats = new Stats();

Stats = function(){}

Stats.prototype.mind = {ATK:  0, DEF: 0, BOOST: 0};
Stats.prototype.body = {ATK:  0, DEF: 0, BOOST: 0};
Stats.prototype.soul = {ATK:  0, DEF: 0, BOOST: 0};
Stats.prototype.vitality = 0;



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

function loadChar(){
	openDialog('load');
	
	$.ajax({	url:'/ajax/load.php',
				dataType:'json',
				success: function(j){
					$list = $('#character-list').empty();
					for(i=0; i<j.length; i++){
						character = j[i];
						
						$added = $('<div id="character-'+character['charid']+'" class="loadable-character">'+character['charname']+'</div>').appendTo($list);
						$added.data('character', character);
					}
				}
			});
}

function doLoad(){
	$this = $('.loadable-character.chosen');
	if ( $this.length == 0 ){
		return false;
	}
	//Show image
	character = $this.data('character');
	if ( character ){
		$.ajax({	url:'/ajax/load.php',
		 			type: "POST",
					data: {id : character.charid},
					dataType: "json",
					success: function(d){ 
						console.log('loaded!',d);
						//Want to refresh the data on the page with the retreived character.
						// refreshAllData();
						// refreshEquippedCards();
						$('#charName h2').html(character.charname);
						if ( character.image ) 
							$('img#cardImg').attr('src','http://testcb.untoldthegame.com/Version1.3/card_imgs/'+character.image);
						else
							$('img#cardImg').attr('src', 'images/unknown.png');
						// disablePopup();
						closeDialog();
					}
			});
	}
	
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
