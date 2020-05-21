<?php
    /* ------------- DB CONNECTION ------------ */
    require_once "database.php";
    require_once "functions.php";
    /* ------------- END DB CONNECTION ------------ */
    $newArray = json_decode(file_get_contents("php://input"), true);
    if(isset($newArray['reparto']) && isset($newArray['NOME']) && isset($newArray['TURNO']) && isset($newArray['DATA'])){
        //data parsing and character escape
        $reparto = $newArray['reparto'];
        unset($newArray['reparto']);
        if(checkIfExistsInDB(array("reparto"=>$reparto, "date"=>$newArray['DATA'], "turno"=>$newArray['TURNO'])))
            die("Nel Database e' gia presente un rapportino di questo reparto con questa data e turno");
        $jsonData = json_encode($newArray);
        switch($reparto){
            case "mulini":
                $insertIntoDB = "INSERT INTO mulini (nome,data,turno,dati) VALUES (:nome,:data,:turno,:dati)";
                break;
            case 'sceltal1':
                $insertIntoDB = "INSERT INTO sceltal1 (nome,data,turno,dati) VALUES (:nome,:data,:turno,:dati)";
                break;
            case 'sceltal2':
                $insertIntoDB = "INSERT INTO sceltal2 (nome,data,turno,dati) VALUES (:nome,:data,:turno,:dati)";
                break;
            case 'sceltal3':
                $insertIntoDB = "INSERT INTO sceltal3 (nome,data,turno,dati) VALUES (:nome,:data,:turno,:dati)";
                break;
            case 'doppia':
                $insertIntoDB = "INSERT INTO doppia (nome,data,turno,dati) VALUES (:nome,:data,:turno,:dati)";
                break;
            case 'singola':
                $insertIntoDB = "INSERT INTO singola (nome,data,turno,dati) VALUES (:nome,:data,:turno,:dati)";
                break;
            case 'presse':
                $insertIntoDB = "INSERT INTO presse (nome,data,turno,dati) VALUES (:nome,:data,:turno,:dati)";
                break;
            default:
                http_response_code(404);
                die();
        }
        $addStmt = $conn->prepare($insertIntoDB);
        $addStmt->bindParam(':nome', $newArray['NOME'], PDO::PARAM_STR);
        $addStmt->bindParam(':data', $newArray['DATA'], PDO::PARAM_STR);
        $addStmt->bindParam(':turno', $newArray['TURNO'], PDO::PARAM_STR);
        $addStmt->bindParam(':dati', $jsonData);
        $addStmt->execute();
    }else{
        http_response_code(404);
        die();
    }

?>
