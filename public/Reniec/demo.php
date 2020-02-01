<?php
    require ("reniec.php");
    require ("curl.php");

    // header('content-type: application/json; charset=utf-8');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-socket-id");
    header('Access-Control-Allow-Credentials: true');
    // $dni=$request->get('dni');
    $dni = $_GET["dni"];
    $persona = new Reniec();
    $yo = $persona->search( $dni );
    if (is_null($yo)) {
        $data=array('estado' => false);
        echo json_encode($data);
    }else{
        if( $yo->success==true )
        {
            $data=array(
                'dni' => $yo->result->DNI,
                'codveri' => $yo->result->CodVerificacion,
                'nombres' => $yo->result->Nombres,
                'apellidos' => $yo->result->Apellidos,
                'grupovota' => $yo->result->gvotacion,
                'distrito' => $yo->result->Distrito,
                'provincia' => $yo->result->Provincia,
                'departamento' => $yo->result->Departamento,
                'estado' => $yo->success
            );
            echo json_encode($data);
        }else{
            $data=array('estado' => $yo->success);
            echo json_encode($data);
        }                
    }
?>
