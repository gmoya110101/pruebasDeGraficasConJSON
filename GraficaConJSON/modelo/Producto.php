<?php

/*************************************************************
 * Producto.php
 * Objetivo: clase que encapsula el manejo del concepto Producto
 * Autor: Pasteleria
 *************************************************************/
error_reporting(E_ALL);
include_once("AccesoDatos.php");

class Producto
{

   private int $nClaveProducto = 0;
   private string $sNombre = "";
   private int $nLineas = 0;
   private int $nTipo = 0;
   private string $sDescripcion = "";
   private string $sSabor = "";
   private string $sImagen = "";
   private float $sPrecio = 0;
   private bool $bActivo=false;

   //Constantes para los filtros por linea
   public const PASTEL = 1;
   public const GALLETA = 2;
   public const GELATINA = 3;
   public const PANQUESITO = 4;
   //Constantes para los filtros tipo
   public const NORMAL = 1;
   public const DIETETICO = 2;
   public const DIABETICO = 3;
   public const VEGANO = 4;

   //No existe en el modelo, pero facilita el manejo de las restricciones
   private static $arrLineas = array(
      self::PASTEL => "Pastel",
      self::GALLETA => "Galleta",
      self::GELATINA => "Gelatina",
      self::PANQUESITO => "Panquesito"
   );

   private static $arrTipos = array(
      self::NORMAL => "Normal",
      self::DIETETICO => "Dietético",
      self::DIABETICO => "Diabético",
      self::VEGANO => "Vegano"
   );

   //Getter y setter
   public function getClaveProducto(): int
   {
      return $this->nClaveProducto;
   }
   public function setClaveProducto(int $valor)
   {
      $this->nClaveProducto = $valor;
   }
   public function getNombre(): string
   {
      return $this->sNombre;
   }
   public function setNombre(string $valor)
   {
      $this->sNombre = $valor;
   }

   public function getLinea(): int
   {
      return $this->nLineas;
   }
   public function setLinea(int $valor)
   {
      $this->nLineas = $valor;
   }

   public function getTipo(): int
   {
      return $this->nTipo;
   }
   public function setTipo(int $valor)
   {
      $this->nTipo = $valor;
   }

   public function getDescripcion(): string
   {
      return $this->sDescripcion;
   }
   public function setDescripcion(string $valor)
   {
      $this->sDescripcion = $valor;
   }

   public function getSabor(): string
   {
      return $this->sSabor;
   }
   public function setSabor(string $valor)
   {
      $this->sSabor = $valor;
   }
   public function getImg(): string
   {
      return $this->sImagen;
   }
   public function setImg(string $valor)
   {
      $this->sImagen = $valor;
   }

   public function getPrecio(): float
   {
      return $this->sPrecio;
   }
   public function setPrecio(float $valor)
   {
      $this->sPrecio = $valor;
   }

   public function getActivo():bool{
      return $this->bActivo;
   }
  public function setActivo(bool $valor){
      $this->bActivo = $valor;
   }

   //Para obtener las descripciones de la linea
   public function getDescripcionLinea(): string
   {
      $sRet = "";
      if (
         $this->nLineas > 0 &&
         array_key_exists($this->nLineas . "", self::$arrLineas)
      )
         $sRet = self::$arrLineas[$this->nLineas . ""];
      return $sRet;
   }

   //No existe set porque la información es fija
   public function getLi(): array
   {
      return self::$arrLineas;
   }


   public function getDescripcionTipo(): string
   {
      $sRet = "";
      if (
         $this->nTipo > 0 &&
         array_key_exists($this->nTipo . "", self::$arrTipos)
      )
         $sRet = self::$arrTipos[$this->nTipo . ""];
      return $sRet;
   }

   //No existe set porque la información es fija
   public function getTip(): array
   {
      return self::$arrTipos;
   }

