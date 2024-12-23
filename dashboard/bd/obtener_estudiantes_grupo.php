<?php
include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $id_grupo = (isset($_POST['id_grupo'])) ? $_POST['id_grupo'] : '';
    
    // Realizar la consulta SQL para obtener los cursos del nivel seleccionado
    $consulta = "SELECT eg.id id_estudiante_grupo, e.*, pe.id id_pago, pe.fecha, pe.monto, pe.estado
                FROM estudiantes_grupos eg
                JOIN estudiantes AS e 
                    ON e.id_estudiante = eg.id_estudiante
                LEFT JOIN pagos_estudiantes pe
                    ON pe.id_estudiante_grupo = eg.id
                WHERE eg.id_grupo = '$id_grupo'
                ORDER BY eg.id ASC, pe.numero_pago ASC";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();

    $estudiantes_grupo = array();
    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
        $id_estudiante_grupo = $fila['id_estudiante_grupo'];
         // Si el estudiante aún no existe en el array, créalo con un array de pagos vacío
        if (!isset($estudiantes_grupo[$id_estudiante_grupo])) {
            $estudiantes_grupo[$id_estudiante_grupo] = array(
                'id_estudiante' => $fila['id_estudiante'],
                'id_estudiante_grupo' => $fila['id_estudiante_grupo'],
                'DNI_estudiante' => $fila['DNI_estudiante'],
                'nombres_estudiante' => $fila['nombres_estudiante'],
                'apellidos_estudiante' => $fila['apellidos_estudiante'],
                'telefono_estudiante' => $fila['telefono_estudiante'],
                'correo_estudiante' => $fila['correo_estudiante'],
                'fecha_inicio_estudiante' => $fila['fecha_inicio_estudiante'],
                'pagos' => array() // Inicializa un array de pagos vacío
            );
        }
        if (!empty($fila['id_pago'])) {
            $estudiantes_grupo[$id_estudiante_grupo]['pagos'][] = array(
                'fecha' => $fila['fecha'],
                'monto' => $fila['monto'],
                'estado' => $fila['estado'],
            );
        }
    }   
    
    // Devolver los cursos como respuesta en formato JSON
    echo json_encode($estudiantes_grupo);
    $conexion=NULL;
?>