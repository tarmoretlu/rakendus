<?php
function saveNews($newsTitle, $newsContent){
$response = null;
//loon andmebaasi ühenduse
$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
$conn->set_charset('utf8');
//valmistan ette sql päringu
$stmt = $conn->prepare("INSERT INTO vr20news (user_id, title, content) values (?,?,?)");
echo $conn->error;
//seon päringuga tegelikud andmed
$user_id = $_SESSION["userid"];
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
    //$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    //$stmt = $conn->prepare("SELECT title, content, cteated FROM vr20news  WHERE deleted IS NULL ORDER BY id DESC");


    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $conn->set_charset('utf8');
    $stmt = $conn->prepare("SELECT 
    
   
    vr20news.title, 
    vr20news.content, 
    vr20news.cteated,
    vr20_users.firstname,
    vr20_users.lastname 
    FROM vr20news  
    
    LEFT JOIN vr20_users ON vr20_users.id = vr20news.user_id
    WHERE vr20news.deleted IS NULL
    ORDER BY vr20news.id DESC");


    echo $conn->error;
    $stmt->bind_result($TitleFromDB, $ContentFromDB, $CreatedFromDB, $firstFromDB, $lastFromDB);
    $stmt->execute();
while ($stmt->fetch()){
$response .="<h2>" .$TitleFromDB." ".$CreatedFromDB. " ".$firstFromDB. "  ".$lastFromDB. "</h2>\n";
$response .="<p>" .$ContentFromDB . "</p>\n";
if ($response == null){
    $response = "<p>Kahjuks uudised puuduvad</p>\n";
}
}
    $stmt->close();
    $conn->close();
    return $response;
}

function readLatestNews(){
    $response = null;
    //loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $conn->set_charset('utf8');
    $stmt = $conn->prepare("SELECT 
   vr20news.title, 
    vr20news.content, 
    vr20news.cteated,
    vr20_users.firstname,
    vr20_users.lastname 
    FROM vr20news  
    
    LEFT JOIN vr20_users ON vr20_users.id = vr20news.user_id
    WHERE vr20news.deleted IS NULL
    ORDER BY vr20news.id DESC
    LIMIT 1");


    echo $conn->error;
    $stmt->bind_result($TitleFromDB, $ContentFromDB, $CreatedFromDB, $firstFromDB, $lastFromDB);
    $stmt->execute();
while ($stmt->fetch()){
$response .="<h2>" .$TitleFromDB." ".$CreatedFromDB. " ".$firstFromDB. "  ".$lastFromDB. "</h2>\n";
$response .="<p>" .$ContentFromDB . "</p>\n";
if ($response == null){
    $response = "<p>Kahjuks uudised puuduvad</p>\n";
}
}
    $stmt->close();
    $conn->close();
    return $response;
}