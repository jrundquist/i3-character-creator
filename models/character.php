<?php
require_once('card.php');
require_once('db_lib.php');

class Character{
	
	private $charID,
	 		$charName,
			$currentUP,
			$totalUP,
			$lastModified,
			$cards,
			$notes,
			//derived stats
	 		$body, 
			$mind, 
			$soul, 
			$vit;

	public function __construct($userid, $tempName, $tempUP){
		$this->setCharID(NULL);
		$this->setUserID($userid);
		$this->setCharName($tempName);
		$this->currentUP = $tempUP;
		$this->totalUP = $tempUP;
		$this->lastModified = date('Y-m-d'); 
		$this->cards = array();
		$this->setCharDesc("");
		$this->setNotes("");

		//initialize derived stats
		$this->body = array("atk" => 0, "def" => 0, "bst" => 0);
		$this->mind = array("atk" => 0, "def" => 0, "bst" => 0);
		$this->soul = array("atk" => 0, "def" => 0, "bst" => 0);
		$this->vit = 0;
		$this->numcardtypes = array(
			"race" => 0, 
			"aspect_body" => 0, 
			"aspect_mind" => 0, 
			"aspect_soul" => 0
		);
	}

	//setter for the user ID
	public function setUserID($x) {
		$this->userID = $x;
	}

	//getter for the user ID
	public function getUserID() {
		return $this->userID;
	}

	//setter for the character ID
	public function setCharID($x){
	    $this->charID = $x;   
	}

	//getter for the character ID
	public function getCharID(){
	  return $this->charID; 
	}

	//setter for the character name
	public function setCharName($x){
	   $this->charName = htmlentities($x);
	}

	//getter for the character name
	public function getCharName(){
	    return $this->charName; 
	}

	//setter for the character's current UP 
	public function setCurrentUP($x){
	     $this->currentUP = $x; 
	}

	//getter for the character's current UP 
	public function getCurrentUP(){
	       return $this->currentUP;
	}

	//setter for the character's total UP 
	public function setTotalUP($x){
		$usedUP = $this->totalUP - $this->currentUP;
		if($usedUP <= $x) {
			$this->totalUP = $x;
			$this->currentUP = $x - $usedUP;
		}
	}

	//getter for the character's total UP 
	public function getTotalUP(){
	     return $this->totalUP; 
	}

	//setter for character's description
	public function setCharDesc($x) {
		$this->chardesc = htmlentities($x);
	}

	//getter for character's description
	public function getCharDesc() {
		return $this->chardesc;
	}

	//setter for notes
	public function setNotes($x) {
		$this->notes = htmlentities($x);
	}

	//getter for notes
	public function getNotes() {
		return $this->notes;
	}

	//getter for the date the character was last modified
	public function getLastModified(){
		return $this->lastModified; 
	}

	//getter for the full cards array
	public function getAllCards() {
		return $this->cards;
	}

	//getter for the body aspect array
	public function getBody(){
	 return $this->body;
	}
	//getter for the soul aspect array
	public function getSoul(){
	 return $this->soul;
	}
	//getter for the mind aspect array
	public function getMind(){
	 return $this->mind;
	}

	//getter for the vitality information
	public function getVit(){
		return $this->vit;
	}

	//adds the card to the group of cards 
	public function addCard($x){
		//Check card type to ensure that only one race card and
		//no more than 3 aspect cards exist per character. The
		//3 aspect cards must also be different from each other,
		//allowing one of each type: body, soul, mind.

		//We know that each aspect card adds to body OR mind OR soul, but
		//no more than one of these types at a time. So we can test the
		//body/mind/soul by testing just one of the values in each
		//array. This is a "cheat" and if we wish to make the test complete,
		//we will test atk, def, and bst, not just atk.
		$cardconflict = FALSE;
		$newcardtype = $x->getTypeAsStr();
		if($newcardtype == "race" && $this->numcardtypes["race"] == 1) {
			$cardconflict = TRUE;
		}
		elseif($newcardtype == "aspect") {
			if($x->body["atk"] > 0 && $this->numcardtypes["aspect_body"] == 1) {
				$cardconflict = TRUE;
			}
			elseif($x->mind["atk"] > 0 && $this->numcardtypes["aspect_mind"] == 1) {
				$cardconflict = TRUE;
			}
			elseif($x->soul["atk"] > 0 && $this->numcardtypes["aspect_soul"] == 1) {
				$cardconflict = TRUE;
			}
		}
	
		if($cardconflict) {
			echo "You're already using a(n) '$newcardtype' card, can't have more than one.";
			return;
		}
	
		$this->cards[]=$x;
		$this->lastModified = date('Y-m-d');
		$this->currentUP = $this->currentUP - $x->cost;
	
		//update derived stats
		$attrindexes = array("atk", "def", "bst");
		$cardbody = $x->getStat("body");
		$cardmind = $x->getStat("mind");
		$cardsoul = $x->getStat("soul");
		foreach($attrindexes as $id => $val) {
			$this->body[$val] += intval($cardbody[$val]);
			$this->mind[$val] += intval($cardmind[$val]);
			$this->soul[$val] += intval($cardsoul[$val]);
		}
	
		$this->vit += $x->getStat("vitality");

		if($newcardtype == "race") {
			$this->numcardtypes["race"] = 1;
		}
		elseif($newcardtype == "aspect") {
			if($x->body["atk"] > 0) {
				$this->numcardtypes["aspect_body"] = 1;
			}
			elseif($x->mind["atk"] > 0) {
				$this->numcardtypes["aspect_mind"] = 1;
			}
			elseif($x->soul["atk"] > 0) {
				$this->numcardtypes["aspect_soul"] = 1;
			}
		}
	}

