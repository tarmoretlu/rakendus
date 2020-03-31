<?php

	require("../../../../configuration.php");
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
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Meie äge koduleht</h1>
	<p>See leht on valminud õppetöö raames!</p>
    <div>
	<p>Tere! <?php echo $_SESSION["userFirstName"] . " " .$_SESSION["userLastName"]; ?> <a href="?logout=1"> >>Logi välja!</a></p>
	<p>See leht on valminud õppetöö raames!</p>
	<p><a href="news.php">Uudised</a></p>
	<p><a href="study_log.php">Õppelogi</a></p>
	</div>
	
</body>
</html>