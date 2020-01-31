<?php
$myname = "Tarmo Reinväli";
$fullTimeNow = date("d.m.y H:i:s");
$timeHTML = "<p>Lehe avamise hetkel oli kell <strong> ".$fullTimeNow."</strong></strong></p>";
$hourNow = date("H");
$partOfDay = "hägune aeg";

if ($hourNow < 10) {

	$partOfDay = "hommik";
}
if ($hourNow >= 10 and $hourNow < 18) {

	$partOfDay = "aeg on tegutseda";
}
//info semestri kulgemise kohta
$semesterStart = new DateTime("2020-1-27");
$semesterEnd = new DateTime("2020-6-22");
$semesterDuration = $semesterStart->diff($semesterEnd);
$today = new DateTime("now");
$fromSemesterStart = $semesterStart->diff($today);
//var_dump ($semesterDuration);
//var_dump ($fromSemesterStart);
// Semester on hoos ja progressiriba.
//semesterProgressHtml ='<p> Semester on hoos: <meter value="'$fromSemesterStart->format("%r%a")'" min="0" max="'$semesterDuration->format("%r%a")'"></meter></p>';

//Loen etteantud kataloogist pildifailid ja kuvan ühe suvalise
$picsDir = "../pics/";
$photoTypesAllowed = ["image/jpeg","image/png"];
$photoList = [];
$allFiles = array_slice (scandir($picsDir), 2);
//var_dump ($allFiles);

foreach ($allFiles as $file) {
	$fileInfo = getimagesize($picsDir .$file);
		if (in_array($fileInfo["mime"], $photoTypesAllowed)){
			array_push($photoList, $file);
}
}
$photoCount = count($photoList);
$photoNum = mt_rand(0, $photoCount - 1);
$randomImageHtml = '<img src="'.$picsDir .$photoList[$photoNum] .'" alt="Juhuslik pilt">';


?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1><?php echo $myname; ?></h1>
	<p>See leht on valminud õppetöö raames!</p>
	<?php echo $timeHTML; ?>
	Praegu on <?php echo $partOfDay; ?>.
	<p> Semester on hoos: <meter value="15" min="0" max="100"></meter></p>;
	<!--<?php echo $semesterProgressHtml; ?>-->
	<?php echo $randomImageHtml; ?>
	
</body>
</html>