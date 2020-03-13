<?php


function saveLog($course, $activity, $time){
$response = null;
//loon andmebaasi ühenduse
$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
$conn->set_charset('utf8');
//valmistan ette sql päringu
$stmt = $conn->prepare("INSERT INTO vr20_studylog (course, activity, time) values (?,?,?)");
echo $conn->error;
//seon päringuga tegelikud andmed
$user_id = 1;
$stmt->bind_param('iid', $course, $activity, $time);
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
function showLog(){
    $response = null;
    //loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $conn->set_charset('utf8');
    $stmt = $conn->prepare("SELECT 
    vr20_studylog_activity.course_activity,
    SUM(vr20_studylog.time),
    vr20_studylog_course.course_name 
    FROM vr20_studylog 
    LEFT JOIN vr20_studylog_course ON vr20_studylog.course=vr20_studylog_course.id 
    LEFT JOIN vr20_studylog_activity ON vr20_studylog.activity=vr20_studylog_activity.id 
    GROUP BY vr20_studylog_course.course_name, vr20_studylog_activity.course_activity
    ORDER BY vr20_studylog_course.course_name");
    echo $conn->error;
    $stmt->bind_result($activityNameFromDB, $timeFromDB, $CourseNameFromDB);
    $stmt->execute();
    $response .="<table border=1><tr><td>Õppeaine</td><td>Tegevus</td><td>Aeg(h)</td></tr>\n";
    while ($stmt->fetch()){
        $response .="<tr><td>" .$CourseNameFromDB." </td><td> ".$activityNameFromDB. "</td><td>" .$timeFromDB. "</td></tr>\n";
        if ($response == null){
            $response = "<p>Kahjuks logi puudub</p>\n";
            }
    }
    $response .="</table>\n";
    $stmt->close();
    $conn->close();
    return $response;
}

function showLatestLog(){
    $response = null;
    //loon andmebaasi ühenduse
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $conn->set_charset('utf8');
    $stmt = $conn->prepare("SELECT 
    vr20_studylog_activity.course_activity,
    vr20_studylog.time, 
    vr20_studylog.day, 
    vr20_studylog_course.course_name 
    FROM vr20_studylog 
    LEFT JOIN vr20_studylog_course ON vr20_studylog.course=vr20_studylog_course.id 
    LEFT JOIN vr20_studylog_activity ON vr20_studylog.activity=vr20_studylog_activity.id 
    ORDER BY vr20_studylog.id DESC LIMIT 3");
    echo $conn->error;
    $stmt->bind_result($activityNameFromDB , $timeFromDB, $timeStampFromDB, $CourseNameFromDB);
    $stmt->execute();
while ($stmt->fetch()){
$response .="<p>" .$CourseNameFromDB." - ".$activityNameFromDB. " - " .$timeFromDB . " - ".$timeStampFromDB."</p>\n";
if ($response == null){
    $response = "<p>Kahjuks logi puudub</p>\n";
}
}
    $stmt->close();
    $conn->close();
    return $response;
}
function selectCourse(){
    if (isset($_POST["course"])) {
    $coursePost = $_POST["course"];
    } else {
        $coursePost = 0;

    }
    //$course=0;
    $response = null;
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $conn->set_charset('utf8');
    $stmt = $conn->prepare("SELECT id, course_name FROM vr20_studylog_course");
    echo $conn->error;
    $stmt->bind_result($courseIdFromDB, $CourseNameFromDB);
    $stmt->execute();
    $selectionAttribute='';
    while ($stmt->fetch()){
        if ($coursePost==$courseIdFromDB) {  
            $response .= "<option selected value=" . $courseIdFromDB. ">" . $CourseNameFromDB . "</option>\n";  
        } else {
            $response .= "<option value=" . $courseIdFromDB. ">" . $CourseNameFromDB . "</option>\n";  

        } 
    }
    
    $stmt->close();
    $conn->close();
    return $response;
    
}
function selectActivity(){
    if (isset($_POST["activity"])) {
        $actPost = $_POST["activity"];
        } else {
            $actPost = 0; 
        }
    $response = null;
   
    $conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $conn->set_charset('utf8');
    $stmt = $conn->prepare("SELECT id, course_activity FROM vr20_studylog_activity");
    echo $conn->error;
    $stmt->bind_result($activityIdFromDB, $CourseActivityFromDB);
    $stmt->execute();
    while ($stmt->fetch()){
        if ($actPost==$activityIdFromDB) { 
        $response .= "<option selected value=" . $activityIdFromDB. ">" . $CourseActivityFromDB . "</option>\n";
        } else {
        $response .= "<option value=" . $activityIdFromDB. ">" . $CourseActivityFromDB . "</option>\n";
        }
    }
    $stmt->close();
    $conn->close();
    return $response;
}