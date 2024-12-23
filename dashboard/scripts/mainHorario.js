$(document).ready(function () {

    var diasSemana = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
    var campos = ['apellidos_estudiante', 'nombres_estudiante', 'telefono_estudiante', 'fecha_inicio_estudiante'];
    var idEstudiante;
    var lista_horas = generarhoras();
    cargarCursos();
    $('#vista').change(function () {
        console.log('llamada');
        var opcionSeleccionada = $(this).val();
        if (opcionSeleccionada === 'opcion1') {
            var fecha = new Date();
            var dia = fecha.getDate();
            var mes = fecha.getMonth() + 1;
            var anio = fecha.getFullYear();
            var numeroDia = fecha.getDay();

            var fechaTrabajo = anio + '-' + mes + '-' + dia;

            opcion1(dia, mes, anio, numeroDia, fechaTrabajo);
        } else if (opcionSeleccionada === 'opcion2') {
            // Acción para la opción 2
            opcion2();
            console.log('Se seleccionó la Vista 2');
        }
    });

    function opcion2() {
        // Obtener la fecha actual
        var fechaActual = new Date();

        // Obtener el número del día de la semana (0-6)
        var numeroDiaSemana = fechaActual.getDay();

        // Restar el número del día de la semana actual al día actual
        var fechaInicioSemana = new Date(fechaActual);
        fechaInicioSemana.setDate(fechaInicioSemana.getDate() - numeroDiaSemana);

        // Obtener la fecha del primer día de la semana
        var primerDiaSemana = fechaInicioSemana.getDate();
        var primerMesSemana = fechaInicioSemana.getMonth() + 1; // Se suma 1 porque los meses se numeran del 0 al 11
        var primerAnioSemana = fechaInicioSemana.getFullYear();

        // Obtener la fecha del último día de la semana
        var fechaFinSemana = new Date(fechaInicioSemana);
        fechaFinSemana.setDate(fechaFinSemana.getDate() + 6);

        var ultimoDiaSemana = fechaFinSemana.getDate();
        var ultimoMesSemana = fechaFinSemana.getMonth() + 1; // Se suma 1 porque los meses se numeran del 0 al 11
        var ultimoAnioSemana = fechaFinSemana.getFullYear();
        $("#mensaje").text("Semana desde: " + primerDiaSemana + '/' + primerMesSemana + '/' + primerAnioSemana + ' Hasta: ' + ultimoDiaSemana + '/' + ultimoMesSemana + '/' + ultimoAnioSemana);

        div = $('#selectorP');
        div.show();
        var select = $('#selectorProfesores');
        select.empty();
        //$('<select>').addClass('form-control');;
        $.ajax({
            url: "bd/obtenerProfesores.php",
            type: "POST",
            dataType: "json",
            success: function (profesores) {
                profesores.forEach(function (profesor) {
                    select.append($('<option>', {
                        value: profesor.id,
                        text: profesor.nombres + ' ' + profesor.apellidos,

                    }));
                });
            },
        });

        var tabla = $('#my_table');
        var tablaContainer = $('#table_container');
        tabla.empty();
        ////
        var thead = $('<thead>');

        // Crear el elemento <tr> para la fila de cabecera
        var tr = $('<tr>');

        // Agregar las celdas <th> a la fila <tr>
        var headers = ['Horas', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
        headers.forEach(function (header) {
            tr.append($('<th>').text(header));
        });

        // Agregar la fila <tr> al elemento <thead>
        thead.append(tr);

        // Agregar <thead> a la tabla
        tabla.append(thead);

        cuerpo = $('<tbody>');
        n = 2 * (21 - 7) - 1;
        hora = 7;
        m = 'am';
        min = '00';
        for (i = 0; i <= n; i++) {
            var fila = $('<tr></tr>');
            fila.addClass("table-sm");

            str = hora + ':' + min;
            //hora++;
            // console.log("str length", str, str.length);
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
            fila.append('<td>' + str + '</td>');
            for (j = 0; j < 7; j++) {
                var celda = $('<td></td>');
                celda.addClass('no-disponible');
                fila.append(celda);
            }
            cuerpo.append(fila);
            tabla.append(cuerpo);
        }
        ////
    }
    function opcion1(dia, mes, anio, numeroDia, fechaTrabajo) {
        $('#vista').val('opcion1');
        $('#selectorP').hide();
        var tabla = $('#my_table');

        var tablaContainer = $('#table_container');
        tabla.empty();
        $("#fechaTrabajo").text("Día: " + diasSemana[numeroDia] + " " + dia + "/" + ('0' + mes).slice(-2) + "/" + anio);
        // Datos para la tabla
        var cabecera = $('<thead>');
        var cabeceraRow = $('<tr>');
        //Creamos la cabecera de la tabla con los nombres de los profesores
        cabeceraRow.append($("<th>").text('Hora').addClass("text-center"));
        cabecera.append(cabeceraRow);
        console.log(fechaTrabajo, numeroDia);
        $.ajax({
            url: "bd/obtenerProfesores.php",
            type: "POST",
            dataType: "json",
            data: {
                fecha: fechaTrabajo,
                dia: numeroDia
            },
            success: function (response) {
                var profesorHorarios = response.horario;
                let nProfesores = Object.keys(profesorHorarios).length;
                tabla.append(crearCuerpoTabla(nProfesores));
                var cuerpoTabla = $('#my_table tbody');
                $('#containerGeneral').css('width', ((240 * (nProfesores + 1)) + 400) + 'px');
                tablaContainer.css('width', (240 * (nProfesores + 1)) + 'px');
                let indice = 0;
                for (let idProfesor in profesorHorarios) {
                    if (profesorHorarios.hasOwnProperty(idProfesor)) {
                        let profesor = profesorHorarios[idProfesor];
                        let nombres = profesor.nombres;
                        let apellidos = profesor.apellidos;
                        let horarios = profesor.horarios;
                        cabeceraRow.append(
                            $("<th>")
                                .attr('data-id', idProfesor) // Cambia .data() por .attr()
                                .text(nombres.split(" ")[0] + ' ' + apellidos.split(" ")[0])
                                .addClass("text-center")
                        );
                        cabecera.append(cabeceraRow);
                        // Recorrer los horarios

                        if (horarios) {
                            horarios.forEach(function (idHora) {
                                if (idHora) {
                                    let fila = idHora.split("_")[1];
                                    let celda = cuerpoTabla.find("tr").eq(fila).find("td").eq(indice + 1);
                                    celda.removeClass('no-disponible');
                                    celda.addClass('disponible');

                                    var btnAgregar = $('<button>', {
                                        'id': 'btnAgregar',
                                        'class': 'btn btn-success btn-sm',
                                        'html': '<i class="fas fa-plus"></i>'
                                    });

                                    celda.append(btnAgregar);
                                }
                            });
                        }
                    }
                    indice++;
                }
                tabla.append(cabecera);

                //agregamos las clases existentes
                /*var clases = response.clases;
                clases.forEach(function (clase) {
                    nombreProfesor = clase.nombres.split(' ')[0] + clase.apellidos.split(' ')[0];
                    index = buscarIndex(clase.idProfesor);
                    crearTarjeta(indiceHora(clase.hora_inicio), indiceHora(clase.hora_fin), indiceHora(clase.hora_inicio), index[0], clase.nombre_curso, nombreProfesor, clase.nombre_alumno, clase.pago, clase.observaciones);
                    console.log(clase);
                });
                */
            },
            error: function (xhr, status, error) {
                console.log("Error en la solicitud AJAX:", error);
            }
        });
    }
    var filClase = -1, colClase = -1;
    function crearCuerpoTabla(nProfesores) {
        var cuerpo = $('<tbody>');
        var n = 2 * (21 - 7) - 1;
        var hora = 7;
        m = 'am';
        min = '00';
        for (var i = 0; i <= n; i++) {
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
            fila.append($("<td>").addClass('cell').text(str).addClass("text-center"));
            for (var j = 0; j < nProfesores; j++) {
                var celda = $('<td>').addClass('cell');
                celda.addClass('no-disponible');
                fila.append(celda);
            }
            cuerpo.append(fila);
        }

        return cuerpo;
    }
    $(document).on('click', '#nuevaClase', function () {
        $(this).prop('disabled', true); // Desactivar el botón
        $("#formClases").trigger("reset");

        //mostramos la primera seccion y ocultamos las demas
        $("#tipoClase").show();
        $("#seccionEstudiante").hide();
        $("#claseUnica").hide();

        $("#separarClase").show();
    });
    $("#siguienteBtn1").on('click', function () {
        $("#tipoClase").hide();
        $("#seccionEstudiante").show();

        

    });
    //boton continuardddd e
    $("#btnContinuar").click(function () {
        var DNI = $('#DNI_estudiante').val();
        console.log('DNI', DNI);
        $.ajax({
            url: "bd/verificar_estudiante2.php",
            type: "POST",
            dataType: "json",
            data: {
                DNI: DNI
            },
            success: function (response) {
                opcion_estudiante = response.status;
                console.log('opcion_estudiante', opcion_estudiante);
                if (response.status == 1) {
                    $('#div_mensaje').show();
                    $('#div_mensaje').text("El estudiante no está registrado");

                    campos.forEach(function (campo) {
                        $("input[name='" + campo + "']").val('');
                    });
                    $('#seccionEstudiante input').prop('disabled', false);
                    idEstudiante = -1;
                } else {
                    // el estudiante se encuentra registrado
                    $('#div_mensaje').text("El estudiante está registrado");
                    let estudiante = response.estudiante;
                    idEstudiante = estudiante['id_estudiante'];
                    campos.forEach(function (campo) {
                        $("input[name='" + campo + "']").val(estudiante[campo]);
                    });
                    $('#seccionEstudiante input').prop('disabled', true);
                    $("#DNI_estudiante").prop('disabled', false);
                }
                $('#siguienteBtn2').prop('disabled', false);
            }
        });
    });


    $("#atras1").on('click', function () {
        $("#tipoClase").show();
        //reiniciamos la seccion Estudiante
        //$('#seccionEstudiante input').val('');
        //$('#seccionEstudiante input').prop('disabled', true);
        //$('#DNI_estudiante').prop('disabled', false);
        $("#seccionEstudiante").hide();

    });
    $("#siguienteBtn2").on('click', function () {
        $("#seccionEstudiante").hide();
        $('#selectFecha').val();
        let selectedValue = $('input[name="radioOption"]:checked').val();
        if (selectedValue == "option1") {
            $("#claseUnica").show();
            $("#botonesClaseUnica").show();
        } else {
            console.log('idEstudiante', idEstudiante);
            $.ajax({
                url: 'bd/obtenerPaquetes.php',
                type: 'POST',
                dataType: 'json',
                data: { idEstudiante: idEstudiante },
                success: function (paquetes) {
                    let selectPaquetes = $('#selectPaquetes');
                    selectPaquetes.empty();
                    selectPaquetes.html('<option value="" selected disabled hidden>Selecciona una opción</option>');  // Opción por defecto
        
                    if(paquetes && paquetes.length > 0) {
                        
                        paquetes.forEach((paquete, index) => {
                            selectPaquetes.append(
                                $('<option>', {
                                    value: paquete.id,
                                    text: `Paquete ${index + 1}: ${paquete.num_clases_reg} / ${paquete.num_clases}`
                                }
                            ));
                        });
                        $('#siguienteBtn3').prop('disabled', false);
                    } else {
                        $('#siguienteBtn3').prop('disabled', true);
                    }
                    $("#claseUnica").show();
                    $("#infoPaquete").show();
                    $("#botonesPaquete").show();
                }
            });
            
        }
    });
    $(".atras2").on('click', function () {
        $("#seccionEstudiante").show();

        $("#claseUnica").hide();
        //ocultamos las demas secciones
        $("#infoPaquete").hide();
        $("#botonesClaseUnica").hide();
        $("#botonesPaquete").hide();

    });
    $("#siguienteBtn3").on('click', function () {
        console.log($('#horaInicio').val());
        $('#formaPago').show();
        $("#claseUnica").hide();
    });
    $("#atras3").on('click', function () {
        $("#formaPago").hide();
        $("#claseUnica").show();

    });
    $(".cancelarBtn1").on('click', function () {
        $("#separarClase").hide();
        $('#nuevaClase').prop('disabled', false);

    });
    
    $('#containerGeneral').on('change', '.cursos', function () {
        var idCurso = $(this).val();
        let id = $(this).attr('id'); // Obtén el ID del select
        console.log('id select', id);
        let nuevoID = id.replace('Curso', 'Profesor');
        console.log('id select', nuevoID);
        var selectProfesor = $('#'+ nuevoID);
        selectProfesor.prop('disabled', true);
        
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
            },
            error: function (xhr, status, error) {
                console.log("Error en la solicitud AJAX:", error);
            }
        });
    });

    //Gestion para  la logica de la opcion Otro del metodo de pago
    $('#pagoOtro').on('change', function () {
        const $otroInputWrapper = $('#otroInputWrapper'); // Seleccionar el contenedor del input

        if ($(this).is(':checked')) {
            $otroInputWrapper.show(); // Mostrar el input si está marcado
        } else {
            $otroInputWrapper.hide(); // Ocultar el input si está desmarcado
            $('#pagoOtroInput').val(''); // Limpiar el contenido del input
        }
    });

    //// GESTION DE LOS PAQUETES /////////////
    $(document).on('click', '#nuevoPaquete', function () {
        $(this).prop('disabled', true); // Desactivar el botón
        $("#formPaquete").trigger("reset");
        $("#separarPaquete").show();
    });
    $("#btnContinuarPaquete").click(function () {
        let DNI = $('#DNI_estudiantePaquete').val();
        console.log('DNI', DNI);
        $.ajax({
            url: "bd/verificar_estudiante2.php",
            type: "POST",
            dataType: "json",
            data: {
                DNI: DNI
            },
            success: function (response) {
                opcion_estudiante = response.status;
                console.log('opcion_estudiante', opcion_estudiante);
                if (response.status == 1) {
                    $('#div_mensajePaquete').text("El estudiante no está registrado");
                    $('#div_mensajePaquete').show();

                    campos.forEach(function (campo) {
                        $("input[name='" + campo + "Paquete']").val('');
                    });
                    $('#seccionEstudiantePaquete input').prop('disabled', false);
                    idEstudiante = -1;
                } else {
                    // el estudiante se encuentra registrado
                    $('#div_mensajePaquete').text("El estudiante está registrado");
                    let estudiante = response.estudiante;
                    idEstudiante = estudiante['id_estudiante'];
                    campos.forEach(function (campo) {
                        $("input[name='" + campo + "Paquete']").val(estudiante[campo]);
                    });
                    $('#seccionEstudiantePaquete input').prop('disabled', true);
                    $("#DNI_estudiantePaquete").prop('disabled', false);
                }
                $('#siguientePaqueteBtn1').prop('disabled', false);
            }
        });
    });
    $("#siguientePaqueteBtn1").on('click', function () {
        $("#seccionEstudiantePaquete").hide();
        $("#pagoPaquete").show();
        //$("#seccionEstudiantePaquete").hide();
        //$("#clasesPaquete").show();
    });
    /*$("#atras1Paquete").on('click', function () {
        $("#clasesPaquete").hide();
        $("#seccionEstudiantePaquete").show();
    });
    $("#siguientePaqueteBtn2").on('click', function () {
        $("#clasesPaquete").hide();
        $("#pagoPaquete").show();
    });
    */
    $("#atras2Paquete").on('click', function () {
        $("#pagoPaquete").hide();
        $("#seccionEstudiantePaquete").show();
        //$("#clasesPaquete").show();
    });
    $(".cancelarBtnPaquete").on('click', function () {
        $("#separarPaquete").hide();
        $('#nuevoPaquete').prop('disabled', false);
    });

    let classCount = 1; // Contador inicial para numeración

    $("#addClassBtn").click(function () {
        // Clonar la primera fila
        const newRow = $(".class-row:first").clone();

        // Incrementar el contador para la nueva fila
        classCount++;

        // Iterar sobre los elementos dentro de la fila clonada
        newRow.find("select, input, label").each(function () {
            const oldId = $(this).attr("id");
            const oldName = $(this).attr("name");

            if (oldId) {
                // Reemplazar el número en el ID con el nuevo contador
                const newId = oldId.replace(/\d+$/, classCount);
                $(this).attr("id", newId);
            }

            if (oldName) {
                // Reemplazar el número en el nombre con el nuevo contador
                const newName = oldName.replace(/\d+$/, classCount);
                $(this).attr("name", newName);
            }

            // Si es un input, limpiar su valor
            if ($(this).is("input")) {
                $(this).val("");
            }

            // Si es un select, limpiar la selección
            if ($(this).is("select")) {
                $(this).prop("selectedIndex", 0); // Seleccionar la primera opción (placeholder)
            }
        });

        // Agregar la nueva fila al contenedor
        $("#nuevasClases").append(newRow);
    });

    /*  Gestion de Tarjetas */
    const horas = [
        '07:00', '07:30', '08:00', '08:30',
        '09:00', '09:30', '10:00', '10:30',
        '11:00', '11:30', '12:00', '12:30',
        '13:00', '13:30', '14:00', '14:30',
        '15:00', '15:30', '16:00', '16:30',
        '17:00', '17:30', '18:00', '18:30',
        '19:00', '19:30', '20:00', '20:30',
        '21:00'
    ];
    function obtenerIndicePorHora(hora) {
        const indice = horas.indexOf(hora);
        return indice;
    }
    // Función para convertir la hora a un formato de minutos (por ejemplo: "8:30" -> 510 minutos)
    function horaAMinutos(hora) {
        var partes = hora.split(':');
        var horas = parseInt(partes[0], 10);
        var minutos = parseInt(partes[1], 10);
        return horas * 60 + minutos;
    }

    // Función para verificar si la hora es exacta (termina en :00 o :30)
    function esHoraExacta(hora) {
        const minutos = parseInt(hora.split(':')[1], 10);  // Obtener los minutos
        return minutos === 0 || minutos === 30;  // Verificar si es :00 o :30
    }
    function redondearHoraAbajo(horaInput) {
        // Obtener horas y minutos
        var hora = horaInput.split(":")[0];
        var minutos = parseInt(horaInput.split(":")[1]);

        // Redondear los minutos al múltiplo más cercano de 30 minutos
        var minutosRedondeados = (Math.round(minutos / 30) * 30) % 60;

        // Si los minutos redondeados son 0, verificar si la hora debe incrementarse
        if (minutosRedondeados === 0 && minutos >= 30) {
            hora = parseInt(hora) + 1;  // Si los minutos son mayores a 30, sumar una hora
        }

        // Asegurarse de que la hora esté en el formato correcto
        if (hora >= 24) {
            hora = hora - 24;  // Si la hora supera las 24 horas, volver a 0
        }

        // Formatear la hora y los minutos como dos dígitos
        var horaFormateada = String(hora).padStart(2, '0') + ":" + String(minutosRedondeados).padStart(2, '0');

        return horaFormateada;
    }
    function redondearHoraArriba(hora) {
        // Convertir la hora en formato HH:mm a minutos totales
        var partes = hora.split(':');
        var minutosTotales = parseInt(partes[0]) * 60 + parseInt(partes[1]);

        // Redondear al múltiplo de 30 minutos más cercano
        var minutosRedondeados = Math.round(minutosTotales / 30) * 30;

        // Convertir los minutos redondeados nuevamente a horas y minutos
        var horasRedondeadas = Math.floor(minutosRedondeados / 60);
        var minutosRedondeadosRestantes = minutosRedondeados % 60;

        // Asegurarse de que los minutos estén en formato de dos dígitos
        var minutosFormato = minutosRedondeadosRestantes < 10 ? '0' + minutosRedondeadosRestantes : minutosRedondeadosRestantes;

        // Retornar la hora redondeada en formato HH:mm
        return horasRedondeadas + ':' + minutosFormato;
    }

    $('#formClases').submit(function (e) {
        e.preventDefault();
        
        let horaInicio = $('#horaInicio').val();
        let horaFin = $('#horaFin').val();
        let idProfesor = $('#selectProfesor').val();
        let indiceColumna = $('#my_table th[data-id="' + idProfesor + '"]').index();
        let indiceFilaInicio = obtenerIndicePorHora(redondearHoraAbajo(horaInicio));
        let indiceFilaFin = obtenerIndicePorHora(redondearHoraArriba(horaFin));

        let fecha = $('#selectFecha').val();
        let idCurso = $('#selectCurso').val();
        
        let DNIEstudiante = $('#DNI_estudiante').val();
        let nombresEstudiante = $('#nombres_estudiante').val();
        let apellidosEstudiante = $('#apellidos_estudiante').val();
        let telefonoEstudiante = $('#telefono_estudiante').val();
        
        let clase = {
            idEstudiante: idEstudiante,
            DNIEstudiante: DNIEstudiante,
            nombresEstudiante: nombresEstudiante,
            apellidosEstudiante: apellidosEstudiante,
            telefonoEstudiante: telefonoEstudiante,
            idProfesor: idProfesor,
            idCurso: idCurso,
            fecha: fecha,
            horaInicio: horaInicio,
            horaFin: horaFin
        };
        let curso = $('#selectCurso option:selected').text();
        let profesor = $('#selectProfesor option:selected').text();
        let estudiante = nombresEstudiante + ' ' + apellidosEstudiante;

        let selectedValue = $('input[name="radioOption"]:checked').val();
        if (selectedValue == "option1") {
            let numEstudiantes = $('#numEstudiantes').val();
            let costo = $('#costoClase').val();
            let pago = $("input[name='radioOptionPago']:checked").val();
            let observaciones = $('#observaciones').val();
            clase = {
                ...clase,
                opcion : 1,
                numEstudiantes: numEstudiantes,
                costo: costo,
                observaciones: observaciones,
                pago: pago,

            };
            agregarClase(clase);
            let celda = crearTarjeta(indiceFilaInicio, indiceFilaFin, indiceColumna, curso, profesor, estudiante, pago, observaciones, 1);
            celda.attr('data-tipo', 1);
            celda.attr('data-clase', JSON.stringify(clase));
        } else {
            let idPaquete = $('#selectPaquetes').val();
            let observaciones = $('#observacionesPaquete').val();
            clase = {
                ...clase,
                opcion : 1,
                idPaquete: idPaquete,
                observaciones: observaciones,
            };
            agregarClasePaquete(clase);
        }
        
        console.log('datos tarjeta', indiceFilaInicio, indiceFilaFin, indiceFilaInicio, indiceColumna, curso, profesor, estudiante, pago, observaciones);

        
        
        
        
    });
    $('#formPaquete').submit(function (e) {
        e.preventDefault();
        let DNIEstudiante = $('#DNI_estudiantePaquete').val();
        let nombresEstudiante = $('#nombres_estudiantePaquete').val();
        let apellidosEstudiante = $('#apellidos_estudiantePaquete').val();
        let telefonoEstudiante = $('#telefono_estudiantePaquete').val();

        
        //let clases = [];

        // Seleccionar todas las filas de clases (incluyendo las generadas dinámicamente)
        /*$("#horarioPaquete .class-row").each(function (index) {
            let num = index + 1;
            let clase = {
                curso: $(`#selectCursoPaquete${num}`).val(),
                profesor: $(`#selectProfesorPaquete${num}`).val(),
                fecha: $(`#selectFechaPaquete${num}`).val(),
                horaInicio: $(`#horaInicioPaquete${num}`).val(),
                horaFin: $(`#horaFinPaquete${num}`).val(),
            };
            let camposVacios = Object.entries(clase).filter(([key, value]) => !value);
            if (camposVacios.length == 0) {
                clases.push(clase);
            }
        });*/
        let costoPaquete = $('#costoPaquete').val();
        let numClases = $('#numClases').val();
        let numEstudiantes = $('#numEstudiantesPaquete').val();
        let pago = $("input[name='radioOptionPagoPaquete']:checked").val();
        let observaciones = $('#observacionesPaquete').val();

        let paquete = {
            'idEstudiante': idEstudiante,
            'DNIEstudiante': DNIEstudiante,
            'nombresEstudiante': nombresEstudiante,
            'apellidosEstudiante': apellidosEstudiante,
            'telefonoEstudiante': telefonoEstudiante,
            'numClases': numClases,
            'costoPaquete': costoPaquete,
            'numEstudiantes': numEstudiantes,
            'observaciones': observaciones,
            'pago': pago,
            //'clases': clases,
        };
        agregarPaquete(paquete);
        let celda = crearTarjeta(indiceFilaInicio, indiceFilaFin, indiceColumna, curso, profesor, estudiante, pago, observaciones, 1);
        celda.attr('data-tipo', 1);
        celda.attr('data-clase', JSON.stringify(clase));
    });
    function crearTarjeta(horaI, horaF, colClase, curso, profesor, estudiante, pago, observaciones) {
        cuerpo = $('#my_table tbody');

        for (var i = horaI + 1; i < horaF; i++) {
            //orarios_array[i][dia - 1] = 1; // Indicamos que el horario está ocupado
            cuerpo.find('tr').eq(i).find('td').eq(colClase).hide(); //ocultamos la celdas de un bloque de horario
        }
        celda = cuerpo.find("tr").eq(horaI).find("td").eq(colClase);
        celda.empty();
        rowSpan = horaF - horaI;
        celda.attr('rowspan', rowSpan);

        console.log("rowSpan: ", rowSpan);

        var buttonsContainer = $('<div>').addClass('buttons-container');
        buttonsContainer.attr('rowspan', rowSpan);
        var editButton = $('<button>').addClass('btn btn-primary btn-sm').html('<i class="fas fa-pencil-alt"></i>');
        var deleteButton = $('<button>')
            .addClass('btn btn-danger btn-sm')
            .attr('id', 'deleteButton') // Asigna un ID al botón
            .html('<i class="fas fa-times"></i>');
        var paidRectangle = $('<div>').addClass('paid-rectangle');
        if (pago == "SI")
            paidRectangle.text('Pago Completo');
        if (pago == "NO")
            paidRectangle.text('Falta Cancelar');
        if (pago == "IN")
            paidRectangle.text('Pago Incompleto');
        var paidContainer = $('<div>').addClass('paid-container');
        paidContainer.append(paidRectangle);
        buttonsContainer.append(paidRectangle, editButton, deleteButton);

        var rectangle = $('<div>').addClass('line-rectangle');
        rectangle.append(buttonsContainer);

        var lines = ['Curso: ' + curso, 'Profesor: ' + profesor, 'Alumno: ' + estudiante, 'Observaciones: ' + observaciones];

        $.each(lines, function (index, value) {
            var line = $('<div class="line">').text(value);
            rectangle.append(line);
        });
        celda.append(rectangle);
        return celda;
    }
    $(document).on('click', '#deleteButton', function (e) {
        // Confirmación para eliminar el curso
        if (confirm("¿Está seguro de eliminar el curso?")) {
            // Referencia a la celda más cercana al botón clickeado
            var celda = $(this).closest('td');
            var rowspan = parseInt(celda.attr('rowspan')); // Obtenemos el rowspan original
            var altoCelda = celda.height(); // Guardamos el alto actual de la celda
            var filaIndex = celda.closest('tr').index(); // Índice de la fila
            var columnaIndex = celda.index(); // Índice de la columna

            // Limpiar contenido y resetear el rowspan
            celda.empty().attr('rowspan', 1).css({
                height: altoCelda / rowspan + 'px' // Altura para una sola celda
            });

            // Crear y agregar el botón "Agregar"
            var btnAgregar = $('<button>', {
                id: 'btnAgregar',
                class: 'btn btn-success btn-sm',
                html: '<i class="fas fa-plus"></i>'
            });
            celda.append(btnAgregar);

            // Mostrar las celdas ocultas de las filas combinadas
            var tableRows = $('#my_table tbody tr'); // Obtener todas las filas de la tabla
            for (var i = filaIndex + 1; i < filaIndex + rowspan; i++) {
                tableRows.eq(i).find('td').eq(columnaIndex).show();
            }

            // Consola para debug
            console.log('height', celda.height(), filaIndex, columnaIndex, rowspan);
        }
    });
    function agregarClase(clase) { //fecha, idProfesor, idCurso, hora_inicio, hora_fin, tipo, nombreAlumno, nAlumnos, costo, metodos, observaciones, estado_pago) {
        $.ajax({
            url: 'bd/crudClaseUnica.php',
            type: 'POST',                // Aquí se especifica el método POST
            contentType: 'application/json', // Tipo de datos que se envían (JSON)
            data: JSON.stringify(clase),  // Se envían los datos como una cadena JSON
            dataType: "json",
            success: function (response) {
                console.log("clase agregada", response.mensaje, response.data);
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }
    function agregarClasePaquete(clase) {
        $.ajax({
            url: 'bd/crudClasePaquete.php',
            type: 'POST',                // Aquí se especifica el método POST
            contentType: 'application/json', // Tipo de datos que se envían (JSON)
            data: JSON.stringify(clase),  // Se envían los datos como una cadena JSON
            dataType: "json",
            success: function (response) {
               
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }
    function agregarPaquete(paquete) {
        paquete['opcion'] = 1;
        $.ajax({
            url: 'bd/crudPaquete.php',
            type: 'POST',                // Aquí se especifica el método POST
            contentType: 'application/json', // Tipo de datos que se envían (JSON)
            data: JSON.stringify(paquete),  // Se envían los datos como una cadena JSON
            dataType: "json",
            success: function (response) {
                console.log("clase agregada", response.mensaje, response.data);
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
            }
        });
    }

    /************************************************** */
    $("#btnSepararClase2").on('click', function () {
        horasSeparadas = [];

        idCurso = $('#nombreCurso option:selected').val();
        idProfesor = $('#nombreProfesor option:selected').val();
        idAlumno = $('#alumnosPaquete  option:selected').val();
        horaInicio = $('#horaInicio  option:selected').val();
        horaFinal = $('#horaFin  option:selected').val();
        horasSeparadas.push([horaInicio, horaFinal]);
        //console.log("horaI: ", horaInicio, "horaF: ", horaFinal);
        $('mensajeClase').val('Esta es la clase :');

        var curso = $('#nombreCurso option:selected').text();
        // var profesor = $('#nombreProfesor option:selected').text();
        profesor = $('#nombreProfesor').text().split(' ')[1];
        var alumno = $('#nombreAlumno').val();
        observaciones = $('#observaciones').val();
        let estudiante = $('#nombres_estudiante').val() + ' ' + $('#apellidos_estudiante').val();

        crearTarjeta(hora1, hora2, filClase, colClase, curso, profesor, estudiante, "SI", observaciones);
        $("#myModal").modal("hide");
    });
    $("#btnSepararClase3").on('click', function () {
        horasSeparadas = [];
        idCurso = $('#nombreCurso option:selected').val();
        profesor = $('#nombreProfesor').text().split(' ')[1] + $('#nombreProfesor').text().split(' ')[2];
        if (camino[0] == 1) {
            console.log('camino 0 es 1');
            var hora1 = $('#horaInicio1  option:selected').val();
            var hora2 = $('#horaFin1 option:selected').val();

            horasSeparadas.push([hora1, hora2]);
            console.log("horaI: ", hora1, "horaF: ", hora2);

            nombreAlumno = $('#nombreAlumno').val();
            /*horaInicio = $('#horaInicio1  option:selected').val();
            horaFinal = $('#horaFin1  option:selected').val();
            */
            nAlumnos = parseInt($('#numero-alumnos').val());
            costo = parseFloat($('#costo-clase').val());

            $('#tituloCosto').text('Costo de la clase:');
            metodos = [];
            pagoEfectivo = $('#pago-efectivo').prop("checked");
            if (pagoEfectivo)
                metodos.push(1);
            pagoYape = $('#pago-yape').prop("checked");
            if (pagoYape)
                metodos.push(2);
            pagoPlin = $('#pago-plin').prop("checked");
            if (pagoPlin)
                metodos.push(3);
            pagoTransferencia = $('#pago-transferencia').prop("checked");
            if (pagoTransferencia)
                metodos.push(4);
            pagoOtro = $('#pago-otro').prop("checked");
            if (pagoOtro)
                metodos.push(5);

            observaciones = $('#observaciones').val();
            var estado_pago = $("input[name='radioOptionPago']:checked").val();
            var curso = $('#nombreCurso option:selected').text();
            //var profesor = $('#nombreProfesor option:selected').text();
            observaciones = $('#observaciones').val();
            crearTarjeta(hora1, hora2, filClase, colClase, curso, profesor, nombreAlumno, estado_pago, observaciones);
            let clase = {

            }
            agregarClase(fechaTrabajo, idProfesor, idCurso, lista_horas[hora1], lista_horas[hora2], 1, nombreAlumno, nAlumnos, costo, metodos, observaciones, estado_pago);
        }
        if (camino[0] == 2) {
            nombreAlumno = $('#nombreAlumno2').val();
            horaInicio = $('#horaInicio2 option:selected').val();
            horaFinal = $('#horaFin2 option:selected').val();

            horasSeparadas.push([horaInicio, horaFin]);

            repetirLunes = $('#repetir-lunes').prop("checked");
            if (repetirLunes) {
                horaInicioLunes = $('#hora-inicio-lunes').val();
                horaFinalLunes = $('#hora-fin-lunes').val();
            }
            alumnos = parseInt($('#numero-alumnos').val());
            parseFloat($('#costo-clase').val());

            $('#tituloCosto').text('Costo del paquete:');
            pagoEfectivo = $('#pago-efectivo').prop("checked");
            pagoYape = $('#pago-yape').prop("checked");
            pagoPlin = $('#pago-plin').prop("checked");
            pagoTransferencia = $('#pago-transferencia').prop("checked");
            pagoOtro = $('#pago-otro').prop("checked");
            observaciones = $('#observaciones').val();
            var valorMarcado = $("input[name='radioOptionPago']:checked").val();
            var curso = $('#nombreCurso option:selected').text();
            var profesor = $('#nombreProfesor option:selected').text();

            crearTarjeta(horaInicio, horaFinal, filClase, colClase, curso, profesor, nombreAlumno, valorMarcado, observaciones);
        }
        $("#myModal").modal("hide");

    });

    //function crearTarjeta(horaI, horaF, filClase, colClase, curso, profesor, alumno, pago, observaciones) {

    var camino = [];
    var previo;
    // boton siguiente del formulario para agregar una clase
    $(document).on('click', '#btnSiguiente1', function () {
        $('#view1').hide(); // Oculta la vista actual
        var selectedValue = $('input[name="radioOption"]:checked').val();
        if (selectedValue == "option1") {
            console.log("hora de inicio: ", horaI);
            //Se desactiva la hora de inicio pues no debe ser modificada
            $('#horaInicio1').val(horaI).prop('disabled', true);

            //Solo se habilitan las horas despues de la hora de inicio
            addValHorario($('#horaFin1'), horaI + 1, 21);
            previo = 1;
            camino.push(1);
            $('#view2opc1').show();
        }
        if (selectedValue == "option2") {
            crearDia();
            addValHorario($('#horaInicio2'));
            addValHorario($('#horaFin2'));
            $('#horaInicio2').val(horaI);
            previo = 2;
            camino.push(2);
            $('#view2opc2').show();
        }
        if (selectedValue == "option3") {
            $('#horaInicio').val(horaI);
            $('#view2opc3').show();
            camino.push(3);
        }
    });
    $(document).on('click', '#btnAtras1', function () {
        prevView(1);
    });
    $(document).on('click', '#btnAtras2', function () {
        prevView(2);
    });
    $(document).on('click', '#btnSiguiente2', function () {
        nextView2(1);
    });
    $(document).on('click', '#btnSiguiente3', function () {
        nextView2(2);
    });
    $(document).on('click', '#btnAtras3', function () {
        prevView(3);
    });
    $(document).on('click', '#btnAtras4', function () {
        prevView2();
    });
    function prevView(opcion) {
        switch (opcion) {
            case 1:
                $('#view2opc1').hide(); // Oculta la vista actual
                break;
            case 2:
                $('#view2opc2').hide(); // Oculta la vista actual
                break;
            case 3:
                $('#view2opc3').hide(); // Oculta la vista actual
                break;
        }
        camino.pop();
        $('#view1').show(); // Muestra la vista anterior
    }
    function prevView2() {
        $('#view3').hide(); // Oculta la vista actual
        if (previo == 1)
            $('#view2opc1').show(); // Muestra la vista anterior
        if (previo == 2)
            $('#view2opc2').show();
        camino.pop();
    }
    function nextView2(opcion) {
        switch (opcion) {
            case 1:
                $('#view2opc1').hide(); // Oculta la vista actual
                break;
            case 2:
                $('#view2opc2').hide(); // Oculta la vista actual
                break;
        }
        camino.push(4);
        $('#view3').show(); // Muestra la siguiente vista
    }
    function reiniciarModalClase() {
        /*for(var i = 1; i < 3; i++) {*/
        if (camino.length > 0) {
            // $('#formClases :input').val("");s
            console.log("camino", camino);
            ultima = camino[camino.length - 1];
            switch (ultima) {
                case 1:
                    $('#view2opc1').hide(); // Oculta la vista actual
                    break;
                case 2:
                    $('#view2opc2').hide(); // Oculta la vista actual
                    break;
                case 3:
                    $('#view2opc3').hide(); // Oculta la vista actual
                    break;
                case 4:
                    $('#view3').hide();
            }
            camino = [];
        }
        //}
        $('#view1').show();

    }
    function obtenerCursosProfesor(idP) {
        var cursosSelect = $('#nombreCurso');
        cursosSelect.html('<option value="" selected disabled hidden>Selecciona una opción</option>');

        $.ajax({
            url: 'bd/obtener_cursos_profesor.php',
            type: 'POST',
            dataType: 'json',
            data: {
                id: idP,
                opcion: 1,
            },
            success: cursos => {
                cursos.forEach(curso => {
                    var option = $('<option>')
                        .val(curso.id)
                        .text(`${curso.nombre_curso} ${curso.nombre_nivel}`);
                    cursosSelect.append(option);
                });
            },
            error: (xhr, status, error) => {
                console.log('Error al obtener los cursos: ' + error);
            }
        });

    }
    var horaI;
    var horasSeparadas = [];
    var idProfesor;
    $(document).on('click', '#btnAgregar', function () {
        $("#formClases").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");

        filClase = $(this).closest('td').closest('tr').index();
        colClase = $(this).closest('td').index();
        console.log('filClase', filClase, 'colClase', colClase);
        reiniciarModalClase();

        var cabeceraColumna = $('#my_table thead tr th').eq(colClase);
        idProfesor = cabeceraColumna.data('id');

        $('#nombreProfesor').text("Profesor: " + cabeceraColumna.text());

        obtenerCursosProfesor(idProfesor);
        addValHorario($('#horaInicio1'), 0, 21);
        horaI = $(this).closest('tr').index();

        $("#myModal").modal("show");

    });

    function addValHorario(selector, inicio = 0, fin = 21) {
        //var n = 2 * (21 - 7) - 1;
        //var hora = 7;
        m = 'am';
        min = '00';
        var hora = 7 + Math.floor(inicio / 2);
        for (var i = inicio; i <= fin; i++) {
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
            var option = $('<option>').val(i).text(str);
            selector.append(option);
        }
    }
    /*$(document).on('click', '#btnSepararPaquete', function () {
        $("#formPaquete").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        //$("#tituloProfesores").text("Nuevo Profesor");
        //$("#modalClase").modal("show");
        $("#myModalPaquete").modal("show");
    });*/

    function crearDia() {
        var diasSemana = ["Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo"];
        var contDias = $("#contenedorDias");
        contDias.empty();
        for (var i = 0; i < 7; i++) {
            var formRow = $('<div>').addClass('form-row');
            var col1 = $('<div>').addClass('col');
            var formCheck = $('<div>').addClass('form-check');
            var checkbox = $('<input>').addClass('form-check-input').attr('type', 'checkbox').attr('name', 'repetir[]');
            var label = $('<label>').addClass('form-check-label').text(diasSemana[i]);
            var col2 = $('<div>').addClass('col');
            var selectInicio = $('<select>').addClass('form-control').attr('id', 'hora-inicio');
            var optionInicio = $('<option>').attr('value', '').text('Hora de inicio:');
            addValHorario(selectInicio);
            var col3 = $('<div>').addClass('col');
            var selectFin = $('<select>').addClass('form-control').attr('id', 'hora-fin');
            var optionFin = $('<option>').attr('value', '').text('Hora de fin:');
            addValHorario(selectFin);
            // Construir la estructura de la fila
            formCheck.append(checkbox, label);
            col1.append(formCheck);
            col2.append(selectInicio.append(optionInicio));
            col3.append(selectFin.append(optionFin));
            formRow.append(col1, col2, col3);
            contDias.append(formRow, $('<br>'));
        }
    }

    function generarhoras() {
        var lista_horas = [];
        var n = 2 * (21 - 7);
        var hora = 7;

        min = '00';
        for (var i = 0; i <= n; i++) {
            str = hora + ':' + min;

            if (str.length == 4)
                str = "0" + str;
            if (i % 2 == 0) {
                min = '30';
            } else {
                min = '00';
                hora++;
            }
            str += ':00';
            lista_horas.push(str)
        }
        return lista_horas;
    }
    function indiceHora(hora) {
        for (var i = 0; i < lista_horas.length; i++) {
            if (lista_horas[i] == hora)
                return i;
        }
    }
    function buscarIndex(idProfesor) {
        indices = [];
        $('#my_table thead th:not(:first-child)').each(function (index) {
            // Verificar si la celda tiene un valor específico en el atributo 'data'

            console.log("id", $(this).data('id'), parseInt(idProfesor));
            if (parseInt($(this).data('id')) === parseInt(idProfesor)) {
                // Realizar alguna acción con la celda encontrada
                console.log('Celda encontrada:', $(this).text() + 1);
                indices.push(index + 1);
                // ...
            }


        });
        return indices;
    }
    $("#btnAnterior").click(function () {
        fecha.setDate(fecha.getDate() - 1);
        fecha = new Date(fecha);

        var dia = fecha.getDate();
        var mes = fecha.getMonth() + 1;
        var anio = fecha.getFullYear();
        var numeroDia = fecha.getDay();

        fechaTrabajo = anio + '-' + mes + '-' + dia;
        opcion1(dia, mes, anio, numeroDia, fechaTrabajo);
    });

    $("#btnSiguiente").click(function () {
        fecha.setDate(fecha.getDate() + 1);
        fecha = new Date(fecha);

        var dia = fecha.getDate();
        var mes = fecha.getMonth() + 1;
        var anio = fecha.getFullYear();
        var numeroDia = fecha.getDay();

        fechaTrabajo = anio + '-' + mes + '-' + dia;
        var opcionSeleccionada = $(this).val();
        if (opcionSeleccionada === 'opcion1') {
            opcion1(dia, mes, anio, numeroDia, fechaTrabajo);
        } else if (opcionSeleccionada === 'opcion2') {
            opcion2(dia, mes, anio);
        }

    });
    $(document).on('click', '#btnNuevoPaquete', function () {
    });
    $('#vista').val('opcion1').trigger('change');

    function cargarCursos() {
        $.ajax({
            url: "bd/obtener_total_cursos.php",
            type: "POST",
            dataType: "json",
            success: function (cursos) {
                // Delegar la acción para todos los select con la clase 'cursos'
                $('#containerGeneral').on('focus', '.cursos', function () {
                    var selectCurso = $(this);  // Referencia al select actual
                    selectCurso.empty();  // Limpiar las opciones existentes
                    selectCurso.html('<option value="" selected disabled hidden>Selecciona una opción</option>');  // Opción por defecto
        
                    // Añadir las nuevas opciones desde la respuesta del servidor
                    cursos.forEach(function (curso) {
                        var option = $('<option>');
                        option.val(curso.id);
                        option.text(curso.nombre_curso + ' - ' + curso.nombre_nivel);
                        selectCurso.append(option);
                    });
                });
        
                // Mostrar el modal
                $('#modalClase').modal('show');
            }
        });
        
    }
});