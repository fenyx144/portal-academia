<?php require_once "vistas/parte_superior.php" ?>

<!--INICIO del cont principal-->

<?php
    include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $consulta = "SELECT  *  FROM profesores";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $data = $resultado->fetchAll(PDO::FETCH_ASSOC);

    $consulta = 'SELECT id, nombre FROM nivel ORDER BY nombre';
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    // Obtener los resultados como un arreglo asociativo
    $niveles = $resultado->fetchAll(PDO::FETCH_ASSOC);
?>
<style>
    .disponible {
        background-color: rgb(153, 255, 153);
    }

    .no-disponible {
        background-color: rgb(255, 102, 102);
    }
</style>

<div class="container">
    <h1>Profesores</h1>
    <div class="row">
        <div class="col-lg-12">
            <button id="btnNuevoProfesores" type="button" class="btn btn-success">Nuevo
                Profesor</button>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table id="tablaProfesores" class="table table-striped table-bordered table-condensed"
                    style="width:100%">
                    <thead class="text-center">
                        <tr>
                            <th>N°</th>
                            <th>Nombres</th>
                            <th>Apellidos</th>
                            <th>Teléfono</th>
                            <th>DNI</th>
                            <th>Correo</th>
                            <th>Fecha de Nacimiento</th>                            
                            <th>Cursos</th>
                            <th>Asignar horario</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $contador = 1;
                        foreach ($data as $dat) {
                            ?>
                            <tr data-id="<?php echo $dat['id'] ?>">
                                <td><?php echo $contador ?></td>
                                <td><?php echo $dat['nombres'] ?></td>
                                <td><?php echo $dat['apellidos'] ?></td>
                                <td><?php echo $dat['telefono'] ?></td>
                                <td><?php echo $dat['DNI'] ?></td>
                                <td><?php echo $dat['correo'] ?></td>
                                <td><?php echo $dat['fNacimiento'] ?></td>                                
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

<!--Modal para CRUD-->
<div class="modal fade" id="CRUDProfesores" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloProfesores"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formProfesores">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombresProfesores" class="col-form-label">Nombres:</label>
                        <input type="text" class="form-control" id="nombresProfesores" name="nombresProfesores" required>
                    </div>
                    <div class="form-group">
                        <label for="apellidosProfesores" class="col-form-label">Apellidos:</label>
                        <input type="text" class="form-control" id="apellidosProfesores" name="apellidosProfesores" required>
                    </div>
                    <div class="form-group">
                        <label for="telefonoProfesores" class="col-form-label">Teléfono:</label>
                        <input type="text" class="form-control" id="telefonoProfesores" name="telefonoProfesores" required>
                    </div>
                    <div class="form-group">
                        <label for="correoProfesores" class="col-form-label">Correo:</label>
                        <input type="text" class="form-control" id="correoProfesores" name="correoProfesores" required>
                    </div>
                    <div class="form-group">
                        <label for="DNIProfesores" class="col-form-label">DNI:</label>
                        <input type="number" class="form-control" id="DNIProfesores" name="DNIProfesores" required>
                    </div>
                    <div class="form-group">
                        <label for="fNacimientoProfesores" class="col-form-label">Fecha de nacimiento:</label>
                        <input type="date" class="form-control" id="fNacimientoProfesores" name="fNacimientoProfesores" required>
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

<!--Modal para CRUD-->
<div class="modal fade" id="AddCurso" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" id="modalHeader">
                <h5 class="modal-title" id="tituloAddCurso"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAddCurso">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nivel">Nivel:</label>
                        <select class="form-control" id="nivelProfesores" name="nivelProfesores">
                            <!--option value="">Seleccione un nivel:</option-->
                            <option value='' selected disabled hidden>Selecciona una opción</option>
                            <?php foreach ($niveles as $nivel): ?>
                                <option value='<?php echo $nivel['id']; ?>' >
                                    <?php echo $nivel['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="curso">Curso:</label>
                        <select class="form-control" id="cursoProfesores" name="cursoProfesores" disabled>
                            <option value='' selected disabled hidden>Selecciona una opción</option>
                            <!-- Opciones del selector de cursos -->
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Cancelar</button>
                    <button type="submit" id="btnGuardarAdd" class="btn btn-dark" disabled>Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="cursosModal" tabindex="-1" role="dialog" aria-labelledby="cursosModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 800px!important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cursosModalTitulo">Cursos del Profesor</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formVerCursosProfesor">
                <br>
                <div class="col-12 col-md-6">
                    <button type="button" class="btn btn-success btnAddCurso">Añadir Curso</button>
                </div>
                
                <br>        
                <div class="modal-body" id="tablaRegistros" style="max-height: 400px; overflow-y: auto;">
                    <table class="table" id="tablaRegistros">
                        <thead>
                            <tr>
                                <th>Nivel</th>
                                <th>Curso</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Filas de cursos generadas dinámicamente -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="asignarHorario" tabindex="-1" role="dialog" aria-labelledby="cursosModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 1500px!important;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cursosModalTitulo">Asignar Horario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Cerrar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formAsignarHorarioProfesor">

                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <div class="form-check form-check-inline">
                        <input type="radio" name="accion" value="disponible" id="radioDisponible" checked>
                        <label for="radioDisponible">Definir Horario Disponible</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio" name="accion" value="no-disponible" id="radioNoDisponible">
                        <label for="radioNoDisponible">Definir Horario No Disponible</label>
                    </div>

                    <table class="table table-bordered" id="tablaAsignarHorario">
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
                        <tbody id="horarioTablaCuerpo">
                            <!-- Filas de la tabla -->
                        </tbody>
                    </table>

                    <tbody>
                        <!-- Filas de cursos generadas dinámicamente -->
                    </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="submit" " class="btn btn-dark">Guardar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!--FIN del cont principal-->
<?php require_once "vistas/parte_inferior.php" ?>
<script type="text/javascript" src="scripts/mainProfesores.js"></script>
<script type="text/javascript" src="scripts/cursosProfesor.js"></script>
</body>

</html>