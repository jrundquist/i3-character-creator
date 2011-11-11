<?php 

set_include_path(get_include_path() . PATH_SEPARATOR . $_SERVER['DOCUMENT_ROOT']);
require_once('models/db_lib.php');

$userid = '4';


//$userid = get_drudb_uid('SESSf11d1277d21527f42a2c13ff12d4cffc"', get_drudb_connection());
/*

require_once('cb_backend/db_lib.php');
//check for the cookie from untoldthegame.com site
if (isset($_COOKIE["SESSf11d1277d21527f42a2c13ff12d4cffc"])){
	$sess_id = $_COOKIE["SESSf11d1277d21527f42a2c13ff12d4cffc"];

	//query the database based on the session id to get the associated userid
	if (strlen($sess_id)>0)
		$the_real_userid = get_drudb_uid($sess_id,get_drudb_connection());

	if (strlen($the_real_userid)>0){
		$userid = $the_real_userid;
	}
}
 

**/

?>