   // ------------------ Funciones de consulta ------------------------
   public function buscarTodos(): array
   {
      $oAccesoDatos = new AccesoDatos();
      $sQuery = "";
      $arrRS = null;
      $arrLinea = null;
      $oProducto = null;
      $arrRet = array();
      if ($oAccesoDatos->conectar()) {
         $sQuery = "SELECT t1.nClaveProducto, t1.sNombre, t1.nLinea, t1.nTipo, t1.sDescripcion,
                           t1.sSabor, t1.sImagen, t1.sPrecio, t1.bActivo  
                            FROM productos t1
                            ORDER BY t1.sNombre;
                  ";
         $arrParams = array();
         $arrRS = $oAccesoDatos->ejecutarConsulta($sQuery, $arrParams);
         $oAccesoDatos->desconectar();
         if ($arrRS) {
            $arrRet = array();
            foreach ($arrRS as $arrLinea) {
               $oProducto = new Producto();
               $oProducto->setClaveProducto($arrLinea[0]);
               $oProducto->setNombre($arrLinea[1]);
               $oProducto->setLinea($arrLinea[2]);
               $oProducto->setTipo($arrLinea[3]);
               $oProducto->setDescripcion($arrLinea[4]);
               $oProducto->setSabor($arrLinea[5]);
               $oProducto->setImg($arrLinea[6]);
               $oProducto->setPrecio($arrLinea[7]);
               $oProducto->setActivo($arrLinea[8]);
               $arrRet[] = $oProducto; //más rápido que array_push($arrRet, $oPlantaOrnato)
            }
         }
      }
      return $arrRet;
   }

   public function buscarTodosFiltroLinea(): array
   {
      $oAccesoDatos = new AccesoDatos();
      $sQuery = "";
      $arrRS = null;
      $arrLinea = null;
      $oProducto = null;
      $arrRet = array();

      //En este ejemplo, el filtro es por linea
      if ($this->nLineas <= 0)
         throw new Exception("Productos->buscarTodosFiltro: faltan datos de Linea"); //cambiar
      else {
         if ($oAccesoDatos->conectar()) {
            $sQuery = "SELECT t1.nClaveProducto, t1.sNombre, t1.nLinea, t1.nTipo, t1.sDescripcion,
            t1.sSabor, t1.sImagen, t1.sPrecio, t1.bActivo 
             FROM productos t1
             WHERE t1.nLinea = :lin
             ORDER BY t1.sNombre;
         ";

            $arrParams = array(":lin" => $this->nLineas);
            $arrRS = $oAccesoDatos->ejecutarConsulta($sQuery, $arrParams);
            $oAccesoDatos->desconectar();
            if ($arrRS) {
               $arrRet = array();
               foreach ($arrRS as $arrLinea) {
                  $oProducto = new Producto();
                  $oProducto->setClaveProducto($arrLinea[0]);
                  $oProducto->setNombre($arrLinea[1]);
                  $oProducto->setLinea($arrLinea[2]);
                  $oProducto->setTipo($arrLinea[3]);
                  $oProducto->setDescripcion($arrLinea[4]);
                  $oProducto->setSabor($arrLinea[5]);
                  $oProducto->setImg($arrLinea[6]);
                  $oProducto->setPrecio($arrLinea[7]);
                  $oProducto->setActivo($arrLinea[8]);
                  $arrRet[] = $oProducto; //más rápido que array_push($arrRet, $oPlantaOrnato)
               }
            }
         }
      }
      return $arrRet;
   }

