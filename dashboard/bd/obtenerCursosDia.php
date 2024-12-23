<?php
include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $dia = (isset($_POST['dia'])) ? $_POST['dia'] : '';
    
    // Realizar la consulta SQL para obtener los cursos del nivel seleccionado
    $consulta = "SELECT CL.id, P.id AS idProfesor, P.nombres, P.apellidos, C.nombre AS nombre_curso, CL.hora_inicio, CL.hora_fin, A.nombre AS nombre_alumno, CL.nAlumnos, CL.costo, CL.pago, CL.observaciones
                FROM clases AS CL
                JOIN profesores AS P 
                    ON P.id = CL.idProfesor
                JOIN cursos AS C 
                    ON C.id = CL.idCurso
                JOIN alumnos AS A 
                    ON A.id = CL.idAlumno
                WHERE CL.dia = '$dia'";

    
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();


    // Obtener los resultados como un arreglo asociativo
    $horas = $resultado->fetchAll(PDO::FETCH_ASSOC);

    // Devolver los cursos como respuesta en formato JSON
    echo json_encode($horas);
    $conexion=NULL;
?>