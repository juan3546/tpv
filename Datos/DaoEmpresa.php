<?php
require_once 'Conexion.php'; /*importa Conexion.php*/
require_once '../Pojos/PojoEmpresa.php';


class DaoEmpresa
{
  private $conexion; /*Crea una variable conexion*/
  private function conectar()
  {
    try{
     $this->conexion = Conexion::abrirConexion(); /*inicializa la variable conexion, llamando a la funcion abrirConexion(); de la clase Conexion por medio de una instancia*/
   }
   catch(Exception $e)
   {
     die($e->getMessage()); /*Si la conexion no se establece se cortara el flujo enviando un mensaje con el error*/
   }
 }
 public function registrarEmpresa(PojoEmpresa $obj)
 {
  $clave=0;
  try 
  {
   $sql = "INSERT INTO empresa(id_empresa, id_nombre_precio, nombre, RFC, estatus) values(?, ?, ?, ?, ?)";

   $this->conectar();
   $this->conexion->prepare($sql)
   ->execute(
    array(
     $obj->id_empresa,
     $obj->id_nombre_precio,
     $obj->nombre,
     $obj->rfc,
     $obj->estatus
   )
  );
   $clave=$this->conexion->lastInsertId();
   return $clave;
 } catch (Exception $e) 
 {
   return $clave;
 }finally{
    /*
    En caso de que se necesite manejar transacciones, no deberá desconectarse mientras la transacción deba persistir
    */
    Conexion::cerrarConexion();
  }
}
  public function eliminarEmpresa($id)
  {
   try 
    {
      $this->conectar();    
      $sentenciaSQL = $this->conexion->prepare("DELETE FROM empresa WHERE id_empresa = ?");                             
      $sentenciaSQL->execute([$id]);
      return true;
    } catch (Exception $e) 
    {
      return false;
    }finally{
      Conexion::cerrarConexion();
    }
  }

  public function getIdEmpresa($id)
  {
   //$idEmpresa = 0;
   try
   {
      $this->conectar();
    
      $lista = array(); /*Se declara una variable de tipo  arreglo que almacenará los registros obtenidos de la BD*/
      $sentenciaSQL = $this->conexion->prepare("SELECT id_empresa from empresa WHERE nombre = ? AND estatus='1'"); /*Se arma la sentencia sql para seleccionar todos los registros de la base de datos*/
      $sentenciaSQL->execute([$id]);/*Se ejecuta la sentencia sql, retorna un cursor con todos los elementos*/
      /*Se recorre el cursor para obtener los datos*/
      foreach($sentenciaSQL->fetchAll(PDO::FETCH_OBJ) as $fila)
      {
        $obj = new PojoEmpresa();
        $obj->id_empresa = $fila->id_empresa;
      }
    return $obj->id_empresa;
    }catch(Exception $e){
      echo $e->getMessage();
      return null;
    } finally {
      Conexion::cerrarConexion();
    }
  }
    
  public function getDatosEmpresa()
  {
    try{
      $this->conectar();
      $lista = array(); /*Se declara una variable de tipo  arreglo que almacenará los registros obtenidos de la BD*/
      $sentenciaSQL = $this->conexion->prepare("SELECT id_empresa, id_nombre_precio, nombre, RFC, estatus FROM empresa"); /*Se arma la sentencia sql para seleccionar todos los registros de la base de datos*/
      $sentenciaSQL->execute();/*Se ejecuta la sentencia sql, retorna un cursor con todos los elementos*/
      /*Se recorre el cursor para obtener los datos*/
      foreach($sentenciaSQL->fetchAll(PDO::FETCH_OBJ) as $fila)
      {
        $obj = new PojoEmpresa();
        $obj->id_empresa = $fila->id_empresa;
        $obj->id_nombre_precio = $fila->id_nombre_precio;
        $obj->nombre = $fila->nombre;
        $obj->rfc = $fila->RFC;
        $obj->estatus = $fila->estatus;
        $lista[] = $obj;
    }
    return $lista;
    }catch(Exception $e){
      echo $e->getMessage();
      return null;
    }finally {
      Conexion::cerrarConexion();
    }
  }
  public function editarEmpresa(PojoEmpresa $obj)
  {
    $sql = "UPDATE empresa SET 
    id_nombre_precio= ?,
    nombre= ?,
    RFC= ?,
    estatus= ?
    WHERE id_empresa = ?";
    $this->conectar();
    $sentenciaSQL = $this->conexion->prepare($sql); 
    $sentenciaSQL->execute(
    array(
      $obj->id_nombre_precio,
      $obj->nombre,
      $obj->rfc,
      $obj->estatus,
      $obj->id_empresa
      ));
    return true;
    Conexion::cerrarConexion();
  }

  public function obtenerEstatus()
  {
    try
    {
      $this->conectar();
      $lista = array(); /*Se declara una variable de tipo  arreglo que almacenará los registros obtenidos de la BD*/
      $sentenciaSQL = $this->conexion->prepare("SELECT estatus FROM empresa"); /*Se arma la sentencia sql para seleccionar todos los registros de la base de datos*/
      $sentenciaSQL->execute();/*Se ejecuta la sentencia sql, retorna un cursor con todos los elementos*/
      /*Se recorre el cursor para obtener los datos*/
      foreach($sentenciaSQL->fetchAll(PDO::FETCH_OBJ) as $fila)
      {
        $obj = new PojoEmpresa();
        $obj->estatus = $fila->estatus;
        $lista[] = $obj;
      }
        return $lista;
    }catch(Exception $e){
      echo $e->getMessage();
      return null;
    }finally {
      Conexion::cerrarConexion();
    }
  }
}