<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    
    $datos = $_POST['asistencias'];  // Array recibido desde Ajax
    $id_grupo = $_POST['id_grupo'];
    // Comenzar la transacción
    $conexion->beginTransaction();

    $sql = "DELETE ae
        FROM asistencia_estudiantes ae
        JOIN estudiantes_grupos eg ON eg.id = ae.id_estudiante_grupos
        WHERE eg.id_grupo = :id_grupo
        ";

    $stmt = $conexion->prepare($sql);
    $stmt->bindParam(':id_grupo', $id_grupo, PDO::PARAM_INT);
    
    $stmt->execute();
    // Preparar la consulta SQL para insertar los registros
    $sql = "INSERT INTO asistencia_estudiantes (id_estudiante_grupos, fecha) VALUES (:id_estudiante_grupos, :fecha)";
    $stmt = $conexion->prepare($sql);

    // Recorrer los datos y ejecutar la inserción para cada uno
    foreach ($datos as $registro) {
        $id_estudiante_grupos = $registro[0];
        $fecha = $registro[1];

        // Ejecutar la consulta con los valores
        $stmt->execute([':id_estudiante_grupos' => $id_estudiante_grupos, ':fecha' => $fecha]);
    }

    // Confirmar la transacción
    $conexion->commit();

    // Enviar una respuesta exitosa
    echo json_encode(['success' => true]);
    $conexion = NULL;
?>