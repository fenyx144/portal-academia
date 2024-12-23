<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    // Obtener el ID del nivel seleccionado desde la solicitud AJAX
    
    $consulta = "SELECT C.id, C.nombre AS nombre_curso, N.nombre AS nombre_nivel 
                FROM cursos AS C  
                JOIN nivel AS N
                    ON C.idnivel = N.id";
    
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();


    // Obtener los resultados como un arreglo asociativo
    $cursos = $resultado->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los cursos como respuesta en formato JSON
    echo json_encode($cursos);
?>