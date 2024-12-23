<?php require_once "vistas/parte_superior.php"?>

<!--INICIO del cont principal-->

<div class="container">
    <h1>Estudiantes</h1>

    <?php
    include_once '../bd/conexion.php';
        $objeto = new Conexion();
        $conexion = $objeto->Conectar();

        $consulta = "SELECT * FROM estudiantes";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <div class="container">
            <div class="row">
                <div class="col-lg-12">            
                    <button id="btnNuevoEstudiante" type="button" class="btn btn-success">Nuevo Estudiante</button>    
                </div>    
            </div>    
    </div>    
        <br>  
    <div class="container">
        <div class="row">
                <div class="col-lg-12">
                    <div class="table-responsive">
                        <table id="tablaEstudiantes" 
                            class="table table-striped table-bordered table-condensed"
                            style="width:100%">
                            <thead class="text-center">
                                <tr>
                                    <th>N°</th>
                                    <th>DNI</th>
                                    <th>Apellidos</th>
                                    <th>Nombres</th>
                                    <th>Teléfono</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $contador = 1;
                                foreach($data as $dat) {
                                ?>
                                <tr  data-id="<?php echo $dat['id_estudiante'] ?>">
                                    <td><?php echo $contador ?></td>
                                    <td><?php echo $dat['DNI_estudiante'] ?></td>
                                    <td><?php echo $dat['apellidos_estudiante'] ?></td>
                                    <td><?php echo $dat['nombres_estudiante'] ?></td>
                                    <td><?php echo $dat['telefono_estudiante'] ?></td>
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
        
    <!--Modal para CRUD-->
    <div class="modal fade" id="modalEstudiante" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tituloModalEstudiante"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEstudiante">    
                    <div class="modal-body">
                        <div class="form-group">
                                <label for="DNI_estudiante" class="col-form-label">DNI:</label>
                                <input type="text" class="form-control" id="DNI_estudiante" name ="DNI_estudiante">
                        </div>
                        <div class="form-group">
                            <label for="nombres_estudiante" class="col-form-label">Nombres:</label>
                            <input type="text" class="form-control" id="nombres_estudiante" name="nombres_estudiante">
                        </div>
                        <div class="form-group">
                            <label for="apellidos_estudiante" class="col-form-label">Apellidos:</label>
                            <input type="text" class="form-control" id="apellidos_estudiante" name="apellidos_estudiante">
                        </div>                
                        <div class="form-group">
                            <label for="telefono_estudiante" class="col-form-label">Teléfono:</label>
                            <input type="number" class="form-control" id="telefono_estudiante" name="telefono_estudiante">
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
</div>
<?php require_once "vistas/parte_inferior.php"?>
<script type="text/javascript" src="scripts/mainEstudiantes.js"></script>
</body>
</html>