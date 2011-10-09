<?php
/* Gives the user id for the logged in user and puts that information on the variable $userid. */
//TODO: Call the described functions in the documentation for retreiving the user id.
//	$userid = '247';




	$userid = '';




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



?>
