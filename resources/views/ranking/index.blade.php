<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('css/estilo.css')}}">
    <link rel="stylesheet" href="{{asset('css/toastr.min.css')}}">
    <style>
        .stage {width: 95%;flex-grow: 1;background-image: url('{{ asset('podium2.png') }}');background-size: cover;background-repeat: no-repeat;background-position: center;display: flex;justify-content: space-around;align-items: flex-end;margin: 0 auto;}
    </style>
    <script src="{{asset('assets/jquery.min.js')}}"></script>
    <script src="{{asset('js/toastr.min.js')}}"></script>
    <link rel="stylesheet" href="{{asset('css/ranking.css')}}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        let cadastrarConcessionaria = "{{route('cadastrar.concessionaria')}}";
        let rankingFiltragem = "{{ route('ranking.filtragem') }}";
        let rankingHistorico = "{{route('ranking.historico')}}";
        let historicoEditar = "{{route('ranking.historico.editar')}}";
        let ranking = "{{ route('ranking.atualizar') }}";
        let audioDesbloqueado = false;
        let liderAtual = @json($liderAtual);
        let somCarro = new Audio('carro_ultrapassagem.mp3');
        let somFogos = new Audio('fogos.mp3');
        let isAnimating = false; // Controle para evitar animações simultâneas

    </script>
    <style>
        .modal-desbloqueio {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw; /* Garante que ocupa toda a largura */
            height: 100vh; /* Garante que ocupa toda a altura */
            background: rgba(0, 0, 0, 0.93); /* Fundo mais escuro */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            visibility: visible; /* Inicialmente visível */
            opacity: 1;
            transition: visibility 0s, opacity 0.3s ease-in-out;
            filter: blur(0.8px); /* Efeito de desfoque */
        }

        /* Efeito de transição para ocultar a modal */
        .modal-desbloqueio.ocultar {
            visibility: hidden;
            opacity: 0;
            filter: none; /* Retira o blur quando estiver ocultando */
        }

        /* Conteúdo da Modal */
        .modal-content-desbloqueio {
            background: rgba(254, 254, 254, 0.18); /* Fundo mais escuro com transparência */
            padding: 80px;
            border-radius: 10px;
            text-align: center;
            max-width: 90%; /* Evita que a modal seja muito grande */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            filter: blur(0); /* Remove o desfoque do conteúdo */
            animation: fadeIn 0.5s ease-in-out; /* Animação de aparição */
        }

        /* Animação para o botão */
        button#btn-desbloquear-audio {
            padding: 35px 70px;
            font-size: 42px; /* Aumenta o tamanho do texto */
            color: #fff;
            background: #007bff;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: all 0.3s ease; /* Efeito de transição para o botão */
            animation: pointerAnimation 2s infinite; /* Animação da mãozinha */
        }

        /* Animação de hover do botão */
        button#btn-desbloquear-audio:hover {
            background: #0056b3;
            transform: scale(1.1); /* Aumenta o botão ao passar o mouse */
        }

        /* Animação de "mãozinha" no botão */
        @keyframes pointerAnimation {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
        }

        /* Estilo do botão quando ele estiver pressionado */
        button#btn-desbloquear-audio:active {
            background: #004085;
        }

        /* Estilo de transição suave */
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

    </style>
    <script type="module">

    </script>
</head>
<body>

<div class="sky ocultar" style="display:flex;background-color: black;z-index: 50;inset: 0;position: fixed;align-items: center;justify-content: center; --tw-bg-opacity: 0.8;">
    <div style="border-radius:10px;color:white;display:flex;flex-direction:column;margin-bottom: 20px;">
        <!-- Header: Compacto com menos altura -->
        <div class="header" style="flex: 0 0 10%; display: flex; align-items: center; justify-content: space-between; padding: 0 10px;width:100%;">
            <img src="{{asset('medalha-primeiro.png')}}" style="width:100px;height:100px;" alt="">
            <p style="font-size:1.5em; display:flex; flex-direction:column; justify-content:center; color:white; text-align:center; line-height: 1;">
                <span style="font-size:2em; font-weight: bold;" class="quantidade_vidas"></span>
                <span style="font-weight: bold;">Vidas</span>
            </p>
        </div>
        <!-- Corpo: Diminuir o tamanho da imagem para encaixar melhor -->
        <div class="corpo" style="display: flex; justify-content: center; align-items: flex-start;">
            <img src="" class="assumir_lider" style="width:550px;height:550px;border-radius:50%;box-sizing:border-box;">
        </div>
        <!-- Footer: Mais compacto com menos altura -->
        <div class="footer footer_ranking" style="flex: 0 0 10%;display:flex;justify-content: center;align-items:center;font-size:1.5em;color:#FFF;font-weight:bold;">
            1º Lugar no Ranking
        </div>
    </div>
