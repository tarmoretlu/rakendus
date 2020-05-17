<?php
require ("../../../../configuration.php");
require ("study_fnc.php");
$logHTML = showLog();

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
	<title>Õppelogi kokkuvõte</title>
</head>
<body>
	<h1>Õppelogi kokkuvõte</h1>
<div><?php echo $logHTML; ?></div>
<div>
<a href="add_studylog.php">Sisestus</a>
<p><a href="home.php">Esilehele</a></p>
<p><a href="?logout=1">Logi välja!</a></p>
</div>
</body>
</html>