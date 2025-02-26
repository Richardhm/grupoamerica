function gerarConfetes() {
    const confettiContainer = document.getElementById('confetti-container');
    const colors = ['#ff0', '#f00', '#0f0', '#00f', '#ff69b4', '#ff8c00', '#1e90ff', '#32cd32'];

    for (let i = 0; i < 100; i++) {  // Gera 100 confetes
        const confetti = document.createElement('div');
        confetti.classList.add('confetti');

        // Define uma cor aleatória para cada confete
        const randomColor = colors[Math.floor(Math.random() * colors.length)];
        confetti.style.backgroundColor = randomColor;

        // Define uma posição horizontal aleatória (de 0 a 100% da largura da tela)
        confetti.style.left = Math.random() * 100 + 'vw';

        // Define tamanhos aleatórios para os confetes
        const randomSize = Math.random() * 10 + 5;  // Entre 5px e 15px
        confetti.style.width = randomSize + 'px';
        confetti.style.height = randomSize + 'px';

        // Adiciona a animação com uma duração aleatória
        confetti.style.animationDuration = Math.random() * 3 + 2 + 's'; // Entre 2s e 5s de duração

        confettiContainer.appendChild(confetti);
    }

    // Remove os confetes após 6 segundos (tempo suficiente para cair)
    setTimeout(() => {
        confettiContainer.innerHTML = '';  // Limpa os confetes
    }, 6000);  // 6 segundos
}


function desbloquearAudio() {

    if(!audioDesbloqueado) {
        somCarro.muted = true;
        somCarro.play().then(() => {
            audioDesbloqueado = true;
            console.log("Áudio desbloqueado.");
        }).catch(error => {
            console.error("Erro ao desbloquear áudio:", error);
        });
        somFogos.muted = true;
        somFogos.play().then(() => {
            audioDesbloqueado = true;
            console.log("Áudio desbloqueado 2.");
        }).catch(error => {
            console.error("Erro ao desbloquear áudio:", error);
        });
    }

}
let rocketIntervalVenda = null;
function animacaoVenda(corretor, imagemCorretor, quantidadeVidas) {
    $('#rankingModal').removeClass('aparecer').addClass('ocultar');

    // Elementos que irão aparecer
    const fundoPreto = $("#fundo-preto");
    const imagem     = $("#imagem-corretor");
    const vidas      = $("#quantidade-vidas");
    const imagem2    = $(".assumir_lider");

    // Definir as informações do corretor e quantidade de vidas
    vidas.text(quantidadeVidas + " Vidas");
    imagem.attr("src", imagemCorretor);
    imagem2.attr("src", imagemCorretor);

    // Mostrar o fundo preto com a imagem do corretor e a quantidade de vidas
    fundoPreto.removeClass('ocultar').addClass('aparecer');
    // Iniciar o som da venda (áudio de 6 segundos)

    const somVenda = new Audio('/som_venda.mp3');
    somVenda.play();
    // Quando o áudio terminar, ocultar a animação
    somVenda.onended = function() {
        fundoPreto.removeClass('aparecer').addClass('ocultar');
        //isAnimating = false;
    };
    gerarConfetes();
    setTimeout(() => {
        fundoPreto.removeClass('aparecer').addClass('ocultar');
        //isAnimating = false;
    }, 6000);
    // Começar a animação dos fogos e o som após 6 segundos
    setTimeout(function() {
        let fogosBg = $("#fundo-preto-fogos-sky");
        fogosBg.find("#quantidade-vidas-fogos-sky").text(quantidadeVidas + " Vidas");
        $("#imagem-corretor-fogos-sky").attr('src',imagemCorretor);
        //let fogosContainer = $(".sky");
        rocketIntervalVenda = setInterval(() => {
            for (let i = 0; i < 3; i++) createRocketVenda(); // Criar vários foguetes ao mesmo tempo
        }, 2000);
        fogosBg.removeClass('ocultar').addClass('aparecer');
        const somFogos = new Audio('fogos.mp3');
        somFogos.play();
        // Definir o tempo de duração da animação dos fogos (20 segundos)
        setTimeout(function() {
            somFogos.pause();
            somFogos.currentTime = 0;
            clearInterval(rocketIntervalVenda);
            document.querySelectorAll('.rocket, .particle').forEach(el => el.remove());
            //sky.fadeOut(4000);
            //fundoPreto.fadeOut(300);
            fogosBg.removeClass('aparecer').addClass('ocultar');
        }, 20000); // 20 segundos
    }, 6000); // Iniciar os fogos após 6 segundos (tempo da venda)
}