</div>

<div id="overlay"></div>
<div id="loading">
    <span style="font-size:1.3em;">.</span>
    <span style="font-size:1.3em;">.</span>
    <span style="font-size:1.3em;">.</span>
</div>
<x-modal-ranking :vendasDiarias="$vendasDiarias"></x-modal-ranking>

<div id="modal-desbloqueio" class="modal-desbloqueio">
    <div class="modal-content-desbloqueio">
        <h2 class="text-white font-bold text-2xl">Bem-vindo ao Ranking</h2>
        <p class="text-white my-4 text-2xl">Pressione o botão para liberar o acesso ao ranking!</p>
        <button class="text-white" id="btn-desbloquear-audio">Liberar</button>
    </div>
</div>

<!-- Modal -->
<div id="planilhaModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="concessionariaForm" style="display: none; margin-top: 20px;">
            <div>
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" style="width: 100%;" class="form-control-sm" required placeholder="Digitar o nome da Concessionaria">
            </div>
            <div style="display: flex; justify-content: space-between;">
                <div style="width: 12%;">
                    <label for="meta_individual">Meta Individual:</label>
                    <input type="number" id="meta_individual" name="meta_individual" class="form-control-sm text-black" required placeholder="Meta Individual" >
                </div>
                <div style="width: 12%;">
                    <label for="individual">Individual:</label>
                    <input type="number" id="individual" name="individual" required class="form-control-sm text-black" placeholder="Valor Individual">
                </div>
                <div style="width: 12%;">
                    <label for="meta_super_simples">Meta Super Simples:</label>
                    <input type="number" id="meta_super_simples" name="meta_super_simples" required class="form-control-sm text-black" placeholder="Meta Super Simples">
                </div>
                <div style="width: 12%;">
                    <label for="super_simples">Super Simples:</label>
                    <input type="number" id="super_simples" name="super_simples" required class="form-control-sm text-black" placeholder="Super Simples">
                </div>
                <div style="width: 12%;">
                    <label for="meta_pme">Meta PME:</label>
                    <input type="number" id="meta_pme" name="meta_pme" required class="form-control-sm text-black" placeholder="Meta PME">
                </div>
                <div style="width: 12%;">
                    <label for="pme">PME:</label>
                    <input type="number" id="pme" name="pme" required class="form-control-sm text-black" placeholder="PME">
                </div>
                <div style="width: 12%;">
                    <label for="meta_adesao">Meta Adesão:</label>
                    <input type="number" id="meta_adesao" name="meta_adesao" required class="form-control-sm text-black" placeholder="Meta Adesão">
                </div>
                <div style="width: 12%;">
                    <label for="adesao">Adesão:</label>
                    <input type="number" id="adesao" name="adesao" required class="form-control-sm text-black" placeholder="Adesão">
                </div>
            </div>
            <button type="submit" class="btn-cadastro btn-primary btn mt-2" style="width: 100%;">Cadastrar</button>
        </div>
        <div class="modal-body">
            <table style="width:80%;margin:0 auto;">
                <thead>
                <tr>
                    <th rowspan="2" class="bg-gray-100 bg-opacity-10 text-white">Concessionaria</th>
                    <th colspan="2" class="bg-individual">Individual</th>
                    <th colspan="2" class="bg-super-simples">Super Simples</th>
                    <th colspan="2" class="bg-pme">PME</th>
                    <th colspan="2" class="bg-adesao">Adesão</th>
                    <th colspan="3" class="bg-total-geral" style="margin-left: 10px;">Total Geral</th>
                </tr>
                <tr>
                    <th class="bg-individual">META</th>
                    <th class="bg-individual">VIDAS</th>
                    <th class="bg-super-simples">META</th>
                    <th class="bg-super-simples">VIDAS</th>
                    <th class="bg-pme">META</th>
                    <th class="bg-pme">VIDAS</th>
                    <th class="bg-adesao">META</th>
                    <th class="bg-adesao">VIDAS</th>
                    <th class="bg-total-geral">META</th>
                    <th class="bg-total-geral">VIDAS</th>
                    <th class="bg-total-geral">%</th>
                </tr>
                </thead>
                <tbody>

                @php
                    $meta_individual_total = 0;
                    $meta_individual_vidas_total = 0;
                    $meta_individual_total_porcentagem = 0;
                @endphp

                @foreach($concessionarias as $c)
                    @php
                        $meta_individual_total = $c->meta_individual + $c->meta_super_simples + $c->meta_pme + $c->meta_adesao;
                        $meta_individual_vidas_total = $c->individual + $c->super_simples + $c->pme + $c->adesao;
                        $meta_individual_total_porcentagem = ($meta_individual_vidas_total != 0 && $meta_individual_total != 0) ? round(($meta_individual_vidas_total / $meta_individual_total) * 100, 2) : 0;
                    @endphp
                    <tr data-id="{{$c->id}}">
                        <td class="bg-gray-700 bg-opacity-20 text-white">{{$c->nome}}</td>
                        <td><input type="number" name="concessionarias[{{$c->id}}][meta_individual]" class="meta_vidas bg-individual text-black" placeholder="Meta" value="{{$c->meta_individual}}"></td>
                        <td><input type="number" name="concessionarias[{{$c->id}}][individual]" class="valor_vidas bg-individual text-black" placeholder="Vidas" value="{{$c->individual}}"></td>
                        <td><input type="number" name="concessionarias[{{$c->id}}][meta_super_simples]" class="meta_vidas bg-super-simples text-black" placeholder="Meta" value="{{$c->meta_super_simples}}"></td>
                        <td><input type="number" name="concessionarias[{{$c->id}}][super_simples]" class="valor_vidas bg-super-simples text-black" placeholder="Vidas" value="{{$c->super_simples}}"></td>
                        <td><input type="number" name="concessionarias[{{$c->id}}][meta_pme]" class="meta_vidas bg-pme text-black" placeholder="Meta" value="{{$c->meta_pme}}"></td>
                        <td><input type="number" name="concessionarias[{{$c->id}}][pme]" class="valor_vidas bg-pme text-black" placeholder="Vidas" value="{{$c->pme}}"></td>
                        <td><input type="number" name="concessionarias[{{$c->id}}][meta_adesao]" class="meta_vidas bg-adesao text-black" placeholder="Meta" value="{{$c->meta_adesao}}"></td>
                        <td><input type="number" name="concessionarias[{{$c->id}}][adesao]" class="valor_vidas bg-adesao text-black" placeholder="Vidas" value="{{$c->adesao}}"></td>
                        <td style="padding-left: 30px;color:white;" id="meta_individual_total"><span>{{$meta_individual_total}}</span></td>
                        <td style="padding-left: 30px;color:white;" id="meta_individual_vidas_total"><span>{{$meta_individual_vidas_total}}</span></td>
                        <td style="padding-left: 30px;color:white;" id="meta_individual_total_porcentagem"><span>{{$meta_individual_total_porcentagem}}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<nav class="navbar navbar-expand-md navbar-light shadow-lg py-2 text-sm" style="background-color:#006EB6;">
    <div class="container-fluid text-white">
        <div class="flex items-center content-center justify-between container-trofeu">
            <div class="flex">
                <img src="{{asset('trofeu.png')}}" alt="Trofeu">
                <h5 class="my-auto font-italic">Ranking de Vendas</h5>
            </div>
            <div>
                <span class="bg-white py-1 px-4 rounded" style="color:#335B99;" id="mes_ano">Goiania - Novembro/2024</span>
            </div>
        </div>
        <div class="container-ranking-title text-center">
            <span class="bg-white py-1 px-4 rounded" style="color:#335B99;" id="titulo_ranking">Ranking - Diario</span>
        </div>
        <div class="d-flex justify-content-between items-center container-button-header">
            <div class="d-flex text-center flex-wrap">
                <span style="width:100%;margin:0;padding:0;">Faltam <span style="color:#F8DA22;font-weight:bold;" id="quantidade_dias">21</span> dias</span>
                <span style="width:100%;">para o</span>
                <span style="width:100%;margin:0;padding:0;">fim do mês</span>
            </div>

