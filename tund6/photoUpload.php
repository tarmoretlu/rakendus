<?php
	require("classes/Session.class.php");
	SessionManager::sessionStart("vr20", 0, "/~tarmo.reinvali/", "tigu.hk.tlu.ee");
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
	require("fnc_photoupload.php");
	require("classes/Photo.class.php");
	//pildi üleslaadimine osa
	$error = null;
	$notice = null;
	$fileUploadSizeLimit = 10000000;
	$fileNamePrefix = "vr_";

	$originalPhotoDir = "../../UploadOriginalPhoto/";
	$normalPhotoDir = "../../UploadNormalPhoto/";
	$thumbPhotoDir = "../../UploadThumbnail/";

	$maxWidth= 600;
	$maxHeight = 400;
	$thumbSize = 100;
	$showClassError = null;

	if(isset($_POST["photoSubmit"]) and !empty($_FILES["fileToUpload"]["tmp_name"])){
			$photoUp = new Photo($_FILES["fileToUpload"], $fileUploadSizeLimit, $fileNamePrefix);
			//Failitüübi ja suuruse kontroll kutsutakse välja, enne kui minnakse koodiga edasi
			if ($photoUp->imageFileTypeCheck()) {
			$photoUp->resizePhoto($maxWidth, $maxHeight);
			$photoUp->addWatermark("vr_watermark.png", 3, 10);
			$result = $photoUp->saveImgToFile($normalPhotoDir .$photoUp->fileName);
			if($result == 1) {
				$notice .= "Vähendatud pilt laeti üles! ";
			} else {
				$error .= "Vähendatud pildi salvestamisel tekkis viga!";
			}
			$photoUp->resizePhoto($thumbSize, $thumbSize);
			$result = $photoUp->saveImgToFile($thumbPhotoDir .$photoUp->fileName);
				if($result == 1) {
					$notice .= "Pisipilt laeti üles! ";
				} else {
					$error .= " Pisipildi salvestamisel tekkis viga!";
			}
			$originalTarget = $originalPhotoDir .$photoUp->fileName;
			if(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $originalTarget)){
				$notice .= "Originaalpilt laeti üles! ";
			} else {
				$error .= " Pildi üleslaadimisel tekkis viga!";
			}
			if($error == null){
				$result = addPhotoData($photoUp->fileName,$photoUp->photoDate, $_POST["altText"], $_POST["privacy"], $_FILES["fileToUpload"]["name"]);
				if($result == 1){
					$notice .= "Pildi andmed lisati andmebaasi!";
				} else {
					$error .= " Pildi andmete lisamisel andmebaasi tekkis tehniline tõrge: " .$result;
				}
			}
		} else {
			//Salvestame klassist errorsõnumi eraldii muutujasse html-i jaoks
			$showClassError = $photoUp->check_error;
		}
		unset($photoUp);
	}
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Fotode üleslaadimine</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<style type="text/css">
	body {margin:20px;}
	</style>
</head>
<body>
	<h1>Fotode üleslaadimine</h1>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<p>
	<a href="photoUpload.php">Fotode laadimine</a>&nbsp;&nbsp;
	<a href="privategallery.php">Privaatne fotoalbum</a>&nbsp;&nbsp;
	<a href="semipublicgallery.php">Fotoalbum kasutajatele</a>&nbsp;&nbsp;
	<a href="publicgallery.php">Avalik fotoalbum</a>
	<a href="deleteusergallery.php">Kasutaja piltide kustutamine</a>
	</p>
	<hr>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label>Vali pildifail! </label><br>
		<input type="file" name="fileToUpload"><br><br>
		<label>Alt tekst:</label>&nbsp;&nbsp;<input type="text" name="altText" size="20"><br>
		<label>Privaatsus:</label>&nbsp;&nbsp;&nbsp;
		<label for="priv1">Privaatne</label>&nbsp;<input id="privacy" type="radio" name="privacy" value="3" checked>&nbsp;&nbsp;
		<label for="priv2">Sisseloginud kasutajatele</label>&nbsp;<input id="privacy" type="radio" name="privacy" value="2">&nbsp;&nbsp;
		<label for="priv3">Avalik</label>&nbsp;<input id="privacy" type="radio" name="privacy" value="1"><br>
		<input type="submit" name="photoSubmit" value="Lae valitud pilt üles!">
		<span><?php echo $error; echo "<br>" .$notice; ?></span>
		<span><?php echo $showClassError; ?></span>
	</form>
	<br>
	<hr>
</body>
</html>