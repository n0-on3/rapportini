<?php 
    require_once "database.php";
    require_once "functions.php";
    header('Content-Type: application/json');
    if(isset($_GET['date']) && $_GET['date'] != "" && isset($_GET['reparto']) && $_GET['reparto'] != "" && !isset($_GET['turno'])){
        switch ($_GET['reparto']) {
            case 'mulini':
                $fetchSQL = "SELECT * FROM mulini WHERE data = :date ORDER BY FIELD(turno, 'M', 'P', 'N') LIMIT 3";
                break;
            case 'sceltal1':
                $fetchSQL = "SELECT * FROM sceltal1 WHERE data = :date ORDER BY FIELD(turno, 'M', 'P', 'N') LIMIT 3";
                break;
            case 'sceltal2':
                $fetchSQL = "SELECT * FROM sceltal2 WHERE data = :date ORDER BY FIELD(turno, 'M', 'P', 'N') LIMIT 3";
                break;
            case 'sceltal3':
                $fetchSQL = "SELECT * FROM sceltal3 WHERE data = :date ORDER BY FIELD(turno, 'M', 'P', 'N') LIMIT 3";
                break;
            case 'doppia':
                $fetchSQL = "SELECT * FROM doppia WHERE data = :date ORDER BY FIELD(turno, 'M', 'P', 'N') LIMIT 3";
                break;
            case 'singola':
                $fetchSQL = "SELECT * FROM singola WHERE data = :date ORDER BY FIELD(turno, 'M', 'P', 'N') LIMIT 3";
                break;
            case 'presse':
                $fetchSQL = "SELECT * FROM presse WHERE data = :date ORDER BY FIELD(turno, 'M', 'P', 'N') LIMIT 3";
                break;
            default:
                http_response_code(404);
                die();
                break;
        }
        //check if data are already inside database
        $fetchSTMT = $conn->prepare($fetchSQL);
        $fetchSTMT->bindParam(":date", $_GET['date'], PDO::PARAM_STR);
        $fetchSTMT->execute();
        if($fetchSTMT->rowCount()){
            $resultData = $fetchSTMT->fetchAll(PDO::FETCH_ASSOC); 
            echo json_encode($resultData);
            die();
        }else{
            http_response_code(404);
            die();
        }
    }elseif(isset($_GET['date']) && $_GET['date'] != "" && isset($_GET['reparto']) && $_GET['reparto'] != "" && isset($_GET['turno']) && $_GET['turno'] != ""){
        if(!checkIfExistsInDB($_GET)){
            echo json_encode(array('isInside' => false));
            die();
        }else{
            echo json_encode(array('isInside' => true));
            die();
        }
    }else{
        http_response_code(400);
        die();
    }
?>