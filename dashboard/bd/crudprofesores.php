<?php
include_once '../bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();
// Recepción de los datos enviados mediante POST desde el JS   

$nombres = (isset($_POST['nombresProfesores'])) ? $_POST['nombresProfesores'] : '';
$apellidos = (isset($_POST['apellidosProfesores'])) ? $_POST['apellidosProfesores'] : '';
$telefono = (isset($_POST['telefonoProfesores'])) ? $_POST['telefonoProfesores'] : '';
$correo = (isset($_POST['correoProfesores'])) ? $_POST['correoProfesores'] : '';
$DNI = (isset($_POST['DNIProfesores'])) ? $_POST['DNIProfesores'] : '';
$fNacimiento = (isset($_POST['fNacimientoProfesores'])) ? $_POST['fNacimientoProfesores'] : '';


$opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';
$id = (isset($_POST['id'])) ? $_POST['id'] : '';


switch ($opcion) {
    case 1: //alta
        $consulta = "INSERT INTO profesores
            (DNI, nombres, apellidos, telefono, correo, fNacimiento)
            VALUES('$DNI', '$nombres', '$apellidos', '$telefono', '$correo', '$fNacimiento') ";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $idProfesor = $conexion->lastInsertId();
            
        echo json_encode(array( 'mensaje' => 'La inserción se realizó correctamente',
                                        'id_estudiante' => $idProfesor));
        break;
    case 2: //modificación
        $consulta = "UPDATE profesores
            SET DNI='$DNI', nombres='$nombres',
                apellidos = '$apellidos', telefono='$telefono',
                correo='$correo', fNacimiento='$fNacimiento'
            WHERE id='$id' ";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();

        echo json_encode(array("mensaje" => "La actualizacion se realizó correctamente"));
        break;
    case 3: //baja
        $consulta = "DELETE FROM profesores WHERE id='$id' ";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        echo json_encode(array("mensaje" => "La eliminación se realizó correctamente"));
        break;

}

$conexion = NULL;

?>