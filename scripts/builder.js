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
Character.prototype.description = "";
Character.prototype.notes = "";
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





/*************************
 * Global Variables
 * 
*************************/
var cardColor = {'1':'red', '2':'yellow', '3':'blue'};
var character = new Character();

// Create the global character object
if ( sessionStorage && sessionStorage.character ){
	try{
		character = cast(JSON.parse(sessionStorage.character), Character);
	}catch(err){
	 // Do nothing
	}
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
	openDialog('saving');
	$.ajax({url:'/ajax/save.php',
			dataType: "json",
			type: "POST",
			data: {'char': JSON.stringify(character) },
			success: function(result){
				if ( result.success ){
					openDialog('saved');
				}else{
					alert(result.info);
					hideOverlay();
				}
			},
			error: function(jqXHR, textStatus, errorThrown){
				hideOverlay();
			}
		});
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
	openDialog('add-cards');
	$('#add-cards-to-deck').attr('where', deck);
	$.ajax({	url:'/ajax/add.php',
				dataType: 'json',
				cache: true,	// We can cache since this does not change often
				success: function(cards){
					$list = $('#deck-cards-list').empty();
					for( i in cards ){
						card = cards[i];
						$added = $('<div id="card-'+card.card.id+'" class="loadable-card '+cardColor[card.card.cardType]+'">'+card.card.cost+' | '+card.name+'</div>').appendTo($list).data('card', card.card);
					}
				}
			});
}

// This is the function that is called when a card set needs to be loaded
function doAddCard(){
	var where = $('#add-cards-to-deck').attr('where'),
		$list = $('.loadable-card.chosen');
	console.log(where);
	for ( i=0; i< $list.length; i++ ){
		card = $($list[i]).data('card');
		console.log('i see', card);
		if ( $('.deckCard[card="'+card.id+'"]').length == 0 ){	// Uniqueness check
			if ( where == 'char' ){
				character.deck.push(card);
				reloadDecks();
				reloadUP();
			}else{
				console.log('adding', card);
				character.swapDeck.push(card);
				reloadDecks();
			}
		}
		
	}
	reloadStats();
	closeDialog();
	// simply add cards to correct character.deck list
	// THen class reloadDeck , UP, and stats (  only stats if where == char )
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
	$('#pointsSB').html(character.swapBuffer).removeClass('negative');
	if ( character.swapBuffer < 0 ){
		$('#pointsSB').addClass('negative');
	}
}

function reloadDecks(){
	// Clear the decks
	var $deck = $('#charDeckContent'),
		$swapDeck = $('#swapDeckContent'),
		template = $('script[type="text/template"]#card').html();
	
	$deck.empty();
	$swapDeck.empty();
	
	for(card in character.deck){
		thisCard = template .replace('{color}', cardColor[character.deck[card].cardType])
							.replace('{id}', character.deck[card].id)
							.replace('{name}', character.deck[card].name)
							.replace('{cost}', character.deck[card].cost);
		$(thisCard).appendTo($deck).data('card', character.deck[card]);
	}
	
	
	for(card in character.swapDeck){
		thisCard = template .replace('{color}', cardColor[character.swapDeck[card].cardType])
							.replace('{id}', character.swapDeck[card].id)
							.replace('{name}', character.swapDeck[card].name)
							.replace('{cost}', character.swapDeck[card].cost);
		$(thisCard).appendTo($swapDeck).data('card', character.swapDeck[card]);
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
	var $deck = $('#charDeckContent'),
		$swapDeck = $('#swapDeckContent'),
		$trashDeck = $('#trash-overlay').empty();
	
	newDeck = [];
	// Build the deck based on 
	$deck.children('.deckCard').each(function(index, card){
		$card = $(card);
		if ( $card.data('card') ){
			newDeck.push( $card.data('card') );
		}
	});
	
	// Check for two race cards
	foundRace = false;
	for ( i in newDeck ){
		if ( newDeck[i].cardType == 1 ){
			if ( foundRace ){
				for( j in character.deck ){
					if ( character.deck[j].cardType == 1 ){
						if ( character.deck[j].id == foundRace ){
							// Move newDeck[i] back to sb
						}else{
							// Move foundRace back to sb 
						}
					}
				}
			}
			foundRace = newDeck[i];
		}
	}
	
	character.deck = newDeck;
	
	character.swapDeck = [];
	// Build the swap deck based on the existing
	$swapDeck.children('.deckCard').each(function(index, card){
		$card = $(card);
		if ( $card.data('card') ){
			character.swapDeck.push( $card.data('card') );
		}
	});
	
	
	// Recalculate everything based on the new card configuration
	reloadUP();
	reloadStats();
	reloadCharacter();
	
}