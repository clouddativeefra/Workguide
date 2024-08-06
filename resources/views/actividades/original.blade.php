<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero de Simulación de Trello</title>
    <link rel="stylesheet" href="actividades.css">
</head>
<body>
    <div class="board">
        <button class="add-list-btn">+ Añadir otra lista</button>
    </div>

    <!-- Modal para añadir tarjetas -->
    <div class="modal" id="cardModal">
        <div class="modal-content">
            <span class="close-btn">&times;</span>
            <h2>Añadir Tarjeta</h2>
            <form id="cardForm">
                <input type="text" id="cardTitle" placeholder="Título" required>
                <input type="text" id="cardWorkers" placeholder="Trabajadores" required>
                <textarea id="cardDescription" placeholder="Descripción" required></textarea>
                <input type="file" id="cardHelp" accept=".pdf,.doc,.docx,.ppt,.pptx">
                <button type="submit">Añadir Tarjeta</button>
            </form>
        </div>
    </div>

    <script src="actividades.js"></script>
</body>
</html>
