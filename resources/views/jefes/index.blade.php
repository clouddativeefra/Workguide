<link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="style">
<!DOCTYPE html>
<html>
<head>
    <title>Jefes</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Jefes</h2>
        <button class="btn btn-success" id="createNewJefe">Crear Nuevo Jefe</button>
        <table id="jefes" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Área</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($jefes as $jefe)
                    <tr id="jefe_{{ $jefe->id }} ">
                        <td>{{ $jefe->id}}</td>
                        <td>{{ $jefe->nombre }}</td>
                        <td>{{ $jefe->apellido }}</td>
                        <td>{{ $jefe->telefono }}</td>
                        <td>{{ $jefe->correo }}</td>
                        <td>{{ $jefe->area ? $jefe->area->nombre : 'Sin área asignada' }}</td>
                        <td>
                            <button class="btn btn-primary editJefe  gap: 10px" data-id="{{ $jefe->id }}">Editar</button>
                            <button class="btn btn-danger deleteJefe" data-id="{{ $jefe->id }}">Eliminar</button>
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
        $('#jefes').dataTable();

    });
    
  </script>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="jefeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear/Editar Jefe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="jefeForm">
                        <input type="hidden" name="jefe_id" id="jefe_id">
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
                            <label for="area_id">Área</label>
                            <select class="form-control" id="area_id" name="area_id" required>
                                <option value="">Selecciona un área</option>
                                @foreach ($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->nombre }}</option>
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

            $('#createNewJefe').click(function () {
                $('#saveBtn').val("create-jefe");
                $('#jefe_id').val('');
                $('#jefeForm').trigger("reset");
                $('#jefeModal').modal('show');
            });

            $('body').on('click', '.editJefe', function () {
                var jefe_id = $(this).data('id');
                $.get("jefes/" + jefe_id + "/edit", function (data) {
                    $('#saveBtn').val("edit-jefe");
                    $('#jefeModal').modal('show');
                    $('#jefe_id').val(data.id);
                    $('#nombre').val(data.nombre);
                    $('#apellido').val(data.apellido);
                    $('#telefono').val(data.telefono);
                    $('#correo').val(data.correo);
                    $('#area_id').val(data.area_id);
                })
            });

            $('#jefeForm').on('submit', function (e) {
                e.preventDefault();
                $('#saveBtn').html('Enviando...');

                var jefe_id = $('#jefe_id').val();
                var url = jefe_id ? 'jefes/' + jefe_id : 'jefes';
                var method = jefe_id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: $('#jefeForm').serialize(),
                    dataType: 'json',
                    success: function (data) {
                        var jefe = '<tr id="jefe_' + data.id + '"><td>' + data.nombre + '</td>';
                        jefe += '<td>' + data.apellido + '</td>';
                        jefe += '<td>' + data.telefono + '</td>';
                        jefe += '<td>' + data.correo + '</td>';
                        jefe += '<td>' + (data.area ? data.area.nombre : 'Sin área asignada') + '</td>';
                        jefe += '<td><button class="btn btn-primary editJefe" data-id="' + data.id + '">Editar</button>';
                        jefe += '<button class="btn btn-danger deleteJefe" data-id="' + data.id + '">Eliminar</button></td></tr>';

                        if (method === 'POST') {
                            $('table tbody').prepend(jefe);
                        } else {
                            $("#jefe_" + data.id).replaceWith(jefe);
                        }

                        $('#jefeForm').trigger("reset");
                        $('#jefeModal').modal('hide');
                        $('#saveBtn').html('Guardar');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Guardar');
                    }
                });
            });

            $('body').on('click', '.deleteJefe', function () {
                var jefe_id = $(this).data("id");

                if (confirm("¿Estás seguro de que deseas eliminar este jefe?")) {
                    $.ajax({
                        type: "DELETE",
                        url: "jefes/" + jefe_id,
                        success: function (data) {
                            $("#jefe_" + jefe_id).remove();
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
