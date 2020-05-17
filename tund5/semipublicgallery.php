<?php
		require("../../../../configuration.php");
	
	//sessiooni käivitamine või kasutamine
	//session_start();
	//var_dump($_SESSION);
	require("classes/Session.class.php");
	SessionManager::sessionStart("vr20", 0, "/~tarmo.reinvali/", "tigu.hk.tlu.ee");
	
	//kas pole sisseloginud
	if(!isset($_SESSION["userid"])){
		//jõuga avalehele
		header("Location: page.php");
	}
	
	//login välja
	if(isset($_GET["logout"])){
		session_destroy();
		header("Location: page.php");
	}

	require("../../../../configuration.php");
	require("fnc_gallery.php");
	
	$privacy_set = 2;
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
	  
	  $galleryHTML = readsemigalleryImages($privacy_set, $page, $limit);

	//$privateThumbnails = readAllSemiPublicPictureThumbs();
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Kasutajate pildid</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<style type="text/css">
	body {margin:20px;}
	.block {width: 150px;float: left;}
	</style>
</head>
<body>
	<h1>Kasutajate pildid</h1>
	<p><?php echo $_SESSION["userFirstName"]. " " .$_SESSION["userLastName"] ."."; ?> Logi <a href="?logout=1">välja</a>!</p>
	<p>Tagasi <a href="home.php">avalehele</a>!</p>
	<p>
	<a href="photoUpload.php">Fotode laadimine</a>&nbsp;&nbsp;
	<a href="privategallery.php">Privaatne fotoalbum</a>&nbsp;&nbsp;
	<a href="semipublicgallery.php">Fotoalbum kasutajatele</a>&nbsp;&nbsp;
	<a href="publicgallery.php">Avalik fotoalbum</a>
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
	<?php
		echo $galleryHTML;
		?>
	</div>
	<hr>
</body>
</html>