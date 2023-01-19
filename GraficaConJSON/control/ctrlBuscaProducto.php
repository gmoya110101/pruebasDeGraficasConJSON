<?php
/*Archivo:  ctrlBuscaProducto.php
Objetivo: control para buscar plantas, considera filtrado
Autor:    Pasteleria
*/
include_once("../modelo/Producto.php");
include_once("../utils/ErroresAplic.php");
$nErr=-1;
$nNum=0;
$oProducto=null;
$arrEncontrados=null;
$sJsonRet = "";
$oErr = null;
	/*Verifica que haya llegado el tipo y el filtro (que puede ser vacío)*/
	$oProducto = new Producto();
	$arrEncontrados = $oProducto->buscarTodos();
	
	
		$sJsonRet = 
		'{
			"success":true,
			"status": "ok",
			"data":{
				"arrProds": [
		';
		//Recorrer arreglo para llenar objetos
		foreach($arrEncontrados as $oProducto){
			$sJsonRet = $sJsonRet.'{
					"clave": '.$oProducto->getClaveProducto().', 
					"nombre":"'.$oProducto->getNombre().'", 
					"linea":"'.$oProducto->getDescripcionLinea().'",
					"tipo":"'.$oProducto->getDescripcionTipo().'",
					"descripcion":"'.$oProducto->getDescripcion().'", 
					"sabor":"'.$oProducto->getSabor().'", 
					"imagen":"'.$oProducto->getImg().'", 
					"precio":'.$oProducto->getPrecio().',
					"activo": ' . ($oProducto->getActivo() ? "true" : "false") . '
					},';
		}
		//Sobra una coma, eliminarla
		$sJsonRet = substr($sJsonRet,0, strlen($sJsonRet)-1);
		
		//Colocar cierre de arreglo y de objeto
		$sJsonRet = $sJsonRet.'
				]
			}
		}';
	
	
	//Retornar JSON a quien hizo la llamada
	header('Content-type: application/json');
	echo $sJsonRet;
