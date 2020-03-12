<?php
require ("../../../../configuration.php");
require ("study_fnc.php");
$logHTML = showLog();
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
<div><a href="add_studylog.php">Sisestus</div>
</body>
</html>