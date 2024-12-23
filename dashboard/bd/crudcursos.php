
<?php
include_once '../bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
// Recepción de los datos enviados mediante POST desde el JS   

$nombre = (isset($_POST['nombre'])) ? $_POST['nombre'] : '';
$nivel = (isset($_POST['nivel'])) ? $_POST['nivel'] : '';
$pago1p = (isset($_POST['pago1p'])) ? $_POST['pago1p'] : '';
$pago2p = (isset($_POST['pago2p'])) ? $_POST['pago2p'] : '';
$pago3p = (isset($_POST['pago3p'])) ? $_POST['pago3p'] : '';
$opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';
$id = (isset($_POST['id'])) ? $_POST['id'] : '';

switch($opcion){
    case 1: //alta
        $consulta = "INSERT INTO cursos
            (nombre, idnivel, pago1p, pago2p, pago3p)
        VALUES('$nombre', '$nivel', '$pago1p', '$pago2p', '$pago3p') ";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();

        $lastId = $conexion->lastInsertId();
        echo $lastId;
        //echo json_encode(array("mensaje" => "La inserción se realizó correctamente"));  
        break;
    case 2: //modificación
        $consulta = "UPDATE cursos
                    SET nombre='$nombre', idnivel='$nivel',
                        pago1p='$pago1p', pago2p='$pago2p',
                        pago3p='$pago3p'
                    WHERE id='$id' ";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        echo json_encode(array("mensaje" => "La modición se realizó correctamente"));
        break;        
    case 3://baja
        $consulta = "DELETE FROM cursos WHERE id='$id' ";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        echo json_encode(array("mensaje" => "La eliminación se realizó correctamente"));
        break;
}
$conexion = NULL;
?>