{{--            <button style="border:none;background-color:#0dcaf0;color:#FFF;border-radius:5%;font-size:1em;padding:7px;display:flex;align-items:center;align-self: center;" onclick="enviarMensagem()">Teste</button>--}}
            <button class="botoes-acoes" id="modal_historico">Historico</button>
            <button class="botoes-acoes" id="modal_concessionarias">Concessionarias</button>
            <button class="botoes-acoes" id="modal_ranking_diario">Ranking</button>
            <div class="d-flex flex-column text-center" style="font-size: 1.5em;">
                <span id="aqui_data">09/08/2024</span>
                <span id="aqui_hora">12:40</span>
            </div>
        </div>
    </div>
</nav>

<div class="carrossel-container ocultar">
    <div class="slides-container">
        <div class="slide-carrossel">
            <img src="{{asset('slides/02.jpg')}}" alt="Imagem 2">
        </div>
    </div>
</div>

<main id="principal" class="d-flex flex-column flex-grow">

    <div class="d-flex" style="min-height:99%;">
        <div style="display:flex;flex-basis:50%;flex-direction:column;">
            @php
                $total_vidas=$totals[0]->total_individual + $totals[0]->total_coletivo + $totals[0]->total_empresarial;
                $meta=10;
                $porcentagem=($total_vidas / $meta) * 100;
            @endphp
            <div id="header_esquerda">
                <div class="container-foguete">
                    <img src="{{asset('foguete.png')}}" alt="Hapvida">
                </div>
                <div class="container-meta">
                    <span class="header_esquerda_title">Meta</span>
                    <span class="header_esquerda_container">
                        <span style="color:#6a1a21;" class="aqui_meta">13</span>
                    </span>
                </div>
                @if(isset($totals[0]) && !empty($totals[0]))
                    <div style="display:flex;flex-direction:column;">
                        <span class="header_esquerda_title">Individual</span>
                        <span style="background-color:rgba(255, 255, 255, 0.8);padding:5px 15px;display:flex;justify-content:center;border-radius:10px;font-weight:bold;width:80px;border:2px solid yellow;">
                            <span style="color:#6a1a21;" class="total_individual">{{$totals[0]->total_individual}}</span>
                        </span>
                    </div>
                    <div style="display:flex;flex-direction:column;">
                        <span class="header_esquerda_title">Coletivo</span>
                        <span style="background-color:rgba(255, 255, 255, 0.8);padding:5px 15px;display:flex;justify-content:center;border-radius:10px;font-weight:bold;width:80px;border:2px solid yellow;">
                            <span style="color:#6a1a21;" class="total_coletivo">{{$totals[0]->total_coletivo}}</span>
                        </span>
                    </div>
                    <div style="display:flex;flex-direction:column;">
                        <span class="header_esquerda_title">Empresarial</span>
                        <span style="background-color:rgba(255, 255, 255, 0.8);padding:5px 15px;display:flex;justify-content:center;border-radius:10px;font-weight:bold;width:80px;border:2px solid yellow;">
                            <span style="color:#6a1a21;" class="total_empresarial">{{$totals[0]->total_empresarial}}</span>
                        </span>
                    </div>
                    <div style="display:flex;flex-direction:column;">
                        <span class="header_esquerda_title">Total</span>
                        <span style="background-color:rgba(255, 255, 255, 0.8);padding:5px 15px;display:flex;justify-content:center;border-radius:10px;font-weight:bold;width:80px;border:2px solid yellow;">
                            <span style="color:#6a1a21;" class="total_vidas">{{$total_vidas}}</span>
                        </span>
                    </div>
                    <div style="display:flex;flex-direction:column;">
                        <span class="header_esquerda_title">%</span>
                        <span style="background-color:rgba(255, 255, 255, 0.8);padding:5px 15px;display:flex;justify-content:center;border-radius:10px;font-weight:bold;width:80px;border:2px solid yellow;">
                            <span style="color:#6a1a21;" class="total_porcentagem">{{ number_format($porcentagem, 2) }}%</span>
                        </span>
                    </div>
                @endif
            </div>

            <div id="header_esquerda_concessionaria" class="ocultar" style="background-color:#2e4a7a; width:95%; border-radius:8px; margin:10px auto; padding:10px; display:flex; align-items:center; justify-content: space-between; height: 70px;">
                <div style="background-color:white;width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:3%;">
                    <img src="{{asset('foguete.png')}}" alt="Hapvida" style="width:80%;height:auto;">
                </div>
                <div class="container-meta">
                    <span class="header_esquerda_title_concessionaria">Meta</span>
                    <span class="container_concessionaria_header">
                        <span style="color:#6a1a21;" class="total_concessioanaria_meta">3050</span>
                    </span>
                </div>
                <div class="flex flex-column">
                    <span class="header_esquerda_title_concessionaria">Individual</span>
                    <span class="container_concessionaria_header">
                        <span style="color:#6a1a21;" class="total_individual_concessionaria">1850</span>
                    </span>
                </div>
                <div class="flex flex-column">
                    <span class="header_esquerda_title_concessionaria">Super Simples</span>
                    <span class="container_concessionaria_header">
                        <span style="color:#6a1a21;" class="total_super_simples_concessionaria">350</span>
                    </span>
                </div>
                <div class="flex flex-column">
                    <span class="header_esquerda_title_concessionaria">PME</span>
                    <span class="container_concessionaria_header">
                        <span style="color:#6a1a21;" class="total_pme_concessionaria">30</span>
                    </span>
                </div>
                <div class="flex flex-column">
                    <span class="header_esquerda_title_concessionaria">Adesão</span>
                    <span class="container_concessionaria_header">
                        <span style="color:#6a1a21;" class="total_adesao_concessionaria">820</span>
                    </span>
                </div>
                <div class="flex flex-column">
                    <span class="header_esquerda_title_concessionaria">Total</span>
                    <span class="container_concessionaria_header">
                        <span style="color:#6a1a21;" class="total_vidas_concessionaria">0</span>
                    </span>
                </div>
                <div class="flex flex-column">
                    <span class="header_esquerda_title_concessionaria">%</span>
                    <span class="container_concessionaria_header">
                        <span style="color:#6a1a21;" class="total_porcentagem_concessionaria">O%</span>
                    </span>
                </div>

            </div>

            <div id="header_esquerda_estrela" class="ocultar" style="background-color:#2e4a7a; width:95%; border-radius:8px; margin:10px auto; padding:10px; display:flex; align-items:center; justify-content: space-around; height: 70px;">
                <div style="background-color:white;width:50px;height:50px;border-radius:50%;display:flex;align-items:center;justify-content:center;margin-right:3%;">
                    <img src="{{asset('foguete.png')}}" alt="Hapvida" style="width:80%;height:auto;">
                </div>
                <div style="display:flex;flex-direction:column;">
                    <span style="color:#FFF;font-weight:bold;display:flex;justify-content:center;">3 Estrelas</span>
                    <div class="d-flex justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path fill="#FFD700" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path fill="#FFD700" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path fill="#FFD700" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                    </div>
                    <span style="color:#FFF;">150 a 190 vidas</span>
                </div>
                <div style="display:flex;flex-direction:column;">
                    <span style="color:#FFF;font-weight:bold;display:flex;justify-content:center;">4 Estrelas</span>
                    <div class="d-flex justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path fill="#FFD700" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path fill="#FFD700" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path fill="#FFD700" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path fill="#FFD700" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>

                    </div>
                    <span style="color:#FFF;">151 a 250 vidas</span>
                </div>
                <div style="display:flex;flex-direction:column;">
                    <span style="color:#FFF;font-weight:bold;display:flex;justify-content:center;">5 Estrelas</span>
                    <div class="d-flex justify-content-center">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path fill="#FFD700" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path fill="#FFD700" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path fill="#FFD700" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path fill="#FFD700" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path fill="#FFD700" d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/>
                        </svg>
                    </div>
                    <span style="color:#FFF;">Apartir de 251 vidas</span>
                </div>
            </div>
            <div class="stage">
            </div>
        </div>
        <div id="dados_direito" style="overflow-y:hidden;height:100%;width:49%;">

        </div>
        {{-- Fim Direita --}}
    </div>
