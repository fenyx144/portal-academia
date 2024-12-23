    $(document).ready(function () {
    //Función para controlar el selector nivel en el modal adCurso
    var filaCursos;//variable para guardar la referencia a la fila de la tabla de los cursos de cada profesor
    var opcionCursos;//variable para la opcion de crear o editar un curso para un profesor
    var idCursoProfesor;//variable para guardar el id del curso del profesor para ser modiicado
    //funcion para modificar el selector del nivel de los cursos
    $('#nivelProfesores').on('change', function () {
        var nivelSeleccionado = $(this).val();
        if (nivelSeleccionado !== '') {
            cargarOpcionesCurso(nivelSeleccionado)
                .then(function() {
                    $('#cursoProfesores').prop('disabled', false);
                    $('#btnGuardarAdd').prop('disabled', true);
                });
        }
    });
    $('#cursoProfesores').on('change', function () {
        $('#btnGuardarAdd').prop('disabled', $(this).val() == '');
    });
    //funcion para cargar los cursos de un determinado nivel 
    function cargarOpcionesCurso(nivelId) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: 'bd/obtener_cursos.php',
                type: 'GET',
                dataType: 'json',
                data: { nivelId: nivelId },
                success: function (cursos) {
                    var cursoSelector = $('#cursoProfesores');
                    cursoSelector.html('<option value="" selected disabled hidden>Selecciona una opción</option>');
                    // Agregar las opciones de los cursos al selector
                    cursos.forEach(function (curso) {
                        var opcion = $('<option>').val(curso.id).text(curso.nombre);
                        cursoSelector.append(opcion);
                    });
                    resolve();
                },
                error: function (xhr, status, error) {
                    console.log('Error al obtener los cursos: ' + error);
                    reject(error);
                }
            });
        });
    }
    //Modal para añadir un curso a un profesor
    $(document).on("click", ".btnAddCurso", function () {
        $("#formAddCurso").trigger("reset");
        $("#modalHeader").css({"background-color": "#4e73df", "color": "white"});
        $("#tituloAddCurso").text("Añadir Curso");

        $('#cursoProfesores').prop('disabled', true);
        $('#cursoProfesores').html('<option value="" selected disabled hidden>Selecciona una opción</option>');
        $('#btnGuardarAdd').prop('disabled', true);

        opcionCursos = 1;
        $("#AddCurso").css('z-index', '1060').modal("show");
    });
    //funcion para crear una fila con el contenido del curso de un profesor
    function crearFilaCurso(curso) {
        // Cacheo de elementos
        var fila = $('<tr>'),
            celdaNivel = $('<td>', {
                text: curso.nombre_nivel,
                'idNivel': curso.id_nivel
            }),
            celdaCurso = $('<td>', {
                text:  curso.nombre_curso,
                'idCurso': curso.id_curso
            }),
            acciones = $('<td>'),
            btnGroup = $('<div class="btn-group" role="group">');
        console.log('idNivel',celdaNivel.attr('idNivel'), 'idCurso', celdaCurso.attr('idCurso'))
        // Plantilla de cadena de texto para botones
        var btnEditar = $('<button>', {
            class: 'btn btn-primary',
            id: 'btnEditar',
            text: 'Editar'
        });
        var btnEliminar = $('<button>', {
            class: 'btn btn-danger',
            id: 'btnEliminar',
            text: 'Eliminar'
        });
    
        // Adjuntar botones al grupo de botones
        btnGroup.append(btnEditar, btnEliminar);
        
        // Configuración de atributos y texto para celdas
        fila.attr('idCursoProfesor', curso.id_curso_profesor);
        acciones.append(btnGroup);
    
        // Adjuntar celdas a la fila
        fila.append(celdaNivel, celdaCurso, acciones);
    
        return fila;
    }
    //id del profesor de la tabla de profesores
    var idProfesorTabla;
    $(document).on("click", ".btnVerCursos", function () {
        $('#tablaRegistros').find('tbody').empty();
        $("#formVerCursosProfesor").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        $('#cursosModalTitulo').text("Lista de cursos");

        idProfesorTabla = $(this).closest('tr').data('id');
        
        $.ajax({
            url: 'bd/obtener_cursos_profesor.php',
            method: 'POST',
            dataType: 'json',
            data: { id: idProfesorTabla, opcion: 1, idNivel:-1 },
            success: function (cursos) {
                var tbody =  $('#tablaRegistros tbody');
                tbody.empty(); // Limpiar el contenido anterior
            
                cursos.forEach(function (curso) {
                    var fila = crearFilaCurso(curso)
                    tbody.append(fila);
                });
            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });
        $("#cursosModal").modal("show");
    });

    $("#formAddCurso").submit(function (e) {
        e.preventDefault();
        var idCursoSeleccionado = $.trim($("#cursoProfesores option:selected").val());
        var repetido = false;
        $('#tablaRegistros tbody tr td:nth-child(2)').each(function() {
            var idCurso = $(this).attr('idCurso');
            if (idCurso === idCursoSeleccionado) {
                repetido = true;
                return;
            }
        });
        
        if(repetido) {
            // Mostrar el mensaje de confirmación sin cerrar el modal
            if (confirm("El curso ya se encuentra en la lista de profesores")) {
                // Aquí puedes agregar código adicional si es necesario
            } 
        } else {
            // Realizar consulta AJAX para agregar o editar el curso de un profesor
            $.ajax({
                url: 'bd/agregarprofesor-curso.php',
                type: "POST",
                dataType: "json",
                data: { 
                    idprofesor: idProfesorTabla,
                    idcurso: idCursoSeleccionado,
                    idcursoprofesor: idCursoProfesor,
                    opcion: opcionCursos
                },
                success: function (response) {
                    var nombreNivel = $('#nivelProfesores option:selected').text();
                    var nombreCurso = $('#cursoProfesores option:selected').text();
                    var idNivel = $('#nivelProfesores').val();
                    if (opcionCursos == 1) {
                        var tablaCuerpo =  $('#tablaRegistros tbody');
                        var fila = crearFilaCurso({
                            id_curso_profesor: response.id_insertado,
                            nombre_nivel: nombreNivel,
                            id_nivel: idNivel,
                            nombre_curso: nombreCurso,
                            id_curso: idCursoSeleccionado
                        });
                        tablaCuerpo.append(fila);
                    }
                    if (opcionCursos == 2) {
                        filaCursos.find('td:eq(0)').text(nombreNivel).attr('idNivel', idNivel);
                        filaCursos.find('td:eq(1)').text(nombreCurso).attr('idCurso', idCursoSeleccionado);
                    }
                    // Ocultar el modal solo después de que se complete la solicitud AJAX
                    $("#AddCurso").modal("hide");
                },
                error: function (xhr, status, error) {
                    console.log('Error al obtener los cursos: ' + error);
                    // En caso de error, aún debes cerrar el modal
                    $("#AddCurso").modal("hide");
                }
            });
        }
    });
    
    $(document).on('click', '#btnEditar', function(e) {
        e.preventDefault();
        filaCursos = $(this).closest('tr');
        idCursoProfesor = filaCursos.attr('idCursoProfesor');
        console.log('idCursoProfesor', idCursoProfesor);
        $('#btnGuardarAdd').prop('disabled', true);
        $("#modalHeader").css("background-color", "#4e73df");
        $("#modalHeader").css("color", "white");
        $("#tituloAddCurso").text("Editar Curso");
        $("#formAddCurso").trigger("reset"); 

        //Recuperamos el id del nivel y curso para mostrarlo en los selectores del modal AddCurso
        var idNivel = filaCursos.find('td:eq(0)').attr('idNivel');
        var idCurso = filaCursos.find('td:eq(1)').attr('idCurso');
        
        $("#nivelProfesores").val(idNivel);
        
        cargarCursosProfesor(idProfesorTabla, idNivel).then(function() {
            $("#cursoProfesores").val(idCurso);        
        });

        opcionCursos = 2;

        $("#AddCurso").css('z-index', '1060');
        $("#AddCurso").modal("show");
    });

    //funcion para utilizar en el editar de un cursos de un profesor
    //para cargar los cursos de un nivel seleccionado
    function cargarCursosProfesor(idProfesor, idNivel) {
        return new Promise(function(resolve, reject) {
            $.ajax({
                url: 'bd/obtener_cursos_profesor.php',
                method: 'POST',
                dataType: 'json',
                data: { id: idProfesor, opcion: 2, idNivel: idNivel },
                success: function (cursos) {
                    var cursoSelector = $('#cursoProfesores');
                    cursoSelector.empty();
                    // Agregar las opciones de los cursos al selector
                    cursos.forEach(function (curso) {
                        var opcion = $('<option>').val(curso.id_curso).text(curso.nombre_curso);
                        cursoSelector.append(opcion);
                    });
                    cursoSelector.prop('disabled', false);
                    $('#btnGuardarAdd').prop('disabled', true);
                    resolve();
                },
                error: function (xhr, status, error) {
                    console.log('Error al obtener los cursos: ' + error);
                    reject(error);
                }
            });
        });
    }

    //funcion para eliminar un curso de un profesor
    $(document).on('click', '#btnEliminar', function(e) {
        e.preventDefault();
        filaCursos = $(this).closest('tr');
        idCursoProfesor = filaCursos.attr('idCursoProfesor');
        console.log('idCursoProfesor', idCursoProfesor);
        var nivel = filaCursos.find('td:eq(0)').text();
        var curso = filaCursos.find('td:eq(1)').text();
        var respuesta = confirm("¿Está seguro de eliminar el curso: \"" + nivel + " - " + curso + "\"?");
        if (respuesta) {
            $.ajax({
                url: 'bd/agregarprofesor-curso.php',
                type: "POST",
                dataType: "json",
                data: {
                    idcursoprofesor: idCursoProfesor,
                    opcion: 3
                },
                success: function (response) {
                    filaCursos.remove();
                }
            });
        }
    });
});