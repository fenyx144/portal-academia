<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    // Obtener el ID del nivel seleccionado desde la solicitud AJAX
    $nivelId = $_GET['nivelId'];

    // Realizar la consulta SQL para obtener los cursos del nivel seleccionado
    $consulta = "SELECT id, nombre FROM cursos WHERE idnivel = '$nivelId'";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();


    // Obtener los resultados como un arreglo asociativo
    $cursos = $resultado->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los cursos como respuesta en formato JSON
    echo json_encode($cursos);
?>