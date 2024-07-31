@include('layouts.header')
<link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="style"><!DOCTYPE html>
<html>
<head>
    <title>CRUD de Admin con AJAX</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Administradores</h2>
        <button class="btn btn-success" id="createNewAdmin">Crear Nuevo Admin</button>
        <table id="admin" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($admin as $admin)
                    <tr id="admin_{{ $admin->id }}">
                        <td>{{ $admin->id }}</td>
                        <td>{{ $admin->nombre }}</td>
                        <td>{{ $admin->apellido }}</td>
                        <td>{{ $admin->telefono }}</td>
                        <td>{{ $admin->correo }}</td>
                        <td>
                            <button class="btn btn-primary editAdmin" data-id="{{ $admin->id }}">Editar</button>
                            <button class="btn btn-danger deleteAdmin" data-id="{{ $admin->id }}">Eliminar</button>
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
        $('#admin').dataTable();

    });
    
  </script>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear/Editar Admin</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="adminForm">
                        <input type="hidden" name="admin_id" id="admin_id">
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

            $('#createNewAdmin').click(function () {
                $('#saveBtn').val("create-admin");
                $('#admin_id').val('');
                $('#adminForm').trigger("reset");
                $('#ajaxModal').modal('show');
            });

            $('body').on('click', '.editAdmin', function () {
                var admin_id = $(this).data('id');
                $.get("admin/" + admin_id + "/edit", function (data) {
                    $('#saveBtn').val("edit-admin");
                    $('#ajaxModal').modal('show');
                    $('#admin_id').val(data.id);
                    $('#nombre').val(data.nombre);
                    $('#apellido').val(data.apellido);
                    $('#telefono').val(data.telefono);
                    $('#correo').val(data.correo);
                })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Enviando...');

                var admin_id = $('#admin_id').val();
                var url = admin_id ? 'admin/' + admin_id : 'admin';
                var method = admin_id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: $('#adminForm').serialize(),
                    dataType: 'json',
                    success: function (data) {
                        var admin = '<tr id="admin_' + data.id + '"><td>' + data.nombre + '</td><td>' + data.apellido + '</td>';
                        admin += '<td>' + data.telefono + '</td><td>' + data.correo + '</td>';
                        admin += '<td><button class="btn btn-primary editAdmin" data-id="' + data.id + '">Editar</button>';
                        admin += '<button class="btn btn-danger deleteAdmin" data-id="' + data.id + '">Eliminar</button></td></tr>';

                        if ($('#saveBtn').val() == 'create-admin') {
                            $('table tbody').prepend(admin);
                        } else {
                            $("#admin_" + data.id).replaceWith(admin);
                        }

                        $('#adminForm').trigger("reset");
                        $('#ajaxModal').modal('hide');
                        $('#saveBtn').html('Guardar');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Guardar');
                    }
                });
            });

            $('body').on('click', '.deleteAdmin', function () {
                var admin_id = $(this).data("id");

                if (confirm("¿Estás seguro de que deseas eliminar este administrador?")) {
                    $.ajax({
                        type: "DELETE",
                        url: "admin/" + admin_id,
                        success: function (data) {
                            $("#admin_" + admin_id).remove();
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
