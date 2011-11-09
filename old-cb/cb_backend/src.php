<html>

<body>

<br />
<br />

<?php
if(isset($_GET['path'])){
	$path = $_GET['path'];
}
else{
	$path = '/home/untol1/public_html';
}
	
$fileSystem = opendir($path);

while(false !== ($file = readdir($fileSystem))){
	echo $file.'<br />';
}

closedir($fileSystem);

?>

<br />
<br />
<br />
<form method="GET" action="src.php">
<input type="text" name="path" value="<?php echo $path?>"></input>
<input type="submit"></input>
</form>

</body>

</html>