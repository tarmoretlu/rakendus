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
	require("fnc_photos.php");
	//pildi üleslaadimine osa
	$error = null;
	$notice = null;
	$fileUploadSizeLimit = 10000000;
	$fileNamePrefix = "vr_";
	$originalPhotoDir = "../../UploadOriginalPhoto/";
	$normalPhotoDir = "../../UploadNormalPhoto/";
	$imageFileType = null;
	$filename = null;
	$maxWidth= 600;
    $maxHeight = 400;
	
		
	if(isset($_POST["photoSubmit"])){
		//Altteksti kontroll ja sisestus
			if (isset($_POST["altText"]) and !empty(test_input($_POST["altText"]))) {
			$altText = $_POST["altText"];
		} else {
			$error = "Pildi alttext on sisestamata. ";
		}
		//privaatsuse sisestus, kontrolli ei tee, kuna radio button peaks suht lollikindel olema
		$privacy = $_POST["privacy"];
		//kas üldse on pilt?
		$check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		if($check !== false){
			//failitüübi väljaselgitamine ja sobivuse kontroll
			if($check["mime"] == "image/jpeg"){
				$imageFileType = "jpg";
			} elseif ($check["mime"] == "image/png"){
				$imageFileType = "png";
			} else {
				$error .= "Ainult jpg ja png pildid on lubatud! "; 
				}
		} else {
			$error = "Valitud fail ei ole pilt! ";
			}
			//ega pole liiga suur
			if($_FILES["fileToUpload"]["size"] > $fileUploadSizeLimit){
				$error .= "Valitud fail on liiga suur! ";
			}
			//Loome failile nime
			$timestamp = microtime(1) * 10000;
			$filename = $fileNamePrefix . $timestamp . "." .$imageFileType;
			//kui vigu pole
			if($error == null){
				//teeme pildi väiksemaks
				if($imageFileType == "jpg"){
					$myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
				}
				if($imageFileType == "png"){
					$myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
				}
				$imageW = imagesx($myTempImage);
				$imageH = imagesy($myTempImage);
				if ($imageW / $maxWidth > $imageH / $maxHeight) {
					$imageSizeRatio = $imageW / $maxWidth;
				} else {
					$imageSizeRatio = $imageH / $maxHeight;
				}
				$newW = round($imageW / $imageSizeRatio);
				$newH = round($imageH / $imageSizeRatio);
				//loome uue ajutise objekti
				$myNewImage = imagecreatetruecolor($newW, $newH);
				imagecopyresampled($myNewImage, $myTempImage, 0, 0, 0, 0, $newW, $newH, $imageW, $imageH);
				
				//Salvestame normal suurusena
				if($imageFileType == "jpg") {
					if(imagejpeg($myNewImage, $normalPhotoDir .$filename, 90)){
						$notice = "JPG fail on vähendatud ja kaustas. ";
						$response = photosToDatabase($filename, $altText, $privacy);
						createThumbnail($myNewImage,$filename,$newW,$newH);
						if ($response == 1) {
							$notice .= "Foto info salvestatud ka andmebaasi. ";
						} else {
							$error .= "Salvestame ebaõnnestus";
						}
					} else {
						$error .= "Vähendatud pildi salvestamisel tekkis viga!";
					}
				}
				if($imageFileType == "png") {
					if(imagepng($myNewImage, $normalPhotoDir .$filename, 6)){
						$notice = "PNG fail on vähendatud ja kaustas. ";
						$response = photosToDatabase($filename, $altText, $privacy);
						createThumbnail($myNewImage,$filename,$newW,$newH);
						if ($response == 1) {
							$notice .= "Foto info salvestatud ka andmebaasi. ";
						} else {
							$error .= "Salvestame ebaõnnestus";
						}
					} else {
						$error .= "Vähendatud pildi salvestamisel tekkis viga!";
					}
				}
			imagedestroy($myTempImage);
            imagedestroy($myNewImage);
			}//kui vigu pole  
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
		<label>Vali pildifail! </label><br>
		<input type="file" name="fileToUpload"><br>
		<label>Alt tekst</label><br>
		<input type="text" name="altText"><br>
		<label>Privaatsus</label><br>
		<label for="priv1">Privaatne</label><input id="priv1" type="radio" name="privacy" value="3" checked>
		<label for="priv2">Kasutajatele</label><input id="priv2" type="radio" name="privacy" value="2">
		<label for="priv3">Avalik</label><input id="priv3" type="radio" name="privacy" value="1"><br>


		<input type="submit" name="photoSubmit" value="Lae valitud pilt üles!">
		<span><?php echo $error;  ?></span>
		<span><?php echo $notice;  ?></span>
	</form>
	
	<br>
	<hr>
	<span><?php echo showThumbs();  ?></span>
	<hr>
	<span><?php echo showPhotos();  ?></span>
</body>
</html>