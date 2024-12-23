<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    // Recepción de los datos enviados mediante POST desde el JS   

    $nombreGrupo = (isset($_POST['nombreGrupo'])) ? $_POST['nombreGrupo'] : '';
    $fechaInicioGrupo = (isset($_POST['fechaInicioGrupo'])) ? $_POST['fechaInicioGrupo'] : '';
    $fechaFinGrupo = (isset($_POST['fechaFinGrupo'])) ? $_POST['fechaFinGrupo'] : '';

    $opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';
    $id = (isset($_POST['id'])) ? $_POST['id'] : '';

    $numeroPagos = (isset($_POST['selectNumeroPagos'])) ? $_POST['selectNumeroPagos'] : '';
    
    switch($opcion){
        case 1: //alta
            $consulta = "INSERT INTO grupos (nombre_grupo, inicio_grupo, fin_grupo, numero_pagos) 
                        VALUES(:nombreGrupo, :fechaInicioGrupo, :fechaFinGrupo, :numeroPagos)";
            $resultado = $conexion->prepare($consulta);
            $resultado->bindParam(':nombreGrupo', $nombreGrupo);
            $resultado->bindParam(':fechaInicioGrupo', $fechaInicioGrupo);
            $resultado->bindParam(':fechaFinGrupo', $fechaFinGrupo);
            $resultado->bindParam(':numeroPagos', $numeroPagos);
            
            $resultado->execute(); 

            $lastId = $conexion->lastInsertId();
            
            for($i = 1; $i <= $numeroPagos; $i++) {
                $fecha_pago = (isset($_POST['fechaPago'.$i])) ? $_POST['fechaPago'.$i] : '';
                $consulta = "INSERT INTO fecha_pagos (id_grupo, fecha_pago) 
                                VALUES(:id, :fecha_pago)";
                $resultado = $conexion->prepare($consulta);
                $resultado->bindParam(':id', $lastId);
                $resultado->bindParam(':fecha_pago', $fecha_pago);
                $resultado->execute();
            }            
            
            echo $lastId;
            //echo json_encode(array("mensaje" => "La inserción se realizó correctamente"));  
            break;
        case 2: //modificación
            $consulta = "UPDATE grupos 
                        SET nombre_grupo='$nombreGrupo', inicio_grupo='$fechaInicioGrupo',
                            fin_grupo='$fechaFinGrupo', numero_pagos='$numeroPagos' 
                        WHERE id_grupo='$id' ";		
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();        
            
            $consulta = "DELETE FROM fecha_pagos
                        WHERE id_grupo='$id' ";		
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();        
            
            for($i = 1; $i <= $numeroPagos; $i++) {
                $fecha_pago = (isset($_POST['fechaPago'.$i])) ? $_POST['fechaPago'.$i] : '';
                $consulta = "INSERT INTO fecha_pagos (id_grupo, fecha_pago) 
                                VALUES(:id, :fecha_pago)";
                $resultado = $conexion->prepare($consulta);
                $resultado->bindParam(':id', $id);
                $resultado->bindParam(':fecha_pago', $fecha_pago);
                $resultado->execute();
            }
            echo json_encode(array("mensaje" => "La modición se realizó correctamente"));
            break; 
        case 3://baja
            $consulta = "DELETE FROM grupos 
                        WHERE id_grupo='$id' ";		
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();

            $consulta = "DELETE FROM fecha_pagos
                        WHERE id_grupo='$id' ";		
            $resultado = $conexion->prepare($consulta);
            $resultado->execute();        
            
            echo json_encode(array("mensaje" => "La eliminación se realizó correctamente"));                       
            break;
    }
    $conexion = NULL;
?>
