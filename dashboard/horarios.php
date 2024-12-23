<?php
  require_once "vistas/parte_superior.php";
  include_once '../bd/conexion.php';
  $objeto = new Conexion();
  $conexion = $objeto->Conectar();

  $consulta = "SELECT  C.id, C.nombre AS nombre_curso, N.nombre AS nombre_nivel FROM cursos AS C JOIN nivel AS N ON N.id = C.idnivel";
  $resultado = $conexion->prepare($consulta);
  $resultado->execute();
  $cursos = $resultado->fetchAll(PDO::FETCH_ASSOC);
  $conexion = null;
?>
<!--INICIO del cont principal-->

<style>

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
    
    height: 30px;
    
    background-color: white;
    color: black;
    text-align: center;
    line-height: 30px;
    font-weight: bold;
    font-size: 0.7rem;
    margin: 0;
  }

  .cell {
    width: 250px;
  
    padding: 0;
    'margin': '0';
    'width': 'auto'; /* Ajusta el ancho automáticamente */
    'text-align': 'center' /* Alinea el texto al centro */
  }

  .line-rectangle {
    width: 240px;
    background-color: #FFFF66;
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 10px;
  }

  .line {
    border-bottom: 1px solid black;
    padding: 5px 0;
    margin: 5px 0;
  }

  .disponible {
    background-color: rgb(153, 255, 153);
  }

  .no-disponible {
    background-color: rgb(255, 102, 102);
  }

  .tarjeta {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 10px;
    border: none;
  }

  .contenido-tarjeta {
    margin-bottom: 10px;

  }

  .titulo {
    font-size: 16px;
    font-weight: bold;
    margin-bottom: 5px;
  }

  .texto {
    font-size: 14px;
    margin-bottom: 3px;
  }

  .botones button {
    margin-right: 5px;
  }

  .texto.profesor {
    line-height: 0.3;
    
  }
  
  .form-group, .btn-group {
      margin-bottom: 0.5px;
  }
 
  /*  estilos para la seccion nuevoPaquete*/
  #separarPaquete {
      display: flex; /* Opcional, si necesitas alineación entre sus hijos */
      flex-direction: column; /* Coloca hijos en columnas */
      align-items: stretch; /* Asegura que se expanda en tamaño */
      /*border: 1px solid #ddd; /* Para visualización, opcional */
      padding: 10px;
  }
/*
  #horarioPaquete {
      flex-grow: 1; /* Permite que este hijo ocupe espacio necesario */
      overflow: auto; /* Habilita desplazamiento si el contenido crece */
  }/* Estilos para la tabla y celdas */
/* Estilos para la tabla y celdas */

table {
  width: 100%;
  border-collapse: collapse;
}

</style>

