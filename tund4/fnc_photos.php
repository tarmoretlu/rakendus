<?php

function test_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
  }

function createThumbnail($myNewImage,$filename,$newW,$newH){
    $thumbnailDir = "../../UploadThumbnail/";
    $thumbWH = 100;
    if($newW < $newH) {
        $width_t=$thumbWH;
        //respect the ratio
        $height_t=round($newH/$newW*$thumbWH);
        //set the offset
        $off_y=ceil(($width_t-$height_t)/2);
        $off_x=0;
    } elseif($newH < $newW) {
        $height_t=$thumbWH;
        $width_t=round($newW/$newH*$thumbWH);
        $off_x=ceil(($height_t-$width_t)/2);
        $off_y=0;
    } else {
        $width_t=$height_t=$thumbWH;
        $off_x=$off_y=0;
    }
    $myNewThumb = imagecreatetruecolor($thumbWH, $thumbWH);
	$bg = imagecolorallocate ( $myNewThumb, 255, 255, 255 );
	imagefill ( $myNewThumb, 0, 0, $bg );
	imagecopyresampled($myNewThumb, $myNewImage, $off_x, $off_y, 0, 0, $width_t, $height_t, $newW, $newH);
	imagepng($myNewThumb, $thumbnailDir .$filename, 6);
    imagedestroy($myNewThumb);
}
function photosToDatabase($fileName, $altText, $privacy){
    $response = null;
    //loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $conn->set_charset('utf8');
    //valmistan ette sql päringu
    $stmt = $conn->prepare("INSERT INTO vr20_photos (userid, filename, alttext, privacy) values (?,?,?,?)");
    echo $conn->error;
    //seon päringuga tegelikud andmed
    $user_id = $_SESSION["userid"];
    $stmt->bind_param('issi', $user_id, $fileName, $altText, $privacy);
    //i-integer; s-string
    if ($stmt->execute()) {
    $response = 1;
    
    } else {
        $response = 0;
        echo $stmt->error;  
    }
    //sulgen andmebaasi ühenduse
    $stmt->close();
    $conn->close();
    return $response;
}

function showPhotos(){
    $normalPhotoDir = "../../UploadNormalPhoto/";
    $response = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $conn->set_charset('utf8');
    $stmt = $conn->prepare("SELECT 
    vr20_photos.alttext, 
    vr20_photos.filename, 
    vr20_users.firstname,
    vr20_users.lastname 

    FROM vr20_photos  
    
    LEFT JOIN vr20_users ON vr20_users.id = vr20_photos.userid
    WHERE vr20_photos.deleted IS NULL
    ORDER BY vr20_photos.id DESC");


    echo $conn->error;
    $stmt->bind_result($AltFromDB, $filenameFromDB, $firstFromDB, $lastFromDB);
    $stmt->execute();
	while ($stmt->fetch()){
		$response .="<h2>" .$AltFromDB." ".$filenameFromDB. " ".$firstFromDB. "  ".$lastFromDB. "</h2>\n";
		$response .="<p><img src=".$normalPhotoDir.$filenameFromDB."></p>\n";
		if ($response == null){
    		$response = "<p>Kahjuks pildid puuduvad</p>\n";
		}
	}
    $stmt->close();
    $conn->close();
    return $response;
}
function showThumbs(){
	$normalPhotoDir = "../../UploadNormalPhoto/";
	$thumbnailDir = "../../UploadThumbnail/";
    $response = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $conn->set_charset('utf8');
    $stmt = $conn->prepare("SELECT 
    vr20_photos.alttext, 
    vr20_photos.filename, 
    vr20_users.firstname,
    vr20_users.lastname 

    FROM vr20_photos  
    
    LEFT JOIN vr20_users ON vr20_users.id = vr20_photos.userid
    WHERE vr20_photos.deleted IS NULL
    ORDER BY vr20_photos.id DESC");


    echo $conn->error;
    $stmt->bind_result($AltFromDB, $filenameFromDB, $firstFromDB, $lastFromDB);
    $stmt->execute();
	while ($stmt->fetch()){
		$response .="<a href=".$normalPhotoDir.$filenameFromDB ." target='_blank'><img src=" .$thumbnailDir.$filenameFromDB . "></a>\n";
		if ($response == null){
    		$response = "<p>Kahjuks pildid puuduvad</p>\n";
		}
	}
    $stmt->close();
    $conn->close();
    return $response;
}
