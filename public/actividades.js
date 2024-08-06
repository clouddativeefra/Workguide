document.addEventListener("DOMContentLoaded", function() {
    const board = document.querySelector('.board');
    const addListButton = document.querySelector('.add-list-btn');
    const cardModal = document.getElementById('cardModal');
    const closeModalButton = document.querySelector('.close-btn');
    const cardForm = document.getElementById('cardForm');

    addListButton.addEventListener('click', function() {
        const newList = document.createElement('div');
        newList.classList.add('list');
        newList.innerHTML = `
            <div class="list-header">
                <h3>Nueva Lista</h3>
                <button class="add-card-btn">Añadir tarjeta</button>
            </div>
            <div class="cards-container"></div>
        `;
        board.insertBefore(newList, addListButton);

        newList.querySelector('.add-card-btn').addEventListener('click', function() {
            openCardModal(newList.querySelector('.cards-container'));
        });
    });

    function openCardModal(cardsContainer) {
        cardModal.style.display = 'flex';
        cardForm.onsubmit = function(event) {
            event.preventDefault();
            const title = document.getElementById('cardTitle').value;
            const workers = document.getElementById('cardWorkers').value;
            const description = document.getElementById('cardDescription').value;
            const helpFile = document.getElementById('cardHelp').files[0];

            const card = document.createElement('div');
            card.classList.add('card');
            card.innerHTML = `
                <div class="card-header">
                    <span class="card-title">${title}</span>
                    <div class="card-options">
                        <button class="options-btn">...</button>
                        <div class="options-menu" style="display:none;">
                            <button>Editar</button>
                            <button>Eliminar</button>
                        </div>
                    </div>
                </div>
                <div class="card-content">
                    <p><strong>Trabajadores:</strong> ${workers}</p>
                    <p><strong>Descripción:</strong> ${description}</p>
                    <p><strong>Ayuda:</strong> ${helpFile ? helpFile.name : 'No adjunto'}</p>
                </div>
            `;

            const optionsButton = card.querySelector('.options-btn');
            const optionsMenu = card.querySelector('.options-menu');

            optionsButton.addEventListener('click', function() {
                optionsMenu.style.display = optionsMenu.style.display === 'none' ? 'block' : 'none';
            });

            cardsContainer.appendChild(card);
            cardModal.style.display = 'none';
            cardForm.reset();
        };
    }

    closeModalButton.addEventListener('click', function() {
        cardModal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === cardModal) {
            cardModal.style.display = 'none';
        }
    });
});
