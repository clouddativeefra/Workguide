@include('layouts.header')
<link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="style">
<!DOCTYPE html>
<html>
<head>
    <title>Trabajadores</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Trabajadores</h2>
        <button class="btn btn-success" id="createNewTrabajador">Crear Nuevo Trabajadores</button>
        <table   id="trabajadores" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Jefe</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($trabajadores as $trabajador)
                    <tr id="trabajador_{{ $trabajador->id }}">
                        <td>{{ $trabajador->id}}</td>
                        <td>{{ $trabajador->nombre }}</td>
                        <td>{{ $trabajador->apellido }}</td>
                        <td>{{ $trabajador->telefono }}</td>
                        <td>{{ $trabajador->correo }}</td>
                        <td>{{ $trabajador->jefe ? $trabajador->jefe->nombre : 'Sin jefe asignado' }}</td>
                        <td>
                            <button class="btn btn-primary editTrabajador" data-id="{{ $trabajador->id }}">Editar</button>
                            <button class="btn btn-danger deleteTrabajador" data-id="{{ $trabajador->id }}">Eliminar</button>
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
        $('#trabajadores').dataTable();

    });
    
  </script>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="trabajadorModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear/Editar Trabajadores</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="trabajadorForm">
                        <input type="hidden" name="trabajador_id" id="trabajador_id">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                        <div class="form-group">
                            <label for="apellido">Apellido</label>
                            <input type="text" class="form-control" id="apellido" name="apellido" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="text" class="form-control" id="telefono" name="telefono" required>
                        </div>
                        <div class="form-group">
                            <label for="correo">Correo</label>
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                        <div class="form-group">
                            <label for="jefe_id">Jefe</label>
                            <select class="form-control" id="jefe_id" name="jefe_id">
                                <option value="">Seleccionar Jefe</option>
                                @foreach ($jefes as $jefe)
                                    <option value="{{ $jefe->id }}">{{ $jefe->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Guardar</button>
                    </form>
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

            $('#createNewTrabajador').click(function () {
                $('#saveBtn').val("create-trabajador");
                $('#trabajador_id').val('');
                $('#trabajadorForm').trigger("reset");
                $('#trabajadorModal').modal('show');
            });

            $('body').on('click', '.editTrabajador', function () {
                var trabajador_id = $(this).data('id');
                $.get("trabajadores/" + trabajador_id + "/edit", function (data) {
                    $('#saveBtn').val("edit-trabajador");
                    $('#trabajadorModal').modal('show');
                    $('#trabajador_id').val(data.id);
                    $('#nombre').val(data.nombre);
                    $('#apellido').val(data.apellido);
                    $('#telefono').val(data.telefono);
                    $('#correo').val(data.correo);
                    $('#jefe_id').val(data.jefe_id);
                })
            });

            $('#trabajadorForm').on('submit', function (e) {
                e.preventDefault();
                $('#saveBtn').html('Enviando...');

                var trabajador_id = $('#trabajador_id').val();
                var url = trabajador_id ? 'trabajadores/' + trabajador_id : 'trabajadores';
                var method = trabajador_id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: $('#trabajadorForm').serialize(),
                    dataType: 'json',
                    success: function (data) {
                        var trabajador = '<tr id="trabajador_' + data.id + '"><td>' + data.nombre + '</td>';
                        trabajador += '<td>' + data.apellido + '</td>';
                        trabajador += '<td>' + data.telefono + '</td>';
                        trabajador += '<td>' + data.correo + '</td>';
                        trabajador += '<td>' + (data.jefe ? data.jefe.nombre : 'Sin jefe asignado') + '</td>';
                        trabajador += '<td><button class="btn btn-primary editTrabajador" data-id="' + data.id + '">Editar</button>';
                        trabajador += '<button class="btn btn-danger deleteTrabajador" data-id="' + data.id + '">Eliminar</button></td></tr>';

                        if (method === 'POST') {
                            $('table tbody').prepend(trabajador);
                        } else {
                            $("#trabajador_" + data.id).replaceWith(trabajador);
                        }

                        $('#trabajadorForm').trigger("reset");
                        $('#trabajadorModal').modal('hide');
                        $('#saveBtn').html('Guardar');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Guardar');
                    }
                });
            });

            $('body').on('click', '.deleteTrabajador', function () {
                var trabajador_id = $(this).data("id");

                if (confirm("¿Estás seguro de que deseas eliminar este trabajador?")) {
                    $.ajax({
                        type: "DELETE",
                        url: "trabajadores/" + trabajador_id,
                        success: function (data) {
                            $("#trabajador_" + trabajador_id).remove();
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
