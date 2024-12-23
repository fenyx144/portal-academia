<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    
    $idCurso = (isset($_POST['idCurso'])) ? $_POST['idCurso'] : '';
    $consulta = "SELECT P.id, P.nombres, P.apellidos 
                FROM profesores AS P 
                    JOIN profesores_cursos  AS PC 
                    ON PC.idProfesor=P.id 
                WHERE PC.idCurso= '$idCurso' ";
    
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();

    // Obtener los resultados como un arreglo asociativo
    $profesores = $resultado->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los cursos como respuesta en formato JSON
    echo json_encode($profesores);
?>