   public function buscarTodosFiltroTipo(): array
   {
      $oAccesoDatos = new AccesoDatos();
      $sQuery = "";
      $arrRS = null;
      $arrLinea = null;
      $oProducto = null;
      $arrRet = array();
      //En este ejemplo, el filtro es por presentación
      if ($this->nTipo <= 0)
         throw new Exception("Productos->buscarTodosFiltro: faltan datos de tipo"); //cambiar
      else {
         if ($oAccesoDatos->conectar()) {
            $sQuery = "SELECT t1.nClaveProducto, t1.sNombre, t1.nLinea, t1.nTipo, t1.sDescripcion,
            t1.sSabor, t1.sImagen, t1.sPrecio, t1.bActivo
             FROM productos t1
             WHERE t1.nTipo = :tip
             ORDER BY t1.sNombre;
         ";

            $arrParams = array(":tip" => $this->nTipo);
            $arrRS = $oAccesoDatos->ejecutarConsulta($sQuery, $arrParams);
            $oAccesoDatos->desconectar();
            if ($arrRS) {
               $arrRet = array();
               foreach ($arrRS as $arrLinea) {
                  $oProducto = new Producto();
                  $oProducto->setClaveProducto($arrLinea[0]);
                  $oProducto->setNombre($arrLinea[1]);
                  $oProducto->setLinea($arrLinea[2]);
                  $oProducto->setTipo($arrLinea[3]);
                  $oProducto->setDescripcion($arrLinea[4]);
                  $oProducto->setSabor($arrLinea[5]);
                  $oProducto->setImg($arrLinea[6]);
                  $oProducto->setPrecio($arrLinea[7]);
                  $oProducto->setActivo($arrLinea[8]);
                  $arrRet[] = $oProducto; //más rápido que array_push($arrRet, $oPlantaOrnato)
               }
            }
         }
      }
      return $arrRet;
   }

   public function buscarTodosFiltroDoble(): array
   {
      $oAccesoDatos = new AccesoDatos();
      $sQuery = "";
      $arrRS = null;
      $arrLinea = null;
      $oProducto = null;
      $arrRet = array();
      //En este ejemplo, el filtro es por presentación
      if ($this->nLineas <= 0 &&  $this->nTipo <= 0)
         throw new Exception("Productos->buscarTodosFiltro: faltan datos de ambos filtros"); //cambiar
      else {
         if ($oAccesoDatos->conectar()) {
            $sQuery = "SELECT t1.nClaveProducto, t1.sNombre, t1.nLinea, t1.nTipo, t1.sDescripcion,
            t1.sSabor, t1.sImagen, t1.sPrecio, t1.bActivo 
             FROM productos t1
             WHERE t1.nLinea = :lin
             AND t1.nTipo = :tip
             ORDER BY t1.sNombre;
         ";

            $arrParams = array(":lin" => $this->nLineas, ":tip" => $this->nTipo);
            $arrRS = $oAccesoDatos->ejecutarConsulta($sQuery, $arrParams);
            $oAccesoDatos->desconectar();
            if ($arrRS) {
               $arrRet = array();
               foreach ($arrRS as $arrLinea) {
                  $oProducto = new Producto();
                  $oProducto->setClaveProducto($arrLinea[0]);
                  $oProducto->setNombre($arrLinea[1]);
                  $oProducto->setLinea($arrLinea[2]);
                  $oProducto->setTipo($arrLinea[3]);
                  $oProducto->setDescripcion($arrLinea[4]);
                  $oProducto->setSabor($arrLinea[5]);
                  $oProducto->setImg($arrLinea[6]);
                  $oProducto->setPrecio($arrLinea[7]);
                  $oProducto->setActivo($arrLinea[8]);
                  $arrRet[] = $oProducto; //más rápido que array_push($arrRet, $oPlantaOrnato)
               }
            }
         }
      }
      return $arrRet;
   }

   // Otras acciones por agregar

   public function buscar(): bool // Busqueda especifica
   {
      $oAccesoDatos = new AccesoDatos();
      $sQuery = "";
      $arrParams = array();
      $arrRS = null;
      $arrLinea = null;
      $bRet = false;
      if ($this->nClaveProducto < 1)
         throw new Exception("Producto->buscar: faltan datos");
      else {
         if ($oAccesoDatos->conectar()) {
            $sQuery = "SELECT t1.nClaveProducto, t1.sNombre, t1.nLinea, t1.nTipo, t1.sDescripcion,
                               t1.sSabor, t1.sImagen, t1.sPrecio, t1.bActivo
                                  FROM productos t1
                        WHERE t1.nClaveProducto = :cve
                     ";
            $arrParams = array(":cve" => $this->nClaveProducto);
            $arrRS = $oAccesoDatos->ejecutarConsulta($sQuery, $arrParams);
            $oAccesoDatos->desconectar();
            if ($arrRS) {
               $this->setNombre($arrRS[0][1]);
               $this->setLinea($arrRS[0][2]);
               $this->setTipo($arrRS[0][3]);
               $this->setDescripcion($arrRS[0][4]);
               $this->setSabor($arrRS[0][5]);
               $this->setImg($arrRS[0][6]);
               $this->setPrecio($arrRS[0][7]);
               $this->setActivo($arrRS[0][8]);
               $bRet = true;
            }
         }
      }
      return $bRet;
   }

