$(document).ready(function () {

    tablaCursos = $("#tablaCursos").DataTable({
        "columnDefs": [
            {
                "targets": -1,
                "data": null,
                "defaultContent": "<div class='text-center'>" +
                    "<div class='btn-group'>" +
                    "<button class='btn btn-primary btnEditarCursos'>" +
                    "Editar" +
                    "</button>" +
                    "<button class='btn btn-danger btnBorrarCursos'>" +
                    "Borrar" +
                    "</button>" +
                    "</div>" +
                    "</div>",
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

    tablaCursos.on('order.dt', function () {
        // Actualizar la columna "N°" después de ordenar
        tablaCursos.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
            cell.innerHTML = i + 1;
        });
    });
    //botón para crear un nuevo curso
    $("#btnNuevoCursos").click(function () {
        $("#formCursos").trigger("reset");
        $(".modal-header").css("background-color", "#1cc88a");
        $(".modal-header").css("color", "white");
        $("#tituloCursos").text("Nuevo Curso");
        //$('#nivelCursos').html('<option value="" selected disabled hidden>Selecciona una opción</option>');
        $("#CRUDCursos").modal("show");
        id = null;
        opcion = 1; //alta
    });

    var filaCursos; //capturar la fila para editar o borrar el registro

    //botón EDITAR Cursos
    $(document).on("click", ".btnEditarCursos", function () {
        filaCursos = $(this).closest('tr');
        //obtenemos el id de la fila
        id = filaCursos.data('id');
        //Obtenemos el id del nivel
        var valorNivel = filaCursos.find('td:eq(2)').attr('data-idNivel');
        console.log('id', id, 'valorNivel', valorNivel);
        nombre = $.trim(filaCursos.find('td:eq(1)').text());
        nivel = $.trim(filaCursos.find('td:eq(2)').text());
        pago1p = parseFloat($.trim(filaCursos.find('td:eq(3)').text()));
        pago2p = parseFloat($.trim(filaCursos.find('td:eq(4)').text()));
        pago3p = parseFloat($.trim(filaCursos.find('td:eq(5)').text()));

        $("#nombreCursos").val(nombre);
        $("#nivelCursos").val(valorNivel);
        $("#pago1pCursos").val(pago1p);
        $("#pago2pCursos").val(pago2p);
        $("#pago3pCursos").val(pago3p);
        opcion = 2; //editar

        $(".modal-header").css("background-color", "#4e73df");
        $(".modal-header").css("color", "white");
        $("#tituloCursos").text("Editar Curso");
        $("#CRUDCursos").modal("show");
    });

    //botón BORRAR Cursos
    $(document).on("click", ".btnBorrarCursos", function () {
        filaCursos = $(this).closest('tr');
        nombre = $.trim(filaCursos.find('td:eq(1)').text());
        nivel = $.trim(filaCursos.find('td:eq(2)').text());
        //obtenemos el id de la fila
        id = filaCursos.data('id');
        opcion = 3; //borrar
        var respuesta = confirm("¿Está seguro de eliminar el curso: \"" + nombre + " - " + nivel + "\"?");
        if (respuesta) {
            $.ajax({
                url: "bd/crudcursos.php",
                type: "POST",
                dataType: "json",
                data: { opcion: opcion, id: id },
                success: function (response) {
                    tablaCursos.row(filaCursos).remove().draw();

                    for (var i = 0; i < tablaCursos.rows().count(); i++)
                        tablaCursos.cell({ row: i, column: 0 }).data(i + 1);

                    /*current_page = tablaCursos.fnPagingInfo().iPage;
                    console.log("current", current_page);
                    tablaCursos.draw()
                    tablaCursos.page(current_page).draw();
*/
                },
                error: function (xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        }
    });

    $("#formCursos").submit(function (e) {
        e.preventDefault();
        nombre = $.trim($("#nombreCursos").val());
        nivel = parseInt($.trim($("#nivelCursos").val()));

        pago1p = $("#pago1pCursos").val();
        pago2p = $("#pago2pCursos").val();
        pago3p = $("#pago3pCursos").val();
        if (!nombre || !pago1p || !pago2p || !pago3p)
            alert("Debes llenar todos los campos");

        pago1p = $.trim($("#pago1pCursos").val());
        pago2p = $.trim($("#pago2pCursos").val());
        pago3p = $.trim($("#pago3pCursos").val());
        nivelNombre = $.trim($("#nivelCursos option:selected").text());

        $.ajax({
            url: "bd/crudcursos.php",
            type: "POST",
            dataType: "json",
            data: { nombre: nombre, nivel: nivel, pago1p: pago1p, pago2p: pago2p, pago3p: pago3p, id: id, opcion: opcion },
            success: function (nuevoId) {
                if (opcion == 1) {
                    pos = tablaCursos.rows().count() + 1;

                    var nuevaFila = tablaCursos.row.add([pos, nombre, nivelNombre, pago1p, pago2p, pago3p]).draw().node();

                    // Agregar atributos 'data-id' y 'data-idNivel'
                    $(nuevaFila).attr('data-id', nuevoId)
                        .find('td:eq(2)').attr('data-idNivel', nivel);

                    // Obtener el valor del atributo 'data-idNivel' de la tercera celda de la fila
                    var valorAtributo = $(nuevaFila).find('td:eq(2)').attr('data-idNivel');

                    console.log('Valor del atributo:', valorAtributo);

                }
                else {
                    pos = parseInt(filaCursos.find('td:eq(0)').text().trim());

                    var filaActualizada = tablaCursos.row(filaCursos).data([pos, nombre, nivelNombre, pago1p, pago2p, pago3p, id]).draw().node();
                    $(filaActualizada).find('td:eq(2)').attr('data-idNivel', nivel);

                    var valorAtributo = $(filaActualizada).find('td:eq(2)').attr('data-idNivel');

                    console.log('Valor del atributo:', valorAtributo);
                    //tablaCursos.cell(filaCursos, 6).data('<input value="' + id + '">');
                }
            }
        });
        $("#CRUDCursos").modal("hide");
    });
});