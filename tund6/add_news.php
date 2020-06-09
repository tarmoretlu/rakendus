<?php
require ("../../../../configuration.php");
require ("fnc_news.php");
require("classes/Session.class.php");
//require("fnc_photoupload.php");
require("classes/Photo.class.php");
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
$error = null;
$notice = null;
$fileUploadSizeLimit = 10000000;
$fileNamePrefix = "news_";
$newsPhotoDir = "../../UploadNewsPhoto/";
$maxWidth= 600;
$maxHeight = 400;
$thumbSize = 100;
$showClassError = null;
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
	if (!empty($_FILES["fileToUpload"]["tmp_name"])){
		$photoUp = new Photo($_FILES["fileToUpload"], $fileUploadSizeLimit, $fileNamePrefix);
		//Failitüübi ja suuruse kontroll kutsutakse välja, enne kui minnakse koodiga edasi
		if ($photoUp->imageFileTypeCheck()) {
		$photoUp->resizePhoto($maxWidth, $maxHeight);
		$photoUp->addWatermark("vr_watermark.png", 3, 10);
		$result = $photoUp->saveImgToFile($newsPhotoDir .$photoUp->fileName);
		if($result == 1) {
			$notice .= "Vähendatud pilt laeti üles! ";
		} else {
			$error .= "Vähendatud pildi salvestamisel tekkis viga!";
		}
		} else {
			$showClassError = $photoUp->check_error;
		}
		}
	if (empty($newsError)){
		$response = saveNews($newsTitle, $newsContent, $photoUp->fileName);
		if ($response == 1) {
			$newsError = "Uudis salvestatud andmebaasi:";
		} else {
			$newsError = "Salvestame ebaõnnestus";
		}
	}
	unset($photoUp);
}
$newsHTML = readLatestNews();
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Uudise lisamine</title>
</head>
<body>
	<h1>Uudise lisamine</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<p><a href="news.php">Loe uudiseid</a></p>
	<p><a href="home.php">Esilehele</a></p>
	<p><a href="?logout=1">Logi välja!</a></p>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
	<labe>Uudise pealkiri</label><br>
	<input type="text" name="newsTitle" placeholder="Uudise pealkiri" value="<?php echo $newsTitle?>"><br>
	<labe>Uudise sisu</label><br>
	<textarea name="newsContent" placeholder="Uudis" rows="5" cols="40">
	<?php echo $newsContent?>
	</textarea><br>
	<br>
	<label>Vali pildifail! </label><br>
	<input type="file" name="fileToUpload"><br><br>
	<input type="submit" name="newsBtn" value="Salvesta uudis"><br>
	<span><?php echo $newsError?></span>
	<span><?php echo $error; echo "<br>" .$notice; ?></span>
	<span><?php echo $showClassError; ?></span>
</form>
<div>
<?php echo $newsHTML; ?>
</div>
</body>
</html>