<?php
/*************************************************************
 * Empleado.php
 * Objetivo: clase que encapsula el manejo del concepto Empleado,
 * 			es decir, trabajadores de la empresa
 * Autor: Pasteleria
 *************************************************************/
error_reporting(E_ALL);
include_once("Usuario.php");

class Empleado extends Usuario {
private string $sNombreCompleto="";
private string $sPerfil="";
private int $nTurno=0;

//Constantes para facilitar lectura de Perfil
public CONST CAJERO = "c";
public CONST ADMINISTRADOR = "g";
public CONST ALMACENISTA = "a";
//No existe en el modelo, pero facilita el manejo de las restricciones
private static $arrPerfiles=array(
								self::CAJERO=>"Cajero",
								self::ADMINISTRADOR=>"Administrador",
								self::ALMACENISTA=>"Almacenista"
							);

							
	public function buscarCvePwd():bool {
	$oAccesoDatos=new AccesoDatos();
	$sQuery="";
	$arrRS=null;
	$bRet = false;
	$arrParams=array();
		if (empty($this->sCorreo) || empty($this->sContrasenia))
			throw new Exception("Empleado->buscarCvePwd: faltan datos");
		else{
			if ($oAccesoDatos->conectar()){
				$sQuery = " SELECT t1.scorreo, t2.snombrecompleto, t2.sPerfil, t2.nTurno
							FROM usuario t1
							JOIN empleado t2 ON t2.scorreo = t1.scorreo
							WHERE t1.scorreo = :pCorreo
							AND t1.scontrasenia = :pPwd
							AND t1.bActivo = true";
				$arrParams = array(":pCorreo"=>$this->sCorreo,
								   ":pPwd"=>$this->sContrasenia);
				$arrRS = $oAccesoDatos->ejecutarConsulta($sQuery, $arrParams);
				$oAccesoDatos->desconectar();
				if ($arrRS){
					$this->sCorreo = $arrRS[0][0];
					$this->sNombreCompleto = $arrRS[0][1];
					$this->sPerfil = $arrRS[0][2];
					$this->nTurno = $arrRS[0][3];
					$this->bActivo = true;
					$bRet = true;
				}
			}
		}
		return $bRet;
	}

	public function buscar():bool {
		throw new Exception("Empleado->buscar: no implementada");
	}

	public function buscarTodos():array {
		throw new Exception("Empleado->buscarTodos: no implementada");
	}

	public function insertar():int {
		throw new Exception("Empleado->insertar: no implementada");
	}

	public function modificar():int {
		throw new Exception("Empleado->modificar: no implementada");
	}

	public function eliminar():int {
		throw new Exception("Empleado->eliminar: no implementada");
	}
	
	public function getNombreCompleto(): string{
       return $this->sNombreCompleto;
    }
	public function setNombreCompleto(string $valor){
       $this->sNombreCompleto = $valor;
    }
    
	public function getPerfil():string{
       return $this->sPerfil;
    }
	public function setPerfil(string $valor){
       $this->sPerfil = $valor;
    }
	
	//No existe en el modelo, es para simplificar la lectura del código
	public function getDescripPerfil():string{
	$sRet="";
		if ($this->sPerfil >0 &&
			array_key_exists($this->sPerfil."", self::$arrPerfiles))
			$sRet = self::$arrPerfiles[$this->sPerfil.""];
		return $sRet;
	}
	
	//No existe set porque la información es fija
	public function getPerfiles():array{
		return self::$arrPerfiles;
	}
}
?>