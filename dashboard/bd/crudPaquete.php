<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();
    // Recepción de los datos enviados mediante POST desde el JS   
    $request_body = file_get_contents('php://input');
    
    //Decodificar el JSON a un arreglo asociativo de PHP
    
    $data = json_decode($request_body, true);
    $opcion = $data['opcion'];
    if ($opcion == 1) {
        $idEstudiante = $data['idEstudiante'];

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
        $numClases = $data['numClases'];
        $numEstudiantes = $data['numEstudiantes'];
        $costoPaquete = $data['costoPaquete'];
        $observaciones = $data['observaciones'];
        $pago = $data['pago'];
        
        $consulta = "INSERT INTO paquetes (id_estudiante, num_clases, costo, observaciones, estado_pago)
            VALUES('$idEstudiante', '$numClases', '$costoPaquete', '$observaciones', '$pago')";
        
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
    }
     
    
    echo json_encode(
        array(
            "mensaje" => "La inserción se realizó correctamente",
           'data' => $data
    ));

    $conexion = NULL;
?>