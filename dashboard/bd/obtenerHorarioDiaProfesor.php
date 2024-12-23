<?php
include_once '../bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$dia = (isset($_POST['dia'])) ? $_POST['dia'] : '';
$fecha = (isset($_POST['fecha'])) ? $_POST['fecha'] : '';

// Realizar la consulta SQL para obtener los cursos del nivel seleccionado
$consulta = "SELECT id FROM horario 
            WHERE idProfesor = '$id' AND dia = '$dia' ";
$resultado = $conexion->prepare($consulta);
$resultado->execute();

$horas = $resultado->fetchAll(PDO::FETCH_ASSOC);

///
$consulta = "SELECT id FROM clases 
            WHERE idProfesor = '$id' AND dia = '$fecha' ";
$resultado = $conexion->prepare($consulta);
$resultado->execute();

$horas2 = $resultado->fetchAll(PDO::FETCH_ASSOC);

///
$consulta = "SELECT id FROM clases_grupo
            WHERE id_profesor = '$id' AND fecha = '$fecha' ";
$resultado = $conexion->prepare($consulta);
$resultado->execute();

$horas3 = $resultado->fetchAll(PDO::FETCH_ASSOC);
$response = array(
    'lista1' => $horas,
    'lista2' => $horas2,
    'lista3' => $horas3
);
// Devolver los cursos como respuesta en formato JSON
echo json_encode($response);
$conexion=NULL;
?>