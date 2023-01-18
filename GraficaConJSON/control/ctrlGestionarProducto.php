<?php
/*Archivo:  ctrlGestionarPlantaOrnato.php
Objetivo: control para buscar gestionar (insertar, modificar, eliminar) una planta
		  de ornato. La eliminación es lógica, no física
Autor:    BAOZ
*/
include_once("../modelo/Producto.php");
include_once("../modelo/Empleado.php");
include_once("../utils/ErroresAplic.php");
session_start();//Requiere datos de sesión, sólo el administrador puede editar
$nErr=-1;
$nNum=0;
$sOpe="";
$oProducto=null;
$arrTiposValidos = ["image/jpg", "image/jpeg", "image/png"];
$sTipo="";
$arrPartes;
$sExtension="";
$sNomArchFinal="";
$nAfectados = -1;
	/*Verifica que esté firmado y sea administrador*/
	if (isset($_SESSION["sTipoFirmado"]) && 
		$_SESSION["sTipoFirmado"]==Empleado::ADMINISTRADOR){
		/*Verifica que hayan llegado los datos mínimos (clave, operación)*/
		if (isset($_REQUEST["txtCve"]) && !empty($_REQUEST["txtCve"])&&
			isset($_REQUEST["txtOpe"]) && !empty($_REQUEST["txtOpe"])){
			try{
				//Convierte el tipo indicado a número
						$oProducto = new Producto();
					//Verifica la operación recibida
					$sOpe = $_REQUEST["txtOpe"];
					if ($sOpe == 'a' || $sOpe == 'b' || $sOpe == 'm'){
						$oProducto->setClaveProducto((int)$_REQUEST["txtCve"]);
						//Paso de datos a menos que sea baja
						if ($sOpe != 'b'){
							if (isset($_REQUEST["txtNom"]) && !empty($_REQUEST["txtNom"]) &&
								isset($_REQUEST["cmbLineaD"]) && !empty($_REQUEST["cmbLineaD"]) &&
								isset($_REQUEST["cmbTipoD"]) && !empty($_REQUEST["cmbTipoD"]) &&
								isset($_REQUEST["txtDescripcion"]) && !empty($_REQUEST["txtDescripcion"]) &&
								isset($_REQUEST["txtSabor"]) && !empty($_REQUEST["txtSabor"]) &&
								isset($_REQUEST["txtPrecio"]) && !empty($_REQUEST["txtPrecio"]) &&
								is_uploaded_file($_FILES["txtImg"]["tmp_name"])){

								$oProducto->setNombre($_REQUEST["txtNom"]);
								$oProducto->setLinea($_REQUEST["cmbLineaD"]);
								$oProducto->setTipo($_REQUEST["cmbTipoD"]);
								$oProducto->setDescripcion($_REQUEST["txtDescripcion"]);
								$oProducto->setSabor($_REQUEST["txtSabor"]);
								$oProducto->setPrecio($_REQUEST["txtPrecio"]);								
								//Verificar tipo de archivo
								$sTipo = mime_content_type($_FILES["txtImg"]["tmp_name"]);

								if (in_array($sTipo, $arrTiposValidos, true)){
									//Verificar tamaño del archivo
									if ($_FILES["txtImg"]["size"]<300000){
										/*El archivo es correcto, copiarlo al directorio 
										de la aplicación con nuevo nombre */
										$arrPartes=explode(".",$_FILES["txtImg"]["name"]);
										$sExtension = end($arrPartes);
										//Generar nuevo nombre
										$sNomArchFinal = $nNum."_".time().".".$sExtension;
										//Pasar archivo
										if (move_uploaded_file($_FILES["txtImg"]["tmp_name"], 
											"../img/".$sNomArchFinal))
											$oProducto->setImg($sNomArchFinal);
										else
											$nErr = ErroresAplic::ARCH_PROBL;
									}else{
										$nErr = ErroresAplic::ARCH_MAYOR;
									}
								}else{
									$nErr = ErroresAplic::ARCH_TIPO_MAL;
								}								
							}else
								$nErr = ErroresAplic::FALTAN_DATOS;
						}
						if ($nErr == -1){

							
							//Llama al método dependiendo de la operación
							switch($sOpe){
								case 'a': $nAfectados = $oProducto->insertar();
											break;
								case 'b': $nAfectados = $oProducto->eliminar();
											break;
								case 'm': $nAfectados = $oProducto->modificar();
											break;
							}
							//Si no afectó al menos un registro, se trata de un error
							if ($nAfectados <1)
								$nErr = ErroresAplic::OPE_NO_REALIZADA;
						}
					}else{
						$nErr = ErroresAplic::OPE_DESCONOCIDA;
					}
				
			}catch(Exception $e){
				//Enviar el error específico a la bitácora de php (dentro de php\logs\php_error_log
				error_log($e->getFile()." ".$e->getLine()." ".$e->getMessage(),0);
				$nErr = ErroresAplic::ERROR_EN_BD;
			}
		}
		else
			$nErr = ErroresAplic::FALTAN_DATOS;
	}else{
		$nErr = ErroresAplic::NO_FIRMADO;
	}
	
	if ($nErr==-1){
		$sCadJson = '{
			"success": true,
			"status": "ok",
			"data":{}
		}';
	}else{
		$oErr = new ErroresAplic();
		$oErr->setError($nErr);
		$sCadJson = '{
			"success": false,
			"status": "'.$oErr->getTextoError().'",
			"data":{}
		}';
	}
	header('Content-type: application/json');
	echo $sCadJson;
?>