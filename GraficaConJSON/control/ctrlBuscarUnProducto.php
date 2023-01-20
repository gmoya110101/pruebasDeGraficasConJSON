<?php
/*Archivo:  ctrlBuscarUnaPlantaOrnato.php
Objetivo: control para buscar los datos de una planta de ornato (recibe tipo y clave)
Autor:    BAOZ
*/
include_once("../modelo/Producto.php");
include_once("../modelo/Empleado.php");
include_once("../utils/ErroresAplic.php");
session_start(); //Requiere datos de sesión, sólo el administrador puede editar (y consultar una)
$nErr = -1;
$nNum = 0;
$oProducto = null;
/*Verifica que esté firmado y sea administrador*/
if (
	isset($_SESSION["sTipoFirmado"]) &&
	$_SESSION["sTipoFirmado"] == Empleado::ADMINISTRADOR
) {
	/*Verifica que hayan llegado los datos*/
	if (isset($_REQUEST["txtCve"]) && !empty($_REQUEST["txtCve"])) {
		try {
			//Convierte el tipo indicado a número
			//$nNum = intval(($_REQUEST["cmbTipo"]),10);
			//if ($nNum == 1 || $nNum == 2 || $nNum == 3 || $nNum == 4){
			$nNum = intval(($_REQUEST["txtCve"]), 10);
			$oProducto = new Producto();
			$oProducto->setClaveProducto($nNum);

			if (!$oProducto->buscar())
				$nErr = ErroresAplic::NO_EXISTE_BUSCADO;
			/*}else
					$nErr = ErroresAplic::TIPO_PROD_INEXISTENTE;*/
		} catch (Exception $e) {
			//Enviar el error específico a la bitácora de php (dentro de php\logs\php_error_log
			error_log($e->getFile() . " " . $e->getLine() . " " . $e->getMessage(), 0);
			$nErr = ErroresAplic::ERROR_EN_BD;
		}
	} else
		$nErr = ErroresAplic::FALTAN_DATOS;
} else {
	$nErr = ErroresAplic::NO_FIRMADO;
}

if ($nErr == -1) {
	$sCadJson = '{
			"success": true,
			"status": "ok",
			"data":{
				"clave": '.$oProducto->getClaveProducto().', 
				"nombre":"'.$oProducto->getNombre().'", 
				"linea":"'.$oProducto->getLinea().'", 
				"tipo":"'.$oProducto->getTipo().'",
				"descripcion":"'.$oProducto->getDescripcion().'",  
				"sabor":"'.$oProducto->getSabor().'", 
				"imagen":"'.$oProducto->getImg().'", 
				"precio":'.$oProducto->getPrecio().',
				"activo": '.($oProducto->getActivo()?"true":"false").'
			}
		}';
} else {
	$oErr = new ErroresAplic();
	$oErr->setError($nErr);
	$sCadJson = '{
			"success": false,
			"status": "' . $oErr->getTextoError() . '",
			"data":{}
		}';
}
header('Content-type: application/json');
echo $sCadJson;
