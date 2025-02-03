
    const openModalButton = document.getElementById('modal_ranking_diario');
    const closeModalButton = document.getElementById('closeModalButtonRanking');
    const modal = document.getElementById('rankingModal');

    // Abre a modal ao clicar no botão
    openModalButton.addEventListener('click', () => {
        modal.classList.remove('ocultar');
        modal.classList.add('aparecer')
    });

    // Fecha a modal ao clicar no botão de fechar
    closeModalButton.addEventListener('click', () => {
        modal.classList.remove('aparecer');
        modal.classList.add('ocultar');
    });

    window.addEventListener('click', (event) => {
        if(event.target === modal) {
            modal.classList.remove('aparecer');
            modal.classList.add('ocultar');
        }
    });


    // Função para atualizar a tabela de ranking com jQuery



