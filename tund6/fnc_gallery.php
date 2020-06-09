<?php

$GLOBALS["originalPhotoDir"] = "../../UploadOriginalPhoto/";
$GLOBALS["normalPhotoDir"] = "../../UploadNormalPhoto/";
$GLOBALS["thumbPhotoDir"] = "../../UploadThumbnail/";

function readprivategalleryImages($fnc_privacy, $fnc_page, $fnc_limit){
	//readgalleryImages(2, $page, $limit);
	$privacy = $fnc_privacy;
	$page = $fnc_page;
	$limit = $fnc_limit;
	//$picCount = countPics($privacy);
	$skip = ($page-1)*$limit;
	$finalHTML = "";
	$html = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$conn->set_charset('utf8');
	$stmt = $conn->prepare("
	SELECT
	vr20_photos.id,
	vr20_photos.filename,
	vr20_users.firstname,
	vr20_users.lastname,
	vr20_photos.alttext,
	AVG(vr20_photoratings.rating) as AvgValue
	FROM vr20_photos
	JOIN vr20_users ON vr20_photos.userid = vr20_users.id
	LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id
	WHERE vr20_photos.privacy <= ? AND vr20_photos.deleted IS NULL AND vr20_photos.userid=?
	GROUP BY vr20_photos.id DESC LIMIT ?, ?");
	echo $conn->error;
	$user = $_SESSION["userid"];
	$stmt->bind_param("iiii", $privacy, $user, $skip, $limit);
	$stmt->bind_result($idFromDB, $filenameFromDb, $firstnameFromDb, $lastnameFromDB, $altFromDB, $ratingFromDB);
	$stmt->execute();
	while($stmt->fetch()){
		$html .= '<div class="galleryelement">'."\n";
		$html .= '<img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" class="thumb" data-fn="'.$filenameFromDb.'" data-id="' .$idFromDB. '">'."\n \t \t";
		$html .="<br>\"". $altFromDB ."\"\n \t \t";
		$html .="<br>". $firstnameFromDb." ".$lastnameFromDB."\n \t \t";
		if ($ratingFromDB!=0){
		$html .="<br>Hinne:". round($ratingFromDB, 2). "\n";
		} else {
			$html .="<br>Hindeid pole\n";
		}
		$html .= "</div>\n";
	}
	if($html != ""){
		$finalHTML = $html;
	} else {
		$finalHTML = "<p>Kahjuks pilte pole!</p>";
	}

	$stmt->close();
	$conn->close();
	return $finalHTML;
}

function readsemigalleryImages($fnc_privacy, $fnc_page, $fnc_limit){
	$privacy = $fnc_privacy;
	$page = $fnc_page;
	$limit = $fnc_limit;
	$skip = ($page-1)*$limit;
	$finalHTML = "";
	$html = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$conn->set_charset('utf8');
	$stmt = $conn->prepare("
	SELECT
	vr20_photos.id,
	vr20_photos.filename,
	vr20_users.firstname,
	vr20_users.lastname,
	vr20_photos.alttext,
	AVG(vr20_photoratings.rating) as AvgValue
	FROM vr20_photos
	JOIN vr20_users ON vr20_photos.userid = vr20_users.id
	LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id
	WHERE vr20_photos.privacy <= ? AND deleted IS NULL
	GROUP BY vr20_photos.id DESC LIMIT ?, ?");
	echo $conn->error;
	$stmt->bind_param("iii", $privacy, $skip, $limit);
	$stmt->bind_result($idFromDB, $filenameFromDb, $firstnameFromDb, $lastnameFromDB, $altFromDB, $ratingFromDB);
	$stmt->execute();
	while($stmt->fetch()){
		$html .= '<div class="galleryelement">'."\n";
		$html .= '<img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" class="thumb" data-fn="'.$filenameFromDb.'" data-id="' .$idFromDB. '">'."\n \t \t";
		$html .="<br>\"". $altFromDB ."\"\n \t \t";
		$html .="<br>". $firstnameFromDb." ".$lastnameFromDB."\n \t \t";
		if ($ratingFromDB!=0){
		$html .="<br>Hinne:". round($ratingFromDB, 2). "\n";
		} else {
			$html .="<br>Hindeid pole\n";
		}
		$html .= "</div>\n";
	}
	if($html != ""){
		$finalHTML = $html;
	} else {
		$finalHTML = "<p>Kahjuks pilte pole!</p>";
	}

	$stmt->close();
	$conn->close();
	return $finalHTML;
}

function readpublicgalleryImages($fnc_privacy, $fnc_page, $fnc_limit){
	$privacy = $fnc_privacy;
	$page = $fnc_page;
	$limit = $fnc_limit;
	$skip = ($page-1)*$limit;
	$finalHTML = "";
	$html = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$conn->set_charset('utf8');
	$stmt = $conn->prepare("
	SELECT
	vr20_photos.id,
	vr20_photos.filename,
	vr20_users.firstname,
	vr20_users.lastname,
	vr20_photos.alttext,
	AVG(vr20_photoratings.rating) as AvgValue
	FROM vr20_photos
	JOIN vr20_users ON vr20_photos.userid = vr20_users.id
	LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id
	WHERE vr20_photos.privacy <= ? AND deleted IS NULL
	GROUP BY vr20_photos.id DESC LIMIT ?, ?");
	echo $conn->error;
	$stmt->bind_param("iii", $privacy, $skip, $limit);
	$stmt->bind_result($idFromDB, $filenameFromDb, $firstnameFromDb, $lastnameFromDB, $altFromDB, $ratingFromDB);
	$stmt->execute();
	while($stmt->fetch()){
		$html .= '<div class="galleryelement">'."\n";
		$html .= '<img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" class="thumb" data-fn="'.$filenameFromDb.'" data-id="' .$idFromDB. '">'."\n \t \t";
		$html .="<br>\"". $altFromDB ."\"\n \t \t";
		$html .="<br>". $firstnameFromDb." ".$lastnameFromDB."\n \t \t";
		if ($ratingFromDB!=0){
		$html .="<br>Hinne:". round($ratingFromDB, 2). "\n";
		} else {
			$html .="<br>Hindeid pole\n";
		}
		$html .= "</div>\n";
	}
	if($html != ""){
		$finalHTML = $html;
	} else {
		$finalHTML = "<p>Kahjuks pilte pole!</p>";
	}
	$stmt->close();
	$conn->close();
	return $finalHTML;
}

function countPics($privacy){
	$notice = null;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$conn->set_charset('utf8');
	$stmt = $conn->prepare("SELECT COUNT(id)
	FROM vr20_photos
	WHERE privacy<=? AND deleted IS NULL");
	echo $conn->error;
	$stmt->bind_param("i", $privacy);
	$stmt->bind_result($count);
	$stmt->execute();
	$stmt->fetch();
	$notice = $count;
	$stmt->close();
	$conn->close();
	return $notice;
}

function privatedeletegalleryImages($fnc_privacy, $fnc_page, $fnc_limit){
	//readgalleryImages(2, $page, $limit);
	$privacy = $fnc_privacy;
	$page = $fnc_page;
	$limit = $fnc_limit;
	//$picCount = countPics($privacy);
	$skip = ($page-1)*$limit;
	$finalHTML = "";
	$html = "";
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$conn->set_charset('utf8');
	$stmt = $conn->prepare("
	SELECT
	vr20_photos.id,
	vr20_photos.filename,
	vr20_users.firstname,
	vr20_users.lastname,
	vr20_photos.alttext,
	AVG(vr20_photoratings.rating) as AvgValue
	FROM vr20_photos
	JOIN vr20_users ON vr20_photos.userid = vr20_users.id
	LEFT JOIN vr20_photoratings ON vr20_photoratings.photoid = vr20_photos.id
	WHERE vr20_photos.privacy <= ? AND vr20_photos.deleted IS NULL AND vr20_photos.userid=?
	GROUP BY vr20_photos.id DESC LIMIT ?, ?");
	echo $conn->error;
	$user = $_SESSION["userid"];
	$stmt->bind_param("iiii", $privacy, $user, $skip, $limit);
	$stmt->bind_result($idFromDB, $filenameFromDb, $firstnameFromDb, $lastnameFromDB, $altFromDB, $ratingFromDB);
	$stmt->execute();
	//$html .= '<form method="post" action="">'."\n";
	//$html .= '<button type="submit" class="btn btn-danger" name="kustutaValitud">Kustuta valitud</button>'."\n \t \t";
	while($stmt->fetch()){
		$html .= '<div class="galleryelement">'."\n";
		$html .= '<img src="' .$GLOBALS["thumbPhotoDir"] .$filenameFromDb .'" class="thumb" data-fn="'.$filenameFromDb.'" data-id="' .$idFromDB. '">'."\n \t \t";
		$html .="<br>\"". $altFromDB ."\"\n \t \t";
		$html .="<br>". $firstnameFromDb." ".$lastnameFromDB."\n \t \t";
		if ($ratingFromDB!=0){
		$html .="<br>Hinne:". round($ratingFromDB, 2). "\n";
		} else {
			$html .='<br>Hindeid pole'."\n";
		}

		//delete
		$html .= '<br><a href="deletePicture.php?photoid=' .$idFromDB. '">Kustuta </a>'."\n \t \t";
		$html .= '<br>  Vali <input type="checkbox" id="checkId" name="check[]" value="' .$idFromDB. '">'."\n \t \t";
		$html .= '</div>'."\n";
	}
	//$html .= '<br><p align="center"><button type="submit" class="btn btn-danger" name="kustutaValitud">Kustuta valitud</button></p>'."\n \t \t";
	//$html .= '<form>'."\n";
	if($html != ""){
		$finalHTML = $html;
	} else {
		$finalHTML = "<p>Kahjuks pilte pole!</p>";
	}

	$stmt->close();
	$conn->close();
	return $finalHTML;
}
function deleteId($Id){
	$id = $Id;
	$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("
    UPDATE vr20_photos
    SET deleted=now()
    WHERE id=? AND userid=?");
	$stmt->bind_param("ii", $id, $_SESSION["userid"]);
	$stmt->execute();
    $stmt->close();
    header("Location: " . $_SERVER['HTTP_REFERER']);
}