<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    // Recepción de los datos enviados mediante POST desde el JS   

    $idprofesor = (isset($_POST['idprofesor'])) ? $_POST['idprofesor'] : '';
    $idcurso = (isset($_POST['idcurso'])) ? $_POST['idcurso'] : '';
    $idcursoprofesor = (isset($_POST['idcursoprofesor'])) ? $_POST['idcursoprofesor'] : '';
    $opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';

    switch($opcion) {
        case 1:
            $consulta = "INSERT INTO profesores_cursos (idProfesor, idCurso) 
                        VALUES('$idprofesor', '$idcurso') ";
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();

            // Obtener el ID del registro insertado
            $id_insertado = $conexion->lastInsertId();

            // Devolver el ID insertado a través de AJAX
            echo json_encode(array("id_insertado" => $id_insertado));

            break;
        case 2:
            $consulta = "UPDATE profesores_cursos
                    SET idCurso='$idcurso' 
                    WHERE id='$idcursoprofesor' ";		
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();        
            echo json_encode(array("mensaje" => "La modición se realizó correctamente"));
        
            break;
        case 3:
            $consulta = "DELETE FROM profesores_cursos 
                    WHERE id='$idcursoprofesor' ";		
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();    
            echo json_encode(array("mensaje" => "La eliminación se realizó correctamente"));                       
            break;
    }
    $conexion = NULL;
?>