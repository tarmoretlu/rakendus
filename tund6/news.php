<?php
//var_dump($_POST);
//echo $_POST["newsTitle"];
require ("../../../../configuration.php");
require ("fnc_news.php");
$newsHTML = readNews();




require("classes/Session.class.php");
SessionManager::sessionStart("vr20", 0, "/~tarmo.reinvali/", "tigu.hk.tlu.ee");

//kui logimist ei ole olnud
if(!isset($_SESSION["userid"])){
	//suunab avalehele
	header("Location: page.php");
}

//login välja
if(isset($_GET["logout"])){
	session_destroy();
	header("Location: page.php");
}




?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<!--<link rel="stylesheet" href="style.css" />-->
	<title>Uudised</title>
</head>
<body>
	<h1>Uudised</h1>
	
	<p>See leht on valminud õppetöö raames!</p>
	<p><a href="add_news.php">Lisa uudis</a></p>
	<p><a href="home.php">Esilehele</a></p>
	<p><a href="?logout=1">Logi välja!</a></p>
<div><?php echo $newsHTML; ?></div>

<?php

?>
</body>
</html>