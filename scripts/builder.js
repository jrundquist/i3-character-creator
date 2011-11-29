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
	openDialog('ajax/cards.php?id='+cardId, 'wide');
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
	$.ajax({url:'/ajax/new.php',
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

// Delete character call
//  -- fade out the selected character, and then make call to server
function deleteChar(){
	$this = $('.loadable-character.chosen');

	if ( $this.length == 0 ){ // If the user hasn't chosen anything return
		return false;
	}
	
	$this.fadeOut();	// Hide the selected character
	
	characterShort = $this.data('character');
	if ( characterShort ){
		// If we are deleting the currently loaded character, then unset the 
		//  client side ID becuase this is technically now a new character
		if (characterShort.charid == character.id){
			character.id = undefined;
		}
		
		// Make the call to the delete script to delete the character
		$.ajax({	url:'/ajax/delete.php',
		 			type: "POST",
					cache: false,	// Dont cache this call
					data: {id : characterShort.charid},
					dataType: "json",
					success: function(charaterLoaded){ 
						//console.log('Character Deleted');
					}
			});
	}
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
						$added = $('<div id="card-'+card.card.id+'" class="loadable-card '+cardColor[card.card.cardType]+'">'+card.name + '<div class="addCardCost">' +card.card.cost+'</div></div>').appendTo($list).data('card', card.card);
					}
				}
			});
}

// This is the function that is called when a card set needs to be loaded
function doAddCard(){
	var where = $('#add-cards-to-deck').attr('where'),
		$list = $('.loadable-card.chosen');
	for ( i=0; i< $list.length; i++ ){
		card = $($list[i]).data('card');
		if ( $('.deckCard[card="'+card.id+'"]').length == 0 ){	// Uniqueness check
			if(where == 'char' && card.cardType == 1){
				//Checks for multiple race cards on add
				reject = false;
				for(i in character.deck){
					if(character.deck[i].cardType == 1){
						reject = true;
						break;
					}
				}
				if (!reject){
					character.deck.push(card);
				}
			}		
			else{
				//Normal Case
				if ( where == 'char' ){
					character.deck.push(card);
				}else{
					character.swapDeck.push(card);
				}
			}
		}
		
	}
	
	reloadDecks();
	reloadStats();
	reloadUP();
	reloadCharacter();
	
	closeDialog();
}

// This is the dialog for loading characters
function loadChar(){
	openDialog('load');
	
	$.ajax({	url:'/ajax/load.php',
				dataType:'json',
				cache: false,
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

// This is the function that is called when a character has been 
//  selected to be loaded
function doLoad(){
	$this = $('.loadable-character.chosen');
	if ( $this.length == 0 ){
		return false;
	}
	characterShort = $this.data('character');
	if ( characterShort ){
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
	var $deck = $('#charDeckContent'),
		$swapDeck = $('#swapDeckContent'),
		template = $('script[type="text/template"]#card').html();
	
	// Clear the decks
	$deck.empty();
	$swapDeck.empty();
	
	// Populate character deck
	for(card in character.deck){
		// console.log(character.deck[card]);
		thisCard = template .replace('{color}', cardColor[character.deck[card].cardType])
							.replace('{id}', character.deck[card].id)
							.replace('{name}', character.deck[card].name)
							.replace('{cost}', character.deck[card].cost)
							.replace('{swap}', character.deck[card].swapType);
		$(thisCard).appendTo($deck).data('card', character.deck[card]);
	}
	
	// Populate swap deck
	for(card in character.swapDeck){
		thisCard = template .replace('{color}', cardColor[character.swapDeck[card].cardType])
							.replace('{id}', character.swapDeck[card].id)
							.replace('{name}', character.swapDeck[card].name)
							.replace('{cost}', character.swapDeck[card].cost)
							.replace('{swap}', character.swapDeck[card].swapType);
		$(thisCard).appendTo($swapDeck).data('card', character.swapDeck[card]);
	}
}

function reloadStats() {
	// Recalulate stats based on decks
	character.calcStats();
	// loops through the stats of the character
	for( aspect in character.stats ){
			for( bonus in character.stats[aspect] ){
				// Update the display
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
			$('img#cardImg').attr('src','http://testcb.untoldthegame.com/card_imgs/'+character.deck[card].backpic)
							.attr('card', character.deck[card].id)
							.addClass('card');
			race = true;
		}
	}
	// If we dont have a race card in the deck, show the missing image
	if ( !race ){
		$('img#cardImg').attr('src', 'images/unknown.png').removeClass('card');
	}
		
	// Description
	$('#charDiscTxt').html(character.description);
	
	// Notes
	$('#notesText').html(character.notes);
	
}

// Update the 
function updateDecks(){
	var $deck = $('#charDeckContent'),
		$swapDeck = $('#swapDeckContent'),
		$trashDeck = $('#trash-overlay');
	
	// Delete the cards from trash so that they are not seen as existing in the 
	//  decks when the "add card" script is called. 
	$trashDeck.children('.deckCard').each(function(index, card){$(card).remove();});
	
	// Build the stored deck based on the DOM eleemnts in the decks
	character.deck = [];
	$deck.children('.deckCard').each(function(index, card){
		$card = $(card);
		if ( $card.data('card') ){
			character.deck.push( $card.data('card') );
		}
	});
	
	// Build the swap deck based on the DOM eleemnts in the decks
	character.swapDeck = [];
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