<?php
	
	//sessiooni käivitamine või kasutamine
	//session_start();
	//var_dump($_SESSION);
	require("classes/Session.class.php");
	SessionManager::sessionStart("vr20", 0, "/~tarmo.reinvali/", "tigu.hk.tlu.ee");
	$sessionHTML = "";
	//kas pole sisseloginud
	if(!isset($_SESSION["userid"])){
		//jõuga avalehele
		//header("Location: page.php");
		$sessionHTML .= '<p>Tere, Külaline!</p>'."\n";
	} else {
		$sessionHTML .= $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"].".\n"; 
		$sessionHTML .= 'Logi <a href=?logout=1>välja</a>!</p>'."\n";

	}
	
	//login välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: publicgallery.php");
	}


	require("../../../../configuration.php");
	require("fnc_gallery.php");
	
	//$privateThumbnails = readAllMyPictureThumbs();
	$privacy_set = 1;
	$page = 1; 
	$limit = 5;
	$picCount = countPics($privacy_set); 
	//$picCount = 20;
	//var_dump($picCount);
	if(!isset($_GET["page"]) or $_GET["page"] < 1){
		$page = 1;
	  } elseif(round($_GET["page"] - 1) * $limit >= $picCount){
		$page = intval(ceil($picCount / $limit));
	  }	else {
		$page = intval($_GET["page"]);
	  }
	  
	  $galleryHTML = readpublicgalleryImages($privacy_set, $page, $limit);

	
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Avalik fotoalbum</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style/gallery.css">
	<link rel="stylesheet" type="text/css" href="style/modal.css">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<script src="javascript/modal.js" defer></script>


</head>
<body>

<div id="modalArea" class="modalArea">
	<!--Sulgemisnupp-->
	<span id="modalClose" class="modalClose">&times;</span>
	<!--pildikoht-->
	<div class="modalHorizontal">
		<div class="modalVertical">
		<p id="modalCaption"></p>
			<img src="empty.png" id="modalImg" class="modalImg" alt="galeriipilt">
			<br>
			
			<div id="rating" class="modalRating">
				<label><input id="rate1" name="rating" type="radio" value="1">1</label>
				<label><input id="rate2" name="rating" type="radio" value="2">2</label>
				<label><input id="rate3" name="rating" type="radio" value="3">3</label>
				<label><input id="rate4" name="rating" type="radio" value="4">4</label>
				<label><input id="rate5" name="rating" type="radio" value="5">5</label>
				<button id="storeRating">Salvesta hinnang!</button>
				<br>
				<p id="avgRating"></p>
			</div>
	
		</div>
	</div>
  </div>  

	<h1>Avalikud pildid</h1>
	<p>
	<?php 
	echo $sessionHTML;
	?> 
	
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<p>
	<a href="photoUpload.php">Fotode laadimine</a>&nbsp;&nbsp;
	<a href="privategallery.php">Privaatne fotoalbum</a>&nbsp;&nbsp;
	<a href="semipublicgallery.php">Fotoalbum kasutajatele</a>&nbsp;&nbsp;
	<a href="publicgallery.php">Avalik fotoalbum</a>
	<a href="deleteusergallery.php">Kasutaja piltide kustutamine</a>
	</p>
	<hr>
	<?php 
		if($page > 1){
			echo '<a href="?page=' .($page - 1) .'">Eelmine leht</a> | ';
		} else {
			echo "<span>Eelmine leht</span> | ";
		}
		if($page *$limit <= $picCount){
			echo '<a href="?page=' .($page + 1) .'">Järgmine leht</a>';
		} else {
			echo "<span> Järgmine leht</span>";
		}
	?>
    <div>
		<br>
		<div class='gallery' id='gallery'>
		<?php
		echo $galleryHTML;
		?>
	</div>
	<hr>
</body>
</html>