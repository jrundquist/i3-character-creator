<?php

require_once('character.php');
require_once('card.php');
require_once('deck.php');

//Gets the connection object to the DB.
function get_cbdb_connection() {
	$params = array(
		"username" => "untol1_testcb",
		"password" => "Ch@racter11",
		"db_name" => "untol1_testcb"
	);
	$link = mysql_connect("testsite.untoldthegame.com", $params["username"], $params["password"]);
	mysql_select_db($params["db_name"], $link);
	return $link;
}

function get_drudb_connection() {
	$params = array(
		"username" => "untol1_testcb",
		"password" => "Ch@racter11",
		"db_name" => "untol1_testdru"
	);
	$link = mysql_connect("testsite.untoldthegame.com", $params["username"], $params["password"]);
	mysql_select_db($params["db_name"], $link);
	return $link;
}

function get_drudb_uid($sid, $dbconn) {
	if(!$dbconn) {
		$dbconn = get_drudb_connection();
	}
	$query = "select uid from drusessions where sid = '$sid'";
	$result = mysql_query($query);
	if(!$result) {
		return False;
	}
	$toret = "";
	while($res = mysql_fetch_assoc($result)) {
		$toret = $res["uid"];
	}
	return $toret;
}

//Gets the deck representation for the passed in userid.
function get_cbdb_deck($userid, $dbconn) {
	if(!$dbconn) {
		$dbconn = get_cbdb_connection();
	}
	//$query = "select cardid from Possession where userid = '$userid'";
	//$query = "select cardid from UserCards where userid = '$userid'";




	$query = "select a.cardid from untol1_testcb.Possession as a";
	$query .= " inner join untol1_testcb.cards_lu as b";
	$query .= " where a.cardid = b.full_id";
	$query .= " and (b.type = '1' or b.type = '2' or b.type = '3')";
	$query .= " and a.userid = '$userid'";



	$result = mysql_query($query);
	if(!$result) {
		return False;
	}
	$toret = array();
	while($res = mysql_fetch_assoc($result)) {
		$toret[] = $res["cardid"];
	}
	return $toret;
}

//Gets all of the card attributes for a given cardid.
function get_cbdb_card($cardid, $dbconn) {
	if(!$dbconn) {
		$dbconn = get_cbdb_connection();
	}
	$query = "select full_id as cardid, front, type, name, swap, cost, body_atk, body_def, body_bst, mind_atk, mind_def, mind_bst, soul_atk, soul_def, soul_bst, vit, level, action, duration, `range`, card_img from cards_lu where full_id = '$cardid'";
	$result = mysql_query($query);
	if(!$result) {
		return False;
	}
	$toret = array();
	while($res = mysql_fetch_assoc($result)) {
		if($res["front"] == "1") {//Then it's the front.
			$toret[1] = $res;
		}
		else {
			$toret[0] = $res;
		}
	}
	return $toret;
}

//Gets a given character using the character id.
function get_cbdb_character($charid, $dbconn) {
	if(!$dbconn) {
		$dbconn = get_cbdb_connection();
	}
	$query = "select charid, charname, totalup, swapbuffer, chardesc, notes, lastmodified from UserCharacter where charid = $charid";
	$result = mysql_query($query);
	if(!$result) {
		return False;
	}
	$char = array("charinfo" => mysql_fetch_assoc($result));
	//Must also get the correlated cards.
	$query = "select cardid from CharacterCards where charid = $charid";

	$result = mysql_query($query);
	if(!$result) {
		return False;
	}
	$char["cards"] = array();
	while($res = mysql_fetch_assoc($result)) {
		$char["cards"][] = $res["cardid"];
	}
	return $char;
}

//Gets the list of characters for a given user.
function get_cbdb_users_characters($userid, $dbconn) {
	if(!$dbconn) {
		$dbconn = get_cbdb_connection();
	}
	$query = "select charid, charname, totalup, swapbuffer, chardesc, lastmodified from UserCharacter where userid = '$userid'";
	$result = mysql_query($query);
	//Just need a list of the user's characters, so no card retrieval needed.
	if(!$result) {
		return False;
	}
	$toret = array();
	while($res = mysql_fetch_assoc($result)) {
		$toret[] = $res;
	}
	return $toret;
}



