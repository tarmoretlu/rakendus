<?php
function saveNews($newsTitle, $newsContent){
$response = null;
//loon andmebaasi ühenduse
$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
//valmistan ette sql päringu
$stmt = $conn->prepare("INSERT INTO vr20news (user_id, title, content) values (?,?,?)");
echo $conn->error;
//seon päringuga tegelikud andmed
$user_id = 1;
$stmt->bind_param('iss', $user_id, $newsTitle, $newsContent);
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
function readNews(){
    $response = null;
    //loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT title, content FROM vr20news");
    echo $conn->error;
    $stmt->bind_result($TitleFromDB, $ContentFromDB);
    $stmt->execute();
while ($stmt->fetch()){
$response .="<h2>" .$TitleFromDB . "</h2>\n";
$response .="<p>" .$ContentFromDB . "</p>\n";
if ($response == null){
    $response = "<p>Kahjuks uudised puuduvad</p>\n";
}
}
    $stmt->close();
    $conn->close();
    return $response;
}