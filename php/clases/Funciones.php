<?php namespace funcionesClases;
class Funciones {
  function doQuery($query, $params=array()){
  	$conexion=\conexionClases\Conexion::conectar();
    try {
      if (empty($params)){
          $statement=$conexion->query($query);
          $conexion=null;
          return $statement;
      } else {
        $statement = $conexion->prepare($query);
        $statement->execute($params);
        $conexion=null;
        return $statement;
      }
    } catch(\PDOException $e){
      echo $e->getMessage();
    }
  }
  
  function doUpdate($datos){
      $conexion=\conexionClases\Conexion::conectar();
      try {
          $conexion->beginTransaction();
          for ($i=0; $i < count($datos); $i++) { 
            $statement = $conexion->prepare($datos[$i]['query']);
            $statement->execute($datos[$i]['params']);
          }
          $conexion->commit();
          $conexion=null;
          return $statement;
        }
       catch(\PDOException $e){
        $conexion->rollBack();
        echo $e->getMessage();
      } 
  }

  function doDelete($query, $params=array()){
    $conexion=\conexionClases\Conexion::conectar();
    try {
        $conexion->beginTransaction();
        $statement = $conexion->prepare($query);
        $statement->execute($params);
        $conexion->commit();
        $conexion=null;
        return $statement;
    } catch(\PDOException $e){
      $conexion->rollBack();
      echo $e->getMessage();
    }
  }

  function doInsert($datos){
    $conexion=\conexionClases\Conexion::conectar();
    try {
        $conexion->beginTransaction();
        for ($i=0; $i < count($datos); $i++) { 
          $statement = $conexion->prepare($datos[$i]['query']);
          $statement->execute($datos[$i]['params']);
        }
          $conexion->commit();
          $conexion=null;
      }
     catch(\PDOException $e){
      $conexion->rollBack();
      echo $e->getMessage();
    }
  }

  function login($email,$password){
    Funciones::logout();
    if (!isset($_SESSION['id'])) {
      $params=array(':email'=> $email);
      $sql ="SELECT id, tipo, password,fecha_sesion as fecha, nombre FROM usuario
        WHERE email=:email";
      $statement=Funciones::doQuery($sql,$params);
      $datosUsuario=array();
      while ($aux=$statement->fetchObject()) {
        $datosUsuario['permisos'] = $aux->tipo;
        $datosUsuario['id']=$aux->id;
        $datosUsuario['password']=$aux->password;
        $datosUsuario['nombre']=$aux->nombre;
        $datosUsuario['fecha']=$aux->fecha;
      }
      if (isset($datosUsuario['id'])) {
        if (password_verify($password,$datosUsuario['password'])) {
          session_start();
          $_SESSION['permisos']=$datosUsuario['permisos'];
          $_SESSION['id']=$datosUsuario['id'];
          $_SESSION['nombre']=$datosUsuario['nombre'];
          $_SESSION['fecha']=$datosUsuario['fecha'];
          $sql = 'UPDATE usuario SET fecha_sesion = CURRENT_TIMESTAMP where id=:id';
          $datos=array();
          $params=array(':id'=>$_SESSION['id']);
          array_push($datos, array('query'=>$sql,'params'=>$params));
          Funciones::doInsert($datos);
          return $_SESSION;
        } else return false;
      } else return false;
    }    
  }

  function logout(){
    session_start();
    session_unset();
    session_destroy();
    $_SESSION=array();
  }
      
}
  




 ?>