<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actividades</title>
    <link rel="stylesheet" href="actividades.css">
</head>
<body>
    <div class="board">
        <button class="add-list-btn">+ Añadir otra lista</button>
        <div class="lists-container" id="activitiesContainer">
            <!-- Aquí se agregarán dinámicamente las actividades -->
            @foreach($actividades as $actividad)
                <div class="card">
                    <h3>{{ $actividad->titulo }}</h3>
                    <p>{{ $actividad->descripcion }}</p>
                    @if ($actividad->ayuda)
                        <a href="{{ Storage::url($actividad->ayuda) }}" target="_blank">Ver Ayuda</a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal para añadir tarjetas -->
    <div class="modal" id="cardModal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Añadir Tarjeta</h2>
            <form id="activityForm" enctype="multipart/form-data" method="POST" action="{{ route('actividades.store') }}">
                @csrf
                <input type="text" name="titulo" placeholder="Título" required>
                <select name="trabajadores_id" required>
                    @foreach($trabajadores as $trabajador )
                        <option value="{{ $trabajador->id }}">{{ $trabajador->nombre }}</option>
                    @endforeach
                </select>
                <textarea name="descripcion" placeholder="Descripción" required></textarea>
                <input type="file" name="ayuda" accept=".pdf,.doc,.docx,.ppt,.pptx">
                <button type="submit">Añadir Tarjeta</button>
            </form>
        </div>
    </div>

    <script src="actividades.js"></script>
</body>
</html>
