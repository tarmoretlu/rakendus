<?php
$GLOBALS["originalPhotoDir"] = "../../UploadOriginalPhoto/";
$GLOBALS["normalPhotoDir"] = "../../UploadNormalPhoto/";
$GLOBALS["thumbPhotoDir"] = "../../UploadThumbnail/";
	
function readprivategalleryImages($fnc_privacy, $fnc_page, $fnc_limit){
	//readgalleryImages(2, $page, $limit);
		$privacy = $fnc_privacy;
		$page = $fnc_page;
		$limit = $fnc_limit;
		//$picCount = countPics($privacy);
		
		$skip = ($page-1)*$limit;
		$finalHTML = "";
		$html = "";
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$conn->set_charset('utf8');
		$stmt = $conn->prepare("
		SELECT 
		vr20_photos.filename,
		vr20_users.firstname,
		vr20_users.lastname
		FROM vr20_photos 
		LEFT JOIN vr20_users ON vr20_photos.userid=vr20_users.id
		WHERE userid=? AND privacy<=? AND deleted IS NULL
		LIMIT ?,?
		");
		echo $conn->error;
		$stmt->bind_param("iiii", $_SESSION["userid"], $privacy, $skip, $limit);
		$stmt->bind_result($filenameFromDb, $firstnameFromDb, $lastnameFromDB);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= "<div class='block'><a href=".$GLOBALS["normalPhotoDir"] .$filenameFromDb ." target='_blank'><img src=" .$GLOBALS["thumbPhotoDir"] .$filenameFromDb ."></a><br>".$firstnameFromDb." ".$lastnameFromDB."</div>\n";
		}
		if($html != ""){
			$finalHTML = $html;
		} else {
			$finalHTML = "<p>Kahjuks pilte pole!</p>";
		}
		
		$stmt->close();
		$conn->close();
		return $finalHTML;
	}
	
	 function countPics($privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
		$conn->set_charset('utf8');
		$stmt = $conn->prepare("SELECT COUNT(id) 
		FROM vr20_photos 
		WHERE privacy<=? AND deleted IS NULL");
		echo $conn->error;
		$stmt->bind_param("i", $privacy);
		$stmt->bind_result($count);
		$stmt->execute();
		$stmt->fetch();
		$notice = $count;
		$stmt->close();
		$conn->close();
		return $notice;
		
	}

	function readsemigalleryImages($fnc_privacy, $fnc_page, $fnc_limit){
		//readgalleryImages(2, $page, $limit);
			$privacy = $fnc_privacy;
			$page = $fnc_page;
			$limit = $fnc_limit;
			//$picCount = countPics($privacy);
			
			$skip = ($page-1)*$limit;
			$finalHTML = "";
			$html = "";
			$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
			$conn->set_charset('utf8');
			$stmt = $conn->prepare("
			SELECT 
			vr20_photos.filename,
			vr20_users.firstname,
			vr20_users.lastname
			FROM vr20_photos 
			LEFT JOIN vr20_users ON vr20_photos.userid=vr20_users.id
			WHERE privacy<=? AND deleted IS NULL
			LIMIT ?,?
			");
			echo $conn->error;
			$stmt->bind_param("iii", $privacy, $skip, $limit);
			$stmt->bind_result($filenameFromDb, $firstnameFromDb, $lastnameFromDB);
			$stmt->execute();
			while($stmt->fetch()){
				$html .= "<div class='block'><a href=".$GLOBALS["normalPhotoDir"] .$filenameFromDb ." target='_blank'><img src=" .$GLOBALS["thumbPhotoDir"] .$filenameFromDb ."></a><br>".$firstnameFromDb." ".$lastnameFromDB."</div>\n";
			}
			if($html != ""){
				$finalHTML = $html;
			} else {
				$finalHTML = "<p>Kahjuks pilte pole!</p>";
			}
			
			$stmt->close();
			$conn->close();
			return $finalHTML;
		}
		function readpublicgalleryImages($fnc_page, $fnc_limit){
			//readgalleryImages(2, $page, $limit);
				
				$page = $fnc_page;
				$limit = $fnc_limit;
				//$picCount = countPics($privacy);
				
				$skip = ($page-1)*$limit;
				$finalHTML = "";
				$html = "";
				$conn = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUserName"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
				$conn->set_charset('utf8');
				$stmt = $conn->prepare("
				SELECT 
				vr20_photos.filename,
				vr20_users.firstname,
				vr20_users.lastname
				FROM vr20_photos 
				LEFT JOIN vr20_users ON vr20_photos.userid=vr20_users.id
				WHERE deleted IS NULL
				LIMIT ?,?
				");
				echo $conn->error;
				$stmt->bind_param("ii", $skip, $limit);
				$stmt->bind_result($filenameFromDb, $firstnameFromDb, $lastnameFromDB);
				$stmt->execute();
				while($stmt->fetch()){
					$html .= "<div class='block'><a href=".$GLOBALS["normalPhotoDir"] .$filenameFromDb ." target='_blank'><img src=" .$GLOBALS["thumbPhotoDir"] .$filenameFromDb ."></a><br>".$firstnameFromDb." ".$lastnameFromDB."</div>\n";
				}
				if($html != ""){
					$finalHTML = $html;
				} else {
					$finalHTML = "<p>Kahjuks pilte pole!</p>";
				}
				
				$stmt->close();
				$conn->close();
				return $finalHTML;
			}
			