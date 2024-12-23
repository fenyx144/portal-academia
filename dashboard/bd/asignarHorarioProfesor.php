<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
     
    $registros = json_decode(file_get_contents("php://input"));
    $registrosCount = count($registros);
    $id = $registros[$registrosCount - 1];

    $consulta = "DELETE FROM horario WHERE idProfesor = '$id'";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute(); 

    for ($i = 0; $i < ($registrosCount - 1); $i++) {
        $registro = $registros[$i];
            
            $consulta = "INSERT INTO horario (id, hora_inicio, hora_fin, idProfesor, dia) VALUES ('$registro[0]', '$registro[1]', '$registro[2]', '$registro[3]', '$registro[4]')";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
    
    }
    //$identificadores = implode(',', $idsMantener);

    /*$consulta = "DELETE FROM horario";
    if (!empty($identificadores)) {
        $consulta .= " WHERE id NOT IN ($identificadores)";
    }
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();*/ 
    // Cerrar la conexión y liberar recursos
    $resultado = null;
    $conexion = null;
?>