//returns id of the race card for the given character
function get_race_card_id($charID, $dbconn){
	if(!$dbconn) {
		$dbconn = get_cbdb_connection();
	}
	$query = "SELECT a.cardid FROM `untol1_testcb`.`CharacterCards` as a ";
	$query .= "inner join `untol1_testcb`.`cards_lu` as b ";
	$query .= "on a.cardid = b.full_id ";
	$query .= "where b.front = 1 ";
	$query .= "and b.type = 1 ";
	$query .= "and a.charid = '$charID'";
	$result = mysql_query($query);
	//Just need a list of the user's characters, so no card retrieval needed.
	if(!$result) {
		return False;
	}
	$toret = array();
	while($res = mysql_fetch_assoc($result)) {
		return $res['cardid'];
	}
}

//returns id of the race card for the given character
function get_race_card_id_two($charID, $dbconn){
	if(!$dbconn) {
		$dbconn = get_cbdb_connection();
	}
	$query = "SELECT a.cardid FROM `untol1_testcb`.`CharacterCards` as a ";
	$query .= "inner join `untol1_testcb`.`cards_lu` as b ";
	$query .= "on a.cardid = b.full_id ";
	$query .= "where b.front = 1 ";
	$query .= "and b.type = 1 ";
	$result = mysql_query($query);
	//Just need a list of the user's characters, so no card retrieval needed.
	if(!$result) {
		return False;
	}
	$toret = "";
	while($res = mysql_fetch_assoc($result)) {
		$toret = $toret . " " . $res[cardid];
	}
	return substr($toret,1);
}



//Includes both updates and first time saves. Saves the passed in
//object representation of a character.
function save_cbdb_character($char, $userid, $dbconn) {
	if(!$dbconn) {
		$dbconn = get_cbdb_connection();
	}
	if($char->getCharID() != NULL) {
		//Then the character already has an ID, so we simply update the table.
		$query = "update UserCharacter set charname = '".$char->getCharName()."', lastmodified = NOW(), totalup = ".$char->getTotalUP().", swapbuffer = ".$char->getCurrentUP().", chardesc = '".$char->getCharDesc()."', notes = '".$char->getNotes()."' where charid = ".$char->getCharID();
	}
	else {
		//Then we must do an insert because the character doesn't exist yet.
		$query = "insert into UserCharacter (charname, userid, totalup, swapbuffer, chardesc, notes, lastmodified) values('".$char->getCharName()."', '$userid', ".$char->getTotalUP().", ".$char->getCurrentUP().", '".$char->getCharDesc()."', '".$char->getNotes()."', NOW())";
	}
	$result = mysql_query($query);
	if(!$result) {
		return False;
	}
	if($char->getCharID() == NULL) {
		$postquery = "select last_insert_id()";
		$postresult = mysql_query($postquery);
		$tempcharid = mysql_fetch_assoc($postresult);
		$char->setCharID($tempcharid["last_insert_id()"]);
	}
	//$newchar = array("charinfo" => mysql_fetch_assoc($result));
	//Now must save the cards for the character.
	//Hackish way to save, will want to optimize later with an insert or update type of insert. Or a custom made insert or update query.
	$preinsert = "delete from CharacterCards where charid = ".$char->getCharID();
	mysql_query($preinsert);
	foreach($char->getAllCards() as $card) {
		$query = "insert into CharacterCards (cardid, charid) values('".$card->id."', ".$char->getCharID().")";
		$result = mysql_query($query);
		if(!$result) {
			print "failed to insert all the cards. ";
			return False;
		}
	}
	return $char;
}

//Deletes the representation of a character from the DB using the
//character id.
function delete_cbdb_character($charid, $userid, $dbconn) {
	if(!$dbconn) {
		$dbconn = get_cbdb_connection();
	}
	//Remove from the character table.
	$query = "delete from UserCharacter where charid = $charid and userid = '$userid'";
	$result = mysql_query($query);
	//Remove the character's card representation.
	$query2 = "delete from CharacterCards where charid = $charid";
	$result2 = mysql_query($query2);
}
?>
