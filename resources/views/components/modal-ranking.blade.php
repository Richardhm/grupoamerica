<div id="rankingModal" class="modals ocultar">
    <div class="modal-content-ranking">
        <!-- Botão para fechar a modal -->
        <span class="close-button" id="closeModalButtonRanking">&times;</span>

        <!-- Conteúdo da Modal -->
        <h2>Ranking Diário</h2>
        <form id="rankingForm" method="POST">

            <!-- Container para a lista de corretores -->
            <div class="corretores-list">
                <table class="table-sm border border-white">
                    <thead>
                    <tr>
                        <th>Corretor</th>
                        <th>Vidas Individual</th>
                        <th>Vidas Coletivo</th>
                        <th>Vidas Empresarial</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                                <td>
                                    <select class="text-black" name="user_id" id="user_id">
                                        <option value="">--Escolher Corretor--</option>
                                        @foreach($vendasDiarias as $venda)
                                            <option style="color:black;" value="{{$venda->user_id}}">{{$venda->nome}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input type="number" name="vidas_individual" value="" min="0"></td>
                                <td><input type="number" name="vidas_coletivo" value="" min="0"></td>
                                <td><input type="number" name="vidas_empresarial" value="" min="0"></td>
                        </tr>

                    </tbody>
                </table>
            </div>
            <button type="submit" style="background-color: #61DDBC;width:100%;color:#FFF;margin-top:10px;border: none;">Atualizar Ranking</button>
        </form>

    </div>
</div>
