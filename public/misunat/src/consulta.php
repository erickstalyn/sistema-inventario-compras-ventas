<?php
	header('Content-Type: text/plain');

	header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-socket-id");
	header('Access-Control-Allow-Credentials: true');
	
	require ("autoload.php");
	$cookie = array(
		'cookie' 		=> array(
			'use' 		=> true,
			'file' 		=> __DIR__ . "/cookie.txt"
		)
	);
	$config = array(
		'representantes_legales' 	=> true,
		'cantidad_trabajadores' 	=> true,
		'establecimientos' 			=> true,
		'cookie' 					=> $cookie
	);
	$company = new \Sunat\ruc( $config );
	
	$ruc = ( isset($_REQUEST["nruc"]))? $_REQUEST["nruc"] : false;
	
	$search1 = $company->consulta( $ruc );
	
	echo $search1->json( NULL, true );
?>
