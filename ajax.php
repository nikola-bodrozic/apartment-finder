<?php

session_start();

include('config.php');

$conn = new PDO("mysql:host=$hostname;dbname=$dataBase;charset=$character_set", $username, $password);

//usleep(250000);
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
	$headers = apache_request_headers();
	$flag = false;
	
	foreach ($headers as $header => $value) {
		if($header == "X-Sec-Header" && $value == $_SESSION["sectok"]) $flag = true;
	}
	
	if($flag) {
    //var_dump($_POST);die;
    $minprice = $_POST["minprice"]; //5000;
    $maxprice = $_POST["maxprice"]; //11000;
    try {
     $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
     $stmt = $conn -> prepare("SELECT 
      a.id, 
      rent_amount, 
      slug,
      lat,
      lang 
        FROM 
      fol_apartment a 
        INNER JOIN 
      fol_city c ON a.area_id = c.id WHERE c.id = :id 
        AND 
      rent_amount BETWEEN :minprice AND :maxprice");

     $stmt->bindParam(":id", $_POST['id'], PDO::PARAM_INT);
     $stmt->bindParam(":minprice", $minprice, PDO::PARAM_INT);
     $stmt->bindParam(":maxprice", $maxprice, PDO::PARAM_INT);     
     $stmt->execute();

     $arr = array();
     array_push($arr, array("numrows" => $stmt->rowCount()) );
     while ($row = $stmt -> fetch()) {
        array_push($arr, array(
          "rent" => $row['rent_amount'], 
          "slug" => $row['slug'], 
          "img" => "house".$row['id'].".jpg",
          "lat" => $row['lat'],
          "lang" => $row['lang']
        ));
     }
     echo json_encode($arr);

    } catch(PDOException $e) {
     echo 'ERROR: ' . $e -> getMessage();
     die;
    }    
	}
}
