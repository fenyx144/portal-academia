<?php
include_once '../bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$id = (isset($_POST['id'])) ? $_POST['id'] : '';

// Realizar la consulta SQL para obtener los cursos del nivel seleccionado
$consulta = "SELECT id FROM horario WHERE idProfesor = '$id'";
$resultado = $conexion->prepare($consulta);
$resultado->execute();


// Obtener los resultados como un arreglo asociativo
$horas = $resultado->fetchAll(PDO::FETCH_ASSOC);

// Devolver los cursos como respuesta en formato JSON
echo json_encode($horas);
$conexion=NULL;
?>