</main>

<!-- Card do 1º lugar -->
<div id="popup-primeiro" class="ocultar" style="position:fixed;inset: 0;z-index: 50;background-color:black;opacity: 50;">
    <div style="width:400px;height:400px;border-radius:10px;color:white;display:flex;flex-direction:column;margin:5px auto;">

        <!-- Header: Compacto com menos altura -->
        <div class="header" style="flex: 0 0 10%; display: flex; align-items: center; justify-content: space-between; padding: 0 10px;background-color:red;">
            <img src="{{asset('medalha-primeiro.png')}}" style="width:100px;height:100px;" alt="">
            <p style="font-size:1.5em; display:flex; flex-direction:column; justify-content:center; color:#FFF; text-align:center; line-height: 1;">
                <span style="font-size:2em; font-weight: bold;" class="quantidade_vidas"></span>
                <span style="font-weight: bold;">Vidas</span>
            </p>
        </div>

        <!-- Corpo: Diminuir o tamanho da imagem para encaixar melhor -->
        <div class="corpo  w-full" style="flex: 1; display: flex; justify-content: center; align-items: flex-start;">
            <img src="" class="assumir_lider" style="width:550px;height:550px;border-radius:50%;box-sizing:border-box;" alt="" title="">
        </div>

        <!-- Footer: Mais compacto com menos altura -->
        <div class="footer w-full bg-gray-200" style="flex: 0 0 10%; display: flex; justify-content: center; align-items: center; font-size: 1.5em; color:#FFF; font-weight:bold;">
            1º Lugar no Ranking
        </div>

    </div>
