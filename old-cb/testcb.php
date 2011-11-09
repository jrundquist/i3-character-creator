<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"

"http://www.w3.org/TR/html4/strict.dtd">



<html>



	<head>

		<title>Untold the Game -- Character Builder</title>

		<meta http-equiv="content-language" content="en-US" />

		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />

		<link rel="stylesheet" type="text/css" href="libs_n_styles/layout.css" />
		<link rel="stylesheet" type="text/css" href="libs_n_styles/popup.css" />
		<link rel="stylesheet" type="text/css" href="libs_n_styles/dropable.css" />

		<script src="libs_n_styles/jquery.min.js"></script>
		<script src="libs_n_styles/jquery-ui.min.js"></script>
		<script src="libs_n_styles/dropable_gen.js.php"></script>
		<script src="libs_n_styles/popup.js"></script>
		<script>


		$(document).ready(function() {
			$("#divhelp").click(function() { 
					var windowWidth = document.documentElement.clientWidth;
					var windowHeight = document.documentElement.clientHeight;
					$("#overlay").css({ "top": "0", "left": windowWidth/2 - 500});
					$("#overlaydiv").fadeIn("slow");
				})
				
				$("#overlay").click(function() { 
					$("#overlaydiv").fadeOut("slow");
				})
				
				
		});


function showCardsWithType(id, type){
	$(id).children().each(function() {
		//console.log(this);
		if (type.indexOf("all")>=0){
			var currentStyle = this.getAttribute("style");
			if (currentStyle.indexOf("display")>=0){
				currentStyle = currentStyle.substring(0,currentStyle.indexOf("display"));
			}
			currentStyle = currentStyle + "display:;";
			this.setAttribute("style", currentStyle);
		}
		else if (this.getAttribute("cardtype").indexOf(type)<0){
			var currentStyle = this.getAttribute("style");
			if (currentStyle.indexOf("display")>=0){
				currentStyle = currentStyle.substring(0,currentStyle.indexOf("display"));
			}
			currentStyle = currentStyle + "display:none;";
			this.setAttribute("style", currentStyle);
		}
		else {
			var currentStyle = this.getAttribute("style");
			if (currentStyle.indexOf("display")>=0){
				currentStyle = currentStyle.substring(0,currentStyle.indexOf("display"));
			}
			currentStyle = currentStyle + "display:;";
			this.setAttribute("style", currentStyle);
		}



	});
}



		function clearCanvas(){
			$("#canvas").html("");
		}	

		</script>

	</head>
	<body id="customBody">	
		<div id="layoutCtn">

		<div id="leftPadding"></div> <!-- left padding -->

		<div id="content"> <!-- top level container -->		
<?php			
require_once('cb_backend/db_lib.php');			
	$display_login = true;	
//checks for cookie from untoldthegame.com		
if (isset($_COOKIE["SESSf11d1277d21527f42a2c13ff12d4cffc"])){
	//its set so store the session id into sess_id				
	$sess_id = $_COOKIE["SESSf11d1277d21527f42a2c13ff12d4cffc"];				
	if (strlen($sess_id)>0)
		//query the database and return the userid matching the session id					
		$the_real_userid = get_drudb_uid($sess_id,get_drudb_connection());					
		if (strlen($the_real_userid)>0){					
			$userid = $the_real_userid;					
			$display_login = false;				
		}			
	}			
	if ($display_login){				
		echo'<div id="login" align="right"> <a href="http://www.testsite.untoldthegame.com/user"> <u>Log in</u> </a> </div>';	

		echo '<script>alert("Please login to use the CharacterBuilder.")</script>';
		//echo '<script>window.location="http://www.testsite.untoldthegame.com/user"</script>';
echo '<script>window.location="http://www.testcb.untoldthegame.com/testsite/user"</script>';

		
	}				

	else {				
		//echo'<div align="right">Welcome ' . $userid . '</div>';				
		//echo'<div id="logout" align="right"> <a href="http://www.testsite.untoldthegame.com/logout"> <u>Log out</u> </a> </div>';			
echo'<div id="logout" align="right"> <a href="http://www.testcb.untoldthegame.com/testsite/logout"> <u>Log out</u> </a> </div>';			

	}			
