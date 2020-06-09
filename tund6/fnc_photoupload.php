<?php

function addPhotoData($fileName,$photoDate, $alt, $privacy, $origName){
    $notice = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $conn->set_charset('utf8');
    $stmt = $conn->prepare("INSERT INTO vr20_photos (userid, filename, photoDate, alttext, privacy, origname) VALUES (?, ?, ?, ?, ?, ?)");
    echo $conn->error;
    $stmt->bind_param("isssis", $_SESSION["userid"], $fileName, $photoDate, $alt, $privacy, $origName);
    if($stmt->execute()){
      $notice = 1;
    } else {
      $notice = $stmt->error;
    }
    $stmt->close();
    $conn->close();
    return $notice;
}
