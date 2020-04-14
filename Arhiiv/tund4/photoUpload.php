<?php

	require("classes/Session.class.php");
	SessionManager::sessionStart("vr20news", 0, "/~tarmo.reinvali/", "tigu.hk.tlu.ee");
	
	//kas pole sisseloginud
	if(!isset($_SESSION["userid"])){
		//jõuga avalehele
		header("Location: page.php");
	}
	
	//login välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	}
	
	require("../../../../configuration.php");
	require("fnc_news.php");

	//
	var_dump($_POST);
	var_dump($_FILES);

	if (isset($_POST["photoSubmit"])) {
		$originalTarget = "../../uploadOriginalPhoto/".$_FILES["fileToUpload"]["name"];
		move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $originalTarget)

	}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1>Fotode üleslaadimine</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<hr>


    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label>Vali pildifail: </label><br>
		<input type="file" name="fileToUpload" ><br>
		
		
		<br>
		<input type="submit" name="photoSubmit" value="Lae pilt üles!">
		<span><?php  ?></span>
	</form>


	<br>
	<hr>
</body>
</html>