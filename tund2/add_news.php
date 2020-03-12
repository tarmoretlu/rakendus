<?php
//var_dump($_POST);
//echo $_POST["newsTitle"];
require ("../../../../configuration.php");
require ("fnc.php");
$newsTitle = null;
$newsContent = null;
$newsError = null;
//$response = null;
function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
  }

if (isset($_POST["newsBtn"])) {
	if (isset($_POST["newsTitle"]) and !empty(test_input($_POST["newsTitle"]))) {
		$newsTitle = $_POST["newsTitle"];
	} else {
		$newsError = "Uudise pealkiri on sisestamata. ";

	}
	if (isset($_POST["newsContent"]) and !empty(test_input($_POST["newsContent"]))) {
		$newsContent = $_POST["newsContent"];
	} else {
		$newsError .= "Uudise sisu pole. ";

	}

//echo $newsTitle;
//echo "<br>";
//echo $newsContent;
if (empty($newsError)){

$response = saveNews($newsTitle, $newsContent);
if ($response == 1) {
	$newsError = "Uudis salvestatud andmebaasi:";
} else {
	$newsError = "Salvestame ebaõnnestus";
}
}
}
$newsHTML = readLatestNews();
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<!--<link rel="stylesheet" href="style.css" />-->
	<title>Uudise lisamine</title>
</head>
<body>
	<h1>Uudise lisamine</h1>
	<p>See leht on valminud õppetöö raames!</p>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<labe>Uudise pealkiri</label><br>
<input type="text" name="newsTitle" placeholder="Uudise pealkiri" value="<?php echo $newsTitle?>"><br>
<labe>Uudise sisu</label><br>
<textarea name="newsContent" placeholder="Uudis" rows="5" cols="40">
<?php echo $newsContent?>
</textarea><br>
<br>
<input type="submit" name="newsBtn" value="Salvesta uudis"><br>
<span><?php echo $newsError?></span>
</form>

<div><?php echo $newsHTML; ?></div>
</body>
</html>