</div>

<!-- Fundo preto para os fogos de artifício -->
<div id="fogos-bg" class="ocultar"  style="display:flex;background-color: black;z-index: 50;inset: 0;position: fixed;align-items: center;justify-content: center; --tw-bg-opacity: 0.8;">
    <div style="border-radius:10px;color:white;display:flex;flex-direction:column;margin-bottom: 20px;">

        <!-- Header: Compacto com menos altura -->
        <div class="header" style="flex: 0 0 10%; display: flex; align-items: center; justify-content: space-between; padding: 0 10px;width:100%;">
                <img src="{{asset('medalha-primeiro.png')}}" style="width:100px;height:100px;" alt="">
            <p style="font-size:1.5em; display:flex; flex-direction:column; justify-content:center; color:white; text-align:center; line-height: 1;">
                <span style="font-size:2em; font-weight: bold;" class="quantidade_vidas"></span>
                <span style="font-weight: bold;">Vidas</span>
            </p>
        </div>

        <!-- Corpo: Diminuir o tamanho da imagem para encaixar melhor -->
        <div class="corpo" style="display: flex; justify-content: center; align-items: flex-start;">
            <img src="" class="assumir_lider" style="width:550px;height:550px;border-radius:50%;box-sizing:border-box;">
        </div>

        <!-- Footer: Mais compacto com menos altura -->
        <div class="footer footer_ranking" style="flex: 0 0 10%;display:flex;justify-content: center;align-items:center;font-size:1.5em;color:#FFF;font-weight:bold;">
            1º Lugar no Ranking
        </div>

    </div>
