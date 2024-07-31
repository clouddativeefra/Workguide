@include('layouts.header')
<link href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css" rel="style">
<!DOCTYPE html>
<html>
<head>
    <title>Areas</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
</head>
<body>
    <div class="container mt-5">
        <h2>Areas</h2>
        <button class="btn btn-success" id="createNewArea">Crear Nueva Área</button>
        <table id="areas" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%">
            <thead class="bg-primary text-white">
                <tr>
                    <th>Id</th>
                    <th>Nombre</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($areas as $area)
                    <tr id="area_{{ $area->id }}">
                        <td>{{ $area->id}}</td>
                        <td>{{ $area->nombre }}</td>
                        <td>
                            <button class="btn btn-primary editArea" data-id="{{ $area->id }}">Editar</button>
                            <button class="btn btn-danger deleteArea" data-id="{{ $area->id }}">Eliminar</button>
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
        $('#areas').dataTable();

    });
    
  </script>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="ajaxModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Crear/Editar Área</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="areaForm">
                        <input type="hidden" name="area_id" id="area_id">
                        <div class="form-group">
                            <label for="nombre">Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
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

            $('#createNewArea').click(function () {
                $('#saveBtn').val("create-area");
                $('#area_id').val('');
                $('#areaForm').trigger("reset");
                $('#ajaxModal').modal('show');
            });

            $('body').on('click', '.editArea', function () {
                var area_id = $(this).data('id');
                $.get("areas/" + area_id + "/edit", function (data) {
                    $('#saveBtn').val("edit-area");
                    $('#ajaxModal').modal('show');
                    $('#area_id').val(data.id);
                    $('#nombre').val(data.nombre);
                })
            });

            $('#saveBtn').click(function (e) {
                e.preventDefault();
                $(this).html('Enviando...');

                var area_id = $('#area_id').val();
                var url = area_id ? 'areas/' + area_id : 'areas';
                var method = area_id ? 'PUT' : 'POST';

                $.ajax({
                    url: url,
                    type: method,
                    data: $('#areaForm').serialize(),
                    dataType: 'json',
                    success: function (data) {
                        var area = '<tr id="area_' + data.id + '"><td>' + data.nombre + '</td>';
                        area += '<td><button class="btn btn-primary editArea" data-id="' + data.id + '">Editar</button>';
                        area += '<button class="btn btn-danger deleteArea" data-id="' + data.id + '">Eliminar</button></td></tr>';

                        if ($('#saveBtn').val() == 'create-area') {
                            $('table tbody').prepend(area);
                        } else {
                            $("#area_" + data.id).replaceWith(area);
                        }

                        $('#areaForm').trigger("reset");
                        $('#ajaxModal').modal('hide');
                        $('#saveBtn').html('Guardar');
                    },
                    error: function (data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Guardar');
                    }
                });
            });

            $('body').on('click', '.deleteArea', function () {
                var area_id = $(this).data("id");

                if (confirm("¿Estás seguro de que deseas eliminar esta área?")) {
                    $.ajax({
                        type: "DELETE",
                        url: "areas/" + area_id,
                        success: function (data) {
                            $("#area_" + area_id).remove();
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
