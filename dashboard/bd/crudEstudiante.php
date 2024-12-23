<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    // Recepción de los datos enviados mediante POST desde el JS   
    $DNI = (isset($_POST['DNI_estudiante'])) ? $_POST['DNI_estudiante'] : '';
    $nombres = (isset($_POST['nombres_estudiante'])) ? $_POST['nombres_estudiante'] : '';
    $apellidos = (isset($_POST['apellidos_estudiante'])) ? $_POST['apellidos_estudiante'] : '';
    $telefono = (isset($_POST['telefono_estudiante'])) ? $_POST['telefono_estudiante'] : '';
    
    $opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';
    $id_estudiante = (isset($_POST['id_estudiante'])) ? $_POST['id_estudiante'] : '';
    
    switch ($opcion) {
        case 1: 
            $consulta = "INSERT INTO estudiantes (
                DNI_estudiante,
                nombres_estudiante,
                apellidos_estudiante,
                telefono_estudiante
            )
            VALUES ('$DNI', '$nombres', '$apellidos', '$telefono')";

            $resultado = $conexion->prepare($consulta);
            $resultado->execute();


            $id_estudiante = $conexion->lastInsertId();
            
            echo json_encode(array( 'mensaje' => 'La inserción se realizó correctamente',
                                            'id_estudiante' => $id_estudiante));
            break;
        case 2: //modificación
            $consulta = "UPDATE estudiantes 
                        SET DNI_estudiante='$DNI', 
                            nombres_estudiante='$nombres',
                            apellidos_estudiante = '$apellidos',
                            telefono_estudiante='$telefono'
                        WHERE id_estudiante = '$id_estudiante' ";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();

            echo json_encode(array("mensaje" => "La actualizacion se realizó correctamente"));
            break;
        case 3: //baja
            $consulta = "DELETE FROM estudiantes
                        WHERE id_estudiante = '$id_estudiante' ";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();

            $consulta = "DELETE FROM estudiantes_grupos 
                        WHERE id_estudiante='$id_estudiante' ";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            echo json_encode(array("mensaje" => "La eliminación se realizó correctamente"));
            break;
    }

    $conexion = NULL;

?>