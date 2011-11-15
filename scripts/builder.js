/*************************
 *
 * This file contains the actual application javascript
 * 
 * 
 * 
 * 
*************************/

/*************************
 * Data Helper Functions  
 * 
 * Functions that are 
 * are purely data helpers
 * such as formatters (none
 * currently exist, but if 
 * they did they would go here), 
 * a caster function, etc.
*************************/

function cast(rawObj, constructor){
    var obj = new constructor();
    for(var i in rawObj){
        obj[i] = rawObj[i];
	}
    return obj;
}


/*************************
 * Object Prototypes
 * 
 * This is where we basically 
 * build out the objects and 
 * define their functions
*************************/
// Sub-object for storing the stats of the character
Stats = function(){}

Stats.prototype.mind = {attack:  0, defense: 0, boost: 0};
Stats.prototype.body = {attack:  0, defense: 0, boost: 0};
Stats.prototype.soul = {attack:  0, defense: 0, boost: 0};
Stats.prototype.vitality = 0;

// Character prototype
Character = function(name, id, totalUP, swapBuffer){}

Character.prototype.name = "[Character Name]";
Character.prototype.id = null;
Character.prototype.totalUP = 0;
Character.prototype.swapBuffer = 0;
Character.prototype.description = null;
Character.prototype.notes = null;
Character.prototype.deck = [];
Character.prototype.swapDeck = [];
Character.prototype.stats = new Stats();

Character.prototype.calcSwapBuffer = function(){
	this.swapBuffer = this.totalUP;
	for(card in this.deck){
		this.swapBuffer -= parseFloat(this.deck[card].cost);
	}
	this.sessionSave();
}

Character.prototype.calcStats = function(){
	
	// Reset stats
	for ( aspect in this.stats ){
		for( bonus in this.stats[aspect] ){
			this.stats[aspect][bonus] = 0;
		}
	}
	this.stats.vit = 0;
	
	// Calculate stats based on the cards in the deck
	for(card in this.deck){
		for ( aspect in this.stats ){
			for( bonus in this.stats[aspect] ){
				this.stats[aspect][bonus] += this.deck[card][aspect][bonus];
			}
		}
		// Update Vitality
		if ( this.deck[card].vit ){
			this.stats.vit += this.deck[card].vit;
		}
	}
	this.sessionSave();
}

Character.prototype.sessionSave = function(){
	sessionStorage.setItem('character', JSON.stringify(this));
}


// Create the global character object
if ( sessionStorage && sessionStorage.character ){
	try{
		charObj = JSON.parse(sessionStorage.character);
		character = cast(charObj, Character);
	}catch(err){
		character = new Character();
	}

}else{
	character = new Character();	
}
resetAll();


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

// Save character call
function save(){
	character; // <-- Save this on the server 
			   //     HINT: start by POSTing it to the server using $.ajax
	openDialog('save');
}

// New dialog call
function newChar(){
	
	$.ajax({	url:'/ajax/new.php',
				dataType:'json',
				success: function(j){
					if ( j == true ){
						character = new Character;
						resetAll();
					}else{
						alert("ERRORRRRR");
					}
				}
			});
}

// Ad card dialog
function addCard(deck){
	alert(deck);
}

// This is the dialog for loading characters
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

// This is the function that is called when a character needs to be loaded
function doLoad(){
	$this = $('.loadable-character.chosen');
	if ( $this.length == 0 ){
		return false;
	}
	characterShort = $this.data('character');
	if ( character ){
		$.ajax({	url:'/ajax/load.php',
		 			type: "POST",
					data: {id : characterShort.charid},
					dataType: "json",
					success: function(charaterLoaded){ 
						// Cast the downloaded character to a js character object
						character = cast(charaterLoaded, Character);
						character.sessionSave();
						// Reset the interface with the new character
						resetAll();
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
	reloadUP();
	reloadDecks();
	reloadStats();
	reloadCharacter();
}

function reloadUP(){
	character.calcSwapBuffer();
	$('#pointsUP').html(character.totalUP);
	$('#pointsSB').html(character.swapBuffer);
}

function reloadDecks(){
	// Clear the decks
	var $deck = $('#charDeckContent'),
		$swapDeck = $('#swapDeckContent'),
		template = $('script[type="text/template"]#card').html(),
		cardColor = {'1':'red', '2':'yellow', '3':'blue'};
	
	$deck.empty();
	$swapDeck.empty();
	
	for(card in character.deck){
		thisCard = template .replace('{color}', cardColor[character.deck[card].cardType])
							.replace('{id}', character.deck[card].id)
							.replace('{name}', character.deck[card].name)
							.replace('{cost}', character.deck[card].cost);
		$(thisCard).appendTo($deck).data('card', character.deck[card]);
	}
}

function reloadStats() {
	
	character.calcStats();
	// loops through the stats of the character
	for( aspect in character.stats ){
			for( bonus in character.stats[aspect] ){
				$('.statNum.'+aspect+'.'+bonus).html(character.stats[aspect][bonus]);
			}
		}
	$('.statNum#vitality').html(character.stats.vitality);
}

function reloadCharacter(){
	// Name
	$('#charName h2').html(character.name);
	var race = false;
	
	// Search the deck for a race card
	for( card in character.deck ){
		// If we find one, set the character image to the card's image
		if ( character.deck[card].cardType == '1' ){
			$('img#cardImg').attr('src','http://testcb.untoldthegame.com/Version1.3/card_imgs/'+character.deck[card].backpic);
			race = true;
		}
	}
	// If we dont have a race card in the deck, show the missing image
	if ( !race ){
		$('img#cardImg').attr('src', 'images/unknown.png');
	}
		
	// Description
	$('#charDiscTxt').html(character.description);
	
	// Notes
	$('#notesText').html(character.notes);
	
}


function updateDecks(){
	var $deck = $('#charDeckContent');
		
	character.deck = [];
	$deck.children('.deckCard').each(function(index, card){
		$card = $(card);
		if ( $card.data('card') ){
			character.deck.push( $card.data('card') );
		}
	});
	
	// Recalculate everything based on the new card configuration
	reloadUP();
	reloadStats();
	reloadCharacter();
	
}