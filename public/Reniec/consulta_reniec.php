
<?php
require 'simple_html_dom.php';
error_reporting(E_ALL ^ E_NOTICE);
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE');
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, x-socket-id");
header('Access-Control-Allow-Credentials: true');   
$dni = $_POST['dni'];
 
//OBTENEMOS EL VALOR
//$consulta = file_get_html('http://aplicaciones007.jne.gob.pe/srop_publico/Consulta/Afiliado/GetNombresCiudadano?DNI='.$dni)->plaintext;
$consulta = file_get_html('https://eldni.com/buscar-por-dni?dni='.$dni);
 
$datosnombres = array();
foreach($consulta->find('td') as $header) {
 $datosnombres[] = $header->plaintext;
}
//print_r($headlines);
 
 
//LA LOGICA DE LA PAGINAS ES APELLIDO PATERNO | APELLIDO MATERNO | NOMBRES
 
//$partes = explode("|", $consulta);
 
 
$datos = array(
        0 => $dni,
        1 => $datosnombres[0],
        2 => $datosnombres[1],
        3 => $datosnombres[2],
);
 
echo json_encode($datos);
 
?>
