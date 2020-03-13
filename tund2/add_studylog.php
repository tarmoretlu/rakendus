<?php
require ("../../../../configuration.php");
require ("study_fnc.php");
$course = null;
$activity = null;
$time = null;
$logError = null;
function clean_input($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
  }
if (isset($_POST["logBtn"])) {
	if (isset($_POST["course"]) and !empty(clean_input($_POST["course"]))) {
		$course = $_POST["course"];
	} else {
		$logError = "<div style='color:red;'>Kursus on määramata. </div>";
	}
	if (isset($_POST["activity"]) and !empty(clean_input($_POST["activity"]))) {
		$activity = $_POST["activity"];
	} else {
		$logError .= "<div style='color:red;'>Tegevus on määrmata. </div>";
	}
	if (isset($_POST["time"]) and !empty(clean_input($_POST["time"]))) {
		$time = $_POST["time"];
	} else {
		$logError .= "<div style='color:red;'>Tegevuse aeg on määrmata. </div>";
	}

	if (empty($logError)){
		$response = saveLog($course, $activity, $time);
			if ($response == 1) {
				$logError = "<p style='color:green;'>Logi salvestatud andmebaasi:</p>";
			} else {
				$logError = "<p style='color:red;'>Salvestame ebaõnnestus</p>";
			}
	}
}
$logHTML = showLatestLog();
$logSelectCourse = selectCourse();
$logSelectActivity = selectActivity();
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<!--<link rel="stylesheet" href="style.css" />-->
	<title>Õppimise logi</title>
</head>
<body>
	<h1>Õppimise logi sissekanne</h1>
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
<label>Kursus</label><br>
<select name="course">
<option value="">---Vali kursus---</option>
<?php echo $logSelectCourse; ?><br>
</select><br>

<label>Tegevus</label><br>
<select name="activity">
<option value="">---Vali tegevus---</option>
<?php echo $logSelectActivity; ?><br>
</select><br>
<label>Aeg</label><br>
<input type="number" min=".25" max="24" step=".25" name="time" placeholder="---Vali aeg---" value="<?php echo $time?>"><br>
<br>
<input type="submit" name="logBtn" value="Salvesta logi"><br>
<span><?php echo $logError?></span>
</form>

<div><?php echo $logHTML; ?></div>
<div><a href="study_log.php">Kokkuvõte</div>
</body>
</html>