<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    
    $dia = (isset($_POST['dia'])) ? $_POST['dia'] : ''; // nuevo
    $fecha = (isset($_POST['fecha'])) ? $_POST['fecha'] : ''; // nuevo

    $consulta = "SELECT p.id AS idProfesor, p.nombres, p.apellidos, h.id AS idHorario
                FROM profesores p
                LEFT JOIN horario h ON p.id = h.idProfesor AND h.dia = :dia
                ORDER BY p.id ASC;
                ";

    // Preparar la consulta
    $resultado = $conexion->prepare($consulta);

    // Pasar los valores de los parámetros
    $resultado->bindParam(':dia', $dia);

    // Ejecutar la consulta
    $resultado->execute();

    // Obtener los resultados
    $horarios = $resultado->fetchAll(PDO::FETCH_ASSOC);

    $profesorHorarios = [];

    // Agrupar los resultados por idProfesor
    foreach ($horarios as $horario) {
        $idProfesor = $horario['idProfesor'];
        $idHorario = $horario['idHorario'];

        // Si el idProfesor no existe en el array, lo inicializamos
        if (!isset($profesorHorarios[$idProfesor])) {
            //$profesorHorarios[$idProfesor] = [];
            $profesorHorarios[$idProfesor] = [
                'nombres' => $horario['nombres'],
                'apellidos' => $horario['apellidos'],
                'horarios' => []
            ];
        }

        // Añadir el id de horario al array del profesor correspondiente
        $profesorHorarios[$idProfesor]['horarios'][] = $idHorario;
    }
    $response = array(
        //'profesores' => $profesores,
        //'clases' => $clases,
        'horario' => $profesorHorarios
    );
    // Devolver los cursos como respuesta en formato JSON
    echo json_encode($response);
    $conexion = null;
?>