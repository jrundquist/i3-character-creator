/*************************
 *
 * This file contains the actual application scripts
 *
*************************/


function cast(rawObj, constructor){
    var obj = new constructor();
    for(var i in rawObj){
        obj[i] = rawObj[i];
	}
    return obj;
}



// Sub-object for storing the stats of the character
Stats = function(){}

Stats.prototype.mind = {attack:  0, defence: 0, boost: 0};
Stats.prototype.body = {attack:  0, defence: 0, boost: 0};
Stats.prototype.soul = {attack:  0, defence: 0, boost: 0};
Stats.prototype.vitality = 0;



// Character prototype
Character = function(name, id, totalUP, swapBuffer){}

Character.prototype.name = null;
Character.prototype.id = null;
Character.prototype.totalUP = null;
Character.prototype.swapBuffer = null;
Character.prototype.description = null;
Character.prototype.deck = [];
Character.prototype.swapDeck = [];
Character.prototype.stats = new Stats();

Character.prototype.calcSwapBuffer = function(){
	this.swapBuffer = this.totalUP;
	for(card in this.deck){
		this.swapBuffer -= parseFloat(this.deck[card].cost);
	}
}
	

// Create the global character object
character = new Character();


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
	$.ajax({	url:'/ajax/new.php',
				dataType:'json',
				success: function(j){
					if ( j == true ){
						//refresh everything
						resetAll();
					}else{
						alert("ERRORRRRR");
					}
				}
			});
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
						characterShort = j[i];
						$added = $('<div id="character-'+characterShort['charid']+'" class="loadable-character">'+characterShort['charname']+'</div>').appendTo($list);
						$added.data('character', characterShort);
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
	characterShort = $this.data('character');
	if ( character ){
		$.ajax({	url:'/ajax/load.php',
		 			type: "POST",
					data: {id : characterShort.charid},
					dataType: "json",
					success: function(charaterLoaded){ 
						
						character = cast(charaterLoaded, Character);
						resetAll();
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

function resetAll() {
	reloadStats();
	reloadCharacter();
	// reloadDecks();
	reloadUP();
}

function reloadUP(){
	$('#pointsUP').html(character.totalUP);
	character.calcSwapBuffer();
	$('#pointsSB').html(character.swapBuffer);
}

function reloadCharacter(){
	// Name
	$('#charName h2').html(character.name);
	
	// Image
	if ( characterShort.image ) 
		$('img#cardImg').attr('src','http://testcb.untoldthegame.com/Version1.3/card_imgs/'+characterShort.image);
	else
		$('img#cardImg').attr('src', 'images/unknown.png');
		
	// Description
	$('#charDiscTxt').html(character.description);
}

function reloadStats() {
	// loops through the stats of the character
	for( aspect in character.stats ){
			for( bonus in character.stats[aspect] ){
				$('.statNum.'+aspect+'.'+bonus).html(character.stats[aspect][bonus]);
			}
		}
	$('.statNum#vitality').html(character.stats.vitality);
}

// function reloadCharacter() {
// 	$.post("char_controller.php?type=get_charname",{  }, function(data){
// 		$('#charname').html(data);
// 		}, "json");
// 	$.post("char_controller.php?type=get_chardesc",{  }, function(data){
// 		$('#chardesc').html(data);
// 		}, "json");
// 	$.post("char_controller.php?type=get_totalup",{  }, function(data){
// 		$('#totalup').html(data);
// 		}, "json");
// 	$.post("char_controller.php?type=get_currentup",{  }, function(data){
// 		$('#swapbuffer').html(data);
// 		}, "json");
// }