<br>
<div id="containerGeneral" class="container">
  <h4>Horarios</h4>
  <div class="form-group form-inline">
    <label for="vista" class="col-form-label mr-2">Elegir Vista:</label>
    <select class="form-control-sm" id="vista" style="width: 250px;">
      <option value="">Elegir Vista:</option>
      <option value="opcion1">Vista 1</option>
      <option value="opcion2">Vista 2</option>
    </select>
  </div>
  <div id = "selectorP" class="col-6" style="display: none;">
    <br>
    <label for='selectorProfesores' class='col-form-label'>Seleccione un Profesor:</label>
    <select class="form-control" id="selectorProfesores">
    </select>
  </div>
  <br>
  <div class="row">
    <div class="col">
      <div class="btn-group mr-2">
        <button id="nuevaClase" class="btn btn-primary btn-sm" type="button">
          Nueva Clase
        </button>
      </div>    
      <div class="btn-group">      
        <button id="nuevoPaquete" class="btn btn-primary btn-sm" type="button">
          Nuevo Paquete
        </button>
      </div>
    </div>
  </div>
  <br>
  <div class="container" id="separarClase" style="display: none;">
      <div class="form-group border p-3" style="max-width: 600px;">
          <h5 class="mb-4">Separar Clase</h5>
          <!-- Formulario de clases -->
          <form id="formClases">
                      <!-- seccion tipo clase -->
            <div id="tipoClase">
              <label>Tipo de Clase:</label>
              <div class="form-check">
                  <input class="form-check-input" type="radio" name="radioOption" id="radioOption1" value="option1" checked>
                  <label class="form-check-label" for="radioOption1">Nueva Clase única</label>
              </div>
              <div class="form-check">
                  <input class="form-check-input" type="radio" name="radioOption" id="radioOption2" value="option2">
                  <label class="form-check-label" for="radioOption2">Clase de un paquete adquirido</label>
              </div>
              <div class="form-group text-right">
                <button type="button" class="btn btn-success btn-sm" id="siguienteBtn1">Siguiente</button>
                <button type="button" class="btn btn-secondary  btn-sm cancelarBtn1">Cancelar</button>
              </div>
            </div>
            <div id="seccionEstudiante">
              <div class="container">
                <div class="row mt-2">
                  <div class="col-4">
                    <label for="DNI_estudiante" class="col-form-label">DNI:</label>
                  </div>
                  <div class="col-4">
                    <input type="number" class="form-control form-control-sm" id="DNI_estudiante" name="DNI_estudiante" required>
                  </div>
                  <div class="col-4">
                    <button type="button" class="btn btn-primary btn-sm" id="btnContinuar">Continuar</button>
                  </div>
                </div>
                <div class="row mt-2" id="div_mensaje"  style="display: none;">
                  <label id="mensaje" class="col-form-label"></label>
                </div>
                <div class="row mt-2">
                  <div class="col-4">
                    <label for="nombres_estudiante" class="col-form-label">Nombres:</label>
                  </div>
                  <div class="col-8">
                    <input type="text" class="form-control form-control-sm" id="nombres_estudiante" name="nombres_estudiante" disabled>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-4">
                    <label for="apellidos_estudiante" class="col-form-label">Apellidos:</label>
                  </div>
                  <div class="col-8">
                    <input type="text" class="form-control form-control-sm" id="apellidos_estudiante" name="apellidos_estudiante" disabled>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class="col-4">
                    <label for="telefono_estudiante" class="col-form-label">Teléfono:</label>
                  </div>
                  <div class="col-8">
                    <input type="text" class="form-control form-control-sm" id="telefono_estudiante" name="telefono_estudiante" disabled>
                  </div>
                </div>
              </div>

            
              <!--div class="form-group text-right"-->
              <div class="text-right mt-3">
                <button type="button" class="btn btn-success btn-sm" id="atras1">Atras</button>
                <button type="button" class="btn btn-success btn-sm" id="siguienteBtn2" disabled>Siguiente</button>
                <button type="button" class="btn btn-secondary  btn-sm cancelarBtn1" id="cancelarBtn">Cancelar</button>
              </div>
            </div>
          
                      <!-- seccion clase unica-->
            <div id="claseUnica" style="display: none;">
              <div class='container'>
                <div class="row mt-2" id="infoPaquete" style="display: none;">
                  <div class='col-4'>
                    <label for='selectPaquetes' class='col-form-label'>Paquetes encontrados:</label>
                  </div>
                  <div class='col-8'>
                    <select class='form-control form-control-sm' id='selectPaquetes' name='selectPaquetes'>
                      <option value="" disabled selected hidden>Seleccione una opción</option>
                    </select>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class='col-4'>
                    <label for='selectCurso' class='col-form-label'>Curso:</label>
                  </div>
                  <div class='col-8'>
                    <select class='form-control form-control-sm cursos' id='selectCurso' name='selectCurso' required>
                      <option value="" disabled selected hidden>Seleccione una opción</option>
                    </select>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class='col-4'>
                    <label for='selectProfesor' class='col-form-label'>Profesor:</label>
                  </div>
                  <div class='col-8'>
                    <select class='form-control form-control-sm' id='selectProfesor' name='selectProfesor' disabled required>
                      <option value="" disabled selected hidden>Seleccione una opción</option>
                    </select>
                  </div>
                </div>
                <div class="row mt-2">
                  <div class='col-4'>
                    <label for='selectFecha' class='col-form-label'>Fecha:</label>
                  </div>
                  <div class='col-8'>
                    <input type="date" class="form-control form-control-sm" id='selectFecha' name='selectFecha'> 
                  </div>
                </div>
                <div class="row mt-2">
                
                  <div class='col-4'>
                    <label for='horaInicio' class='col-form-label'>Hora Inicio:</label>
                  </div>
                  <div class='col-8'>
                  <!-- Campo para seleccionar la hora -->
                
                    <input class='form-control form-control-sm' type="time" id="horaInicio" name="horaInicio" min="07:00" max="21:00" step="900">
                    <!--select class='form-control form-control-sm' id='horaInicio' name='horaInicio' required>
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
                          <option valu|e='24'>07:00 pm</option>
                          <option value='25'>07:30 pm</option>
                          <option value='26'>08:00 pm</option>
                          <option value='27'>08:30 pm</option>
                          <option value='28'>09:00 pm</option>
                      </select-->
                  </div>
                </div>
                <div class='row mt-2'>
                  <div class='col-4'>
                    <label for='horaFin' class='col-form-label'>Hora Fin:</label>
                  </div>  
                  <div class='col-8'>
                  <input class='form-control form-control-sm' type="time" id="horaFin" name="horaFin" min="07:00" max="21:00" step="900">
                    <!--select class='form-control form-control-sm' id='horaFin' name='horaFin' required>
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
                      </select-->
                  </div>
                </div>
                <div class="form-group text-right" id="botonesClaseUnica" style="display: none;">
                  <button type="button" class="btn btn-success btn-sm atras2">Atras</button>
                  <button type="button" class="btn btn-success btn-sm" id="siguienteBtn3">Siguiente</button>
                  <button type="button" class="btn btn-secondary  btn-sm cancelarBtn1" id="cancelarBtn">Cancelar</button>
                </div>
                <div class="form-group text-right" id="botonesPaquete" style="display: none;">
                  <div class="form-group">
                    <label for="observacionesPaquete">Observaciones:</label>
                    <textarea class="form-control" id="observacionesPaquete" rows="3"></textarea>
                  </div>
                  <button type="button" class="btn btn-success btn-sm atras2">Atras</button>
                  <button type="submit" class="btn btn-success">Guardar</button>
                  <button type="button" class="btn btn-secondary  btn-sm cancelarBtn1" id="cancelarBtn">Cancelar</button>
                </div>
              </div>
            </div>
                      <!-- seccion forma Pago -->
            <div id="formaPago" style="display: none;">
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="numEstudiantes">Número de estudiantes:</label>
                    <select class="form-control" id="numEstudiantes" name="numEstudiantes">
                      <option value="">Elije la cantidad de estudiantes:</option>
                      <?php for ($i = 1; $i <= 10; $i++) { ?>
                        <option value="<?php echo $i; ?>">
                          <?php echo $i; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="costoClase" class="form-label">Costo total</label>
                    <input type="text" class="form-control" id="costoClase" >
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Método de pago:</label>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="pagoEfectivo">
                  <label class="form-check-label" for="pagoEfectivo">Efectivo</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="pagoYape">
                  <label class="form-check-label" for="pagoYape">Yape</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="pagoPlin">
                  <label class="form-check-label" for="pagoPlin">Plin</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="pagoTransferencia">
                  <label class="form-check-label" for="pagoTransferencia">Transferencia bancaria</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="pagoOtro">
                  <label class="form-check-label" for="pagoOtro">Otro</label>
                </div>
                 <!-- Campo de texto oculto -->
                <div id="otroInputWrapper" class="mt-2" style="display: none;">
                  <input type="text" class="form-control" id="pagoOtroInput" placeholder="Especificar método de pago">
                </div>
              </div>
              <div class="form-group">
                <label for="observaciones">Observaciones:</label>
                <textarea class="form-control" id="observaciones" rows="3"></textarea>
              </div>
              <label>¿El pago de la clase ya se realizado?:</label>
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="radioOptionPago" id="radioOptionSi" value="SI"
                    checked>
                  <label class="form-check-label" for="radioOptionSi">Si</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="radioOptionPago" id="radioOptionNo" value="NO">
                  <label class="form-check-label" for="radioOptionNo">No</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="radioOptionPago" id="radioOptionIn" value="IN">
                  <label class="form-check-label" for="radioOptionNo">Queda un saldo</label>
                </div>
              </div>
              <div class="form-group text-right">
                <button type="button" class="btn btn-success btn-sm" id="atras3">Atrás</button>
                <button type="submit" class="btn btn-success btn-sm">Guardar</button>
                <button type="button" class="btn btn-secondary btn-sm cancelarBtn1">Cancelar</button>
              </div>
            </div>
          </form>
      </div>
  </div>
  
    
  <div class="container" id="separarPaquete" style="display: none;">
    <div class="form-group border p-3" style="max-width: 800px;">
        <h5 class="mb-4">Nuevo Paquete</h5>
        <form id="formPaquete">
          <div id="seccionEstudiantePaquete">
            <div class="container">
              <div class="row mt-2">
                <div class="col-4">
                  <label for="DNI_estudiantePaquete" class="col-form-label">DNI:</label>
                </div>
                <div class="col-4">
                  <input type="number" class="form-control form-control-sm" id="DNI_estudiantePaquete" name="DNI_estudiantePaquete" required>
                </div>
                <div class="col-4">
                  <button type="button" class="btn btn-primary btn-sm" id="btnContinuarPaquete">Continuar</button>
                </div>
              </div>
              <div class="row mt-2" id="div_mensajePaquete"  style="display: none;">
                <label id="mensajePaquete" class="col-form-label"></label>
              </div>
              <div class="row mt-2">
                <div class="col-4">
                  <label for="nombres_estudiantePaquete" class="col-form-label">Nombres:</label>
                </div>
                <div class="col-8">
                  <input type="text" class="form-control form-control-sm" id="nombres_estudiantePaquete" name="nombres_estudiantePaquete" disabled>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-4">
                  <label for="apellidos_estudiantePaquete" class="col-form-label">Apellidos:</label>
                </div>
                <div class="col-8">
                  <input type="text" class="form-control form-control-sm" id="apellidos_estudiantePaquete" name="apellidos_estudiantePaquete" disabled>
                </div>
              </div>
              <div class="row mt-2">
                <div class="col-4">
                  <label for="telefono_estudiantePaquete" class="col-form-label">Teléfono:</label>
                </div>
                <div class="col-8">
                  <input type="text" class="form-control form-control-sm" id="telefono_estudiantePaquete" name="telefono_estudiantePaquete" disabled>
                </div>
              </div>
              <div class="form-group text-right">
                <button type="button" class="btn btn-success" id="siguientePaqueteBtn1" disabled>Siguiente</button>
                <button type="button" class="btn btn-secondary cancelarBtnPaquete">Cancelar</button>
              </div>
            </div>
          </div>
                      <!-- Seccion de las clases de paquete-->
          <!--div id="clasesPaquete" style="display: none;">
            <div class="form-row row">
              <div class="form-group col-md-6">
                <label for="numClases">Número de Clases:</label>
                <select class="form-control" id="numClases" name="numClases">
                  <option value="">Elije el numero de clases:</option>
                  <?php for ($i = 1; $i <= 20; $i++) { ?>
                    <option value="<?php echo $i; ?>">
                      <?php echo $i; ?>
                    </option>
                  <?php } ?>
                </select>
              </div>
            </div>

            <div id="horarioPaquete">
              <div class="row align-items-end class-row">
                
                <div class="col-md">
                  <label for="selectCursoPaquete1" class="col-form-label">Curso:</label>
                  <select class="form-control cursos" id="selectCursoPaquete1" name="selectCursoPaquete1">
                    <option value="" disabled selected hidden>Seleccione una opción</option>
                  </select>
                </div>
                
                <div class="col-md">
                  <label for="selectProfesorPaquete1" class="col-form-label">Profesor:</label>
                  <select class="form-control" id="selectProfesorPaquete1" name="selectProfesorPaquete1" disabled>
                    <option value="" disabled selected hidden>Seleccione una opción</option>
                  </select>
                </div>

                <div class="col-md">
                  <label for="selectFechaPaquete1" class="col-form-label">Día:</label>
                  <input type="date" class="form-control" id="selectFechaPaquete1" name="selectFechaPaquete1">
                </div>

                <div class="col-md">
                  <label for="horaInicioPaquete1" class="col-form-label">Hora Inicio:</label>
                  <input class='form-control form-control-sm' type="time" id="horaInicioPaquete1" name="horaInicioPaquete1" min="07:00" max="21:00" step="900">
                </div>

                <div class="col-md">
                  <label for="horaFinPaquete1" class="col-form-label">Hora Fin:</label>
                  <input class='form-control form-control-sm' type="time" id="horaFinPaquete1" name="horaFinPaquete1" min="07:00" max="21:00" step="900">
                </div>
              </div>
              <div id="nuevasClases">
              </div>
              <div class="row">
                <button type="button" class="btn btn-primary" id="addClassBtn">
                  <i class="fas fa-plus"></i> Agregar nueva clase
                </button>
              </div>
              
              <div class="row mt-3">
                <div class="col">
                  <label id="clasesSeparadas">Clases separadas: 0</label>
                </div>
              </div>
            
              <div class="form-group text-right">
                <button type="button" class="btn btn-success" id="atras1Paquete">Atrás</button>
                <button type="button" class="btn btn-success" id="siguientePaqueteBtn2">Siguiente</button>
                <button type="button" class="btn btn-secondary cancelarBtnPaquete">Cancelar</button>
              </div>
            </div>
          </div-->
          
          <div id="pagoPaquete" style="display: none;">
              <div class="row">
                <div class="form-group col">
                  <label for="numClases">Número de Clases:</label>
                  <select class="form-control" id="numClases" name="numClases">
                    <option value="">Elije el numero de clases:</option>
                    <?php for ($i = 1; $i <= 20; $i++) { ?>
                      <option value="<?php echo $i; ?>">
                        <?php echo $i; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="numEstudiantesPaquete">Número de estudiantes:</label>
                    <select class="form-control" id="numEstudiantesPaquete" name="numEstudiantesPaquete">
                      <option value="">Elije la cantidad de estudiantes:</option>
                      <?php for ($i = 1; $i <= 10; $i++) { ?>
                        <option value="<?php echo $i; ?>">
                          <?php echo $i; ?>
                        </option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="costoPaquete" class="form-label">Costo total</label>
                    <input type="text" class="form-control" id="costoPaquete" name="costoPaquete" >
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label class="form-label">Método de pago:</label>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="pagoEfectivoPaquete" name="pagoEfectivoPaquete">
                  <label class="form-check-label" for="pagoEfectivoPaquete">Efectivo</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="pagoYapePaquete" name="pagoYapePaquete">
                  <label class="form-check-label" for="pagoYapePaquete">Yape</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="pagoPlinPaquete" name="pagoPlinPaquete">
                  <label class="form-check-label" for="pagoPlinPaquete">Plin</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="pagoTransferenciaPaquete" name="pagoTransferenciaPaquete">
                  <label class="form-check-label" for="pagoTransferenciaPaquete">Transferencia bancaria</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="pagoOtroPaquete" name="pagoOtroPaquete">
                  <label class="form-check-label" for="pagoOtroPaquete">Otro</label>
                </div>
              </div>
              <div class="form-group">
                <label for="observacionesPaquete">Observaciones:</label>
                <textarea class="form-control" id="observacionesPaquete" name="observacionesPaquete" rows="3"></textarea>
              </div>
              <label>¿El pago de la clase ya se realizado?:</label>
              <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="radioOptionPagoPaquete" id="radioOptionSiPaquete" value="SI"
                    checked>
                  <label class="form-check-label" for="radioOptionSi">Si</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="radioOptionPagoPaquete" id="radioOptionNoPaquete" value="NO">
                  <label class="form-check-label" for="radioOptionNo">No</label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="radioOptionPagoPaquete" id="radioOptionInPaquete" value="IN">
                  <label class="form-check-label" for="radioOptionNo">Queda un saldo</label>
                </div>
              </div>
              <div class="form-group text-right">
                <button type="button" class="btn btn-success" id="atras2Paquete">Atrás</button>
                <button type="submit" class="btn btn-success">Guardar</button>
                <button type="button" class="btn btn-secondary cancelarBtnPaquete">Cancelar</button>
              </div>
            </div>
          <div>
        </form>
    </div>
  </div>
  <div class="row">
    <div class="col">
      <div class="btn-group mr-2">
        <button id="btnAnterior" class="btn btn-primary  btn-sm" type="button">
          <i class="fas fa-arrow-left"></i> 
        </button>
      </div>
      <div class="btn-group">
        <button id="btnSiguiente" class="btn btn-primary btn-sm" type="button">
          <i class="fas fa-arrow-right"></i>
        </button> 
      </div>
    </div>
    <div class="col">
      <label id="fechaTrabajo" class="col-form-label"></label>
    </div>
  </div>
  
  
</div>
<div id="table_container">
    <table id="my_table" class="table table-bordered  pagin-table"  style="
      max-height: calc(100vh - 20px); /* Altura fija del contenedor principal */
      max-width: calc(100vw);

        overflow-y: auto;            /* Habilita el scroll vertical */
        overflow-x: auto;            /* Habilita el scroll horizontal */
      ">
    </table>
  </div>
<!-- Modal -->
<!--div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Separar Clase</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formClases">
        <div id="view1">
          <div class="modal-body">
            <div class="form-group">
              <label id="nombreProfesor" class="col-form-label"></label>
            </div>
            <div class="form-group">
              <label for="nombreCurso">Curso:</label>
              <select class="form-control" id="nombreCurso" name="nombreCurso">
                <option value="">Elije el curso</option>
              </select>
            </div>
            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioOption" id="radioOption1" value="option1"
                  checked>
                <label class="form-check-label" for="radioOption1">
                  Nueva Clase única
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioOption" id="radioOption2" value="option2">
                <label class="form-check-label" for="radioOption2">
                  Nuevo Paquete de Clases
                </label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioOption" id="radioOption3" value="option3">
                <label class="form-check-label" for="radioOption3">
                  Esta clase pertenece a un paquete de clases previamente adquirido
                </label>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="btnSiguiente1">Siguiente</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
        <div id="view2opc1" style="display: none;">
          <div class="modal-body">
            <div class="form-group">
              <label for="nombreAlumno" class="form-label">Nombre del alumno:</label>
              <input type="text" class="form-control" id="nombreAlumno">
            </div>
            <div class="form-group">
              <label><b>Horario para la clase:</b></label>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="horaInicio1">Hora de Inicio:</label>
                  <select class="form-control" id="horaInicio1" name="horaInicio1">
                    <option value="" selected disabled hidden>Selecciona la hora de inicio:</option>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="horaFin1">Hora de Fin:</label>
                  <select class="form-control" id="horaFin1" name="horaFin1">
                    <option value="" selected disabled hidden>Selecciona la hora de fin</option>
                  </select>
                </div>
              </div>
            </div>
            <p id="mensajeClase"></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btnAtras1">Atrás</button>
            <button type="button" class="btn btn-primary" id="btnSiguiente2">Siguiente</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
        <div id="view2opc2" style="display: none;">
          <div class="modal-body">
            <div class="form-group">
              <label for="nombreAlumno2" class="form-label">Nombre del alumno:</label>
              <input type="text" class="form-control" id="nombreAlumno2">
            </div>
            
            <div class="form-group">
              <label for="numeroHoras">Numero de horas:</label>
              <input type="number" class="form-control" id="numeroHoras">
            </div>
          
            <div class="form-group">
              <p><b>Horario para la primera clase:</b></p>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="horaInicio2">Hora de Inicio:</label>
                  <select class="form-control" id="horaInicio2" name="horaInicio">
                    <option value="" selected disabled hidden>Selecciona la hora de inicio</option>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="horaFin2">Hora de Fin:</label>
                  <select class="form-control" id="horaFin2" name="horaFin">
                    <option value="" selected disabled hidden>Selecciona la hora de fin:</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label><b>Elija el horario semanal del paquete:</b></label>
            </div>
            <div id="contenedorDias">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btnAtras2">Atrás</button>
            <button type="button" class="btn btn-primary" id="btnSiguiente3">Siguiente</button>
          </div>
        </div>

        <div id="view2opc3" style="display: none;">
          <div class="modal-body">
            <div class="form-group">
              <p><b>Horario para la clase:</b></p>
              <div class="form-group">
                <label for="alumnosPaquete">Alumnos con paquete:</label>
                <select class="form-control" id="alumnosPaquete" name="alumnosPaquete">
                  <option value="">Seleccione al alumno:</option>
                  <?php /*foreach ($cursos as $curso): ?>
     <option value="<?php echo $curso['id']; ?>">
       <?php echo $curso['nombre_curso'] . ' - ' . $curso['nombre_nivel']; ?>
     </option>
   <?php endforeach;*/?>

                </select>
              </div>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="horaInicio">Hora de Inicio:</label>
                    <select class="form-control" id="horaInicio" name="horaInicio">
                      <option value="" selected disabled hidden>Selecciona la hora de incio:</option>
                    </select>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="horaFin">Hora de Fin:</label>
                    <select class="form-control" id="horaFin" name="horaFin">
                      <option value="" selected disabled hidden>Selecciona la hora de fin</option>
                    </select>
                  </div>
                </div>
              </div>
              <p id="mensajeClase"></p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btnAtras3">Atrás</button>
            <button type="button" class="btn btn-primary" id="btnSepararClase2">Aceptar</button>
          </div>
        </div>

        <div id="view3" style="display: none;">
          <div class="modal-body">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="numero-alumnos">Número de alumnos:</label>
                  <select class="form-control" id="numero-alumnos" name="numero-alumnos">
                    <option value="">Elije la cantidad de alumnos:</option>
                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                      <option value="<?php echo $i; ?>">
                        <?php echo $i; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label class="form-label" id="tituloCosto">Costo total</label>
                  <input type="text" class="form-control" id="costo-clase">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Método de pago:</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pago-efectivo">
                <label class="form-check-label" for="pago-efectivo">Efectivo</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pago-yape">
                <label class="form-check-label" for="pago-yape">Yape</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pago-plin">
                <label class="form-check-label" for="pago-plin">Plin</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pago-transferencia">
                <label class="form-check-label" for="pago-transferencia">Transferencia bancaria</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pago-otro">
                <label class="form-check-label" for="pago-otro">Otro</label>
              </div>
            </div>
            <div class="form-group">
              <label for="observaciones">Observaciones:</label>
              <textarea class="form-control" id="observaciones" rows="3"></textarea>
            </div>
            <label>¿El pago de la clase ya se realizado?:</label>
            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioOptionPago" id="radioOptionSi" value="SI"
                  checked>
                <label class="form-check-label" for="radioOptionSi">Si</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioOptionPago" id="radioOptionNo" value="NO">
                <label class="form-check-label" for="radioOptionNo">No</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioOptionPago" id="radioOptionIn" value="IN">
                <label class="form-check-label" for="radioOptionNo">Queda un saldo</label>
              </div>
            </div>
            
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="btnAtras4">Atrás</button>
              <button type="button" class="btn btn-primary" id="btnSepararClase3">Aceptar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div-->

<!--div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title">Separar Clase</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formClases">
       
        <div class="form-group">
          <div class="form-check">
            <input class="form-check-input" type="radio" name="radioOption" id="radioOption1" value="option1"
              checked>
            <label class="form-check-label" for="radioOption1">
              Nueva Clase única
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="radioOption" id="radioOption2" value="option2">
            <label class="form-check-label" for="radioOption2">
              Nuevo Paquete de Clases
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="radioOption" id="radioOption3" value="option3">
            <label class="form-check-label" for="radioOption3">
              Esta clase pertenece a un paquete de clases previamente adquirido
            </label>
          </div>
        </div>
                  
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
                    
        <div id="claseUnica">
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
        <div id="view2opc2" style="display: none;">
          <div class="form-group">
              <label for="numeroHoras">Numero de horas:</label>
              <input type="number" class="form-control" id="numeroHoras">
          </div>
          <div id="horarioClasePaquete">
          <div>
        </div>
            <div class="form-group">
              <p><b>Horario para la primera clase:</b></p>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="horaInicio2">Hora de Inicio:</label>
                  <select class="form-control" id="horaInicio2" name="horaInicio">
                    <option value="" selected disabled hidden>Selecciona la hora de inicio</option>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="horaFin2">Hora de Fin:</label>
                  <select class="form-control" id="horaFin2" name="horaFin">
                    <option value="" selected disabled hidden>Selecciona la hora de fin:</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label><b>Elija el horario semanal del paquete:</b></label>
            </div>
            <div id="contenedorDias">
            </div>
          
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btnAtras2">Atrás</button>
            <button type="button" class="btn btn-primary" id="btnSiguiente3">Siguiente</button>
          </div>
        </div>

        <div id="view2opc3" style="display: none;">
          <div class="modal-body">
            <div class="form-group">
              <p><b>Horario para la clase:</b></p>
              <div class="form-group">
                <label for="alumnosPaquete">Alumnos con paquete:</label>
                <select class="form-control" id="alumnosPaquete" name="alumnosPaquete">
                  <option value="">Seleccione al alumno:</option>
                  <?php /*foreach ($cursos as $curso): ?>
     <option value="<?php echo $curso['id']; ?>">
       <?php echo $curso['nombre_curso'] . ' - ' . $curso['nombre_nivel']; ?>
     </option>
   <?php endforeach;*/?>

                </select>
              </div>
              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <label for="horaInicio">Hora de Inicio:</label>
                    <select class="form-control" id="horaInicio" name="horaInicio">
                      <option value="" selected disabled hidden>Selecciona la hora de incio:</option>
                    </select>
                  </div>
                </div>
                <div class="col">
                  <div class="form-group">
                    <label for="horaFin">Hora de Fin:</label>
                    <select class="form-control" id="horaFin" name="horaFin">
                      <option value="" selected disabled hidden>Selecciona la hora de fin</option>
                    </select>
                  </div>
                </div>
              </div>
              <p id="mensajeClase"></p>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btnAtras3">Atrás</button>
            <button type="button" class="btn btn-primary" id="btnSepararClase2">Aceptar</button>
          </div>
        </div>

        <div id="view3" style="display: none;">
          <div class="modal-body">
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="numero-alumnos">Número de alumnos:</label>
                  <select class="form-control" id="numero-alumnos" name="numero-alumnos">
                    <option value="">Elije la cantidad de alumnos:</option>
                    <?php for ($i = 1; $i <= 10; $i++) { ?>
                      <option value="<?php echo $i; ?>">
                        <?php echo $i; ?>
                      </option>
                    <?php } ?>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label class="form-label" id="tituloCosto">Costo total</label>
                  <input type="text" class="form-control" id="costo-clase">
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Método de pago:</label>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pago-efectivo">
                <label class="form-check-label" for="pago-efectivo">Efectivo</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pago-yape">
                <label class="form-check-label" for="pago-yape">Yape</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pago-plin">
                <label class="form-check-label" for="pago-plin">Plin</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pago-transferencia">
                <label class="form-check-label" for="pago-transferencia">Transferencia bancaria</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="pago-otro">
                <label class="form-check-label" for="pago-otro">Otro</label>
              </div>
            </div>
            <div class="form-group">
              <label for="observaciones">Observaciones:</label>
              <textarea class="form-control" id="observaciones" rows="3"></textarea>
            </div>
            
            <label>¿El pago de la clase ya se realizado?:</label>
            <div class="form-group">
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioOptionPago" id="radioOptionSi" value="SI"
                  checked>
                <label class="form-check-label" for="radioOptionSi">Si</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioOptionPago" id="radioOptionNo" value="NO">
                <label class="form-check-label" for="radioOptionNo">No</label>
              </div>
              <div class="form-check">
                <input class="form-check-input" type="radio" name="radioOptionPago" id="radioOptionIn" value="IN">
                <label class="form-check-label" for="radioOptionNo">Queda un saldo</label>
              </div>
            </div>
            
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="btnAtras4">Atrás</button>
              <button type="button" class="btn btn-primary" id="btnSepararClase3">Aceptar</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div-->                                                                      
<!--div class="modal fade" id="myModalPaquete" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true">

  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Separar Clase</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="formPaquete">
        <div class="modal-body">
          <div class="form-group">
            <div class="form-group">
              <label for="nombreAlumno2" class="form-label">Nombre del alumno:</label>
              <input type="text" class="form-control" id="nombreAlumno2">
            </div>
            <div class="form-group">
              <p><b>Horario para la primera clase:</b></p>
            </div>
            <div class="row">
              <div class="col">
                <div class="form-group">
                  <label for="horaInicio2">Hora de Inicio:</label>
                  <select class="form-control" id="horaInicio2" name="horaInicio">
                    <option value="">Elije la hora de inicio:</option>
                  </select>
                </div>
              </div>
              <div class="col">
                <div class="form-group">
                  <label for="horaFin2">Hora de Fin:</label>
                  <select class="form-control" id="horaFin2" name="horaFin">
                    <option value="">Elije la hora de fin:</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label><b>Elija el horario semanal del paquete:</b></label>
            <br>
            <div class="form-row">
              <div class="col-2">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="repetir-lunes" name="repetir[]" value="lunes">
                  <label class="form-check-label" for="repetir-lunes">Lunes</label>
                </div>
              </div>
              <div class="col-4">
                <select class="form-control" id="hora-inicio-lunes">
                  <option value="">Hora de inicio:</option>
                </select>
              </div>
              <div class="col-4">
                <select class="form-control" id="hora-fin-lunes">
                  <option value="">Hora de fin:</option>
                </select>
              </div>
            </div>
            <br>
            <div class="form-row">
              <div class="col-2">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="repetir-martes" name="repetir[]" value="martes">
                  <label class="form-check-label" for="repetir-martes">Martes</label>
                </div>
              </div>
              <div class="col-4">
                <select class="form-control" id="hora-inicio-martes">
                  <option value="">Hora de inicio:</option>
                </select>
              </div>
              <div class="col-4">
                <select class="form-control" id="hora-fin-martes">
                  <option value="">Hora de fin:</option>
                </select>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="btnAtras2">Atrás</button>
            <button type="button" class="btn btn-primary" id="btnSiguiente3">Siguiente</button>
          </div>
        </div>
    </div>
    </form>
  </div>
</div>
</div-->
<!--FIN del cont principal-->
<?php require_once "vistas/parte_inferior.php" ?>

<script type="text/javascript" src="scripts/mainHorario.js"></script>
</body>

</html>