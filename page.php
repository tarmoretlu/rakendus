<?php
$myname = "Tarmo Reinväli";
$fullTimeNow = date("d.m.y H:i:s");
$timeHTML = "<p>Lehe avamise hetkel oli kell <strong> ".$fullTimeNow."</strong></strong></p>";
$hourNow = date("H");


if ($hourNow >= 5 and $hourNow < 10) {
	$partOfDay = "hommik";
	echo '<body style="background-color:yellow">';
}
elseif  ($hourNow >= 10 and $hourNow < 11) {
	$partOfDay = "ennelõuna";
	echo '<body style="background-color:orange">';
}
elseif  ($hourNow >= 11 and $hourNow < 13) {
	$partOfDay = "lõuna";
	echo '<body style="background-color:red">';
}
elseif  ($hourNow >= 13 and $hourNow < 15) {
	$partOfDay = "pärastlõuna";
	echo '<body style="background-color:green">';
}
elseif  ($hourNow >= 15 and $hourNow < 17) {
	$partOfDay = "õhtupoolne aeg";
	echo '<body style="background-color:blue">';
}
elseif  ($hourNow >= 17 and $hourNow < 21) {
	$partOfDay = "õhtu";
	echo '<body style="background-color:brown">';
}
elseif  ($hourNow >= 21 and $hourNow < 23) {
	$partOfDay = "hilisõhtu";
	echo '<body style="background-color:grey">';	
}
else  {
	$partOfDay = "öine aeg";
	echo '<body style="background-color:black">';
	echo '<html style="color:white;">';
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
//Üherealine versioon parandatud ja töötab, punktid olid puudu
//$semesterProgressHTML1 ='<p> Semester on hoos: <meter value="'.$fromSemesterStart->format("%r%a").'" min="0" max="'.$semesterDuration->format("%r%a").'"></meter></p>'."\n";
if ($today > $semesterStart and $today < $semesterEnd) {
$semesterProgressHTML = '<p>Semester on hoos: <meter class="meter-gauge" min="0" max="';
$semesterProgressHTML .= $semesterDuration->format("%r%a");
$semesterProgressHTML .= '" value="';
$semesterProgressHTML .= $fromSemesterStart->format("%r%a");
$semesterProgressHTML .= '"></meter></p>' ."\n";
}
elseif ($today < $semesterStart){
	$semesterProgressHTML = '<p>Semester pole veel alanud.</p>';
}
elseif ($today > $semesterEnd){
	$semesterProgressHTML = '<p>Semester on juba läbi.</p>';
}
//Loen etteantud kataloogist pildifailid, kontrollin failitüüpi ja lisan listi
$picsDir = "../pics/";
$photoTypesAllowed = ["image/jpeg","image/png"];
$photoList = [];
$allFiles = array_slice(scandir($picsDir), 2);
//var_dump ($allFiles);
foreach ($allFiles as $file) {
	$fileInfo = getimagesize($picsDir .$file);
		if (in_array($fileInfo["mime"], $photoTypesAllowed)){
			array_push($photoList, $file);
		}
}
//Tekitame randomi abil kolmese listi photolisti indexitest, mis ei kordu
$photoCount = count($photoList);
$counter = 1;
$photoNumsSelected = [];
while($counter <= 3) {
	$photoNum = mt_rand(0, $photoCount - 1);
	if (!in_array($photoNum, $photoNumsSelected)) {
		array_push($photoNumsSelected, $photoNum);
		$counter++;
		}
   }
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="style.css" />
	<title>Veebirakendused ja nende loomine 2020</title>
</head>
<body>
	<h1><?php echo $myname; ?></h1>
	<p>See leht on valminud õppetöö raames!</p>
	<?php echo $timeHTML; ?>
	Praegu on <?php echo $partOfDay; ?>.
	<?php echo $semesterProgressHTML; ?>
	<?php
//Kuvame photolistist kolme indexiga listi abil pildid
foreach ($photoNumsSelected as $value) {
echo '<img src="'.$picsDir .$photoList[$value].'" width="300" alt="Juhuslik pilt">  ';
}
?>
</body>
</html>