<?php

session_start();

$username = "root";
$password = "";
$hostname = "localhost";
$dataBase = "test";
$character_set = "utf8";
$conn = new PDO("mysql:host=$hostname;dbname=$dataBase;charset=$character_set", $username, $password);

// is AJAX call? check the HTTP header
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    $headers = apache_request_headers();
    $flag = false;

    // check for my HTTP header
    foreach ($headers as $header => $value) {
        if ($header == "X-Sec-Header" && $value == $_SESSION["sectok"])
            $flag = true;
    }

    // start processing POSTed data
    if ($flag) {
        $minprice = $_POST["minprice"]; //5000;
        $maxprice = $_POST["maxprice"]; //11000;
        try {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $stmt = $conn->prepare("SELECT 
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

            // create array
            $arr = array();
            array_push($arr, array("numrows" => $stmt->rowCount()));
            while ($row = $stmt->fetch()) {
                array_push($arr, array(
                    "rent" => $row['rent_amount'],
                    "slug" => $row['slug'],
                    "img" => "house" . $row['id'] . ".jpg",
                    "lat" => $row['lat'],
                    "lang" => $row['lang']
                ));
            }

            // create and send JSON based on the array
            echo json_encode($arr);
        } catch (PDOException $e) {
            echo 'ERROR: ' . $e->getMessage();
            die;
        }
    }
}
