<?php

    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    // Recepción de los datos enviados mediante POST desde el JS   
    
    $DNI = (isset($_POST['DNI'])) ? $_POST['DNI'] : '';
    $id_grupo = (isset($_POST['id_grupo'])) ? $_POST['id_grupo'] : '';
    /*$consulta = "SELECT *, COUNT(*) AS total_registros
                FROM estudiantes
                WHERE DNI_estudiante = '$DNI'";
    
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $registro_estudiante = $resultado->fetch();

    echo json_encode($registro_estudiante);

    $conexion = NULL;
    */
    $consulta = "
            SELECT 
            e.*,
            eg.id AS id_estudiantes_grupos            
        FROM estudiantes e
        LEFT JOIN estudiantes_grupos eg ON e.id_estudiante = eg.id_estudiante AND eg.id_grupo = '$id_grupo'
        WHERE e.DNI_estudiante = '$DNI'
        LIMIT 1;
    ";

    $resultado = $conexion->prepare($consulta);
    //$resultado->bindParam(":estudiante_id", $estudiante_id, PDO::PARAM_INT);
    $resultado->execute();

    // Obtener el resultado
    $result = $resultado->fetch();

    // Procesar el resultado
    if (!$result) {
        echo json_encode(array(
            "mensaje" => "El estudiante no se encuentra en ninguna tabla.",
            "status" => 1
        ));
    } elseif ($result['id_estudiante'] && !$result['id_estudiantes_grupos']) {
        echo json_encode(array(
            "mensaje" => "El estudiante se encuentra solo en la tabla estudiantes. ID del estudiante: ",
            "status" => 2,
            "estudiante" => $result
        ));
    } else {
        echo json_encode(array(
            "mensaje" => "El estudiante se encuentra en ambas tablas. ID del estudiante: ",
            "status" => 3,
            "estudiante" => $result
        ));
    }

?>