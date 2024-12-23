<?php
    session_start();
    include_once 'conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    //recepción de datos enviados mediante POST desde ajax
    $usuario = (isset($_POST['usuario'])) ? $_POST['usuario'] : '';
    $password = (isset($_POST['password'])) ? $_POST['password'] : '';
    //encripto la clave enviada por el usuario
    $pass = md5($password);

    $consulta = "SELECT * FROM usuarios 
        WHERE usuario='$usuario' AND password='$pass' ";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();

    if($resultado->rowCount() >= 1){
        $data = $resultado->fetchAll(PDO::FETCH_ASSOC);
        $_SESSION["s_usuario"] = $usuario;
    }else{
        $_SESSION["s_usuario"] = null;
        $data=null;
    }
    print json_encode($pass);
    $conexion=null;
?>
