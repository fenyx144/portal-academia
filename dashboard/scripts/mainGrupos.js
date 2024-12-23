$(document).ready(function () {
    window.tablaGrupos = $("#tablaGrupos").DataTable({
        "columnDefs": [
            {
                "targets": -1,
                "data": null,
                "defaultContent": "<div class='text-center'>" +
                    "<div class='btn-group'>" +
                    "<button class='btn btn-primary btnEditarGrupo'>Editar</button>" +
                    "<button class='btn btn-danger btnBorrarGrupo'>Borrar</button>" +
                    "</div>" +
                    "</div>",
                "visible": true,
                "searchable": false
            },
            {
                "targets": -2,
                "data": null,
                "defaultContent": "<div class='text-center'><button class='btn btn-success btnVerEstudiantes'>Ver</button></div>",
                "visible": true,
                "searchable": false
            },
            {
                "targets": -3,
                "data": null,
                "defaultContent": "<div class='text-center'><button class='btn btn-warning btnVerHorario'>Ver</button></div>",
                "visible": true,
                "searchable": false
            },
        ],
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando...",
        }
    });
    var horarios_array = Array.from({ length: 28 }, () => Array(8).fill(0));
    var id_clase = -1, id_grupo;
    var filaGrupo; //capturar la fila para editar o borrar el registro
    /* GESTIÓN DE GRUPOS*/
    $("#btnNuevoGrupo").click(function () {
        $("#formGrupos").trigger("reset");
        $("#tituloModalGrupo").text("Crear Grupo");
        //$(".modal-header").css("background-color", "#1cc88a");
        // $(".modal-header").css("color", "white");
        $('#contenedor').empty();
        $("#modalNuevoGrupo").modal("show");
        id_grupo = null;
        opcion_grupo = 1;

        $('#selectNumeroPagos').data('numero_pagos', 0);
    });

    //botón EDITAR Grupos
    $(document).on("click", ".btnEditarGrupo", function () {
        $("#formGrupos").trigger("reset");
        filaGrupo = $(this).closest("tr");
        id_grupo = filaGrupo.data('id');
        numero_pagos = filaGrupo.data('numeropagos');

        $('#tituloModalGrupo').text('Editar Grupo');
        var nombre = filaGrupo.find('td:nth-child(2)').text().trim();
        var fechaInicio = convertirFechaYMD(filaGrupo.find('td:nth-child(3)').text().trim());
        var fechaFin = convertirFechaYMD(filaGrupo.find('td:nth-child(4)').text().trim());

        console.log('fechaInicio', fechaInicio, 'fechaFin', fechaFin);
        console.log('fechasData', fechasData);
        $('#fechaFinGrupo').attr('min', fechaInicio);
        $('#nombreGrupo').val(nombre);
        $('#fechaInicioGrupo').val(fechaInicio);
        $('#fechaFinGrupo').val(fechaFin);
        $('#selectNumeroPagos').data('numero_pagos', numero_pagos);
        if (numero_pagos !== 0) {
            $('#selectNumeroPagos').val(numero_pagos);
            var fechasData = JSON.parse(filaGrupo.attr('data-fechas'));//data('fechas')
        }
        
        $('#contenedor').empty();

        for (var i = 0; i < numero_pagos; i++) {
            var div_form_group = crear_fechas_pago(i + 1, fechasData[i]);
            // Agregar el label y el input al elemento contenedor (por ejemplo, un div con ID 'contenedor')
            $('#contenedor').append(div_form_group);
        }
        opcion_grupo = 2; //editar
        $('#modalNuevoGrupo').modal('show');
    });

    //botón BORRAR Grupo
    $(document).on("click", ".btnBorrarGrupo", function () {
        filaGrupo = $(this).closest('tr');
        id_grupo = filaGrupo.data('id');
        opcion_grupo = 3; //borrar
        var respuesta = confirm("¿Está seguro de eliminar el grupo seleccionado?");
        if (respuesta) {
            $.ajax({
                url: "bd/crudgrupos.php",
                type: "POST",
                dataType: "json",
                data: { opcion: opcion_grupo, id: id_grupo },
                success: function (response) {
                    eliminarFilaTabla(tablaGrupos, filaGrupo);
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    });

    $("#formGrupos").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('opcion', opcion_grupo);
        formData.append('id', id_grupo);
        return new Promise(resolve => {
            $.ajax({
                url: "bd/crudgrupos.php",
                type: "POST",
                processData: false,
                contentType: false,
                dataType: 'json',
                data: formData,
                success: function (data) {
                    var fila;
                    if (opcion_grupo == 1) {
                        id = data;
                        pos = tablaGrupos.rows().count() + 1;
                        tablaGrupos.row.add([pos, formData.get('nombreGrupo'), convertirFechaDMY(formData.get('fechaInicioGrupo')), convertirFechaDMY(formData.get('fechaFinGrupo'))]).draw();
                        fila = $("#tablaGrupos tr:last");                        
                    }
                    else {
                        pos = parseInt(filaGrupo.find('td:eq(0)').text().trim());
                        tablaGrupos.row(filaGrupo).data([pos, formData.get('nombreGrupo'), convertirFechaDMY(formData.get('fechaInicioGrupo')), convertirFechaDMY(formData.get('fechaFinGrupo'))]).draw();
                        fila = $('#tablaGrupos tbody tr').eq(pos - 1);
                    }
                    var numero_pagos = formData.get('selectNumeroPagos');
                    //if (numero_pagos != undefined)
                        fila.attr('data-numeropagos', numero_pagos);
                    //else 
                    //    fila.attr('data-numeropagos', 0);
                    console.log('fila data', fila.data('numeropagos'));

                    fechas = [];
                    for (var i = 1; i <= numero_pagos; i++) {
                        console.log(formData.get('fechaPago' + i));
                        fechas.push(formData.get('fechaPago' + i));
                    }

                    fila.attr('data-fechas', JSON.stringify(fechas));
                    $("#modalNuevoGrupo").modal("hide");
                    resolve();
                },
                error: function () {
                    $("#modalNuevoGrupo").modal("hide");
                    resolve();
                }
            });
        });
    });
    function convertirFechaDMY(fecha) {
        const [year, month, day] = fecha.split('-');
        return `${day}-${month}-${year}`;

    }
    function convertirFechaYMD(cadenaFecha) {
        // Dividir la cadena por el guion
        let partes = cadenaFecha.split('-');
        let dia = partes[0].padStart(2, '0'); // Asegurar que el día tenga 2 dígitos
        let mes = partes[1].padStart(2, '0'); // Asegurar que el mes tenga 2 dígitos
        let anio = partes[2]; // El año ya está en el formato adecuado

        // Crear la fecha en formato "YYYY-MM-DD"
        let fecha = `${anio}-${mes}-${dia}`;
        return fecha;
    }

    //crear fechas de pago de cada grupo
    function crear_fechas_pago(numero_pago, fecha) {
        var label = $('<label>', {
            text: 'Fecha de pago ' + numero_pago + ':',
            for: 'fechaPago', // Asigna el atributo 'for' para asociar el label con el input
            class: 'col-form-label'
        });

        // Crear un input de tipo date dinámicamente
        var inputDate = $('<input>', {
            type: 'date',
            id: 'fechaPago' + numero_pago, // Asigna un ID al input para la asociación con el label
            name: 'fechaPago' + numero_pago, // Asigna un nombre al input (opcional)
            class: 'form-control',
            value: fecha
        });

        var div_form_group = $('<div>').addClass('form-group');
        div_form_group.append(label, inputDate);
        return div_form_group;
    }
    /**************************************************************
        GESTION DEL HORARIO DE UN GRUPO    
    */
    const horas = [
        '07:00:00', '07:30:00', '08:00:00', '08:30:00',
        '09:00:00', '09:30:00', '10:00:00', '10:30:00',
        '11:00:00', '11:30:00', '12:00:00', '12:30:00',
        '13:00:00', '13:30:00', '14:00:00', '14:30:00',
        '15:00:00', '15:30:00', '16:00:00', '16:30:00',
        '17:00:00', '17:30:00', '18:00:00', '18:30:00',
        '19:00:00', '19:30:00', '20:00:00', '20:30:00',
        '21:00:00'
    ];
    function obtenerIndicePorHora(hora) {
        const indice = horas.indexOf(hora);
        return indice;
    }

    function obtenerHoraPorIndice(indice) {
        return horas[indice];
    }
    var fila_grupo, opcion_horario, id_profesor_seleccionado, celda_editar;
    /* GESTIÓN DEL HORARIO DE CADA GRUPO */

    $(document).on("click", ".btnVerHorario", function () {
        fila_grupo = $(this).closest('tr');
        id_grupo = fila_grupo.data('id');
        $("#formHorario").trigger("reset");
        crear_horario();
        $.ajax({
            url: "bd/crudClaseGrupo.php",
            type: "POST",
            dataType: 'json',
            data: {
                id_grupo: id_grupo,
                opcion: 4
            },
            success: function (clases) {
                clases.forEach(function (clase) {
                    var indice_col_ini = obtenerIndicePorHora(clase.hora_inicio);
                    var indice_col_fin = obtenerIndicePorHora(clase.hora_fin);
                    let nombre_profesor = clase.nombres + ' ' + clase.apellidos;
                    crearTarjetaClase(indice_col_ini, indice_col_fin, clase.nombre_curso, clase.id_curso, nombre_profesor, clase.id_profesor, clase.dia, clase.id);
                });
            }
        });
        $("#modalHorario").modal("show");
    });
    function crear_horario() {
        cuerpo = $('#tabla_horario tbody');
        cuerpo.empty();
        //filas desde las 7am hasta las 9pm con intervalos de media hora
        n = 2 * (21 - 7) - 1;
        hora = 7;
        m = 'am';
        min = '00';
        for (i = 0; i <= n; i++) {
            var fila = $('<tr>');
            fila.addClass("table-sm");

            str = hora + ':' + min;

            if (str.length == 4)
                str = "0" + str;

            str += ' ' + m;

            if (i % 2 == 0) {
                min = '30';
            } else {
                min = '00';
                hora++;
            }

            if (hora == 12) {
                if (min == '00')
                    m = 'm';
                else
                    m = 'pm'
            } else if (hora > 12) {
                hora = 1;
                m = 'pm';
            }
            str2 = hora + ':' + min;
            if (str2.length == 4)
                str2 = "0" + str2;

            str2 += ' ' + m;

            str += ' - ' + str2;
            //console.log('hora:', str);
            var celda = $('<td>');
            celda.text(str);

            fila.append(celda);
            for (j = 0; j < 7; j++) {
                var celda = $('<td>');
                var btnAgregar = $('<button>', {
                    'id': 'btnAgregarClase',
                    'class': 'btn btn-success btn-sm',
                    'html': '<i class="fas fa-plus"></i>'
                });

                celda.append(btnAgregar);
                fila.append(celda);
            }
            cuerpo.append(fila);
        }
    }
    $(document).on("click", "#btnAgregarClase", function (e) {
        e.preventDefault();
        $('#formClase').trigger('reset');
        var celda = $(this).closest('td');
        var fila = celda.closest('tr').index(); // Obtiene el índice de la fila
        var columna = celda.index(); // Obtiene el índice de la columna dentro de la fila

        $('#horaInicio').val(fila);
        $('#selectDia').val(columna);

        var selectProfesor = $('#selectProfesor');
        selectProfesor.prop('disabled', true);
        selectProfesor.html('<option value="" selected disabled hidden>Selecciona una opción</option>');
        opcion_horario = 1;
        $.ajax({
            url: "bd/obtener_total_cursos.php",
            type: "POST",
            dataType: "json",
            success: function (cursos) {
                selectCurso = $('#selectCurso');
                selectCurso.empty();
                selectCurso.html('<option value="" selected disabled hidden>Selecciona una opción</option>');
                cursos.forEach(function (curso) {
                    var option = $('<option>');
                    option.val(curso.id);
                    option.text(curso.nombre_curso + ' - ' + curso.nombre_nivel);
                    selectCurso.append(option);
                });
                $('#modalClase').modal('show');
            }
        });
    });

    //evento para cargar los profesores despues que se seleccione un determinado curso
    $('#selectCurso').change(function () {
        var idCurso = $(this).val();
        var selectProfesor = $('#selectProfesor');
        selectProfesor.prop('disabled', true);
        console.log('idCurso', idCurso);
        $.ajax({
            url: 'bd/obtenerProfesoresPorCurso.php',
            type: 'POST',
            dataType: 'json',
            data: { idCurso: idCurso },
            success: function (profesores) {
                profesores.forEach(function (profesor) {
                    selectProfesor.append(
                        $('<option>', {
                            value: profesor.id,
                            text: profesor.nombres + profesor.apellidos
                        }
                        ));
                });
                selectProfesor.prop('disabled', false);
                if (opcion_horario == 2) {
                    selectProfesor.val(id_profesor_seleccionado);
                }
            },
            error: function (xhr, status, error) {
                console.log("Error en la solicitud AJAX:", error);
            }
        });
    });

    function obtenerDiasEnRango(inicio_str, fin_str, dia) {
        let inicio = new Date(inicio_str);
        let fin = new Date(fin_str);
        let dias = [];

        // Ajustar fecha fin para incluir el último día en el rango
        fin.setDate(fin.getDate() + 1);

        while (inicio < fin) {
            // Si el día es jueves (4), agregar al array
            if (inicio.getDay() === dia) {
                // Formatear la fecha como yyyy-mm-dd
                let yyyy = inicio.getFullYear();
                let mm = String(inicio.getMonth() + 1).padStart(2, '0'); // Enero es 0!
                let dd = String(inicio.getDate()).padStart(2, '0');
                let fechaFormateada = `${yyyy}-${mm}-${dd}`;

                dias.push(fechaFormateada);
            }

            // Avanzar al siguiente día
            inicio.setDate(inicio.getDate() + 1);
        }

        return dias;
    }
    function crearTarjetaClase(indice_col_ini, indice_col_fin, nombre_curso, id_curso, nombre_profesor, id_profesor, dia, id) {
        var cuerpo = $('#tabla_horario tbody');
        horarios_array[indice_col_ini][dia - 1] = 1; // Indicamos que el horario está ocupado
        for (var i = indice_col_ini + 1; i < indice_col_fin; i++) {
            horarios_array[i][dia - 1] = 1; // Indicamos que el horario está ocupado
            cuerpo.find('tr').eq(i).find('td').eq(dia).hide(); //ocultamos la celdas de un bloque de horario
        }
        var celda = cuerpo.find('tr').eq(indice_col_ini).find('td').eq(dia);
        celda.empty();
        celda.data({
            id_clase: id,
            id_curso: id_curso,
            id_profesor: id_profesor
        });

        var ancho_celda = celda.height();

        celda.attr('rowspan', indice_col_fin - indice_col_ini);
        var nuevo_alto;
        console.log('rowspan', celda.attr('rowspan'));
        if (celda.attr('rowspan') <= 3) {
            nuevo_alto = ancho_celda * 4;
        } else {
            nuevo_alto = ancho_celda * celda.attr('rowspan')
        }
        celda.css({
            height: nuevo_alto + 'px' // Ajusta el alto de la celda
        });
        console.log('anterior alto', ancho_celda, 'nuevo alto', celda.height());
        ancho_celda = celda.height();

        //////////////// Tarjeta del horario de la clase   ////////////////
        var buttonsContainer = $('<div>').addClass('buttons-container');
        var editButton = $('<button>')
            .addClass('btn btn-primary btn-sm')
            .attr('id', 'editButton')
            .html('<i class="fas fa-pencil-alt"></i>');
        var deleteButton = $('<button>')
            .addClass('btn btn-danger btn-sm')
            .attr('id', 'deleteButton')
            .html('<i class="fas fa-times"></i>');

        buttonsContainer.append(editButton, deleteButton);

        var rectangle = $('<div>').addClass('line-rectangle');
        rectangle.append(buttonsContainer);
        
        var lines = [nombre_curso, nombre_profesor];//, 'Profesor: ' + profesor, 'Alumno: ' + alumno, 'Observaciones: ' + observaciones];

        $.each(lines, function (index, value) {
            var line = $('<div class="line">').text(value);
            rectangle.append(line);
        });
        rectangle.css('height', ancho_celda + 'px');
        celda.append(rectangle);
    }
    function verificar_horario_libre(indice_fila_ini, indice_fila_fin, dia) {
        let libre = true;
        //verificamos que el rango de celdas no este ocupado por otra clase
        for (var i = indice_fila_ini; i < indice_fila_fin; i++) {
            if (horarios_array[i][dia - 1] != 0) {
                libre = false;
                break;
            }
        }
        return libre;
    }
    // subimos la informacion del horario de una nueva clase
    $('#formClase').submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);

        var indice_fila_ini = parseInt(formData.get('horaInicio'));
        var indice_fila_fin = parseInt(formData.get('horaFin'));
        var dia = parseInt(formData.get('selectDia'));
        var valido = true;
        if (opcion_horario == 1) {
            valido = verificar_horario_libre(indice_fila_ini, indice_fila_fin, dia);
        } else if (opcion_horario == 2) {
            var fila_celda_ini = celda_editar.closest('tr').index(); // Obtiene el índice de la fila
            var fila_celda_fin = fila_celda_ini + parseInt(celda_editar.attr('rowspan'));
            var columna_celda_ini = celda_editar.index();
            console.log('indices:', fila_celda_ini, indice_fila_ini, fila_celda_fin, indice_fila_fin, columna_celda_ini, dia);
            if (!(fila_celda_ini == indice_fila_ini && fila_celda_fin == indice_fila_fin && columna_celda_ini == dia)) {
                valido = verificar_horario_libre(indice_fila_ini, indice_fila_fin, dia);
            }
        }

        if (valido) {
            var id_curso = formData.get('selectCurso');
            var nombre_curso = $("#selectCurso option[value='" + id_curso + "']").text().split(' - ')[0];

            var id_profesor = formData.get('selectProfesor');
            var nombre_profesor = $("#selectProfesor option[value='" + id_profesor + "']").text();

            var fecha_inicio = convertirFechaYMD(fila_grupo.find('td').eq(2).text().trim());//`${partes_fecha_inicio[2]}-${partes_fecha_inicio[1]}-${partes_fecha_inicio[0]}`;
            var fecha_fin = convertirFechaYMD(fila_grupo.find('td').eq(3).text().trim());//`${partes_fecha_fin[2]}-${partes_fecha_fin[1]}-${partes_fecha_fin[0]}`;

            var fechas = obtenerDiasEnRango(fecha_inicio, fecha_fin, dia);
            console.log('fecha inicio', fecha_inicio, 'fecha fin', fecha_fin, fechas);
            var data = {
                opcion: opcion_horario, //insercion
                id_curso: id_curso,
                id_profesor: id_profesor,
                id_grupo: id_grupo,
                id_clase: id_clase,
                fechas: fechas,
                hora_inicio: obtenerHoraPorIndice(indice_fila_ini),
                hora_fin: obtenerHoraPorIndice(indice_fila_fin),
                dia: dia
            };
            console.log('data', data);
            $.ajax({
                url: 'bd/crudClaseGrupo.php',
                type: 'POST',
                dataType: 'json',
                data: data,
                success: function (response) {
                    if (opcion_horario == 1)
                        crearTarjetaClase(indice_fila_ini, indice_fila_fin, nombre_curso, id_curso, nombre_profesor, id_profesor, dia, response.id);
                    else if (opcion_horario == 2) {
                        eliminarTarjeta(celda_editar);
                        crearTarjetaClase(indice_fila_ini, indice_fila_fin, nombre_curso, id_curso, nombre_profesor, id_profesor, dia, id_clase);
                    }
                    $('#modalClase').modal('hide');
                },
                error: function (xhr, status, error) {
                    console.log("Error en la solicitud AJAX:", error);
                }
            });
        } else {
            alert("El horario se encuentra ocupado");
        }
    });
    $('#modalClase').on('hidden.bs.modal', function () {
        // Reasigna el enfoque al modal principal
        //$("#modalHorario").modal('hide')
        //$("#modalHorario").focus();
    });
    // evento del boton editar la hora de una clase
    $(document).on('click', '#editButton', function (e) {
        e.preventDefault();
        $('#tituloModalClase').text('Editar el horario de una clase');
        opcion_horario = 2; // modo edicion
        $("#formClase").trigger("reset");
        celda_editar = $(this).closest('td');
        var fila = celda_editar.closest('tr').index(); // Obtiene el índice de la fila
        var columna = celda_editar.index(); // Obtiene el índice de la columna dentro de la fila

        $('#horaInicio').val(fila);
        $('#selectDia').val(columna);
        $('#horaFin').val(fila + parseInt(celda_editar.attr('rowspan')));
        id_clase = celda_editar.data('id_clase');
        let id_curso = celda_editar.data('id_curso');
        let id_profesor = celda_editar.data('id_profesor');
        id_profesor_seleccionado = id_profesor;

        $.ajax({
            url: "bd/obtener_total_cursos.php",
            type: "POST",
            dataType: "json",
            success: function (cursos) {
                selectCurso = $('#selectCurso');
                selectCurso.empty();
                selectCurso.html('<option value="" selected disabled hidden>Selecciona una opción</option>');
                cursos.forEach(function (curso) {
                    var option = $('<option>');
                    option.val(curso.id);
                    option.text(curso.nombre_curso + ' - ' + curso.nombre_nivel);
                    selectCurso.append(option);
                });
                $('#selectProfesor').prop('disabled', false);
                $("#selectCurso").val(id_curso).trigger('change');
                $('#modalClase').modal('show');
            }
        });

    });
    function eliminarTarjeta(celda) {
        celda.empty();
        var celdas = parseInt(celda.attr('rowspan'));
        var alto_celda = celda.height();
        celda.attr('rowspan', 1);

        celda.css({
            height: alto_celda / celdas + 'px' // Devolvemos el alto de una celda individual
        });

        var fila = celda.closest('tr').index(); // Obtiene el índice de la fila
        var columna = celda.index(); // Obtiene el índice de la columna dentro de la fila

        horarios_array[fila][columna - 1] = 0;
        var btnAgregar = $('<button>', {
            'id': 'btnAgregar',
            'class': 'btn btn-success btn-sm',
            'html': '<i class="fas fa-plus"></i>'
        });
        //cuerpo.find('tr').eq(fila - 1).find('td').eq(columna).append(btnAgregar); 
        celda.append(btnAgregar);
        for (var i = fila + 1; i < (fila + celdas - 1); i++) {
            console.log(i, columna - 1, horarios_array[i][columna - 1]);
            horarios_array[i][columna - 1] = 0; // Indicamos que el horario está desocupado

            cuerpo.find('tr').eq(i).find('td').eq(columna).show(); //mostramos la celdas de un bloque de horario
        }
    }
    //evento del boton eliminar eliminar la hora de una clase
    $(document).on('click', '#deleteButton', function (e) {
        e.preventDefault();
        var confirmDelete = confirm("¿Está seguro de eliminar la clase?");
        if (confirmDelete) {
            var celda = $(this).closest('td');
            let id = celda.data('id_clase');
            $.ajax({
                url: 'bd/crudClaseGrupo.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id,
                    opcion: 3
                },
                success: function (response) {
                    eliminarTarjeta(celda);
                }
            });
        }
    });

    /*************************    
    GESTIÓN DE ESTUDIANTES DE UN GRUPO 
    ***************************/
    var tablaEstudiantesGrupo = $("#tablaEstudiantesGrupo").DataTable({
        "language": {
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "No se encontraron resultados",
            "info": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Procesando...",
        }
    });
    tablaEstudiantesGrupo.on('order.dt', function () {
        // Actualizar la columna "N°" después de ordenar
        tablaEstudiantesGrupo.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    });
    var fecha_inicio, fecha_fin;
    var id_estudiante = -1;
    $(document).on("click", ".btnVerEstudiantes", function () {
        filaGrupo = $(this).closest('tr');
        fecha_inicio = filaGrupo.find('td').eq(2).text();
        fecha_fin = filaGrupo.find('td').eq(3).text();

        id_grupo = filaGrupo.data('id');
        numero_pagos = filaGrupo.data('numeropagos');
        console.log('numero_pagos', numero_pagos);
        $("#formEstudiantes").trigger("reset");
        //$(".modal-header").css("background-color", "#1cc88a");
        //$(".modal-header").css("color", "white");
        //$("#tituloProfesores").text("Nuevo Profesor");
        tablaEstudiantesGrupo.clear().draw();
        $.ajax({
            url: "bd/obtener_estudiantes_grupo.php",
            type: "POST",
            dataType: "json",
            data: { id_grupo: id_grupo },
            success: function (estudiantes) {
                console.log('estudiantes', estudiantes);
                //estudiantes.forEach(function (estudiante) {
                for (var id_estudiante_grupo in estudiantes) {
                    if (estudiantes.hasOwnProperty(id_estudiante_grupo)) {
                        var estudiante = estudiantes[id_estudiante_grupo];

                        insertar_estudiante(estudiante.DNI_estudiante, estudiante.nombres_estudiante,
                            estudiante.apellidos_estudiante, estudiante.telefono_estudiante,
                            //estudiante.correo_estudiante,
                            estudiante.fecha_inicio_estudiante,
                            numero_pagos,
                            estudiante.id_estudiante,
                            estudiante.id_estudiante_grupo, estudiante.pagos);
                    }
                }
            }
        });
        $("#modalEstudiantes").modal("show");
    });
    var pagos_colores = {
        'A': 'rojo',
        'B': 'verde',
        'C': 'amarillo'
    };
    var fila_estudiante;
    function insertar_estudiante(DNI_estudiante, nombres_estudiante, apellidos_estudiante,
        telefono_estudiante,
        //correo_estudiante,
        fecha_inicio_estudiante, numero_pagos,
        id_estudiante,
        id_estudiante_grupo, pagos_estado = null) {

        var numero_de_filas = $('#tablaEstudiantesGrupo tbody tr').length;

        // Crear botón "Pagos"
        var botonPagos = $('<button/>', {
            text: 'Pagos',
            class: 'btn btn-primary btnPagosEstudiantes'
        });

        var div_center = $('<div>').addClass('btn-group').append(botonPagos);

        // Crear celdas adicionales
        var celda8 = $('<td>');
        var fila_row = $('<div>').addClass('row');

        for (var i = 0, k = 0; i < numero_pagos; i++) {
            var color = (pagos_estado && k < pagos_estado.length) ? pagos_colores[pagos_estado[k++].estado] : 'rojo';

            $('<div>').addClass(`col-md-${numero_pagos}`)
                .append(
                    $('<div>')
                        .addClass('circulo')
                        .addClass(color)
                        .attr({ 'data-index': i, color: color })
                )
                .appendTo(fila_row);
        }

        celda8.append(fila_row);

        // Agregar botones Editar y Borrar
        var codigoHTML = "<div class='btn-group'>" +
            "<button class='btn btn-primary btnEditarEstudiante'>Editar</button>" +
            "<button class='btn btn-danger btnBorrarEstudiante'>Borrar</button>" +
            "</div>";
        var celda9 = $('<td>').html(codigoHTML);

        // Crear la fila de DataTable y añadirla

        let nuevaFila = tablaEstudiantesGrupo.row.add([
            numero_de_filas + 1, // Número de fila
            DNI_estudiante,
            apellidos_estudiante,
            nombres_estudiante,
            telefono_estudiante,
            //correo_estudiante,
            fecha_inicio_estudiante,
            div_center[0].outerHTML, // Convertir el div a HTML
            celda8[0].outerHTML,     // Convertir el div a HTML
            celda9[0].outerHTML      // Convertir el div a HTML
        ]).draw(false).node(); // false para no reiniciar el paginador

        // Opcional: añadir data a la fila
        $(nuevaFila).attr('data-id', id_estudiante);
        $(nuevaFila).attr('data-ideg', id_estudiante_grupo);
    }

    $("#btnNuevoEstudiante").click(function () {
        $("#formEstudiante").trigger("reset");
        $("#tituloModalEstudiante").text("Nuevo Estudiante");
        //$(".modal-header").css("background-color", "#1cc88a");
        // $(".modal-header").css("color", "white");
        $("#modalNuevoEstudiante").modal("show");
        id_estudiante = null;
    });

    var campos = ['apellidos_estudiante', 'nombres_estudiante', 'telefono_estudiante', 'fecha_inicio_estudiante'];

    $("#btnContinuar").click(function () {
        var DNI = $('#DNI_estudiante').val();
        console.log('DNI', DNI, 'id_grupo', id_grupo);
        $.ajax({
            url: "bd/verificar_estudiante.php",
            type: "POST",
            dataType: "json",
            data: {
                DNI: DNI,
                id_grupo: id_grupo
            },
            success: function (response) {
                opcion_estudiante = response.status;
                console.log('opcion_estudiante', opcion_estudiante);
                if (response.status == 1) {
                    campos.forEach(function (campo) {
                        $("input[name='" + campo + "']").val('');
                    });
                }
                else if (response.status == 2 || response.status == 3) {
                    let estudiante = response.estudiante;
                    id_estudiante = estudiante['id_estudiante'];
                    campos.forEach(function (campo) {
                        $("input[name='" + campo + "']").val(estudiante[campo]);
                    });
                }
                if (response.status == 3) {
                    fila_estudiante = buscarFilaEstudiante(DNI, 1);
                    console.log('id fila', fila_estudiante.data('id'));
                }
                $('#formEstudiante :input').prop('disabled', false);
            }
        });
    });
    $(document).on("click", ".btnEditarEstudiante", function (e) {
        e.preventDefault();
        $("#formEstudiante").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        $("#tituloModalEstudiante").text("Editar Estudiante");
        fila_estudiante = $(this).closest("tr");
        id_estudiante = fila_estudiante.data('id');

        opcion_estudiante = 3;
        // Iterar sobre los campos y obtener los valores desde la segunda celda
        $('#DNI_estudiante').val(fila_estudiante.find('td:nth-child(2)').text().trim());
        campos.forEach(function (campo, i) {
            var valor = fila_estudiante.find('td:nth-child(' + (i + 3) + ')').text().trim();
            $("input[name='" + campo + "']").val(valor);
        });
        $('#formEstudiante :input').prop('disabled', false);
        $("#modalNuevoEstudiante").modal("show");
    });
    $(document).on("click", ".btnBorrarEstudiante", function (e) {
        e.preventDefault();
        fila_estudiante = $(this).closest('tr');
        id_estudiante_grupo = fila_estudiante.data('ideg');

        opcion_estudiante = 4; //borrar
        console.log('opcion_estudiante', opcion_estudiante, 'ieg', id_estudiante_grupo);
        var respuesta = confirm("¿Está seguro de eliminar el estudiante de este grupo?");
        if (respuesta) {
            $.ajax({
                url: "bd/crud_estudiante_curso.php",
                type: "POST",
                dataType: "json",
                data: {
                    opcion_estudiante: opcion_estudiante,
                    id_estudiante_grupo: id_estudiante_grupo
                },
                success: function (response) {
                    console.log('eliminado');
                    eliminarFilaTabla(tablaEstudiantesGrupo, fila_estudiante);
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText, error);
                }
            });
        }
    });
    $("#formEstudiante").submit(function (e) {
        e.preventDefault();
        numero_pagos = filaGrupo.data('numeropagos');

        var formData = new FormData(this);

        formData.append('id_estudiante', id_estudiante);
        formData.append('id_grupo', id_grupo);
        formData.append('opcion_estudiante', opcion_estudiante);

        $.ajax({
            url: 'bd/crud_estudiante_curso.php',
            type: "POST",
            processData: false,
            contentType: false,
            dataType: 'json',
            data: formData,
            success: function (data) {
                if (opcion_estudiante == 1 || opcion_estudiante == 2) {
                    insertar_estudiante(
                        formData.get('DNI_estudiante'), formData.get('nombres_estudiante'),
                        formData.get('apellidos_estudiante'), formData.get('telefono_estudiante'),
                        //formData.get('correo_estudiante'), 
                        formData.get('fecha_inicio_estudiante'),
                        numero_pagos,
                        id_estudiante,
                        data.id_estudiante_grupo);
                } else if (opcion_estudiante == 3) {
                    var nuevosDatos = [
                        tablaEstudiantesGrupo.row(fila_estudiante).data()[0], // Mantener el N°
                        formData.get('DNI_estudiante'),
                        formData.get('apellidos_estudiante'),
                        formData.get('nombres_estudiante'),
                        formData.get('telefono_estudiante'),
                        formData.get('fecha_inicio_estudiante'),
                        tablaEstudiantesGrupo.row(fila_estudiante).data()[6], // Mantener el contenido de la columna de Acciones
                        tablaEstudiantesGrupo.row(fila_estudiante).data()[7],
                        tablaEstudiantesGrupo.row(fila_estudiante).data()[8],
                    ];
                    // Guardar el índice de la página actual
                    var paginaActual = tablaEstudiantesGrupo.page();

                    // Actualizar la fila
                    tablaEstudiantesGrupo.row(fila_estudiante).data(nuevosDatos).draw(false);

                    // Regresar a la página original
                    tablaEstudiantesGrupo.page(paginaActual).draw('page');
                }
            }
        });
        $("#modalNuevoEstudiante").modal("hide");
    });
    function buscarFilaEstudiante(valorBuscado, columnaIndex) {
        var filaEncontrada = null;

        tablaEstudiantesGrupo.rows().every(function (rowIdx, tableLoop, rowLoop) {
            var data = this.data();
            if (data[columnaIndex] == valorBuscado) {
                filaEncontrada = this.node(); // Guardar la referencia al nodo de la fila
                return false; // Para romper el loop una vez encontrada
            }
        });

        return $(filaEncontrada);
    }
    /******************* *
        Gestion de pagos
    *********************/
    $(document).on("click", ".btnPagosEstudiantes", function (e) {
        e.preventDefault();
        $("#formCuotas").trigger("reset");
        fila_estudiante = $(this).closest('tr');
        id_estudiante_grupo = fila_estudiante.data('ideg');

        $.ajax({
            url: "bd/obtener_pagos_estudiante.php",
            type: "POST",
            dataType: "json",
            data: { id: id_estudiante_grupo },
            success: function (pagos) {
                console.log('pagos', pagos);
                var contenedor_cuotas = $('#contenedor_cuotas');
                contenedor_cuotas.empty();
                contenedor_cuotas.data('numeropagos', numero_pagos);
                var k = 0;
                for (var i = 0; i < numero_pagos; i++) {
                    //estado pendiente
                    var fecha = '', monto = '0', estado = 'A';
                    if (k < pagos.length) {
                        fecha = pagos[k]['fecha'];
                        monto = pagos[k]['monto'];
                        estado = pagos[k]['estado'];
                        k++;
                    }
                    var div = crear_fechas_estudiante(i + 1, fecha, monto, estado);
                    contenedor_cuotas.append(div);
                }
                $("#modalCuotas").css('z-index', '1060');
                $("#modalCuotas").modal("show");
            },
            error: function (xhr, status, error) {
                console.log("Error en la solicitud AJAX:", error);
            }
        });
    });
    $('#selectNumeroPagos').on('change', function () {
        // Tu código aquí se ejecutará cuando cambie la selección        
        var valor_anterior = parseInt($(this).data('numero_pagos'));
        var valorSeleccionado = parseInt($(this).val());
        $(this).data('numero_pagos', valorSeleccionado);

        if (valor_anterior < valorSeleccionado) {
            var dif = valorSeleccionado - valor_anterior;
            for (var i = valor_anterior + 1; i <= valorSeleccionado; i++) {
                // Crear un label dinámicamente
                var div_form_group = crear_fechas_pago(i, '');
                // Agregar el label y el input al elemento contenedor (por ejemplo, un div con ID 'contenedor')
                $('#contenedor').append(div_form_group);
            }
        } else if (valor_anterior > valorSeleccionado) {
            var dif = valor_anterior - valorSeleccionado;
            for (var i = 0; i < dif; i++) {
                $('#contenedor').children().last().remove();
            }
        }
    });

    //crear fechas de cada pago realizado por el estudiante
    function crear_fechas_estudiante(numero_pago, fecha, monto, estado) {
        var fila_row = $('<div>').addClass('row');
        var col_1 = $('<div>').addClass('col');
        var label_1 = $('<label>', {
            text: 'Fecha en que se realiza el pago ' + numero_pago + ':',
            for: 'fechaEstudiantePago' + numero_pago, // Asigna el atributo 'for' para asociar el label con el input
            class: 'col-form-label'
        });

        // Crear un input de tipo date dinámicamente
        var inputDate_1 = $('<input>', {
            type: 'date',
            id: 'fechaEstudiantePago' + numero_pago, // Asigna un ID al input para la asociación con el label
            name: 'fechaEstudiantePago' + numero_pago, // Asigna un nombre al input (opcional)
            class: 'form-control',
            value: fecha
        });
        var col_2 = $('<div>').addClass('col');
        var label_2 = $('<label>', {
            text: 'Monto:',
            for: 'monto' + numero_pago, // Asigna el atributo 'for' para asociar el label con el input
            class: 'col-form-label'
        });

        // Crear un input de tipo date dinámicamente
        var inputDate_2 = $('<input>', {
            type: 'text',
            id: 'monto' + numero_pago, // Asigna un ID al input para la asociación con el label
            name: 'monto' + numero_pago, // Asigna un nombre al input (opcional)
            class: 'form-control',
            value: monto
        });
        var col_3 = $('<div>').addClass('col');
        var label_3 = $('<label>', {
            text: 'Estado :',
            for: 'estadoPago' + numero_pago, // Asigna el atributo 'for' para asociar el label con el input
            class: 'col-form-label'
        });

        // Crear un input de tipo date dinámicamente
        var select = $('<select>', {
            id: 'estadoPago' + numero_pago, // Asigna un ID al input para la asociación con el label
            name: 'estadoPago' + numero_pago, // Asigna un nombre al input (opcional)
            class: 'form-control',
            value: 'pendiente'
        }).append(
            $('<option>', {
                value: '',
                text: 'Seleccione una opción',
                disabled: true,
                hidden: true
            }),
            $('<option>', {
                value: 'A',
                text: 'Pendiente'
            }),
            $('<option>', {
                value: 'B',
                text: 'Pagado'
            }),
            $('<option>', {
                value: 'C',
                text: 'Incompleto'
            })
        );
        select.val(estado);
        col_1.append(label_1, inputDate_1);
        col_2.append(label_2, inputDate_2);
        col_3.append(label_3, select);
        fila_row.append(col_1, col_2, col_3);
        return fila_row;
    }
    //subimos el formulario de pagos de cada estudiante
    $("#formCuotas").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        var contenedor_cuotas = $('#contenedor_cuotas');
        var numero_pagos = contenedor_cuotas.data('numeropagos');
        formData.append('id_estudiante_grupo', id_estudiante_grupo);
        formData.append('numero_pagos', numero_pagos);
        console.log('formCuotas');
        for (const entry of formData) {
            console.log(entry[0], entry[1]);
        }
        $.ajax({
            url: "bd/crud_pagos_estudiante.php",
            type: "POST",
            processData: false,
            contentType: false,
            dataType: 'json',
            data: formData,
            success: function (pagos) {
                var celda = fila_estudiante.find('td').eq(7);

                for (var i = 0; i < numero_pagos; i++) {
                    circulo = celda.find(".circulo[data-index=" + i + "]");
                    circulo.removeClass(circulo.attr('color')).addClass(pagos_colores[formData.get('estadoPago' + (i + 1))]);
                }
            }

        });
        $('#modalCuotas').modal('hide');
    });

    var accionSeleccionada = 'disponible';

    $('input[name="accion"]').change(function () {
        accionSeleccionada = $(this).val();
    });

    var fechaInicioGrupo = $("#fechaInicioGrupo");
    var fechaFinGrupo = $("#fechaFinGrupo");

    // Agregar un controlador de eventos para el campo de entrada de inicio
    fechaInicioGrupo.on("change", function () {
        // Obtener la fecha seleccionada en el campo de entrada de inicio
        var fechaSeleccionada = fechaInicioGrupo.val();
        console.log('fecha:', fechaSeleccionada);
        // Actualizar el atributo "min" del campo de entrada de fin
        fechaFinGrupo.attr("min", fechaSeleccionada);
    });

    //gestión de la asistencia de los estudiantes
    $("#btn_asistencia_estudiante").click(function () {
        $("#form_asistencia_estudiante").trigger("reset");

        var tablaAsistencia = $('#tabla_asistencia');

        // Limpiar encabezado y agregar columnas iniciales
        var filaEncabezado = tablaAsistencia.find('thead tr:first').empty()
            .append($('<th>').text('N°'))
            .append($('<th>').text('Estudiantes'));

        // Configuración de fechas
        var fechaInicioObj = new Date(convertirFechaYMD(fecha_inicio.trim()));
        var fechaFinObj = new Date(convertirFechaYMD(fecha_fin.trim()));
        fechaInicioObj.setDate(fechaInicioObj.getDate() + 1);
        fechaFinObj.setDate(fechaFinObj.getDate() + 1);

        // Agregar columnas de fecha al encabezado
        var dias = 0;
        for (var fecha = new Date(fechaInicioObj); fecha <= fechaFinObj; fecha.setDate(fecha.getDate() + 1)) {
            var dia = fecha.getDate().toString().padStart(2, '0');
            var mes = (fecha.getMonth() + 1).toString().padStart(2, '0');
            var anio = (fecha.getFullYear() % 100).toString().padStart(2, '0');
            filaEncabezado.append(`<th>${dia}/${mes}/${anio}</th>`);
            dias++;
        }

        // Obtener datos de la primera tabla sin modificarla
        var datosCopia = $('#tablaEstudiantesGrupo tbody tr').map(function () {
            return {
                idEg: $(this).data('ideg'),
                apellidos: $(this).find('td').eq(2).text(),
                nombres: $(this).find('td').eq(3).text()
            };
        }).get();

        // Ordenar datos por apellido
        datosCopia.sort((a, b) => a.apellidos.localeCompare(b.apellidos));
        var checkboxesHTML = '<td><input type="checkbox"></td>'.repeat(dias);
        // Crear cuerpo de la tabla
        var cuerpoTabla = tablaAsistencia.find('tbody').empty();
        ids = [];
        datosCopia.forEach(function (fila, index) {
            ids.push(fila.idEg);
            var nuevaFila = $('<tr>').attr('data-ideg', fila.idEg)
                .append(`<td>${index + 1}</td>`)
                .append(`<td>${fila.apellidos} ${fila.nombres}</td>`)
                .append(checkboxesHTML);;

            cuerpoTabla.append(nuevaFila);
        });
        $.ajax({
            url: 'bd/obtenerAsistenciaGrupo.php',  // Nombre del archivo PHP que maneja la consulta
            type: 'POST',
            data: { 
                id_grupo: id_grupo,
                id_estudiante_grupos: ids,
            },  // Datos enviados al servidor
            dataType: 'json',  // Indicar que esperamos un JSON en la respuesta
            success: function(asistencias) {
                console.log(asistencias);
                $('#tabla_asistencia tbody tr').each(function() {
                    let fila = $(this);
                    let id = fila.data('ideg');
                    if (asistencias[id]) {
                        fechas = asistencias[id];
                        console.log('fechas', fechas);  
                        fechas.forEach(function(fecha) {
                            let fechaObj = new Date(fecha);
                            fechaObj.setDate(fechaObj.getDate() + 1);
                            let dias = diasEntreFechas(fechaInicioObj, fechaObj);
                            console.log('fecha', fecha, 'dias', dias);
                            let celda = fila.find('td').eq(dias + 2);
                            let checkbox = celda.find('input[type="checkbox"]');
                            checkbox.prop('checked', true);
                        });
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error: ' + error);
            }
        });
        $("#modal_asistencia_estudiante").modal("show");
    });
    function diasEntreFechas(fecha1, fecha2) {
        // Calcular la diferencia en milisegundos
        let diferenciaEnMilisegundos = fecha2 - fecha1;

        // Convertir la diferencia a días (milisegundos en un día: 1000 * 60 * 60 * 24)
        let diferenciaEnDias = diferenciaEnMilisegundos / (1000 * 60 * 60 * 24);
        return diferenciaEnDias;
    }
    $("#form_asistencia_estudiante").submit(function (e) {
        e.preventDefault();
        var tablaAsistencia = $('#tabla_asistencia');
        var cuerpoTabla = tablaAsistencia.find('tbody');
        var filaEncabezado = tablaAsistencia.find('thead tr:first');
        var asistencias = [];
        // Recorrer cada fila del cuerpo de la tabla
        cuerpoTabla.find('tr').each(function () {
            // Obtener la fila actual del cuerpo
            var filaCuerpo = $(this);

            
            // Recorrer cada celda de la fila actual del cuerpo
            filaCuerpo.find('td').slice(2).each(function (index) {
                // Obtener la celda actual del cuerpo
                var celdaCuerpo = $(this);
                var checkboxEnCelda = celdaCuerpo.find('input[type="checkbox"]');

                // Verificar si el checkbox está seleccionado
                var isChecked = checkboxEnCelda.prop('checked');
                // Obtener la celda correspondiente en la fila de encabezado
                if (isChecked) {
                    var celdaEncabezado = filaEncabezado.find('th').eq(index + 2);
                    var fecha = convertirFecha(celdaEncabezado.text());
                    var idEstudianteGrupo = filaCuerpo.data('ideg');
                    let asistencia = [idEstudianteGrupo, fecha];
                    asistencias.push(asistencia);
                    console.log('fecha', celdaEncabezado.text(), fecha, 'id', idEstudianteGrupo);
                }
            });
        });
        console.log(asistencias);
        $.ajax({
            url: 'bd/crudAsistenciaGrupo.php',  // Nombre del archivo PHP que maneja la consulta
            type: 'POST',
            data: {
                id_grupo: id_grupo,
                asistencias: asistencias
            },  // Datos enviados al servidor
            dataType: 'json',  // Indicar que esperamos un JSON en la respuesta
            success: function(response) {
                $("#modal_asistencia_estudiante").modal("hide");
            }
        });
    });
    function convertirFecha(fecha) {
        const [dia, mes, anio] = fecha.split('/');
        var fechaFormateada = '20' + anio + '-' + mes + '-' + dia;
        
        return fechaFormateada;
    }
    //funcion para eliminar una fila de una tabla
    function eliminarFilaTabla(tabla, fila) {
        var paginaActual = tabla.page();
        var esUltimaPagina = (paginaActual === tabla.page.info().pages - 1);

        // Eliminar la fila seleccionada
        tabla.row(fila).remove().draw(false);

        // Actualizar la numeración en la primera columna
        for (var i = 0; i < tabla.rows().count(); i++) {
            tabla.cell({ row: i, column: 0 }).data(i + 1);
        }

        // Si la última página está vacía después de la eliminación y era la última, ve a la penúltima
        var totalPaginas = tabla.page.info().pages;
        if (esUltimaPagina && totalPaginas < paginaActual + 1) {
            // Ir a la penúltima página
            tabla.page(totalPaginas - 1).draw('page');
        } else {
            // Mantener la página actual
            tabla.page(paginaActual).draw('page');
        }
    }
});