?>
			
			<div id="subCtnLeft"> <!-- left side subcontainer, contains title, info, manipulation area -->

			<!--	<div id="title"><img class="constrained" src="components/title.png" /></div>  title -->
				<span id="untold logo"><img class="constrained" src="components/untoldLogo.png" style="width: 260px; height: 100px; margin-left: 42px;" /></span> <!-- title -->
				<span id="character builder title"><img class="constrained" src="components/characterBuilderTitle.png" style="width: 490px; height: 95px; margin-left: 15px;" /></span> <!-- title -->

					<div id="subCtnElements"> <!-- element subcontainer, contains info and manipulation area -->

						<div id="subCtnInfo"> <!-- information panel -->

							<div id="race">
								<img class="constrained" src="components/race.png" />
							</div>

							<div id="nameDesc">
								<a href="#"><img src="components/buttons/edit-button.png" id="edit-charname" width="18" height="18" title="Edit" style="float:right;" /></a>
								<strong style="line-height: 150%;">Character Name</strong>: <span id="charname">will be replaced</span><br />
								<a href="#"><img src="components/buttons/edit-button.png" id="edit-chardesc" width="18" height="18" title="Edit" style="float:right;" /></a>
								<strong>Character Description</strong>: <span id="chardesc">will be replaced</span><br />
							</div>

							<div id="stats">
								<table class="statTable"> <!-- lower table -->
								<tr>
									<td>&nbsp;</td>
									<td><img class="constrained" src="components/tables/bottom table/ATK-button.png"></td>
									<td><img class="constrained" src="components/tables/bottom table/DEF-button.png"></td>
									<td><img class="constrained" src="components/tables/bottom table/BOOST-button.png"></td>
								</tr>
								<tr>
									<td><img class="constrained" src="components/tables/bottom table/BODY-button.png"></td>
									<td><span id="bodyatk">0</span></td>
									<td><span id="bodydef">0</span></td>
									<td><span id="bodybst">0</span></td>
								</tr>
								<tr>
									<td><img class="constrained" src="components/tables/bottom table/MIND-button.png"></td>
									<td><span id="mindatk">0</span></td>
									<td><span id="minddef">0</span></td>
									<td><span id="mindbst">0</span></td>
								</tr>
								<tr>
									<td><img class="constrained" src="components/tables/bottom table/SOUL-button.png"></td>
									<td><span id="soulatk">0</span></td>
									<td><span id="souldef">0</span></td>
									<td><span id="soulbst">0</span></td>
								</tr>
								<tr> <!-- vitality row -->
									<td><img class="constrained" src="components/tables/bottom table/VIT-button.png" /></td>
									<td><span id="vitality">0</span></td>
									<td style="border-width: 0px;">&nbsp;</td>
									<td style="border-width: 0px;">&nbsp;</td>
								</tr>
								</table>
							</div>
							<div id="btncontainer">
								<ul class="buttonlistrow">
								<li style="margin-right: 6px;"><div id="edit-buffercards"><a href="#"><img src="components/buttons/AddCardButton.png" width="140" height="35" /></a></div></li>

								<li style="margin-right: -4px;"><div id="return-to-charsel"><span id="load-new-char"><a href="#"><img src="components/buttons/CharacterSelector.png" width="140" height="35" /></a></span></div></li>
								</ul>
								<ul class="buttonlistrow">
								<li style="margin-right: 6px;"><div id="divhelp"><a href="#" id="hrefhelp"><img src="components/buttons/HelpButton.png" width="140" height="35" /></a></div></li>
				
								<li style="margin-right: -4px;"><div id="save-character"><a href='#'><img src="components/buttons/SaveButton.png" width="140" height="35" /></a></div></li>
								</ul>
								
							</div>

						</div>


						
						<div id="mArea"><img src="components/buttons/clearButton.png" style="float:right;margin-right:30px;margin-top:4px;width:130px;cursor:pointer;" 
									onclick="clearCanvas()" ></img>


							<!--<img class="constrained" src="components/mArea.png" />-->
							<div id="canvas" class="droppable"></div>
							
						</div> <!-- manipulation area -->

					</div>
			</div>

			<div id="subCtnRight"> <!-- right side subcontainer, contains equipped and buffer cards -->

				<div id="upoints">
					<table class="upTable"> <!-- UP table -->
						<tr>
							<td style="width: 120px;"><img style="width: 100px; height: 25px;" src="components/tables/top table/Total-UP-button.png" /></td>
							<td style="width: 80px;"><span id="totalup">0</span><a href="#"><img src="components/buttons/edit-button.png" id="edit-totalup" width="18" height="18" title="Edit" style="float:right;" /></a></td>
						</tr>
						<tr>
							<td style="width: 120px;"><img style="width: 100px; height: 25px;" src="components/tables/top table/Swap-Buffer-button.png" /></td>
							<td style="width: 80px;"><span id="swapbuffer">0</span></td>
						</tr>
					</table>
				</div>

				<div id="divequipped">
					<!--<img class="constrained" src="components/equipped.png" />-->
					<center><img style="margin-bottom:3px;" src="components/equipped.png"/></center>

				<div id="tabs1">
					<span id="tabscontent1" onclick=showCardsWithType('#sortable','all')>All</span>
					<span id="tabscontent2" class="marginLeftNegative" onclick=showCardsWithType('#sortable','race')>Race</span>
					<span id="tabscontent3" class="marginLeftNegative" onclick=showCardsWithType('#sortable','aspect')>Aspect</span>
					<span id="tabscontent4" class="marginLeftNegative" onclick=showCardsWithType('#sortable','power')>Power</span>
				</div>

					<div class="connectedSortable">
						<ul id="sortable" class="connectedSortable">
						    
						</ul>
					</div>
				</div> <!-- equipped cards -->

				<div id="divbuffer" style="margin-top:10px;">
					<center><img style="margin-bottom:3px;" src="components/buffer.png"/></center>


					<div id="tabs1">
						<span id="tabscontent1" onclick=showCardsWithType('#buffer','all')>All</span>
						<span id="tabscontent2" class="marginLeftNegative" onclick=showCardsWithType('#buffer','race')>Race</span>
						<span id="tabscontent3" class="marginLeftNegative" onclick=showCardsWithType('#buffer','aspect')>Aspect</span>
						<span id="tabscontent4" class="marginLeftNegative" onclick=showCardsWithType('#buffer','power')>Power</span>
					</div>
					<div class="connectedSortable">
						<ul id="buffer" class="connectedSortable">
						    
						</ul>
					</div>
					<!--<img class="constrained" src="components/buffer.png" />-->
				</div> <!-- buffer cards -->

			</div>



			
		</div>

		<div id="topCtnRight"></div> <!-- right side padding -->

		</div>
	

		<div id="user-dash"> <!-- dashboard -->
		<span id="about-info-link"><a href="#">About the Character Builder</a></span>&nbsp;&#124;&nbsp;<span id="user-account-link"><a href="#">User Profile</a></span>
		</div>

		
		<!-- Popup HTML -->
		<center><div id="foregroundPopup"><a id="popupClose" href="#"><img src="components/buttons/XButton.png" width="25px" height="25px" /></a>
			<h1 id="headerPopup">Title of popup</h1>
			<p id="paragraphPopup">More text</p>

	<script>
