<?php

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']);


require_once('models/db_lib.php');

class Card {

	//card constructor
	public function __construct($cardID) {
		//sql retrieval statement
			
		$cardRepArr = get_cbdb_card($cardID, get_cbdb_connection());
		if(!$cardRepArr) {
			//Then the id didn't exist in the DB or there was a DB error.
			print "<h2>Failed to get the passed in cardid of $cardID from the DB.</h2>";
			return;
		}
		//Grabs the front.
		$cardRep = $cardRepArr[1];
		
		//card information
		$this->id = $cardRep["cardid"];
		$this->name = $cardRep["name"];
		$this->picture = $cardRep["card_img"];
		$this->backpic = $cardRepArr[0]["card_img"];
		$this->cardType = $cardRep["type"];
		$this->swapType = $cardRep["swap"];
		$this->extraInfo = "DEFAULT VALUE";

		//card statistics
		$this->cost = $cardRep["cost"];
		$this->body = array(
			"atk" => intval($cardRep["body_atk"]),
			"def" => intval($cardRep["body_def"]),
			"bst" => intval($cardRep["body_bst"])
		);
		$this->mind = array(
			"atk" => intval($cardRep["mind_atk"]),
			"def" => intval($cardRep["mind_def"]),
			"bst" => intval($cardRep["mind_bst"])
		);
		$this->soul = array(
			"atk" => intval($cardRep["soul_atk"]),
			"def" => intval($cardRep["soul_def"]),
			"bst" => intval($cardRep["soul_bst"])
		);
		$this->vit = $cardRep["vit"];
		$this->attributes = array(
			"level" => $cardRep["level"],
			"action" => $cardRep["action"],
			"duration" => $cardRep["duration"],
			"range" => $cardRep["range"]
		);

		//non aspect card statistics
		$this->boostInfo = "DEFAULT VALUE";
		$this->magnitude = "DEFAULT VALUE";
		
		//Assign the information in the $cardRep (card representation) to the
		//private variables.
		
		
		//checks to ensure that boostInfo and magnitude are zero for aspect cards
		$this->checkType(); 
	}

	//returns a statistic based off of the string parameter passed in
	public function getStat($parameter) {
		if(strcmp($parameter, "name") == 0) return $this->name;
		elseif(strcmp($parameter, "picture") == 0) return $this->picture;
		elseif(strcmp($parameter, "backpic") == 0) return $this->backpic;
		elseif(strcmp($parameter, "cardtype") == 0) return $this->cardType;
		elseif(strcmp($parameter, "swaptype") == 0) return $this->swapType;
		elseif(strcmp($parameter, "extrainfo") == 0) return $this->extraInfo;
		elseif(strcmp($parameter, "cost") == 0) return $this->cost;
		elseif(strcmp($parameter, "body") == 0) return $this->body;
		elseif(strcmp($parameter, 'mind') == 0) return $this->mind;
		elseif(strcmp($parameter, "soul") == 0) return $this->soul;
		elseif(strcmp($parameter, "vitality") == 0) return $this->vit;
		elseif(strcmp($parameter, "attributes") == 0) return $this->attributes;
		elseif(strcmp($parameter, "boostInfo") == 0) return $this->boostInfo;
		elseif(strcmp($parameter, "magnitude") == 0) return $this->magnitude;
		elseif(strcmp($parameter, "id") == 0) return $this->id;
		elseif(strcmp($parameter, "image") == 0) return $this->picture;
		//print an error message if the parameter is incorrect
		else echo "'$parameter' is an invalid card parameter";
	}
	
	//if cardType equals aspect, set boost info and magnitude to null.
	public function checkType() {
		//Because the DB gave us a string for the card type, we must
		//ensure that they are strings and compare accordingly.
		if (strcmp($this->getTypeAsStr(), "aspect")==0) {
			$this->boostInfo = 0;
			$this->magnitude = 0;
		}
	}

	//Gets us the string representation for a given card. Allows us
	//to quickly determine which type of card it is so that other
	//functions can ignore the numerical value of the card and focus
	//instead of just doing specific rules according to what type it is.
	public function getTypeAsStr() {
		$cardtypes = array(
			"race"   => 1,
			"aspect" => 2,
			"power"  => 3,
			"minion" => 10,
			"bane"   => 20
		);
		foreach($cardtypes as $typestr => $typeval) {
			if($this->cardType == $typeval) {
				return $typestr;
			}
		}
	}

	//Prints general information about the card.
	public function printCardInfo() {
		print $this->strRepresentation();
	}

	//Creates the string representation of the card that is used by printCardInfo.
	public function strRepresentation() {
		$retstr = "";
		$retstr .= "Card ".$this->name." - ".$this->id." is a(n) ".$this->getTypeAsStr()." card with a cost of ".$this->cost.".<br />It boosts the following stats:<br />";
		$retstr .= "Body: ";
		$retstr .= print_r($this->body, true);
		$retstr .= "<br />Mind: ";
		$retstr .= print_r($this->mind, true);
		$retstr .= "<br />Soul: ";
		$retstr .= print_r($this->soul, true);
		$retstr .= "<br />And gives a vitality boost of ".$this->vit;
		return $retstr;
	}
}

function createCardListName($thecard) {
	return "[".$thecard->getStat("cost")."] ".$thecard->getStat("name");
}
?>