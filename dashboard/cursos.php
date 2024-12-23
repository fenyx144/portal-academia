<?php require_once "vistas/parte_superior.php" ?>
<?php
include_once '../bd/conexion.php';
    $objeto = new Conexion();
    $conexion = $objeto->Conectar();

    $consulta = "SELECT cursos.id, cursos.nombre, pago1p, pago2p, pago3p,
                    nivel.nombre AS nombre_nivel,
                    nivel.id AS id_nivel
                FROM cursos
                JOIN nivel
                    ON  cursos.idnivel = nivel.id ";
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $data = $resultado->fetchAll(PDO::FETCH_ASSOC);

    $consulta = 'SELECT id, nombre FROM nivel ORDER BY nombre';
    $resultado = $conexion->prepare($consulta);
    $resultado->execute();
    $niveles = $resultado->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container">
    <h1>Cursos</h1>
    <div class="row">
        <div class="col-lg-12">
            <button id="btnNuevoCursos" type="button" class="btn btn-success">Nuevo Curso</button>
        </div>
    </div>
</div>
<br>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="table-responsive">
                <table id="tablaCursos" 
                    class="table table-striped table-bordered table-condensed" 
                    style="width:100%">
                    <thead class="text-center">
                        <tr>
                            <th>N°</th>
                            <th>Nombre</th>
                            <th>Nivel</th>
                            <th>Costo 1E</th>
                            <th>Costo 2E</th>
                            <th>Costo 3E</th>
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
                                <td><?php echo $dat['nombre'] ?></td>
                                <td data-idNivel="<?php echo $dat['id_nivel'] ?>">
                                    <?php echo $dat['nombre_nivel']?>
                                </td>
                                <td><?php echo $dat['pago1p'] ?></td>
                                <td><?php echo $dat['pago2p'] ?></td>
                                <td><?php echo $dat['pago3p'] ?></td>
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
<div class="modal fade" id="CRUDCursos" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloCursos"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formCursos">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombreCursos" class="col-form-label">Nombre:</label>
                        <input type="text" class="form-control" id="nombreCursos">
                    </div>
                    <div class="form-group">
                        <label for="nivelCursos">Nivel:</label>
                        <select class="form-control" id="nivelCursos" name="nivelCursos">
                            <option value='' selected disabled hidden>Selecciona una opción</option>
                            <?php foreach ($niveles as $nivel): ?>
                                <option value="<?php echo $nivel['id']; ?>">
                                    <?php echo $nivel['nombre']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pago1pCursos" class="col-form-label">Costo 1 estudiante:</label>
                        <input type="number" step="0.01" class="form-control" id="pago1pCursos">
                    </div>
                    <div class="form-group">
                        <label for="pago2pCursos" class="col-form-label">Costo 2 estudiantes:</label>
                        <input type="number" step="0.01" class="form-control" id="pago2pCursos">
                    </div>
                    <div class="form-group">
                        <label for="pago3pCursos" class="col-form-label">Costo 3 estudiantes:</label>
                        <input type="number" step="0.01" class="form-control" id="pago3pCursos">
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
<!--FIN del cont principal-->
<?php require_once "vistas/parte_inferior.php" ?>

<script type="text/javascript" src="scripts/mainCursos.js"></script>  
</body>
</html>