   public function insertar(): int
   {
      $oAccesoDatos = new AccesoDatos();
      $sQuery = "";
      $arrParams = array();
      $nRet = -1;
      if (
         empty($this->sNombre) || empty($this->sDescripcion) ||
         empty($this->sImagen) || $this->sPrecio < 1 || $this->nTipo < 1
         || $this->nLineas < 1 || empty($this->sSabor)
      )

         throw new Exception("Producto->insertar: faltan datos");
      else {
         $sQuery = "INSERT INTO Productos (sNombre, nLinea, nTipo,
                      sDescripcion, sSabor, sImagen, sPrecio, bActivo)
                      VALUES (:nom, :linea, :tipo, :descripcion, :sabor,
                            :img, :precio, true);";
         $arrParams = array(
            ":nom" => $this->sNombre,
            ":linea" => $this->nLineas,
            ":tipo" => $this->nTipo,
            ":descripcion" => $this->sDescripcion,
            ":sabor" => $this->sSabor,
            ":img" => $this->sImagen,
            ":precio" => $this->sPrecio
         );
         if ($oAccesoDatos->conectar()) {
            $nRet = $oAccesoDatos->ejecutarComando($sQuery, $arrParams);
            $oAccesoDatos->desconectar();
         }
      }
      return $nRet;
   }

   public function modificar(): int
   {
      $oAccesoDatos = new AccesoDatos();
      $sQuery = "";
      $arrParams = array();
      $nRet = -1;
      if (
         $this->nClaveProducto<1 ||
         empty($this->sNombre) || empty($this->sDescripcion) ||
         empty($this->sImagen) || $this->sPrecio < 1 || $this->nTipo < 1
         || $this->nLineas < 1 || empty($this->sSabor)
      )

         throw new Exception("Producto->insertar: faltan datos");
      else {
         $sQuery = "UPDATE  Productos
                     SET 
                     sNombre = :nom, 
                     nLinea = :linea,
                     nTipo = :tipo,
                     sDescripcion = :descripcion,
                     sSabor = :sabor, 
                     sImagen = :img,
                     sPrecio = :precio,
                     bActivo = true
                     WHERE nClaveProducto = :clave
                       ;";
         $arrParams = array(
            ":nom" => $this->sNombre,
            ":linea" => $this->nLineas,
            ":tipo" => $this->nTipo,
            ":descripcion" => $this->sDescripcion,
            ":sabor" => $this->sSabor,
            ":img" => $this->sImagen,
            ":precio" => $this->sPrecio,
            ":clave" =>$this->nClaveProducto
         );
         if ($oAccesoDatos->conectar()) {
            $nRet = $oAccesoDatos->ejecutarComando($sQuery, $arrParams);
            $oAccesoDatos->desconectar();
         }
      }
      return $nRet;
   }

   public function eliminar():int{
      $oAccesoDatos=new AccesoDatos();
      $sQuery="";
      $arrParams = array();
      $nRet = -1;
         if ($this->nClaveProducto<1)
            throw new Exception("Producto->eliminar: faltan datos");
         else{
            $sQuery = "UPDATE productos 
                  SET bActivo = false
                  WHERE nClaveProducto = :cve
                  AND bActivo = true;";
            $arrParams = array(":cve"=>$this->nClaveProducto);
            if ($oAccesoDatos->conectar()){
               $nRet = $oAccesoDatos->ejecutarComando($sQuery, $arrParams);
               $oAccesoDatos->desconectar();
            }
         }
         return $nRet;
   }
}