function showCardsWithType(id, type){
	$(id).children().each(function() {
		//console.log(this);
		if (type.indexOf("all")>=0){
			var currentStyle = this.getAttribute("style");
			if (currentStyle.indexOf("display")>=0){
				currentStyle = currentStyle.substring(0,currentStyle.indexOf("display"));
			}
			currentStyle = currentStyle + "display:;";
			this.setAttribute("style", currentStyle);
		}
		else if (this.getAttribute("cardtype").indexOf(type)<0){
			var currentStyle = this.getAttribute("style");
			if (currentStyle.indexOf("display")>=0){
				currentStyle = currentStyle.substring(0,currentStyle.indexOf("display"));
			}
			currentStyle = currentStyle + "display:none;";
			this.setAttribute("style", currentStyle);
		}
		else {
			var currentStyle = this.getAttribute("style");
			if (currentStyle.indexOf("display")>=0){
				currentStyle = currentStyle.substring(0,currentStyle.indexOf("display"));
			}
			currentStyle = currentStyle + "display:;";
			this.setAttribute("style", currentStyle);
		}



	});
}
	</script>

		</div></center>
		<div id="backgroundPopup"></div>
		<!-- Overlay image -->
		<div id="overlaydiv">
		<img id="overlay" src="components/helpOverlay.png" />
		</div>

	</body>
</html>
