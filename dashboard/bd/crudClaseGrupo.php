<?php
include_once '../bd/conexion.php';
$objeto = new Conexion();
$conexion = $objeto->Conectar();

$opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';

switch ($opcion) {
    case 1:
        $id_grupo = (isset($_POST['id_grupo'])) ? $_POST['id_grupo'] : '';
        $id_profesor = (isset($_POST['id_profesor'])) ? $_POST['id_profesor'] : '';
        $id_curso = (isset($_POST['id_curso'])) ? $_POST['id_curso'] : '';
        
        $fechas = (isset($_POST['fechas'])) ? $_POST['fechas'] : '';
        $hora_inicio = (isset($_POST['hora_inicio'])) ? $_POST['hora_inicio'] : '';
        $hora_fin = (isset($_POST['hora_fin'])) ? $_POST['hora_fin'] : '';
        $dia = (isset($_POST['dia'])) ? $_POST['dia'] : '';
        
        $consulta = "INSERT INTO clase_semanal_grupo (id_profesor, id_curso, id_grupo, hora_inicio, hora_fin, dia) 
                        VALUES('$id_profesor', '$id_curso', '$id_grupo', '$hora_inicio', '$hora_fin', '$dia')";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $lastId = $conexion->lastInsertId();
        foreach ($fechas as $fecha) {
            $consulta = "INSERT INTO clases_grupo (fecha, id_clase_semanal_grupo) 
                            VALUES('$fecha', '$lastId') ";            
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
        }
        $response = array(
            "mensaje" => "Datos enviados correctamente",
            "id" => $lastId
        );
        break;
    case 2:
        $id_profesor = (isset($_POST['id_profesor'])) ? $_POST['id_profesor'] : '';
        $id_curso = (isset($_POST['id_curso'])) ? $_POST['id_curso'] : '';
        $id_clase = (isset($_POST['id_clase'])) ? $_POST['id_clase'] : '';
        $fechas = (isset($_POST['fechas'])) ? $_POST['fechas'] : '';
        $hora_inicio = (isset($_POST['hora_inicio'])) ? $_POST['hora_inicio'] : '';
        $hora_fin = (isset($_POST['hora_fin'])) ? $_POST['hora_fin'] : '';
        $dia = (isset($_POST['dia'])) ? $_POST['dia'] : '';
        $consulta = "UPDATE clase_semanal_grupo
                    SET id_profesor='$id_profesor',
                        id_curso='$id_curso',
                        hora_inicio='$hora_inicio',
                        hora_fin='$hora_fin', 
                        dia='$dia'
                    WHERE id='$id_clase' ";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        
        $consulta = "DELETE FROM clases_grupo
                    WHERE id_clase_semanal_grupo = '$id_clase' ";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        foreach ($fechas as $fecha) {
            $consulta = "INSERT INTO clases_grupo (fecha, id_clase_semanal_grupo) 
                            VALUES('$fecha', '$id_clase') ";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
        }
        $response = array(
            "mensaje" => "Clase modificada correctamente"
        );
        break;
    case 3:
        $id =  (isset($_POST['id'])) ? $_POST['id'] : '';
        $consulta = "DELETE FROM clase_semanal_grupo
                    WHERE id = '$id'";       
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();

        $consulta = "DELETE FROM clases_grupo
                    WHERE id_clase_semanal_grupo = '$id'";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $response = array(
            "mensaje" => "Clase eliminada correctamente"
        );
        break;
    case 4:
            $id_grupo = (isset($_POST['id_grupo'])) ? $_POST['id_grupo'] : '';
            $consulta = "SELECT CSG.*, P.id id_profesor, P.nombres , P.apellidos, C.id id_curso, C.nombre nombre_curso
                        FROM clase_semanal_grupo CSG
                        JOIN profesores P
                            ON P.id = CSG.id_profesor
                        JOIN cursos C
                            ON C.id = CSG.id_curso
                        WHERE CSG.id_grupo = '$id_grupo'";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();
            $response = $resultado->fetchAll(PDO::FETCH_ASSOC);    
            break;     
}

// Crear la respuesta JSON

// Enviar la respuesta JSON
echo json_encode($response);

$conexion = null;
?>