	//removes the card with the specified index in the parameter from cards.
	public function removeCard($x){
		foreach($this->cards as $key => $card) {
			if(strcmp($card->getStat("id"), $x->getStat("id")) == 0) {
				//Resetting appropriate fields in numcardtypes.
				if($card->getTypeAsStr() == "race") {
					$this->numcardtypes["race"] = 0;
				}
				elseif($card->getTypeAsStr() == "aspect") {
					if($card->body["atk"] > 0) {
						$this->numcardtypes["aspect_body"] = 0;
					}
					elseif($card->mind["atk"] > 0) {
						$this->numcardtypes["aspect_mind"] = 0;
					}
					elseif($card->soul["atk"] > 0) {
						$this->numcardtypes["aspect_soul"] = 0;
					}
				}
				//The other requirements for removing a card happen to all cards,
				//not just those of type race or aspect.
				unset($this->cards[$key]);
				$this->lastModified = date('Y-m-d');
	
				//update untold points
				$this->currentUP += $x->cost;
		
				//update derived stats
				$attrindexes = array("atk", "def", "bst");
				$cardbody = $x->getStat("body");
				$cardmind = $x->getStat("mind");
				$cardsoul = $x->getStat("soul");
				foreach($attrindexes as $id => $val) {
					$this->body[$val] -= $cardbody[$val];
					$this->mind[$val] -= $cardmind[$val];
					$this->soul[$val] -= $cardsoul[$val];
				}
	
				//update vitality
				$this->vit -= $x->getStat("vitality");
				break;
			}
		}
	}

	//Saves the character's representation to the DB.
	public function saveCharacter() {
		//TODO: Call the save_cbdb_character() function.
		return save_cbdb_character($this, $this->getUserID(), get_cbdb_connection());
/*		if($res->getCharID() != NULL && $res->getCharID() != "") {
			$this->setCharID($res->getCharID());
		}*/
	}

	//Deletes the character's representation from the DB.
	//When this function is called, should likely null out
	//the object as well to discourage further interaction with
	//the character.
	public function l() {
		delete_cbdb_character($this->getCharID(), $this->getUserID(), get_cbdb_connection());
		unset($this);
	}

	//Constructs the character object from its character id.
	public function getCharacter($charid, $userid) {
		$newcharrep = get_cbdb_character($charid, $userid, get_cbdb_connection());

		//Overwrite this character's data with what the DB returned.
		$this->__construct($userid, $newcharrep["charinfo"]["charname"], $newcharrep["charinfo"]["totalup"]);
		$this->setCharID($charid);
		$this->setCharDesc($newcharrep["charinfo"]["chardesc"]);
		$this->setNotes($newcharrep["charinfo"]["notes"]);
	
		$this->lastModified = $newcharrep["charinfo"]["lastmodified"];

		foreach($newcharrep["cards"] as $index => $cardid) {
			$tempcard = new Card($cardid);
			$this->addCard($tempcard);
		}
	}

	//Constructs the character object from its character id.
	public static function factoryChar($charid, $userid) {
		$newcharrep = get_cbdb_character($charid, $userid, get_cbdb_connection());

		//Overwrite this character's data with what the DB returned.
		$char = new Character($userid, $newcharrep["charinfo"]["charname"], $newcharrep["charinfo"]["totalup"]);
		$char->setCharID($charid);
		$char->setCharDesc($newcharrep["charinfo"]["chardesc"]);
		$char->setNotes($newcharrep["charinfo"]["notes"]);
		$char->lastModified = $newcharrep["charinfo"]["lastmodified"];

		foreach($newcharrep["cards"] as $index => $cardid) {
			$tempcard = new Card($cardid);
			$char->addCard($tempcard);
		}
		return $char;
	}


	public function toJSON(){
		$result = '{ "name": "'.$this->charName.'", "id": '.$this->charID.', "description": "'.$this->chardesc.'", "notes":"'.$this->notes.'", "totalUP": "'.$this->totalUP.'", "swapBuffer":"'.$this->getCurrentUP().'", ';
		$result .= '"deck": '.json_encode($this->cards).', "swapDeck": [], ';
		$result .= '"stats": { "mind": '.json_encode($this->mind).', "body": '.json_encode($this->body).', "soul": '.json_encode($this->soul).', "vitality": '.json_encode($this->vit).'} }';
	
		$result = str_replace(array('atk','def','bst'), array('attack', 'defense', 'boost'), $result);
		// Todo-- 
	
		return $result;
	
	}

	//Prints info about the character.
	public function printCharInfo() {
		print $this->strRepresentation();
	}

	//Creates the string to be printed by printCharInfo() and for comparisons
	//in unit tests.
	public function strRepresentation() {
		$retstr = "";
		$retstr .= "Character: '".$this->getCharName()."' - id=".$this->getCharID()."<br />";
		$retstr .= "Belongs to user id ".$this->getUserID()."<br />";
		$retstr .= "has num cards: ".sizeof($this->cards)."<br />Card info is<ul>";
		foreach($this->cards as $card) {
			$retstr .= "<li>";
			$retstr .= $card->strRepresentation();
			$retstr .= "</li>";
		}
		$retstr .= "</ul>";
		$retstr .= "Resulting in total up being reduced from ".$this->getTotalUP()." to ".$this->getCurrentUP()."<br />";
		$retstr .= "Body -> ";
		$retstr .= print_r($this->body, TRUE);
		$retstr .= "<br />Mind -> ";
		$retstr .= print_r($this->mind, TRUE);
		$retstr .= "<br />Soul -> ";
		$retstr .= print_r($this->soul, TRUE);
		$retstr .= "<br />Vitality -> ".$this->vit;
		return $retstr;
	}
}
?>