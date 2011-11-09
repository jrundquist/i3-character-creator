<?php
	/*readfile($_GET["file"]);
	exit;*/
	
	//symlink("../testsite/install.php", "cb_backend/install.php");
	echo readLink("cb_backend/install.php");
?>