</div>

<div id="fundo-preto-fogos" class="ocultar" style="display:flex;background-color: black;z-index: 50;inset: 0;position: fixed;align-items: center;justify-content: center; --tw-bg-opacity: 0.8;">
    <div class="text-center">
        <p id="quantidade-vidas-fogos" style="font-size: 2.5em; color: white; font-weight: bold;"></p>
        <img id="imagem-corretor-fogos" src="" alt="Corretor" style="width: 550px; height: 550px; border-radius: 50%; margin-top: 20px;">
    </div>
</div>

<div id="fundo-preto-fogos-sky" class="ocultar sky-venda" style="display:flex;background-color: black;z-index: 50;inset: 0;position: fixed;align-items: center;justify-content: center; --tw-bg-opacity: 0.8;">
    <div class="text-center">
        <p id="quantidade-vidas-fogos-sky" style="font-size: 2.5em; color: white; font-weight: bold;"></p>
        <img id="imagem-corretor-fogos-sky" src="" alt="Corretor" style="width: 550px; height: 550px; border-radius: 50%; margin-top: 20px;">
    </div>
</div>

<div id="fundo-preto" class="ocultar" style="display:flex;background-color: black;z-index: 50;inset: 0;position: fixed;align-items: center;justify-content: center; --tw-bg-opacity: 0.8;">
    <div class="text-center">
        <p id="quantidade-vidas" style="font-size: 2.5em; color: white; font-weight: bold;">10 Vidas</p>
        <img id="imagem-corretor" src="" alt="Corretor" style="width: 550px; height: 550px; border-radius: 50%; margin-top: 20px;">
    </div>
    <div id="confetti-container"></div>
