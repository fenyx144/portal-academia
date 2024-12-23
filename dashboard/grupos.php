<?php
    require_once "vistas/parte_superior.php";
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $consulta = "SELECT g.*, fp.fecha_pago 
                FROM grupos g
                LEFT JOIN fecha_pagos fp 
                    ON g.id_grupo = fp.id_grupo";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    //$data=$resultado->fetchAll(PDO::FETCH_ASSOC);
    $data = array();

    while ($fila = $resultado->fetch(PDO::FETCH_ASSOC)) {
        // Obtener el ID del grupo
        $id_grupo = $fila['id_grupo'];

        // Si el grupo no existe en el arreglo de datos, crear una entrada para él
        if (!isset($data[$id_grupo])) {
            $data[$id_grupo] = array(
                'id_grupo' => $fila['id_grupo'],
                'nombre_grupo' => $fila['nombre_grupo'],
                'inicio_grupo' => $fila['inicio_grupo'],
                'fin_grupo' => $fila['fin_grupo'],
                'numero_pagos' => $fila['numero_pagos'],
                'fecha_pagos' => array()
            );
        }
        // Agregar la fecha de pago al grupo correspondiente
        $data[$id_grupo]['fecha_pagos'][] = $fila['fecha_pago'];
    }
?>

<!--INICIO del cont principal-->
<style>
    .circulo {
        width: 20px; /* Ancho del círculo */
        height: 20px; /* Altura del círculo */
        border-radius: 50%; /* Hace que el borde sea redondeado para crear un círculo */
        display: inline-block; /* Permite que los círculos se muestren en línea */
        margin-right: 5px; /* Espacio entre los círculos */
        border: 2px solid #333;
    }

    .verde {
        background-color: #98FB98; /* Color de fondo del círculo verde */
    }

    .amarillo {
        background-color: #FFFF99 /* Color de fondo del círculo azul */
    }

    .rojo {
        background-color: #FFCCCC; /* Color de fondo del círculo rojo */
    }
    .blanco {
        background-color: #FFFFFF;
    }

    /* TARJETA */
    .buttons-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .paid-container {
        margin-left: auto;
    }

    .paid-rectangle {
        display: inline-block;
        width: 100px;
        /* Ajusta el ancho según tus necesidades */
        height: 30px;
        /* Ajusta la altura según tus necesidades */
        background-color: white;
        color: black;
        text-align: center;
        line-height: 30px;
        font-weight: bold;
        font-size: 0.7rem;
        margin: 0;
    }
    .line-rectangle {
        background-color: #FFFF66;
        border-radius: 10px;
        padding: 10px;
        margin-bottom: 10px;
        width: 220px; /* El rectángulo ocupa todo el ancho de la celda */
        height: 100%; El rectángulo ocupa todo el alto de la celda */
        box-sizing: border-box; /* Incluye el padding en el ancho y alto */
    }

    .line {
        border-bottom: 1px solid black;
        padding: 5px 0;
        margin: 5px 0;
        font-size: smaller; 
    }
    #tabla_horario th,
    #tabla_horario td {
        border: 1px solid black; /* Define el borde de 1 píxel sólido de color negro */
        padding: 8px; /* Añade espacio interno para mejorar la apariencia */
    }

    /* Estilos adicionales si es necesario */
    #tabla_horario {
        border-collapse: collapse; /* Fusiona los bordes de las celdas para que se vean más nítidos */
    }

    /* Estilo para el borde exterior de la tabla */
    #tabla_horario {
        border: 2px solid black; /* Bordes exteriores de la tabla */
    }
    /* width: 100%;*/
    #tabla_horario td {
        white-space: nowrap;
    }
