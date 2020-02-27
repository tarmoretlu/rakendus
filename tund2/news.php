<?php
//var_dump($_POST);
//echo $_POST["newsTitle"];
require ("../../../../configuration.php");
require ("fnc.php");
$newsHTML = readNews();


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
<div><?php echo $newsHTML; ?></div>
<?php

?>
</body>
</html>