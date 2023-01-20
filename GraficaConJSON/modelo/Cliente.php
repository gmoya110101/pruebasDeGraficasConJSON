<?php
/*************************************************************
 * Cliente.php
 * Objetivo: clase que encapsula el manejo del concepto Cliente
 * Autor: Pasteleria
 *************************************************************/
error_reporting(E_ALL);
include_once("Usuario.php");


class Cliente extends Usuario {
private string $scorreo="";
private string $sNombre="";
private string $calle="";
private int $numero=0;
private string $colonia="";
private string $ciudad="";
private string $estado="";
private string $sNumTelCel="";
private string $sNumTelCasa="";
private array $arrCompras=array();


	public function buscarCvePwd():bool {
	$oAccesoDatos=new AccesoDatos();
	$sQuery="";
	$arrRS=null;
	$bRet = false;
	$arrParams=array();
		if (empty($this->sCorreo) || empty($this->sContrasenia))
			throw new Exception("Cliente->buscarCvePwd: faltan datos");
		else{
			if ($oAccesoDatos->conectar()){
				$sQuery = " SELECT t1.sCorreo, t2.sNombre, t2.calle, t2.numero, t2.colonia, 
				                   t2.ciudad, t2.estado, t2.sNumTelCel, t2.sNumTelCasa
							FROM usuario t1
							JOIN cliente t2 ON t2.scorreo = t1.sCorreo
							WHERE t1.sCorreo = :pCorreo
							AND t1.sContrasenia = :pPwd
							AND t1.bActivo = true";
				$arrParams = array(":pCorreo"=>$this->sCorreo,
								   ":pPwd"=>$this->sContrasenia);
				$arrRS = $oAccesoDatos->ejecutarConsulta($sQuery, $arrParams);
				$oAccesoDatos->desconectar();
				if ($arrRS){
					$this->scorreo = $arrRS[0][0];
					$this->sNombre = $arrRS[0][1];
					$this->calle = $arrRS[0][2];
					$this->numero = $arrRS[0][3];
					$this->colonia = $arrRS[0][4];
					$this->ciudad = $arrRS[0][5];
					$this->estado = $arrRS[0][6];
					$this->sNumTelCel = $arrRS[0][7];
					$this->sNumTelCasa = $arrRS[0][8];
					$this->bActivo = true;
					$bRet = true;
				}
			}
		}
		return $bRet;
	}

	public function buscar():bool {
		throw new Exception("Cliente->buscar: no implementada");
	}

	public function buscarTodos():array {
		throw new Exception("Cliente->buscarTodos: no implementada");
	}

	public function insertar():int {
		throw new Exception("Cliente->insertar: no implementada");
	}

	public function modificar():int {
		throw new Exception("Cliente->modificar: no implementada");
	}

	public function eliminar():int {
		throw new Exception("Cliente->eliminar: no implementada");
	}
	
	public function getNombre():string{
		return $this->sNombre;
	 }
	public function setNombre(string $valor){
		$this->sNombre = $valor;
	 }
  
	 public function getCalle():string{
		return $this->calle;
	 }
	public function setCalle(string $valor){
		$this->calle = $valor;
	 }

	 public function getNumero():int{
		return $this->numero;
	 }
	public function setNumero(int $valor){
		$this->numero = $valor;
	 }
  
	 public function getColonia():string{
		return $this->colonia;
	 }
	public function setColonia(string $valor){
		$this->colonia = $valor;
	 }
  
	 public function getCiudad():string{
		return $this->ciudad;
	 }
	public function setCiudad(string $valor){
		$this->ciudad = $valor;
	 }
  
	 public function getEstado():string{
		return $this->estado;
	 }
	public function setEstado(string $valor){
		$this->estado = $valor;
	 }
  
	 public function getTelefonoCel():string{
		return $this->sNumTelCel;
	 }
	public function setTelefonoCel(string $valor){
		$this->sNumTelCel = $valor;
	 }
  
	 public function getTelefonoCasa():string{
		return $this->sNumTelCasa;
	 }
	public function setTelefonoCasa(string $valor){
		$this->sNumTelCasa = $valor;
	 }
	
	
}
?>