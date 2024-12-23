<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    // Obtener los valores de los id_estudiante_grupos desde el request AJAX (pueden ser múltiples IDs)
    $id_estudiante_grupos = $_POST['id_estudiante_grupos']; // Un array de IDs

    // Convertir el array de IDs en un string para usarlo en la consulta
    $id_estudiante_grupos_str = implode(',', $id_estudiante_grupos);

    // Crear la consulta para obtener las fechas asociadas a los id_estudiante_grupos
    $sql = "SELECT ae.id_estudiante_grupos, ae.fecha
            FROM asistencia_estudiantes ae
            JOIN estudiantes_grupos eg ON eg.id = ae.id_estudiante_grupos
            WHERE eg.id_grupo = :id_grupo AND ae.id_estudiante_grupos IN ($id_estudiante_grupos_str)";

    // Preparar la consulta usando PDO
    $stmt = $conexion->prepare($sql);

    // Bind de parámetros
    $stmt->bindParam(':id_grupo', $_POST['id_grupo'], PDO::PARAM_INT);

    // Ejecutar la consulta
    $stmt->execute();

    // Obtener los resultados
    $fechas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Estructurar la respuesta como un array asociativo
    $fechasAsociadas = [];
    foreach ($fechas as $fecha) {
        $fechasAsociadas[$fecha['id_estudiante_grupos']][] = $fecha['fecha'];
    }

    // Enviar la respuesta en formato JSON
    echo json_encode($fechasAsociadas);
?>
