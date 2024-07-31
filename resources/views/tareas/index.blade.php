@include('layouts.header')
<link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="style">
<!DOCTYPE html>
<html>
<head>
    <title>Tareas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Tareas</h2>
        <button class="btn btn-success" id="createNewTarea">Crear Nueva Tarea</button>
        <table id="tareas" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Cantidad de T</th>
                    <th>Descripción</th>
                    <th>Ayuda</th>
                    <th>Trabajadores</th>
                    <th>Acciones</th>   
                </tr>
            </thead>
            <tbody>
                @foreach ($tareas as $tarea)
                    <tr id="tarea_{{ $tarea->id }}">
                        <td>{{ $tarea->nombre }}</td>
                        <td>{{ $tarea->nombre }}</td>
                        <td>{{ $tarea->cantidad_trabajadores }}</td>
                        <td>{{ $tarea->descripcion }}</td>
                        <td>{{ $tarea->ayuda }}</td>
                        <td>{{ $tarea->trabajadores ? $tarea->trabajadores->nombre : 'Sin trabajador asignado' }}</td>
                        <td>
                            <button class="btn btn-primary editTarea" data-id="{{ $tarea->id }}">Editar</button>
                            <button class="btn btn-danger deleteTarea" data-id="{{ $tarea->id }}">Eliminar</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
                  <!-- js plugin -->

  <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>

  <script>

    $(document).ready(function(){
        $('#tareas').dataTable();

    });
    
  </script>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear/Editar Tarea</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="tareaForm">
                        <input type="hidden" name="tarea_id" id="tarea_id">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="cantidad_trabajadores">Cantidad de Trabajadores</label>
                            <input type="number" class="form-control" id="cantidad_trabajadores" name="cantidad_trabajadores" required>
                        </div>
                        <div class="form-group">
                            <label for="descripcion">Descripción</label>
                            <textarea class="form-control" id="descripcion" name="descripcion" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="ayuda">Ayuda</label>
                            <textarea class="form-control" id="ayuda" name="ayuda" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="trabajadores_id">Trabajador Asignado</label>
                            <select class="form-control" id="trabajadores_id" name="trabajadores_id" required>
                                <option value="">Seleccionar Trabajador</option>
                                @foreach ($trabajadores as $trabajador)
                                    <option value="{{ $trabajador->id }}">{{ $trabajador->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Guardar</button>
                    </form>
                    <div id="errorMessages" class="alert alert-danger mt-2" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#createNewTarea').click(function () {
                $('#saveBtn').val("create-tarea");
                $('#tarea_id').val('');
                $('#tareaForm').trigger("reset");
                $('#errorMessages').hide().empty();
                $('#ajaxModal').modal('show');
            });

            $('body').on('click', '.editTarea', function () {
                var tarea_id = $(this).data('id');
                $.get("tareas/" + tarea_id + "/edit", function (data) {
                    $('#saveBtn').val("edit-tarea");
                    $('#ajaxModal').modal('show');
                    $('#tarea_id').val(data.id);
                    $('#nombre').val(data.nombre);
                    $('#cantidad_trabajadores').val(data.cantidad_trabajadores);
                    $('#descripcion').val(data.descripcion);
                    $('#ayuda').val(data.ayuda);
                    $('#trabajadores_id').val(data.trabajadores_id);
                })
            });

    $('#tareaForm').on('submit', function (e) {
    e.preventDefault();
    $('#saveBtn').html('Enviando...');

    var tarea_id = $('#tarea_id').val();
    var url = tarea_id ? 'tareas/' + tarea_id : 'tareas';
    var method = tarea_id ? 'PUT' : 'POST';

    $.ajax({
        url: url,
        type: method,
        data: $('#tareaForm').serialize(),
        dataType: 'json',
        success: function (data) {
            var tarea = '<tr id="tarea_' + data.id + '"><td>' + data.nombre + '</td>';
            tarea += '<td>' + data.cantidad_trabajadores + '</td>';
            tarea += '<td>' + data.descripcion + '</td>';
            tarea += '<td>' + data.ayuda + '</td>';
            tarea += '<td>' + (data.trabajadores ? data.trabajadores.nombre : 'Sin trabajador asignado') + '</td>';
            tarea += '<td><button class="btn btn-primary editTarea" data-id="' + data.id + '">Editar</button>';
            tarea += '<button class="btn btn-danger deleteTarea" data-id="' + data.id + '">Eliminar</button></td></tr>';

            if (method === 'POST') {
                $('table tbody').prepend(tarea);
            } else {
                $("#tarea_" + data.id).replaceWith(tarea);
            }

            $('#tareaForm').trigger("reset");
            $('#ajaxModal').modal('hide');
            $('#saveBtn').html('Guardar');
        },
        error: function (data) {
            console.log('Error:', data);
            $('#saveBtn').html('Guardar');
            var errors = data.responseJSON.errors;
            var errorMessages = '';
            for (var error in errors) {
                if (errors.hasOwnProperty(error)) {
                    errorMessages += '<p>' + errors[error][0] + '</p>';
                }
            }
            $('#errorMessages').show().html(errorMessages);
        }
    });
});


            $('body').on('click', '.deleteTarea', function () {
                var tarea_id = $(this).data("id");

                if (confirm("¿Estás seguro de que deseas eliminar esta tarea?")) {
                    $.ajax({
                        type: "DELETE",
                        url: "tareas/" + tarea_id,
                        success: function (data) {
                            $("#tarea_" + tarea_id).remove();
                        },
                        error: function (data) {
                            console.log('Error:', data);
                        }
                    });
                }
            });
        });
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>
