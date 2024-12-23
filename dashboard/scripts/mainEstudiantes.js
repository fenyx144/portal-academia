$(document).ready(function () {
    botonesDefault = "<div class='text-center'>" +
                    "<div class='btn-group'>" +
                    "<button class='btn btn-primary btnEditarEstudiante'>" +
                    "Editar" +
                    "</button>" +
                    "<button class='btn btn-danger btnBorrarEstudiante'>" +
                    "Borrar" +
                    "</button>" +
                    "</div>" +
                    "</div>";
    tablaEstudiantes = $("#tablaEstudiantes").DataTable({
        "columnDefs": [
            {
                "targets": -1,
                "data": null,
                "defaultContent": botonesDefault ,
                "visible": true,
                "searchable": false
            }],
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
    var filaEstudiante;
    var campos = ['DNI_estudiante', 'apellidos_estudiante', 'nombres_estudiante', 'telefono_estudiante'];
    tablaEstudiantes.on('order.dt', function () {
        // Actualizar la columna "N°" después de ordenar
        tablaEstudiantes.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    });

    $("#btnNuevoEstudiante").click(function () {
        $("#formEstudiante").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        $("#tituloModalEstudiante").text("Nuevo Estudiante");
        $("#modalEstudiante").modal("show");
        id_estudiante = null;
        opcion = 1;
    });
    $("#formEstudiante").submit(function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('id_estudiante', id_estudiante);
        formData.append('opcion', opcion);
        
        $.ajax({
            url: 'bd/crudEstudiante.php',
            type: "POST",
            processData: false,
            contentType: false,
            dataType: 'json',
            data: formData,
            success: function (data) {
                console.log('opcion', opcion);
                if (opcion == 1) {
                    // Agregar una nueva fila al final
                    var nuevaFila = tablaEstudiantes.row.add([
                        tablaEstudiantes.rows().count() + 1,  // N° fila (automático)
                        formData.get('DNI_estudiante'),
                        formData.get('apellidos_estudiante'),
                        formData.get('nombres_estudiante'),                        
                        formData.get('telefono_estudiante'),
                    ]).draw(false).node();  // .node() devuelve el nodo tr recién creado

                    // Agregar atributo data-id a la fila recién creada
                    $(nuevaFila).attr('data-id', data.id_estudiante);

                    // Cambiar a la última página para ver la nueva fila
                    tablaEstudiantes.page('last').draw(false);
                } else if (opcion == 2) {
                    var nuevosDatos = [
                        tablaEstudiantes.row(filaEstudiante).data()[0], // Mantener el N°
                        formData.get('DNI_estudiante'),
                        formData.get('apellidos_estudiante'),
                        formData.get('nombres_estudiante'),                        
                        formData.get('telefono_estudiante'),
                        tablaEstudiantes.row(filaEstudiante).data()[5] // Mantener el contenido de la columna de Acciones
                    ];
                    // Guardar el índice de la página actual
                    var paginaActual = tablaEstudiantes.page();

                    // Actualizar la fila
                    tablaEstudiantes.row(filaEstudiante).data(nuevosDatos).draw(false);

                    // Regresar a la página original
                    tablaEstudiantes.page(paginaActual).draw('page');
                }
            },
            error: function() {
                console.log('error');
            }
        });
        $("#modalEstudiante").modal("hide");
    });
    $(document).on("click", ".btnEditarEstudiante", function () {
        $("#formEstudiante").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        $("#tituloModalEstudiante").text("Editar Estudiante");
        filaEstudiante = $(this).closest("tr");
        id_estudiante = filaEstudiante.data('id');
                
        opcion = 2;
        // Iterar sobre los campos y obtener los valores desde la segunda celda
        campos.forEach(function(campo, i) {
            var valor = filaEstudiante.find('td:nth-child(' + (i + 2) + ')').text().trim();
            $("input[name='" + campo + "']").val(valor);
        });
        $("#modalEstudiante").modal("show");
    });
    $(document).on("click", ".btnBorrarEstudiante", function () {
        filaEstudiante = $(this).closest('tr');
        id_estudiante = filaEstudiante.data('id');
        opcion = 3; //borrar
        var respuesta = confirm("¿Está seguro de eliminar el estudiante?");
        if (respuesta) {
            $.ajax({
                url: "bd/crudEstudiante.php",
                type: "POST",
                dataType: "json",
                data: { opcion: opcion, id_estudiante: id_estudiante },
                success: function (response) {
                    // Guardar el índice de la página actual y verificar si es la última
                    var paginaActual = tablaEstudiantes.page();
                    var esUltimaPagina = (paginaActual === tablaEstudiantes.page.info().pages - 1);

                    // Eliminar la fila seleccionada
                    tablaEstudiantes.row(filaEstudiante).remove().draw(false);

                    // Actualizar la numeración en la primera columna
                    for (var i = 0; i < tablaEstudiantes.rows().count(); i++) {
                        tablaEstudiantes.cell({ row: i, column: 0 }).data(i + 1);
                    }

                    // Si la última página está vacía después de la eliminación y era la última, ve a la penúltima
                    var totalPaginas = tablaEstudiantes.page.info().pages;
                    if (esUltimaPagina && totalPaginas < paginaActual + 1) {
                        // Ir a la penúltima página
                        tablaEstudiantes.page(totalPaginas - 1).draw('page');
                    } else {
                        // Mantener la página actual
                        tablaEstudiantes.page(paginaActual).draw('page');
                    }

                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    });
});