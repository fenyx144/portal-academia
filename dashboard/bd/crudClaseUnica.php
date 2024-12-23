<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    // Recepción de los datos enviados mediante POST desde el JS   
    $request_body = file_get_contents('php://input');
    
    //Decodificar el JSON a un arreglo asociativo de PHP
    
    $data = json_decode($request_body, true);
    if ($data['opcion'] == 1) {
        $fecha = $data['fecha'];
        $idEstudiante = $data['idEstudiante'];
        $idProfesor = $data['idProfesor'];
        $idCurso = $data['idCurso'];
        $horaInicio = $data['horaInicio']; 
        $horaFin = $data['horaFin'];
        $numEstudiantes = $data['numEstudiantes'];
        $costo = $data['costo'];
        $observaciones = $data['observaciones'];
        $pago = $data['pago'];
        if ($idEstudiante == -1 ) {
            $DNI = $data['DNIEstudiante'];
            $nombres = $data['nombresEstudiante'];
            $apellidos = $data['apellidosEstudiante'];
            $telefono = $data['telefonoEstudiante'];
            
            $consulta = "INSERT INTO estudiantes (DNI_estudiante, nombres_estudiante, apellidos_estudiante, telefono_estudiante) 
                VALUES('$DNI', '$nombres', '$apellidos', '$telefono') ";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();

            $idEstudiante = $conexion->lastInsertId();
        }

        $consulta = "INSERT INTO clases (fecha, id_profesor, id_curso, hora_inicio, hora_fin, id_estudiante, num_estudiantes, costo, observaciones, pago)
            VALUES('$fecha', '$idProfesor', '$idCurso', '$horaInicio', '$horaFin', '$idEstudiante', '$numEstudiantes', '$costo', '$observaciones', '$pago') ";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        echo json_encode(
            array(
                "mensaje" => "La inserción se realizó correctamente",
               'data' => $data
        ));
    }
    /*
    $dia = (isset($_POST['fecha'])) ? $_POST['fecha'] : '';
    $idProfesor = (isset($_POST['idProfesor'])) ? $_POST['idProfesor'] : '';
    $idCurso = (isset($_POST['idCurso'])) ? $_POST['idCurso'] : '';
    $hora_inicio = (isset($_POST['hora_inicio'])) ? $_POST['hora_inicio'] : '';    
    $hora_fin = (isset($_POST['hora_fin'])) ? $_POST['hora_fin'] : '';

    $tipo = (isset($_POST['tipo'])) ? $_POST['tipo'] : '';
    $nombreAlumno = (isset($_POST['nombreAlumno'])) ? $_POST['nombreAlumno'] : '';
    $nAlumnos = (isset($_POST['nAlumnos'])) ? $_POST['nAlumnos'] : '';

    $costo = (isset($_POST['costo'])) ? $_POST['costo'] : '';
    $observaciones = (isset($_POST['observaciones'])) ? $_POST['observaciones'] : '';
    $estado_pago = (isset($_POST['estado_pago'])) ? $_POST['estado_pago'] : '';
    
    $consulta = "INSERT INTO alumnos (nombre) VALUES('$nombreAlumno') ";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $idAlumno = $conexion->lastInsertId();

    $consulta = "INSERT INTO clases (dia, idProfesor, idCurso, hora_inicio, hora_fin, tipo, idAlumno, nAlumnos, costo, observaciones, pago) VALUES('$dia', '$idProfesor', '$idCurso', '$hora_inicio', '$hora_fin', '$tipo', '$idAlumno', '$nAlumnos', '$costo', '$observaciones', '$estado_pago') ";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    */
    

    $conexion = NULL;
?>