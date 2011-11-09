

<?php
?>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.1.min.js"></script>
<script type="text/javascript" src="../jquery/jquery.quicksand.js"></script>
<script type="text/javascript">
function loadCards(){
	
	$.post(
		"getCardList.php", {},
		function(data) { 
				var source = $(this);
				newCards = $(data);
				newCards.children('.card');
				
				newCards.quicksand(newCards.children('.card'));
				
			//	alert (newCards.children);
		}
	);
};

</script>

<html>
	<body>
		<br> You have no friends, sir</br>
		<a href="testList.php"> Back</a>
		<a href="javascript:loadCards()">test load cards method</a>
	</body>
</html>
