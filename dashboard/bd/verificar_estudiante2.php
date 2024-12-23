<?php

    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    // Recepción de los datos enviados mediante POST desde el JS   
    
    $DNI = (isset($_POST['DNI'])) ? $_POST['DNI'] : '';
    $consulta = "
        SELECT 
            e.*
        FROM estudiantes e
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
    } else{
        echo json_encode(array(
            "mensaje" => "El estudiante se encuentra en la tabla estudiantes.",
            "status" => 2,
            "estudiante" => $result
        ));
    } 

?>