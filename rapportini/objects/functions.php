<?php

if (!function_exists('http_response_code')){
    function http_response_code($newcode = NULL){
        static $code = 200;
        if($newcode !== NULL){
            header('X-PHP-Response-Code: '.$newcode, true, $newcode);
            if(!headers_sent())
                $code = $newcode;
        }       
        return $code;
    }
}

function checkIfExistsInDB($data){
    global $conn;
    switch ($data['reparto']) {
        case 'mulini':
            $fetchSQL = "SELECT * FROM mulini WHERE data = :date AND turno = :turno LIMIT 1";
            break;
        case 'sceltal1':
            $fetchSQL = "SELECT * FROM sceltal1 WHERE data = :date AND turno = :turno LIMIT 1";
            break;
        case 'sceltal2':
            $fetchSQL = "SELECT * FROM sceltal2 WHERE data = :date AND turno = :turno LIMIT 1";
            break;
        case 'sceltal3':
            $fetchSQL = "SELECT * FROM sceltal3 WHERE data = :date AND turno = :turno LIMIT 1";
            break;
        case 'doppia':
            $fetchSQL = "SELECT * FROM doppia WHERE data = :date AND turno = :turno LIMIT 1";
            break;
        case 'singola':
            $fetchSQL = "SELECT * FROM singola WHERE data = :date AND turno = :turno LIMIT 1";
            break;
        case 'presse':
            $fetchSQL = "SELECT * FROM presse WHERE data = :date AND turno = :turno LIMIT 1";
            break;
        default:
            http_response_code(404);
            die();
            break;
    }
    $fetchSTMT = $conn->prepare($fetchSQL);
    $fetchSTMT->bindParam(":date", $data['date'], PDO::PARAM_STR);
    $fetchSTMT->bindParam(":turno", $data['turno'], PDO::PARAM_STR);
    $fetchSTMT->execute();
    if($fetchSTMT->rowCount() == 0)
        return false;
    else
        return true;
}


?>