somCarro.onplay = function() {
    console.log("Som do carro começou.");
};
somCarro.onerror = function(e) {
    console.error("Erro ao carregar som do carro:", e);
};
somFogos.onplay = function() {
    console.log("Som do fogos começou.");
};
somFogos.onerror = function(e) {
    console.error("Erro ao carregar som do fogo:", e);
};
var usuarioInteragiu = false;
var rocketInterval = null;
function verificarTrocaDeLider(novoRanking, venda) {
    if(novoRanking && novoRanking.length > 0) {
        $('#rankingModal').removeClass('aparecer').addClass('ocultar');
        const novoLider = novoRanking[0];
        if (novoLider.nome != liderAtual?.trim()) {
            liderAtual = novoLider.nome.trim();
            $(".assumir_lider").attr("src", venda.image);
            $(".quantidade_vidas").text(venda.total);
            //let popUp = $("#popup-primeiro");
            let sky = $(".sky");
            let fogosBg = $("#fogos-bg");
            let fogosContainer = $("#fogos-container");
            sky.removeClass('ocultar').addClass('aparecer');
            if(usuarioInteragiu) {
                isAnimating = true;
                const somCarro = new Audio('carro_ultrapassagem.mp3');
                somCarro.play();
                rocketInterval = setInterval(() => {
                    for (let i = 0; i < 3; i++) createRocket(); // Criar vários foguetes ao mesmo tempo
                }, 2000);
                somCarro.onended = function() {
                    sky.fadeIn(300);
                    const somFogos = new Audio('fogos.mp3');
                    somFogos.play();
                    //somFogos.muted = false;
                    somFogos.onended = function() {
                        //fogosContainer.fadeOut(300);
                        //fogosBg.addClass('ocultar').removeClass("aparecer");
                        sky.fadeOut(4000);
                        clearInterval(rocketInterval);
                        // Limpar foguetes e partículas
                        document.querySelectorAll('.rocket, .particle').forEach(el => el.remove());
                        isAnimating = false;
                    };
                };
            }
        } else {
            animacaoVenda(venda.nome,venda.image,venda.total);
        }
    }
}
document.addEventListener('click', () => usuarioInteragiu = true);
document.addEventListener('keydown', () => usuarioInteragiu = true);
function abaEstaVisivel() {
    return document.visibilityState === 'visible';
}

const sky = document.querySelector('.sky');
const sky_venda = document.querySelector('.sky-venda');

function createRocketVenda() {
    const rocket = document.createElement('div');
    rocket.className = 'rocket';

    // Determinar a posição inicial dos foguetes (bordas da tela)
    const edge = Math.floor(Math.random() * 4); // 0 = inferior, 1 = superior, 2 = esquerda, 3 = direita
    let originX, originY, destinationX, destinationY;

    if (edge === 0) {
        // Inferior
        originX = Math.random() * window.innerWidth;
        originY = window.innerHeight;
        destinationX = Math.random() * window.innerWidth - originX;
        destinationY = -Math.random() * window.innerHeight;
    } else if (edge === 1) {
        // Superior
        originX = Math.random() * window.innerWidth;
        originY = 0;
        destinationX = Math.random() * window.innerWidth - originX;
        destinationY = Math.random() * window.innerHeight;
    } else if (edge === 2) {
        // Esquerda
        originX = 0;
        originY = Math.random() * window.innerHeight;
        destinationX = Math.random() * window.innerWidth;
        destinationY = Math.random() * window.innerHeight - originY;
    } else {
        // Direita
        originX = window.innerWidth;
        originY = Math.random() * window.innerHeight;
        destinationX = -Math.random() * window.innerWidth;
        destinationY = Math.random() * window.innerHeight - originY;
    }

    rocket.style.left = `${originX}px`;
    rocket.style.top = `${originY}px`;
    rocket.style.setProperty('--dx', `${destinationX}px`);
    rocket.style.setProperty('--dy', `${destinationY}px`);

    sky_venda.appendChild(rocket);

    // Após o lançamento, criar a explosão
    rocket.addEventListener('animationend', () => {
        createExplosionVenda(
            rocket.getBoundingClientRect().left + 7, // Centro do foguete
            rocket.getBoundingClientRect().top + 7
        );
        rocket.remove();
    });
}

