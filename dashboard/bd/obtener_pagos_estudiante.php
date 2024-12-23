<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $id = (isset($_POST['id'])) ? $_POST['id'] : '';
    //COUNT(*) AS total_registros
    // Realizar la consulta SQL para obtener los cursos del nivel seleccionado
    $consulta = "SELECT *
                FROM pagos_estudiantes pe
                WHERE pe.id_estudiante_grupo = '$id'";
    
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();

    // Obtener los resultados como un arreglo asociativo
    $pagos_estudiante = $resultado->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los cursos como respuesta en formato JSON
    echo json_encode($pagos_estudiante);
    $conexion=NULL;
?>