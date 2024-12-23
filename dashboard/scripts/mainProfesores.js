$(document).ready(function () {
    window.tablaProfesores = $("#tablaProfesores").DataTable({
        "columnDefs": [
            { "visible": false, "targets": [3, 4, 5, 6, 7] },
            {
                "targets": -1,
                "data": null,
                "defaultContent": "<div class='text-center'><div class='btn-group'><button class='btn btn-primary btnEditarProfesores'>Editar</button><button class='btn btn-danger btnBorrarProfesores'>Borrar</button></div></div>",
                "visible": true,
                "searchable": false
            },
            {
                "targets": -2,
                "data": null,
                "defaultContent": "<div class='text-center'><button class='btn btn-success btnAsignarHorario' style='font-size: 0.64rem;'>Ver Horario</button></div>",
                "visible": true,
                "searchable": false
            },
            {
                "targets": -3,
                "data": null,
                "defaultContent": "<div class='text-center'><button class='btn btn-warning btnVerCursos'>Ver</button></div>",
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
    
    $('<button>Mostrar/Ocultar columnas adicionales</button>')
        .appendTo($('#tablaProfesores_wrapper .dataTables_filter'))
        .click(function() {
            window.tablaProfesores.columns([3, 4, 5, 6]).visible(!window.tablaProfesores.column(3).visible());
        });
    $("#btnNuevoProfesores").click(function () {
        $("#formProfesores").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        $("#tituloProfesores").text("Nuevo Profesor");
        $("#CRUDProfesores").modal("show");
        
        id = null;
        opcion = 1; //alta
    });

    var filaProfesores; //capturar la fila para editar o borrar el registro

    //botón EDITAR Profesores    
    $(document).on("click", ".btnEditarProfesores", function () {
        filaProfesores = $(this).closest("tr");
        // Obtener el índice de la fila
        var indiceFila = window.tablaProfesores.row(filaProfesores).index();

        // Obtener los datos de la fila específica
        var datosFila = window.tablaProfesores.row(indiceFila).data();

        id = filaProfesores.data('id');
        
        $("#nombresProfesores").val(datosFila[1]);
        $("#apellidosProfesores").val(datosFila[2]);
        $("#telefonoProfesores").val(datosFila[3]);
        $("#DNIProfesores").val(datosFila[4]);
        $("#correoProfesores").val(datosFila[5]);
        $("#fNacimientoProfesores").val(datosFila[6]);
        opcion = 2; //editar

        $(".modal-header").css("background-color", "#4e73df");
        $(".modal-header").css("color", "white");
        $("#tituloProfesores").text("Editar Profesor");
        $("#CRUDProfesores").modal("show");
    });

    //botón BORRAR Profesores
    $(document).on("click", ".btnBorrarProfesores", function () {
        filaProfesores = $(this).closest("tr"); // $(this);
        nombres = $.trim(filaProfesores.find('td:eq(1)').text());
        apellidos = $.trim(filaProfesores.find('td:eq(2)').text());
        id = filaProfesores.data('id');
        opcion = 3; //borrar
        var respuesta = confirm("¿Está seguro de eliminar el registro del profesor: " + nombres + " " + apellidos + "?");
        if (respuesta) {
            $.ajax({
                url: "bd/crudprofesores.php",
                type: "POST",
                dataType: "json",
                data: { opcion: opcion, id: id },
                success: function (response) {
                    var paginaActual = tablaProfesores.page();
                    var esUltimaPagina = (paginaActual === tablaProfesores.page.info().pages - 1);

                    // Eliminar la fila seleccionada
                    tablaProfesores.row(filaProfesores).remove().draw(false);
                    // Actualizar la numeración en la primera columna
                    for (var i = 0; i < tablaProfesores.rows().count(); i++) {
                        tablaProfesores.cell({ row: i, column: 0 }).data(i + 1);
                    }

                    // Si la última página está vacía después de la eliminación y era la última, ve a la penúltima
                    var totalPaginas = tablaProfesores.page.info().pages;
                    if (esUltimaPagina && totalPaginas < paginaActual + 1) {
                        // Ir a la penúltima página
                        tablaProfesores.page(totalPaginas - 1).draw('page');
                    } else {
                        // Mantener la página actual
                        tablaProfesores.page(paginaActual).draw('page');
                    }
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    });

    $("#formProfesores").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('id', id);
        formData.append('opcion', opcion);
        
        $.ajax({
            url: "bd/crudprofesores.php",
            type: "POST",
            processData: false,
            contentType: false,
            dataType: 'json',
            data: formData,
            success: function (data) {
                if (opcion == 1) {
                    // Agregar una nueva fila al final
                    var nuevaFila = tablaProfesores.row.add([
                        tablaProfesores.rows().count() + 1,  // N° fila (automático)
                        formData.get('nombresProfesores'),
                        formData.get('apellidosProfesores'),
                        formData.get('telefonoProfesores'),                        
                        formData.get('DNIProfesores'),
                        formData.get('correoProfesores'),
                        formData.get('fNacimientoProfesores'),
                    ]).draw(false).node();  // .node() devuelve el nodo tr recién creado
                    // Agregar atributo data-id a la fila recién creada
                    $(nuevaFila).attr('data-id', data.idProfesor);
                    // Cambiar a la última página para ver la nueva fila
                    tablaProfesores.page('last').draw(false);
                } else if (opcion == 2) {
                    var nuevosDatos = [
                        tablaProfesores.row(filaProfesores).data()[0], // Mantener el N°
                        formData.get('nombresProfesores'),
                        formData.get('apellidosProfesores'),
                        formData.get('telefonoProfesores'),                        
                        formData.get('DNIProfesores'),
                        formData.get('correoProfesores'),
                        formData.get('fNacimientoProfesores'),
                        tablaProfesores.row(filaProfesores).data()[7],
                        tablaProfesores.row(filaProfesores).data()[8],
                        tablaProfesores.row(filaProfesores).data()[9],
                    ];
                    // Guardar el índice de la página actual
                    var paginaActual = tablaProfesores.page();
                    // Actualizar la fila
                    tablaProfesores.row(filaProfesores).data(nuevosDatos).draw(false);
                    // Regresar a la página original
                    tablaProfesores.page(paginaActual).draw('page');
                }
            }
        });
        $("#CRUDProfesores").modal("hide");
    });

    //botón Asignar horario de los Profesores
    $(document).on("click", ".btnAsignarHorario", function () {
        filaProfesores =  $(this).closest('tr'); 
        id = filaProfesores.data('id');
       
        $("#formAsignarHorarioProfesor").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        //$("#tituloProfesores").text("Nuevo Profesor");
        crearHorario();
        $("#asignarHorario").modal("show");

    });
    function crearHorario() {
        cuerpo = $('#tablaAsignarHorario tbody');
        cuerpo.empty();
        //filas desde las 7am hasta las 9pm con intervalos de media hora
        n =  2 * (21 - 7) - 1;
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
        }

        $.ajax({
            url: "bd/obtenerHorarioProfesor.php",
            type: "POST",
            dataType: "json",
            data: {id:id},
            success: function(horas) {
                
                for(i = 0; i < horas.length; i++) {
                    idHora = horas[i].id;
                    
                    cf = idHora.split("_");
                    c = cf[0];
                    f = cf[1];
                    console.log("f c", f, c);
                    $celda = $("#horarioTablaCuerpo").find("tr").eq(f).find("td").eq(c);
                    $celda.removeClass('no-disponible');
                    $celda.addClass('disponible');
                }
            },
            error: function(xhr, status, error) {
                console.log("Error en la solicitud AJAX:", error);
            }
        });
    }
    var accionSeleccionada = 'disponible';

    $('input[name="accion"]').change(function () {
        accionSeleccionada = $(this).val();
    });

    $(document).on('click', '#tablaAsignarHorario tbody td:not(:first-child)', function () {
        var $celda = $(this);

        if (accionSeleccionada === 'disponible') {
            $celda.removeClass('no-disponible');
            $celda.addClass('disponible');
        } else if (accionSeleccionada === 'no-disponible') {
            $celda.removeClass('disponible');
            $celda.addClass('no-disponible');
        }
    });
    function obtenerHoras(cadena) {
        var tokens = cadena.split(" - ");
        var var1Tokens = tokens[0].split(" ");
        var primeraHora = var1Tokens[0]; // "12:30"
        var horaInicioParts = primeraHora.split(":");
        var horasI = horaInicioParts[0];
        var minutosI = horaInicioParts[1];
        var var1 = var1Tokens[1]; // "m"

        if(var1 == "pm" && parseInt(horasI) > 12) {
            horasI = (parseInt(horasI) + 12).toString();
        }
        var var2Tokens = tokens[1].split(" ");
        var segundaHora = var2Tokens[0]; // "12:30"
        var horaFinParts = segundaHora.split(":");
        var horasF = horaFinParts[0];
        var minutosF = horaFinParts[1];
        var var2 = var2Tokens[1]; // "m"
        if(var2 == "pm" && parseInt(horasF) > 12) {
            horasF = (parseInt(horasF) + 12).toString()
        }
        hora1 = horasI + ':' + minutosI + ":00";
        hora2 = horasF + ':' + minutosF + ":00";
        resp = [hora1, hora2];
        return resp;
        
    }
    $("#formAsignarHorarioProfesor").submit(function (e) {
        var tablaCuerpo = $("#horarioTablaCuerpo"); 
        registros = [];
        tablaCuerpo.find("tr").each(function(indiceFila) {            
            var fila = $(this);
            var celdas = fila.find("td");//.not(":first-child");
            var hora;
            celdas.each(function(indiceColumna) {
                var celda = $(this);
                if(indiceColumna == 0) {
                    texto = celda.text();
                    horas = obtenerHoras(texto);
                } else {
                    var clase = $(this).attr("class");
                    if (clase === "disponible") {
                        registro = [];                        
                        horas = obtenerHoras(texto)
                        registro.push(indiceColumna.toString() + '_' + indiceFila.toString());
                        registro.push(horas[0]);
                        registro.push(horas[1]);
                        registro.push(id);
                        registro.push(indiceColumna);
                        //console.log("registro", registro);
                        registros.push(registro);
                        //console.log("Celda en la fila " + fila + ", columna " +columna + " es de color verde.");
                    } else if (clase === "no-disponible") {
                        //console.log("Celda en la fila " + fila + ", columna " + columna + " es de color rojo.");
                    }
                }
            });
        });
        registros.push(id); 
        console.log("registros", registros);
        var data = JSON.stringify(registros);
        console.log("data", data);
        $.ajax({
            url: "bd/asignarHorarioProfesor.php",
            type: "POST",
            contentType: "application/json",
            data: data,
            success: function(response) {
                console.log(response);
            },
            error: function(xhr, status, error) {
                console.log("Error en la solicitud AJAX:", error);
            }
        });
    });
});