// Função para criar foguetes
function createRocket() {
    const rocket = document.createElement('div');
    rocket.className = 'rocket';

    // Determinar a posição inicial dos foguetes (bordas da tela)
    const edge = Math.floor(Math.random() * 4); // 0 = inferior, 1 = superior, 2 = esquerda, 3 = direita
    let originX, originY, destinationX, destinationY;

    if (edge === 0) {
        // Inferior
        originX = Math.random() * window.innerWidth;
        originY = window.innerHeight;
        destinationX = Math.random() * window.innerWidth - originX;
        destinationY = -Math.random() * window.innerHeight;
    } else if (edge === 1) {
        // Superior
        originX = Math.random() * window.innerWidth;
        originY = 0;
        destinationX = Math.random() * window.innerWidth - originX;
        destinationY = Math.random() * window.innerHeight;
    } else if (edge === 2) {
        // Esquerda
        originX = 0;
        originY = Math.random() * window.innerHeight;
        destinationX = Math.random() * window.innerWidth;
        destinationY = Math.random() * window.innerHeight - originY;
    } else {
        // Direita
        originX = window.innerWidth;
        originY = Math.random() * window.innerHeight;
        destinationX = -Math.random() * window.innerWidth;
        destinationY = Math.random() * window.innerHeight - originY;
    }

    rocket.style.left = `${originX}px`;
    rocket.style.top = `${originY}px`;
    rocket.style.setProperty('--dx', `${destinationX}px`);
    rocket.style.setProperty('--dy', `${destinationY}px`);

    sky.appendChild(rocket);

    // Após o lançamento, criar a explosão
    rocket.addEventListener('animationend', () => {
        createExplosion(
            rocket.getBoundingClientRect().left + 7, // Centro do foguete
            rocket.getBoundingClientRect().top + 7
        );
        rocket.remove();
    });
}

function createExplosionVenda(x, y) {
    const numParticles = 60; // Aumentar o número de partículas
    for (let i = 0; i < numParticles; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        // Cor aleatória
        particle.style.background = randomColor();
        // Posição inicial da explosão
        particle.style.left = `${x}px`;
        particle.style.top = `${y}px`;

        // Direção aleatória da partícula
        const angle = Math.random() * 2 * Math.PI;
        const distance = Math.random() * 250; // Explosão maior
        particle.style.setProperty('--dx', `${Math.cos(angle) * distance}px`);
        particle.style.setProperty('--dy', `${Math.sin(angle) * distance}px`);

        sky_venda.appendChild(particle);

        // Remover partícula após a explosão
        particle.addEventListener('animationend', () => particle.remove());
    }
}

// Função para criar explosões
function createExplosion(x, y) {
    const numParticles = 15; // Aumentar o número de partículas
    for (let i = 0; i < numParticles; i++) {
        const particle = document.createElement('div');
        particle.className = 'particle';
        // Cor aleatória
        particle.style.background = randomColor();
        // Posição inicial da explosão
        particle.style.left = `${x}px`;
        particle.style.top = `${y}px`;

        // Direção aleatória da partícula
        const angle = Math.random() * 2 * Math.PI;
        const distance = Math.random() * 100; // Explosão maior
        particle.style.setProperty('--dx', `${Math.cos(angle) * distance}px`);
        particle.style.setProperty('--dy', `${Math.sin(angle) * distance}px`);
        sky.appendChild(particle);
        // Remover partícula após a explosão
        particle.addEventListener('animationend', () => particle.remove());
    }
}

// Função para gerar uma cor aleatória
function randomColor() {
    return `hsl(${Math.random() * 360}, 100%, 70%)`;
}
// Gerar foguetes periodicamente


