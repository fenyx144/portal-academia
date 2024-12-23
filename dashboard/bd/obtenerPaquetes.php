<?php

    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    // Recepción de los datos enviados mediante POST desde el JS   
    
    $idEstudiante = (isset($_POST['idEstudiante'])) ? $_POST['idEstudiante'] : '';
    
    $consulta = "
            SELECT p.id, p.num_clases, p.num_clases_reg
        FROM paquetes p
        WHERE p.id_estudiante = '$idEstudiante' AND p.num_clases_reg < p.num_clases ;
    ";

    $resultado = $conexion->prepare($consulta);
    //$resultado->bindParam(":estudiante_id", $estudiante_id, PDO::PARAM_INT);
    $resultado->execute();

    // Obtener el resultado
    $data = $resultado->fetchAll();

    // Devolver los resultados en formato JSON
    
    echo json_encode($data);
?>