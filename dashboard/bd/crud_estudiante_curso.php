<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    // Recepción de los datos enviados mediante POST desde el JS   
    $DNI = (isset($_POST['DNI_estudiante'])) ? $_POST['DNI_estudiante'] : '';
    $nombres = (isset($_POST['nombres_estudiante'])) ? $_POST['nombres_estudiante'] : '';
    $apellidos = (isset($_POST['apellidos_estudiante'])) ? $_POST['apellidos_estudiante'] : '';
    $telefono = (isset($_POST['telefono_estudiante'])) ? $_POST['telefono_estudiante'] : '';
    $correo = (isset($_POST['correo_estudiante'])) ? $_POST['correo_estudiante'] : '';
    $fechaInicio = (isset($_POST['fecha_inicio_estudiante'])) ? $_POST['fecha_inicio_estudiante'] : '';
    
    
    $opcion = (isset($_POST['opcion_estudiante'])) ? $_POST['opcion_estudiante'] : '';
    $id_estudiante = (isset($_POST['id_estudiante'])) ? $_POST['id_estudiante'] : '';
    $id_grupo = (isset($_POST['id_grupo'])) ? $_POST['id_grupo'] : '';
    switch ($opcion) {
        case 1: //alta
            $consulta = "INSERT INTO estudiantes (DNI_estudiante, nombres_estudiante, apellidos_estudiante, telefono_estudiante, correo_estudiante, fecha_inicio_estudiante) 
                        VALUES('$DNI', '$nombres', '$apellidos', '$telefono', '$correo', '$fechaInicio') ";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();

            $id_estudiante = $conexion->lastInsertId();

            $consulta = "INSERT INTO estudiantes_grupos (id_estudiante, id_grupo)
                        VALUES ('$id_estudiante', '$id_grupo')";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            $id_estudiante_grupo = $conexion->lastInsertId();

            echo json_encode(
                array( 
                                'mensaje' => 'La inserción se realizó correctamente',
                                'id_estudiante' => $id_estudiante,                                
                                'id_estudiante_grupo' => $id_estudiante_grupo)
                            );
            break;
        case 2:
            $consulta = "UPDATE estudiantes 
                        SET DNI_estudiante='$DNI', nombres_estudiante='$nombres', 
                            apellidos_estudiante = '$apellidos', telefono_estudiante='$telefono', 
                            correo_estudiante='$correo', fecha_inicio_estudiante='$fechaInicio' 
                        WHERE id_estudiante='$id_estudiante' ";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();

            $consulta = "INSERT INTO estudiantes_grupos (id_estudiante, id_grupo)
                        VALUES ('$id_estudiante', '$id_grupo')";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            $id_estudiante_grupo = $conexion->lastInsertId();

            echo json_encode(array( 
                'mensaje' => 'La inserción se realizó correctamente',
                                    //'opcion' => 1, 
                'id_estudiante_grupo' => $id_estudiante_grupo));
            break;

        case 3: //modificación
            $id_estudiante_grupo = (isset($_POST['id_estudiante_grupo'])) ? $_POST['id_estudiante_grupo'] : '';
            $consulta = "UPDATE estudiantes 
                        SET DNI_estudiante='$DNI', nombres_estudiante='$nombres', 
                            apellidos_estudiante = '$apellidos', telefono_estudiante='$telefono', 
                            correo_estudiante='$correo', fecha_inicio_estudiante='$fechaInicio' 
                        WHERE id_estudiante='$id_estudiante' ";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();

            echo json_encode(array(
                "mensaje" => "La edición se realizó correctamente",  
                //'opcion' => 1
            ));
            break;
        case 4: //baja
            $id_estudiante_grupo = (isset($_POST['id_estudiante_grupo'])) ? $_POST['id_estudiante_grupo'] : '';
            
            $consulta = "DELETE FROM estudiantes_grupos 
                        WHERE id='$id_estudiante_grupo' ";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            echo json_encode(array(
                "mensaje" => "La eliminación se realizó correctamente"
            ));
            break;
    }

    $conexion = NULL;

?>