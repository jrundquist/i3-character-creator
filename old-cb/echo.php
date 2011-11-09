<?php
	/*readfile($_GET["file"]);
	exit;*/
	
	//symlink("../testsite/install.php", "cb_backend/install.php");

	copy("cb_backend/verify_login.php", "../testsite/verify_login.php");
?>