<?php
	require_once('cb_backend/db_lib.php');

	if (!isset($userid)){
		if (isset($_COOKIE["SESSf11d1277d21527f42a2c13ff12d4cffc"])){
			$sess_id = $_COOKIE["SESSf11d1277d21527f42a2c13ff12d4cffc"];
			if (strlen($sess_id)>0)
				$the_real_userid = get_drudb_uid($sess_id,get_drudb_connection());

			if (strlen($the_real_userid)>0){
				echo $the_real_userid;
				$userid = $the_real_userid;
			}
		}
	}

?>
