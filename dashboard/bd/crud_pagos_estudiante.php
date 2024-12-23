<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $id = (isset($_POST['id_estudiante_grupo'])) ? $_POST['id_estudiante_grupo'] : '';
    $numero_pagos = (isset($_POST['numero_pagos'])) ? $_POST['numero_pagos'] : '';
    
    // Realizar la consulta SQL para obtener los cursos del nivel seleccionado
    $consulta = "DELETE
                FROM pagos_estudiantes
                WHERE id_estudiante_grupo = '$id'";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    for($i = 1; $i <= $numero_pagos; $i++) {
        $fecha = (isset($_POST['fechaEstudiantePago'.$i])) ? $_POST['fechaEstudiantePago'.$i] : '';
        $monto = (isset($_POST['monto'.$i])) ? $_POST['monto'.$i] : '';
        $estado = (isset($_POST['estadoPago'.$i])) ? $_POST['estadoPago'.$i] : '';
        $consulta = "INSERT INTO pagos_estudiantes (id_estudiante_grupo, numero_pago, fecha, monto, estado) 
                        VALUES('$id', '$i', '$fecha', '$monto', '$estado')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
    }
    // Devolver los cursos como respuesta en formato JSON
    echo json_encode(array("mensaje" => "La inserción se realizó correctamente"));  
    $conexion=NULL;
?>