</div>

<!-- Animação dos fogos (opcionalmente um canvas para os fogos) -->
<div id="fogos-container" class="hidden" style="position:fixed;z-index: 50;inset: 0;">
    <!-- Conteúdo dos fogos (Canvas, SVG, etc) -->
</div>

<!-- Fundo preto para a animação do carro -->
<div id="carro-bg" class="ocultar" style="background-color:black;inset:0;position:fixed;opacity:0.9;"></div>

<footer id="footer-aqui">
    <div class="footer-buttons d-flex justify-content-between">
        <button class="footer-btn active" data-corretora="concessi">Concessionarias</button>

        <button class="footer-btn" data-corretora="grupo">Grupo America</button>
        <button class="footer-btn" data-corretora="america">America</button>
        <button class="footer-btn" data-corretora="vivaz">Vivaz</button>

    </div>
    <div class="d-flex justify-content-center" style="background-color:#2e4a7a;">
        <img src="{{asset('hapvida-notreDame.png')}}" alt="Hapvida Logo" class="img-fluid my-auto" style="max-width: 200px;">
    </div>
</footer>

<div id="modalHistorico" style="display:none; position:fixed; top:50%; left:50%; transform:translate(-50%, -50%); background-color:white; border-radius:8px; box-shadow:0 4px 8px rgba(0,0,0,0.2); padding:20px; z-index:1000; width:60%;">
    <div class="flex justify-end">
        <button style="margin-top:20px; background-color:#dc3545; color:white; border:none; padding:10px 20px; border-radius:5px;" id="fecharModal">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    <h3 id="tituloData" class="text-2xl font-bold text-center py-2">Histórico de Vendas Diárias - <span id="dataAtual"></span></h3>
    <table id="tabelaHistorico" style="width:100%; border-collapse:collapse; margin-top:10px;">
        <thead>
        <tr>
            <th>Data</th>
            <th>Corretor</th>
            <th>Vidas Individual</th>
            <th>Vidas Coletivo</th>
            <th>Vidas Empresarial</th>
            <th>Ações</th>
        </tr>
        </thead>
        <tbody>
        <!-- Linhas preenchidas via AJAX -->
        </tbody>
        <tfoot class="bg-blue-400">
            <tr>
                <th colspan="2" style="padding: 10px;">Total</th>
                <th id="totalVidasIndividual" style="padding: 10px;">0</th>
                <th id="totalVidasColetivo" style="padding: 10px;">0</th>
                <th id="totalVidasEmpresarial" style="padding: 10px;">0</th>
                <th></th>
            </tr>
        </tfoot>
    </table>
    <div class="flex justify-between">
        <button id="btnBack" style="background-color:#6c757d; color:white; border:none; padding:10px 15px; border-radius:5px; margin-bottom:10px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path d="M9.195 18.44c1.25.714 2.805-.189 2.805-1.629v-2.34l6.945 3.968c1.25.715 2.805-.188 2.805-1.628V8.69c0-1.44-1.555-2.343-2.805-1.628L12 11.029v-2.34c0-1.44-1.555-2.343-2.805-1.628l-7.108 4.061c-1.26.72-1.26 2.536 0 3.256l7.108 4.061Z" />
            </svg>
        </button>
        <button id="btnNext" style="background-color:#6c757d; color:white; border:none; padding:10px 15px; border-radius:5px;">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-6">
                <path d="M5.055 7.06C3.805 6.347 2.25 7.25 2.25 8.69v8.122c0 1.44 1.555 2.343 2.805 1.628L12 14.471v2.34c0 1.44 1.555 2.343 2.805 1.628l7.108-4.061c1.26-.72 1.26-2.536 0-3.256l-7.108-4.061C13.555 6.346 12 7.249 12 8.689v2.34L5.055 7.061Z" />
            </svg>
        </button>
    </div>

</div>
<div id="overlayHistorico" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color:rgba(0,0,0,0.5); z-index:999;"></div>



<script src="{{asset('js/ranking.js')}}"></script>



</body>
</html>