</style>
<div class="container">
    <h1>Grupos</h1>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <button id="btnNuevoGrupo" type="button" class="btn btn-success" data-toggle="modal">Nuevo Grupo</button>    
            </div>
        </div>
    </div>
    <br>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">        
                    <table id="tablaGrupos" class="table table-striped table-bordered table-condensed" style="width:100%">
                        <thead class="text-center">
                            <tr>
                                <th>N°</th>
                                <th>Nombre</th>                                
                                <th>Inicio</th>
                                <th>Fin</th>
                                <th>Horario</th>        
                                <th>Estudiantes</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php         
                                $contador = 1;                   
                                foreach($data as $dat) {                                                        
                            ?>
                                <tr data-id="<?php echo $dat['id_grupo']; ?>"
                                    data-numeropagos="<?php echo $dat['numero_pagos']; ?>"
                                    
                                    data-fechas='<?php 
                                    
                                    $fechas_json = json_encode($dat['fecha_pagos']);
                                    echo htmlspecialchars($fechas_json); ?>'
                                >
                                    <td><?php echo $contador ?></td>
                                    <td><?php echo $dat['nombre_grupo'] ?></td>
                                    <td><?php
                                            $date = new DateTime($dat['inicio_grupo']);
                                            echo $date->format('d-m-Y');
                                        ?>
                                    </td>
                                    <td><?php
                                            $date = new DateTime($dat['fin_grupo']);
                                            echo $date->format('d-m-Y');
                                        ?>
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            <?php
                                    $contador++;
                                }
                            ?>                                
                        </tbody>        
                    </table>                    
                </div>
            </div>
        </div>  
    </div>    
        
    <!--Modal para crear un grupo-->
    <div class="modal fade" id="modalNuevoGrupo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1cc88a; color: white;">
                    <h5 class="modal-title" id="tituloModalGrupo">Nuevo Grupo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            
                <form id="formGrupos">    
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombreGrupo" class="col-form-label">Nombre:</label>
                            <input type="text" class="form-control" id="nombreGrupo" name="nombreGrupo" required>
                        </div>
                        <div class="form-group">
                            <label for="fechaInicioGrupo" class="col-form-label">Fecha Inicio:</label>
                            <input type="date" class="form-control" id="fechaInicioGrupo" name="fechaInicioGrupo" required>
                        </div>                
                        <div class="form-group">
                            <label for="fechaFinGrupo" class="col-form-label">Fecha Fin:</label>
                            <input type="date" class="form-control" id="fechaFinGrupo" name="fechaFinGrupo" required>
                        </div>
                        <div class='form-group'>
                            <label for='selectNumeroPagos' class='col-form-label'>Numero de Pagos:</label>
                            <select class='form-control' id='selectNumeroPagos' name='selectNumeroPagos' required>
                                <option value="" disabled selected hidden>Seleccione una opción</option>
                                <option value=1>1</option>
                                <option value=2>2</option>
                                <option value=3>3</option>
                                <option value=4>4</option>
                                <option value=5>5</option>
                            </select>
                        </div>
                        <div id='contenedor'>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                    </div>
                </form>
            </div>    
        </div>
    </div>  

    <!--Modal para definir el horario de un grupo-->
    <div class="modal fade" id="modalHorario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 1100px!important;">
            <div class="modal-content" style="max-height: 100vh; overflow-y: auto;">
                <div class="modal-header" style="background-color: #1cc88a; color: white;">
                    <h5 class="modal-title" id="tituloModalHorarios">Horario del grupo</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            
                <form id="formHorario">    
                    <div class="modal-body">
                        <div id='contenedor_horario' style='overflow-x: auto; max-width: 100%;'>
                            <table class='table table-bordered' id='tabla_horario'>
                                <thead>
                                    <tr>
                                        <th>Horas</th> <!-- Celda vacía para la esquina superior izquierda -->
                                        <th>Lunes</th>
                                        <th>Martes</th>
                                        <th>Miércoles</th>
                                        <th>Jueves</th>
                                        <th>Viernes</th>
                                        <th>Sábado</th>
                                        <th>Domingo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                    </div>
                </form>
            </div>    
        </div>
    </div>
    <!-- Modal para definir el horario de una clase-->
    <div class="modal fade" id="modalClase" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true"  style="overflow: scroll;">
        <div class="modal-dialog" role="document" style="max-height: 80vh; overflow-y: auto;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1cc88a; color: white;">
                    <h5 class="modal-title" id="tituloModalClase">Definir el Horario de una Clase</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            
                <form id="formClase">
                    <div class="modal-body">
                        <div class="form-row">
                            <div class='col'>
                                <label for='selectCurso' class='col-form-label'>Curso:</label>
                                <select class='form-control' id='selectCurso' name='selectCurso' required>
                                    <option value="" disabled selected hidden>Seleccione una opción</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class='col'>
                                <label for='selectProfesor' class='col-form-label'>Profesor:</label>
                                <select class='form-control' id='selectProfesor' name='selectProfesor' disabled required>
                                    <option value="" disabled selected hidden>Seleccione una opción</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class='col'>
                                <label for='selectDia' class='col-form-label'>Día:</label>
                                <select class='form-control' id='selectDia' name='selectDia'required>                                    
                                    <option value=1>Lunes</option>
                                    <option value=2>Martes</option>
                                    <option value=3>Miércoles</option>
                                    <option value=4>Jueves</option>
                                    <option value=5>Viernes</option>
                                    <option value=6>Sábado</option>
                                    <option value=7>Domingo</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class='col'>
                                <label for='horaInicio' class='col-form-label'>Hora Inicio:</label>
                                <select class='form-control' id='horaInicio' name='horaInicio' required>
                                    <option value='0'>07:00 am</option>
                                    <option value='1'>07:30 am</option>
                                    <option value='2'>08:00 am</option>
                                    <option value='3'>08:30 am</option>
                                    <option value='4'>09:00 am</option>
                                    <option value='5'>09:30 am</option>
                                    <option value='6'>10:00 am</option>
                                    <option value='7'>10:30 am</option>
                                    <option value='8'>11:00 am</option>
                                    <option value='9'>11:30 am</option>
                                    <option value='10'>12:00 pm</option>
                                    <option value='11'>12:30 pm</option>
                                    <option value='12'>01:00 pm</option>
                                    <option value='13'>01:30 pm</option>
                                    <option value='14'>02:00 pm</option>
                                    <option value='15'>02:30 pm</option>
                                    <option value='16'>03:00 pm</option>
                                    <option value='17'>03:30 pm</option>
                                    <option value='18'>04:00 pm</option>
                                    <option value='19'>04:30 pm</option>
                                    <option value='20'>05:00 pm</option>
                                    <option value='21'>05:30 pm</option>
                                    <option value='22'>06:00 pm</option>
                                    <option value='23'>06:30 pm</option>
                                    <option value='24'>07:00 pm</option>
                                    <option value='25'>07:30 pm</option>
                                    <option value='26'>08:00 pm</option>
                                    <option value='27'>08:30 pm</option>
                                    <option value='28'>09:00 pm</option>
                                </select>
                            </div>
                            <!--div class='col'>
                                <label for='minutosInicio' class='col-form-label' style="visibility: hidden;">minutos</label>
                                <select class='form-control' name="minutosInicio" id="minutosInicio">
                                    <option value='0'>00</option>
                                    <option value='1'>30</option>
                                </select>
                            </div-->
                        </div>
                        <div class='form-row'>
                            <div class='col'>
                                <label for='horaFin' class='col-form-label'>Hora Fin:</label>
                                <select class='form-control' id='horaFin' name='horaFin' required>
                                    <option value="" disabled selected hidden>Seleccione hora de fin</option>
                                    <option value='0'>07:00 am</option>
                                    <option value='1'>07:30 am</option>
                                    <option value='2'>08:00 am</option>
                                    <option value='3'>08:30 am</option>
                                    <option value='4'>09:00 am</option>
                                    <option value='5'>09:30 am</option>
                                    <option value='6'>10:00 am</option>
                                    <option value='7'>10:30 am</option>
                                    <option value='8'>11:00 am</option>
                                    <option value='9'>11:30 am</option>
                                    <option value='10'>12:00 pm</option>
                                    <option value='11'>12:30 pm</option>
                                    <option value='12'>01:00 pm</option>
                                    <option value='13'>01:30 pm</option>
                                    <option value='14'>02:00 pm</option>
                                    <option value='15'>02:30 pm</option>
                                    <option value='16'>03:00 pm</option>
                                    <option value='17'>03:30 pm</option>
                                    <option value='18'>04:00 pm</option>
                                    <option value='19'>04:30 pm</option>
                                    <option value='20'>05:00 pm</option>
                                    <option value='21'>05:30 pm</option>
                                    <option value='22'>06:00 pm</option>
                                    <option value='23'>06:30 pm</option>
                                    <option value='24'>07:00 pm</option>
                                    <option value='25'>07:30 pm</option>
                                    <option value='26'>08:00 pm</option>
                                    <option value='27'>08:30 pm</option>
                                    <option value='28'>09:00 pm</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                    </div>
                </form>
            </div>    
        </div>
    </div>
    <!--Modal para crear un grupo-->
    <div class="modal fade" id="modalCuotas" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 900px!important;">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1cc88a; color: white;">
                    <h5 class="modal-title" id="tituloModalCuotas">Pagos del Estudiante</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            
                <form id="formCuotas">    
                    <div class="modal-body">
                        <div id='contenedor_cuotas'>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--Modal para mostrar los estudiantes de un grupo-->
    <div class="modal fade" id="modalEstudiantes" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document" style="max-width: 1100px!important;">
            <div class="modal-content" style="max-height: 80vh; overflow-y: auto; overflow-x: auto;">
                <div class="modal-header" style="background-color: #1cc88a; color: white;">
                    <h5 class="modal-title" id="tituloModalGrupo">Estudiantes</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>
            
                <form id="formEstudiantes">    
                    <div class="modal-body">
                        <div class="container">
                            <!--div class="row"-->
                                <!--div class="col-lg-12"-->
                            <div class="btn-group mr-2">            
                                <button id="btnNuevoEstudiante" type="button" class="btn btn-success" data-toggle="modal">Nuevo Estudiante</button>    
                            </div>
                                <!--div class="col-lg-12"-->            
                            <div class="btn-group">
                                <button id="btn_asistencia_estudiante" type="button" class="btn btn-success" data-toggle="modal">Asistencia</button>    
                            </div>    
                        </div> 
                        <br>
                        <div class="container">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="table-responsive">
                                        <table id="tablaEstudiantesGrupo" class="table table-striped table-bordered table-condensed" style="width:100%">
                                            <thead class="text-center">
                                                <tr>
                                                    <th>N°</th>
                                                    <th>DNI</th>
                                                    <th>Apellidos</th>
                                                    <th>Nombres</th>                                                    
                                                    <th>Teléfono</th>
                                                    <th>Fecha Inicio</th>
                                                    <th>Pagos</th>
                                                    <th>Alerta</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>        
                                        </table>                    
                                    </div>
                                </div>
                            </div>  
                        </div>            
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                    </div>
                </form>   
            </div>        
        </div>
    </div>  

    <!--Modal para ingresar un nuevo estudiante a un grupo -->
    <div class="modal fade" id="modalNuevoEstudiante" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #1cc88a; color: white;">
                    <h5 class="modal-title" id="tituloModalEstudiante"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEstudiante">
                    <div class="modal-body" style="max-height: 500px; overflow-y: auto;">
                        <div class="form-group">
                            <label for="DNI_estudiante" class="col-form-label">DNI:</label>
                        </div>
                        <div class="form-row">
                            <div class="col-md-5">
                                <input type="number" class="form-control" id="DNI_estudiante" name="DNI_estudiante" required>
                            </div>
                            <div class="col-md-3">
                                <button type="button" class="btn btn-primary" id="btnContinuar">Continuar</button>
                            </div>
                        </div>
                        <div class="form-row" id="div_mensaje" hidden>
                            <label id="mensaje" class="col-form-label"></label>
                        </div>
                        <div class="form-group">
                            <label for="nombres_estudiante" class="col-form-label">Nombres:</label>
                            <input type="text" class="form-control" id="nombres_estudiante" name="nombres_estudiante" disabled>
                        </div>
                        <div class="form-group">
                            <label for="apellidos_estudiante" class="col-form-label">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos_estudiante" name="apellidos_estudiante"  disabled>
                        </div>
                        <div class="form-group">
                            <label for="telefono_estudiante" class="col-form-label">Teléfono:</label>
                            <input type="text" class="form-control" id="telefono_estudiante" name="telefono_estudiante" disabled>
                        </div>
                        <div class="form-group">
                            <label for="fecha_inicio_estudiante" class="col-form-label">Fecha de Inicio:</label>
                            <input type="date" class="form-control" id="fecha_inicio_estudiante" name="fecha_inicio_estudiante" disabled>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal para la gestión de la asistencia-->
    <div class="modal fade" id="modal_asistencia_estudiante" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document"  style="max-width: 1100px!important;">
            <div class="modal-content" style="max-height: 80vh; overflow-y: auto;">
                <div class="modal-header" style="background-color: #1cc88a; color: white;">
                    <h5 class="modal-title" id="titulo_modal_asistencia">Lista de Asistencia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_asistencia_estudiante">
                    <br>
                    <div class="table-responsive">        
                        <table id="tabla_asistencia" class="table table-striped table-bordered table-condensed" style="width:100%">
                            <thead class="text-center">
                                <tr>
                                    <th>
                                        N°
                                    </th>
                                    <th>
                                        Estudiantes
                                    </th>
                                </tr>  
                            </thead>
                            <tbody>
                                                                
                            </tbody>        
                        </table>                    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                        <button type="submit" id="btnGuardar" class="btn btn-dark">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<!--FIN del cont principal-->
<?php require_once "vistas/parte_inferior.php"?>
<script type="text/javascript" src="scripts/mainGrupos.js"></script>
</body>
</html>