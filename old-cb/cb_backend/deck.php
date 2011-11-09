<?php
require_once('db_lib.php');
require_once('card.php');

/* Data Structure containing a set of cards. The cards are carried in a 
 * Dictionary type structure, with cardID strings as keys to Card object
 * values.
 */
class Deck {
	//The userID of the owner of the Deck.	
	var $sUserID;
	//The actual array (Dictionary) of Card objects.
	var $arr;
	
	//Initializes the array, and sets the userID if given.
	public function __construct($sUserID = FALSE) {
		if($sUserID) {
			$this->sUserID = $sUserID;
		}
		else {
			$this->sUserID = '';
		}
		$this->arr = array();
		
		$tempdeck = get_cbdb_deck($this->sUserID, get_cbdb_connection());

		if(!$tempdeck) {
			//Couldn't retrieve the user's deck.
		}
		else {
			foreach($tempdeck as $tempindex => $tempcardid) {
				$tempcard = new Card($tempcardid);
				$this->addCard($tempcard, $tempcardid);
			}
		}
	}
	
	//Sets the userID.
	public function setUserID($sUserID) {
		$this->sUserID = $sUserID;
	}

	//Returns the userID of the owner of the Deck.	
	public function getUserID() {
		return $this->sUserID;
	}
	
	//Adds a Card object to the array using a cardID string as the key.
	//If no cardID is given, then the cardID is retreived from the the Card
	//object.
	public function addCard($oCard, $sCardID = FALSE) {
		if($sCardID) {
			$this->arr[$sCardID] = $oCard;
		}
		else {
			$sCardID = $oCard->getStat("id");
			$this->arr[$sCardID] = $oCard;
		}
	}
	
	//Removes and returns the Card matched with the given cardID.
	//If the cardID does not exist in the array, then null is returned.
	function removeCard($sCardID) {
		if(hasCard($sCardID)) {
			$oCard = $this->getCard($sCardID);
			unset($this->arr[$sCardID]);
			return $oCard;
		}
		else {
			return null;
		}
	}
	
	//Checks if the cardID exists within the set of keys of the array.
	public function hasCard($sCardID) {
		return array_key_exists($sCardID, $this->arr);
	}
	
	//Retrieves the Card matched with the given cardID.
	//If the cardID does not exist in the array, then null is returned.
	//Also note this function has the same functionality as removeCard, except it
	//does not remove the Card from the array after retrieving it.
	public function getCard($sCardID) {
		if($this->hasCard($sCardID)) {
			return $this->arr[$sCardID];
		}
		else {
			return null;
		}
	}
	
	//Sorts the Cards by the cardID.
	public function sortDeck() {
		ksort($this->arr);
	}

	//Gets the array found in the deck.
	public function getDeckArr() {
		return $this->arr;
	}
}

?>
