<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    // Obtener el ID del nivel seleccionado desde la solicitud AJAX
    $idProfesor = (isset($_POST['id'])) ? $_POST['id'] : '';
    $idNivel =  (isset($_POST['idNivel'])) ? $_POST['idNivel'] : '';
    $opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';

    switch($opcion) {
        case 1:
            $consulta = "SELECT C.id AS id_curso, 
                                C.nombre AS nombre_curso, 
                                nivel.nombre AS nombre_nivel, 
                                nivel.id AS id_nivel,
                                P.id AS id_curso_profesor 
                        FROM profesores_cursos AS P 
                        JOIN cursos AS C 
                            ON P.idCurso = C.id 
                        JOIN nivel 
                            ON C.idnivel = nivel.id 
                        WHERE P.idProfesor = '$idProfesor'";
            
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            // Obtener los resultados como un arreglo asociativo
            $cursos = $resultado->fetchAll(PDO::FETCH_ASSOC);

            // Devolver los cursos como respuesta en formato JSON
            echo json_encode($cursos);
            break;
        case 2:
            $consulta = "SELECT C.id AS id_curso, 
                                C.nombre AS nombre_curso, 
                                nivel.nombre AS nombre_nivel, 
                                nivel.id AS id_nivel,
                                P.id AS id_curso_profesor 
                        FROM profesores_cursos AS P 
                        JOIN cursos AS C 
                            ON P.idCurso = C.id 
                        JOIN nivel 
                            ON C.idnivel = nivel.id
                        WHERE P.idProfesor = '$idProfesor'
                            AND nivel.id = '$idNivel'";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            // Obtener los resultados como un arreglo asociativo
            $cursos = $resultado->fetchAll(PDO::FETCH_ASSOC);

            // Devolver los cursos como respuesta en formato JSON
            echo json_encode($cursos);
            break;

            
    }
?>