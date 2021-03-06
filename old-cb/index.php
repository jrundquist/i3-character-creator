<?php
require_once('cb_backend/db_lib.php');

// Check user login 
if (!isset($userid)){
	if (isset($_COOKIE["SESSf11d1277d21527f42a2c13ff12d4cffc"])){
		$sess_id = $_COOKIE["SESSf11d1277d21527f42a2c13ff12d4cffc"];
		if (strlen($sess_id)>0)
			$the_real_userid = get_drudb_uid($sess_id,get_drudb_connection());
	}
}
?>


<!DOCTYPE html>
<html lang="en">
	<head>
		<title>
			Untold the Game -- Character Builder
		</title>
		<meta charset=utf-8 /> 
		<meta http-equiv="X-UA-Compatible" content="chrome=1">
		<link rel="stylesheet" type="text/css" href="styles/layout.css">
		<link rel="stylesheet" type="text/css" href="styles/popup.css">
		<link rel="stylesheet" type="text/css" href="styles/dropable.css">
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/dropable_gen.js.php"></script>
		<script src="js/popup.js"></script>
		<script>
			$(document).ready(function() {
			    $("#divhelp").hover(
					function() {
				        var windowWidth = document.documentElement.clientWidth;
				        var windowHeight = document.documentElement.clientHeight;
				        $("#overlay").css({
				            "top": "0",
				            "left": windowWidth / 2 - 500
				        });
				        $("#overlaydiv").fadeIn("slow");
				    },
				    function() {
				        $("#overlay").hover(function() {},
				        function() {
				            $("#overlaydiv").fadeOut("slow");
				        });
				    }
				);
			});
		</script>
	</head>
	<body id="customBody">
		<div id="login" align="right" style="display:none">
			<a href="http://www.testsite.untoldthegame.com/user"><u>Log in</u></a>
		</div>
		<div id="logout" align="right" style="display:none">
			<a href="http://www.testsite.untoldthegame.com/logout"><u>Log out</u></a>
		</div>
		
		<script>
		<?php if (strlen($the_real_userid)>0): ?>
			document.getElementById("logout").style.display = "";
		<?php else: ?>
			document.getElementById("login").style.display = "";
		<?php endif;?>
		</script>
		<div id="layoutCtn">
			<div id="leftPadding"></div><!-- left padding -->
			<div id="content">
				<!-- top level container -->
				<div id="subCtnLeft">
					<!-- left side subcontainer, contains title, info, manipulation area -->
					<div id="title">
						<img class="constrained" src="components/title.png">
					</div><!-- title -->
					<div id="subCtnElements">
						<!-- element subcontainer, contains info and manipulation area -->
						<div id="subCtnInfo">
							<!-- information panel -->
							<div id="race">
								<img class="constrained" src="components/race.png">
							</div>
							<div id="nameDesc">
								<a href="#"><img src="components/buttons/edit-button.png" id="edit-charname" width="18" height="18" title="Edit" style="float:right;" name="edit-charname"></a> <strong style="line-height: 150%;">Character Name</strong>: <span id="charname">will be replaced</span><br>
								<a href="#"><img src="components/buttons/edit-button.png" id="edit-chardesc" width="18" height="18" title="Edit" style="float:right;" name="edit-chardesc"></a> <strong>Character Description</strong>: <span id="chardesc">will be replaced</span><br>
							</div>
							<div id="stats">
								<table class="statTable">
									<!-- lower table -->
									<tr>
										<td>
											&nbsp;
										</td>
										<td>
											<img class="constrained" src="components/tables/bottom%20table/ATK-button.png">
										</td>
										<td>
											<img class="constrained" src="components/tables/bottom%20table/DEF-button.png">
										</td>
										<td>
											<img class="constrained" src="components/tables/bottom%20table/BOOST-button.png">
										</td>
									</tr>
									<tr>
										<td>
											<img class="constrained" src="components/tables/bottom%20table/BODY-button.png">
										</td>
										<td>
											<span id="bodyatk">0</span>
										</td>
										<td>
											<span id="bodydef">0</span>
										</td>
										<td>
											<span id="bodybst">0</span>
										</td>
									</tr>
									<tr>
										<td>
											<img class="constrained" src="components/tables/bottom%20table/MIND-button.png">
										</td>
										<td>
											<span id="mindatk">0</span>
										</td>
										<td>
											<span id="minddef">0</span>
										</td>
										<td>
											<span id="mindbst">0</span>
										</td>
									</tr>
									<tr>
										<td>
											<img class="constrained" src="components/tables/bottom%20table/SOUL-button.png">
										</td>
										<td>
											<span id="soulatk">0</span>
										</td>
										<td>
											<span id="souldef">0</span>
										</td>
										<td>
											<span id="soulbst">0</span>
										</td>
									</tr>
									<tr>
										<!-- vitality row -->
										<td>
											<img class="constrained" src="components/tables/bottom%20table/VIT-button.png">
										</td>
										<td>
											<span id="vitality">0</span>
										</td>
										<td style="border-width: 0px;">
											&nbsp;
										</td>
										<td style="border-width: 0px;">
											&nbsp;
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div id="mArea">
							<!--<img class="constrained" src="components/mArea.png" />-->
							<div id="canvas" class="droppable"></div>
							<div id="divhelp">
								<a href="#" id="hrefhelp" name="hrefhelp"><img src="components/buttons/help-button.png" width="32" height="32"></a>
							</div>
						</div><!-- manipulation area -->
					</div>
				</div>
				<div id="subCtnRight">
					<!-- right side subcontainer, contains equipped and buffer cards -->
					<div id="upoints">
						<table class="upTable">
							<!-- UP table -->
							<tr>
								<td style="width: 120px;">
									<img style="width: 100px; height: 25px;" src="components/tables/top%20table/Total-UP-button.png">
								</td>
								<td style="width: 80px;">
									<span id="totalup">0</span><a href="#"><img src="components/buttons/edit-button.png" id="edit-totalup" width="18" height="18" title="Edit" style="float:right;" name="edit-totalup"></a>
								</td>
							</tr>
							<tr>
								<td style="width: 120px;">
									<img style="width: 100px; height: 25px;" src="components/tables/top%20table/Swap-Buffer-button.png">
								</td>
								<td style="width: 80px;">
									<span id="swapbuffer">0</span>
								</td>
							</tr>
						</table>
					</div>
					<div id="divequipped">
						<!--<img class="constrained" src="components/equipped.png" />-->
						<div class="connectedSortable">
							<ul id="sortable" class="connectedSortable"></ul>
						</div>
					</div><!-- equipped cards -->
					<div id="divbuffer">
						<div id="edit-buffercards">
							<a href="#">Add cards from deck</a>
						</div>
						<div class="connectedSortable">
							<ul id="buffer" class="connectedSortable"></ul>
						</div><!--<img class="constrained" src="components/buffer.png" />-->
					</div><!-- buffer cards -->
				</div>
			</div>
			<div id="topCtnRight"></div><!-- right side padding -->
		</div>
		<div id="user-dash">
			<!-- dashboard --><span id="about-info-link"><a href="#">About the Character Builder</a></span>&nbsp;|&nbsp;<span id="load-new-char"><a href="#">Return to Character Selector</a></span>&nbsp;|&nbsp;<span id="user-account-link"><a href="#">User Profile</a></span>
		</div><!-- Popup HTML -->
		<center>
			<div id="foregroundPopup">
				<a id="popupClose" href="#" name="popupClose">X</a>
				<h1 id="headerPopup">
					Title of popup
				</h1>
				<p id="paragraphPopup">
					More text
				</p>
			</div>
		</center>
		<div id="backgroundPopup"></div>
		<!-- Overlay image -->
		<div id="overlaydiv">
			<img id="overlay" src="components/helpOverlay.png" name="overlay">
		</div>
		
		

		
	</body>
</html>
