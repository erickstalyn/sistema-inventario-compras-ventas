<?php
    require ("curl.php");
    require ("sunat.php");
    $cliente = new Sunat();
    $ruc = $_GET["ruc"];//print_r($ruc);exit();
    //$ruc="20441053658";
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-socket-id");
    header('Access-Control-Allow-Credentials: true');
    
    //$empresa = $cliente->BuscaDatosSunat($ruc);
    //$cliente->BuscaDatosSunat($ruc);
    echo json_encode( $cliente->BuscaDatosSunat($ruc), JSON_PRETTY_PRINT );
?>