$(document).ready(function() {
    const hoje = new Date();
    const ano = hoje.getFullYear();
    const mes = (hoje.getMonth() + 1).toString().padStart(2, '0'); // Adiciona zero à esquerda
    const dia = hoje.getDate().toString().padStart(2, '0'); // Adiciona zero à esquerda

    const dataHoje = `${ano}-${mes}-${dia}`;
    function atualizarEstadoBotoes(dataAtual) {
        // Desabilita o botão "Next" se a dataAtual for igual à dataHoje
        if (dataAtual === dataHoje) {
            $("#btnNext").prop("disabled", true).css("background-color", "#d6d6d6");
        } else {
            $("#btnNext").prop("disabled", false).css("background-color", "#6c757d");
        }

        // // Desabilita o botão "Back" se a dataAtual for igual à menorData
        // if (dataAtual === menorData) {
        //     $("#btnBack").prop("disabled", true).css("background-color", "#d6d6d6");
        // } else {
        //     $("#btnBack").prop("disabled", false).css("background-color", "#6c757d");
        // }
    }




    function formatarData(dataISO) {
        const diasSemana = [
            "Domingo",
            "Segunda-feira",
            "Terça-feira",
            "Quarta-feira",
            "Quinta-feira",
            "Sexta-feira",
            "Sábado",
        ];

        const meses = [
            "janeiro",
            "fevereiro",
            "março",
            "abril",
            "maio",
            "junho",
            "julho",
            "agosto",
            "setembro",
            "outubro",
            "novembro",
            "dezembro",
        ];

        // Converte a string ISO em data ajustada para o fuso horário local
        const partes = dataISO.split('-'); // Divide o formato "YYYY-MM-DD"
        const data = new Date(partes[0], partes[1] - 1, partes[2]); // Cria a data como local

        const dia = data.getDate();
        const mes = meses[data.getMonth()];
        const ano = data.getFullYear();
        const diaSemana = diasSemana[data.getDay()];

        return `${dia.toString().padStart(2, '0')}/${(data.getMonth() + 1)
            .toString()
            .padStart(2, '0')}/${ano} (${diaSemana}) - ${dia}º dia do mês de ${mes}`;
    }

    let dataAtual = null; // Armazena a data sendo exibida atualmente
    $("#modal_historico").on("click", function () {
        carregarHistorico();
        $("#overlayHistorico").css("display", "block");
        $("#modalHistorico").css("display", "block");
    });

    $("#fecharModal, #overlayHistorico").on("click", function () {
        $("#overlayHistorico").css("display", "none");
        $("#modalHistorico").css("display", "none");
    });

    // $("#fecharModal, #overlay").on("click", function () {
    //     $("#modalHistorico").hide();
    //     $("#overlay").hide();
    // });

    $("#btnBack").on("click", function () {
        if (dataAtual) {
            carregarHistorico(dataAtual, true,false); // Busca o histórico do dia anterior
        }
    });

    $("#btnNext").on("click", function () {
        if (dataAtual) {
            carregarHistorico(dataAtual, false, true); // Avança para o próximo dia
        }
    });

    function editarHistorico(id) {
        console.log("Olaaaaa ",id);
        const btn = $(this);
        const row = btn.closest("tr"); // Linha correspondente
        const isEditing = btn.data("editing") === true; // Verifica se já está em modo de edição

        row.find(".editable").each(function () {
            const field = $(this).data("field"); // Campo (ex: vidas_individual)
            const value = $(this).text().trim(); // Valor editado
            updatedData[field] = value; // Adiciona ao objeto


        });

    }




    function carregarHistorico(data = null, retroceder = false, avancar = false) {
        $.ajax({
            url: rankingHistorico,
            method: "POST",
            data: { data: data, retroceder: retroceder,avancar:avancar }, // Envia a data atual e o comando de retrocesso
            success: function (response) {
                console.log(response);
                if (response.data_atual) {
                    dataAtual = response.data_atual; // Atualiza a data atual exibida
                    const dataFormatada = formatarData(dataAtual); // Formata a data
                    $("#dataAtual").text(dataFormatada); // Exibe no título
                    atualizarEstadoBotoes(dataAtual);
                }

                let tbody = $("#tabelaHistorico tbody");
                tbody.empty();

                let totalVidasIndividual = 0;
                let totalVidasColetivo = 0;
                let totalVidasEmpresarial = 0;



                response.dados.forEach(item => {
                    const dataFormatada = formatarDataTabela(item.data);

                    totalVidasIndividual += item.vidas_individual;
                    totalVidasColetivo += item.vidas_coletivo;
                    totalVidasEmpresarial += item.vidas_empresarial;


                    tbody.append(`
                            <tr>
                                <td>${dataFormatada}</td>
                                <td>${item.nome}</td>
                                <td contenteditable="true" class="editable" data-field="vidas_individual">${item.vidas_individual}</td>
                                <td contenteditable="true" class="editable" data-field="vidas_coletivo">${item.vidas_coletivo}</td>
                                <td contenteditable="true" class="editable" data-field="vidas_empresarial">${item.vidas_empresarial}</td>
                                <td>
                                    <button class="editarHistorico" data-id="${item.id}" style="background-color:#ffc107; border:none; color:white; padding:5px 10px; border-radius:5px;">Editar</button>
                                </td>
                            </tr>
                        `);
                });

                $("#totalVidasIndividual").text(totalVidasIndividual);
                $("#totalVidasColetivo").text(totalVidasColetivo);
                $("#totalVidasEmpresarial").text(totalVidasEmpresarial);

                $("#tabelaHistorico").on("click", ".editarHistorico", function () {
                    const btn = $(this); // Botão clicado
                    const row = btn.closest("tr"); // Linha correspondente
                    const isEditing = btn.data("editing") === true; // Verifica o estado de edição

                    let individual = row.find('[data-field="vidas_individual"]').text().trim();
                    let coletivo = row.find('[data-field="vidas_coletivo"]').text().trim();
                    let empresarial = row.find('[data-field="vidas_empresarial"]').text().trim();
                    let id = btn.attr("data-id");
                    let updatedData = {individual,coletivo,empresarial,id};
                    $.ajax({
                        url: historicoEditar, // Substituir pela rota correta
                        method: "POST",
                        data: updatedData,
                        success: function (response) {
                            toastr.options = {
                                "closeButton": true,
                                "debug": false,
                                "newestOnTop": false,
                                "progressBar": true,
                                "positionClass": "toast-top-right", // Posição da mensagem
                                "preventDuplicates": true,
                                "onclick": null,
                                "showDuration": "300",
                                "hideDuration": "1000",
                                "timeOut": "5000", // Tempo de exibição
                                "extendedTimeOut": "1000",
                                "showEasing": "swing",
                                "hideEasing": "linear",
                                "showMethod": "fadeIn",
                                "hideMethod": "fadeOut"
                            };
                            toastr.success("Histórico atualizado com sucesso!");
                            recalcularTotais()

                        },
                        error: function (xhr, status, error) {
                            console.error("Erro ao atualizar:", error);
                        }
                    });

                });
            }
        });
    }

    function recalcularTotais() {
        let totalVidasIndividual = 0;
        let totalVidasColetivo = 0;
        let totalVidasEmpresarial = 0;

        // Percorrer todas as linhas da tabela e somar os valores
        $("#tabelaHistorico tbody tr").each(function () {
            const individual = parseInt($(this).find('[data-field="vidas_individual"]').text().trim()) || 0;
            const coletivo = parseInt($(this).find('[data-field="vidas_coletivo"]').text().trim()) || 0;
            const empresarial = parseInt($(this).find('[data-field="vidas_empresarial"]').text().trim()) || 0;

            totalVidasIndividual += individual;
            totalVidasColetivo += coletivo;
            totalVidasEmpresarial += empresarial;
        });

        // Atualizar os totais na interface
        $("#totalVidasIndividual").text(totalVidasIndividual);
        $("#totalVidasColetivo").text(totalVidasColetivo);
        $("#totalVidasEmpresarial").text(totalVidasEmpresarial);
    }

    function formatarDataTabela(dataISO) {
        const partes = dataISO.split('-'); // Divide o formato "YYYY-MM-DD"
        return `${partes[2]}/${partes[1]}/${partes[0]}`; // Retorna no formato "DD/MM/YYYY"
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function calcularTotalMeta(linha) {
        let totalMeta = 0;
        linha.find('.meta_vidas').each(function() {
            let valor = parseFloat($(this).val()) || 0;
            totalMeta += valor;
        });
        linha.find('#total_meta').text(totalMeta);
        calcularPorcentagem(linha, totalMeta);
    }

    function calcularTotalVidas(linha) {
        let totalVidas = 0;
        linha.find('.valor_vidas').each(function() {
            let valor = parseFloat($(this).val()) || 0;
            totalVidas += valor;
        });

        linha.find('#meta_individual_vidas_total').text(totalVidas);
        let totalMeta = parseFloat(linha.find('#meta_individual_total').text()) || 0;
        calcularPorcentagem(linha, totalMeta, totalVidas);
    }

    function calcularPorcentagem(linha, totalMeta, totalVidas) {
        totalVidas = totalVidas || parseFloat(linha.find('#meta_individual_vidas_total').text()) || 0;
        let porcentagem = 0;
        if (totalMeta > 0) {
            porcentagem = (totalVidas / totalMeta) * 100;
        }
        linha.find('#meta_individual_total_porcentagem').text(porcentagem.toFixed(2));
    }

    $('.meta_vidas, .valor_vidas').on('input', function() {
        let linha = $(this).closest('tr');
        if ($(this).hasClass('meta_vidas')) {
            calcularTotalMeta(linha);
        }
        else if ($(this).hasClass('valor_vidas')) {
            calcularTotalVidas(linha);
        }
    });
    function getMonthName(monthIndex) {
        const months = [
            "Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho",
            "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"
        ];
        return months[monthIndex];
    }
    function updateMonthYear() {
        const now = new Date();
        const month = getMonthName(now.getMonth());
        const year = now.getFullYear();
        document.getElementById('mes_ano').textContent = `Goiania - ${month}/${year}`;
    }
    updateMonthYear();
    function updateDateTime() {
        const now = new Date();
        const date = now.toLocaleDateString('pt-BR');
        const time = now.toLocaleTimeString('pt-BR', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        document.getElementById('aqui_data').textContent = date;
        document.getElementById('aqui_hora').textContent = time;
    }
    function updateDaysRemaining() {
        const now = new Date();
        const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);
        const daysRemaining = Math.ceil((endOfMonth - now) / (1000 * 60 * 60 * 24));
        document.getElementById('quantidade_dias').textContent = daysRemaining;
    }
    setInterval(updateDateTime, 1000);
    // Atualiza os dias restantes uma vez ao carregar a página
    updateDaysRemaining();
    document.getElementById('modal_concessionarias').onclick = function() {
        document.getElementById('planilhaModal').style.display = 'block';
    };

    function salvarConcecionario() {
        let concessionariasData = [];
        $('tbody tr').each(function () {
            let concessionariaId = $(this).data('id');
            // Coleta os valores dos inputs correspondentes a essa concessionária
            let meta_individual = $(this).find('input[name="concessionarias[' + concessionariaId + '][meta_individual]"]').val();
            let individual = $(this).find('input[name="concessionarias[' + concessionariaId + '][individual]"]').val();
            let meta_super_simples = $(this).find('input[name="concessionarias[' + concessionariaId + '][meta_super_simples]"]').val();
            let super_simples = $(this).find('input[name="concessionarias[' + concessionariaId + '][super_simples]"]').val();
            let meta_pme = $(this).find('input[name="concessionarias[' + concessionariaId + '][meta_pme]"]').val();
            let pme = $(this).find('input[name="concessionarias[' + concessionariaId + '][pme]"]').val();
            let meta_adesao = $(this).find('input[name="concessionarias[' + concessionariaId + '][meta_adesao]"]').val();
            let adesao = $(this).find('input[name="concessionarias[' + concessionariaId + '][adesao]"]').val();
            concessionariasData.push({
                id: concessionariaId,
                meta_individual: meta_individual,
                individual: individual,
                meta_super_simples: meta_super_simples,
                super_simples: super_simples,
                meta_pme: meta_pme,
                pme: pme,
                meta_adesao: meta_adesao,
                adesao: adesao
            });
        });
        $.ajax({
            url: cadastrarConcessionaria,  // Define a rota para o update
            method: 'POST',
            data: {
                concessionarias: concessionariasData  // Dados a serem enviados
            }
        });
    }
    document.getElementsByClassName('close')[0].onclick = function() {
        document.getElementById('planilhaModal').style.display = 'none';
        salvarConcecionario();
    };
    window.onclick = function(event) {
        if (event.target == document.getElementById('planilhaModal')) {
            document.getElementById('planilhaModal').style.display = 'none';
            salvarConcecionario();
        }
    };

    const modal = $("#modal-desbloqueio");

    $("#btn-desbloquear-audio").on("click",function(){
        $("#modal-desbloqueio").fadeOut('fast');
        somCarro.play().then(() => {
            somCarro.pause();
            somCarro.currentTime = 0;

            somFogos.play().then(() => {
                somFogos.pause();
                somFogos.currentTime = 0;

                // Remove a modal após desbloqueio
                modal.style.display = "none";
                console.log("Áudio desbloqueado com sucesso!");
            }).catch(err => console.error("Erro ao desbloquear somFogos:", err));
        }).catch(err => console.error("Erro ao desbloquear somCarro:", err));
    });

    modal.css({"display":"flex"});


    function calcularPorcentagemEtotal() {
        let metaIndividual = parseFloat(document.getElementById('meta_individual').value) || 0;
        let vidasIndividual = parseFloat(document.getElementById('vidas_individual').value) || 0;
        let metaSuperSimples = parseFloat(document.getElementById('meta_super_simples').value) || 0;
        let vidasSuperSimples = parseFloat(document.getElementById('vidas_super_simples').value) || 0;
        let metaPme = parseFloat(document.getElementById('meta_pme').value) || 0;
        let vidasPme = parseFloat(document.getElementById('vidas_pme').value) || 0;
        let metaAdesao = parseFloat(document.getElementById('meta_adesao').value) || 0;
        let vidasAdesao = parseFloat(document.getElementById('vidas_adesao').value) || 0;
        document.getElementById('percent_individual').innerText = ((vidasIndividual / metaIndividual) * 100).toFixed(2) + '%';
        document.getElementById('percent_super_simples').innerText = ((vidasSuperSimples / metaSuperSimples) * 100).toFixed(2) + '%';
        document.getElementById('percent_pme').innerText = ((vidasPme / metaPme) * 100).toFixed(2) + '%';
        document.getElementById('percent_adesao').innerText = ((vidasAdesao / metaAdesao) * 100).toFixed(2) + '%';
        document.getElementById('total_meta').innerText = metaIndividual + metaSuperSimples + metaPme + metaAdesao;
        document.getElementById('total_vidas').innerText = vidasIndividual + vidasSuperSimples + vidasPme + vidasAdesao;
    }

    function fecharRankingModal() {
        $('.modal_ranking_diario').removeClass('aparecer').addClass('ocultar');
    }

    function abrirFogosModal() {
        $('#fogosModal').addClass('aparecer').removeClass('ocultar');
    }




    // Função para verificar a troca de liderança
    $("body").on('change','#user_id',function(){
        let user_id = $(this).val();
        $("#overlay").show();
        $("#loading").show();
        $.ajax({
            url:"{{route('ranking.verificar.corretor')}}",
            method:"POST",
            data: {user_id},
            success:function(res) {
                $("body").find("input[name='vidas_individual']").val(res.individual);
                $("body").find("input[name='vidas_coletivo']").val(res.coletivo);
                $("body").find("input[name='vidas_empresarial']").val(res.empresarial);
            },
            complete: function() {
                // Ocultar o loading quando a requisição AJAX for concluída
                $("#overlay").hide();
                $("#loading").hide();
            }
        });
    });

    //AJAX para atualizar o ranking e verificar troca de liderança
    $('#rankingForm').on('submit', function (e) {
        e.preventDefault();
        $.ajax({
            url: ranking,
            method: "POST",
            data: $(this).serialize(),
            success: function (res) {

            }
        });
    });

    let activeButtonIndex = 0; // Começamos com o índice 0 (Vivaz)
    const footerButtons = $('.footer-btn'); // Botões do rodapé
    const slidesContainer = $('.slides-container');
    const totalSlides = $('.slide-carrossel').length; // Total de slides
    let currentSlide = 0; // Índice do slide atual

    $(footerButtons).on('click', function () {
        activeButtonIndex = footerButtons.index(this); // Atualiza o índice do botão ativo
        trocaDeAba(); // Chama trocaDeAba com o novo índice
    });

    function trocaDeAba() {
        desbloquearAudio();
        document.body.dispatchEvent(new Event('click'));
        footerButtons.removeClass('active'); // Remove a classe 'active' de todos os botões
        footerButtons.eq(activeButtonIndex).addClass('active'); // Adiciona 'active' ao botão atual

        let corretora = footerButtons.eq(activeButtonIndex).data('corretora'); // Define a corretora pelo índice ativo

        if (corretora !== "carrossel") {

            if(corretora == "diario") {
                $("#titulo_ranking").text("Ranking - Diario");
            } else if(corretora == "semanal") {
                $("#titulo_ranking").text("Ranking - Semanal");
            } else if(corretora == "america") {
                $("#titulo_ranking").text("Ranking - America");
            } else if(corretora === "vivaz") {
                $("#titulo_ranking").text("Ranking - Vivaz");
            } else if(corretora == "grupo") {
                $("#titulo_ranking").text("Ranking - Grupo America");
            } else if(corretora == "concessi") {
                $("#titulo_ranking").text("Ranking - Concessionárias");
            }



            $(".carrossel-container").addClass("ocultar");
            $("#principal").addClass("d-flex flex-column flex-grow").removeClass('ocultar');
            $("#footer-aqui").removeClass("ocultar");
            document.body.dispatchEvent(new Event('click'));
            $.ajax({
                url: rankingFiltragem,
                type: 'GET',
                data: { corretora: corretora },
                success: function (data) {

                    // Atualiza o conteúdo do ranking e os valores do lado direito
                    $(".stage").html(data.podium);
                    $("#dados_direito").html(data.ranking);
                    let total_vidas = parseInt(data.totals[0].total_individual) + parseInt(data.totals[0].total_coletivo) + parseInt(data.totals[0].total_empresarial);
                    let meta = defineMetaPorCorretora(corretora);

                    $(".aqui_meta").text(meta);
                    $(".total_vidas").text(total_vidas);
                    $(".total_porcentagem").text(((total_vidas / meta) * 100).toFixed(2));
                    updateHeader(corretora,data.totals[0]);
                    let numGroups = corretora === "concessi" ? $('.slide-corretora').length : $('.slide-group').length;
                    if (corretora === "concessi") {
                        createSlideShowCorretora(numGroups);
                    } else {
                        createSlideShow(numGroups);
                    }
                    setTimeout(() => {
                        activeButtonIndex = (activeButtonIndex + 1) % footerButtons.length;
                        changeActiveButton(); // Troca para o próximo botão após o tempo
                    }, numGroups * 15000);
                }
            });
        } else {
            iniciarCarrossel();
        }
    }

    function defineMetaPorCorretora(corretora) {
        switch (corretora) {
            case 'grupo': return 944;
            case 'america': return 472;
            case 'diario': return 10;
            case 'semanal': return 65;
            case 'estrela': return 150;
            case 'concessi': return 3629;
            case 'vivaz': return 472;
            default: return 0;
        }
    }

    function iniciarCarrossel() {
        $("#principal").addClass('ocultar');
        $(".carrossel-container").removeClass("ocultar");
        $("#footer-aqui").addClass("ocultar");
        $("#titulo_ranking").text("Ranking - Campanhas")
        currentSlide = 0;
        showSlide(currentSlide);
        startCarousel();
        setTimeout(() => {
            activeButtonIndex = (activeButtonIndex + 1) % footerButtons.length;
            changeActiveButton();
        }, 63000); // 63 segundos para o carrossel
    }

    function changeActiveButton() {
        trocaDeAba(); // Chama a troca de aba para o próximo botão
    }

    // Funções auxiliares para slides e cabeçalho omitidas para brevidade, mas mantidas iguais
    trocaDeAba()

    function updateHeader(corretora,totais) {
        // Lógica de atualização de header conforme a corretora
        if (corretora === 'concessi') {
            $("#header_esquerda_concessionaria").removeClass('ocultar').addClass('aparecer');
            $("#header_esquerda").removeClass('aparecer').addClass('ocultar');
            $("#header_esquerda_estrela").removeClass('aparecer').addClass('ocultar');

            $(".total_individual_concessionaria").text(totais.total_individual);
            $(".total_super_simples_concessionaria").text(totais.total_super_simples);
            $(".total_pme_concessionaria").text(totais.total_pme);
            $(".total_adesao_concessionaria").text(totais.total_adesao);
            $(".total_vidas_concessionaria").text(totais.total_vidas);
            $(".total_porcentagem_concessionaria").text(totais.porcentagem_geral);



        } else if (corretora === 'estrela') {
            $("#header_esquerda_estrela").removeClass('ocultar').addClass('aparecer');
            $("#header_esquerda_concessionaria").removeClass('aparecer').addClass('ocultar');
            $("#header_esquerda").removeClass('aparecer').addClass('ocultar');
        } else {
            $("#header_esquerda").removeClass('ocultar').addClass('aparecer');
            $("#header_esquerda_concessionaria").removeClass('aparecer').addClass('ocultar');
            $("#header_esquerda_estrela").removeClass('aparecer').addClass('ocultar');

            $(".total_individual").text(totais.total_individual);
            $(".total_coletivo").text(totais.total_coletivo);
            $(".total_empresarial").text(totais.total_empresarial);




        }
    }

    function createSlideShow(numGroups) {
        const slideGroups = document.querySelectorAll('.slide-group');
        let currentGroup = 0;
        function showSlideGroup(n) {
            slideGroups.forEach((group, index) => {
                group.style.display = index === n ? 'flex' : 'none';
            });
        }
        function nextSlideGroup() {
            currentGroup = (currentGroup + 1) % slideGroups.length;
            showSlideGroup(currentGroup);
        }
        // Exibe o primeiro grupo de slides
        showSlideGroup(currentGroup);
        // Tempo de exibição de cada grupo (mínimo de 15 segundos)
        let groupTime = Math.max(15, 15);
        setInterval(nextSlideGroup, groupTime * 1000);
    }

    function showSlide(index) {
        const slideWidth = 100; // Cada slide ocupa 100% da largura
        slidesContainer.css('transform', `translateX(-${index * slideWidth}%)`); // Mover o contêiner dos slides
    }



    function createSlideShowCorretora(numGroups) {
        const slideGroups = document.querySelectorAll('.slide-corretora');
        let currentGroup = 0;

        function showSlideGroup(n) {
            slideGroups.forEach((group, index) => {
                group.style.display = index === n ? 'block' : 'none';
            });
        }
        function nextSlideGroup() {
            currentGroup = (currentGroup + 1) % slideGroups.length;
            showSlideGroup(currentGroup);
        }            // Exibe o primeiro grupo de slides
        showSlideGroup(currentGroup);
        // Tempo de exibição de cada grupo (mínimo de 15 segundos)
        let groupTime = Math.max(15, 15);
        setInterval(nextSlideGroup, groupTime * 1000);
    }

    let carouselInterval;

    function startCarousel() {
        currentSlide = 0; // Sempre começa do primeiro slide
        if (carouselInterval) {
            clearInterval(carouselInterval);
        }
        carouselInterval = setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides; // Avança para o próximo slide, volta ao primeiro se chegar ao final
            showSlide(currentSlide); // Mostra o slide atual
        }, 7000); // Troca a cada 3 segundos
    }





    function logCorretora() {
        const currentButton = footerButtons.eq(activeButtonIndex);
    }


    function numberFormat(number, decimals = 2, decPoint = '.', thousandsSep = ',') {
        // Define o número de decimais
        const fixedNumber = number.toFixed(decimals);

        // Separa a parte inteira da parte decimal
        let [integerPart, decimalPart] = fixedNumber.split('.');

        // Adiciona separadores de milhares
        integerPart = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, thousandsSep);

        // Junta a parte inteira e decimal com o separador decimal
        return decimalPart ? integerPart + decPoint + decimalPart : integerPart;
    }


});















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



