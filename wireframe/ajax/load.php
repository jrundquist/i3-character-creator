<?php
$json = '{"129":"[Nameless Character]","127":"booboohead","126":"asdf","109":"[Nameless Character]","112":"CoolCharacter","113":"Captain Awesome","120":"ksdjfnksd","125":"asdf","128":"1","130":"[Nameless Character]"}';
echo $json;


die();
session_start();
//Sets the $userid variable.
require_once('get_userid.php');

require_once('cb_backend/db_lib.php');
if(isset($_GET['type']) || isset($_POST['type'])) {
	$command = isset($_GET['type'])?$_GET['type']:$_POST['type'];
	switch($command) {
		case 'get_usercharslist':
			$arr = get_cbdb_users_characters($userid, get_cbdb_connection());
			$toret = array();
			foreach($arr as $index => $char) {
				$toret[$char["charid"]] = (strlen(trim($char["charname"])) > 0)?$char["charname"]:"[Nameless Character]";
			}
			echo json_encode($toret);
			break;

		case 'get_racecard':
			if (isset($_GET['charid'])){
				$charId = $_GET['charid'];
				$retVal = get_race_card_id($charId, get_cbdb_connection());
				echo $retVal;
			}
			break;
		default:
			break;
	}
}
?>