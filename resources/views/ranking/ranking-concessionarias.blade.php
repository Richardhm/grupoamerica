@foreach($ranking as $i => $r)
        @if ($i % 6 === 0)
            <div class="slide-corretora" style="height:80%;display: {{ $i === 0 ? 'block' : 'none' }}">
        @endif
            <section style="display:flex;width:99%;justify-content:center;margin:0 0 5px 0;background-color:#2e4a7a;border-radius:10px;padding:3px 0;align-items: center;">
                <!-- 1º Div: Posição ocupa toda a altura da section -->
                <div class="text-center text-white rounded me-2" style="margin-left:5px;width:40px;height:100%;display:flex;align-items:center;justify-content:center;padding:5px 10px;font-size:1em;font-weight:bold;background:rgba(254, 254, 254, 0.18);backdrop-filter:blur(15px);">
                    {{$loop->iteration}}°
                </div>

                <!-- 2º Div: Imagem centralizada no eixo Y com border-radius, próxima da 1ª div -->
                <div style="flex:0 1 auto;display:flex;align-items:center;margin-left:8px;background-color: #FFF;justify-content: center;justify-items: center;height:60px;width:60px;">
                    <img src="{{ asset($r->imagem) }}" class="rounded" style="border-radius:50%;width:100%;" />
                </div>

                <!-- 3º Div: Próxima da 2ª div -->
                <div style="display:flex;flex-direction: column;margin-left: 8px;color:#FFF;font-size:0.875em;min-width:50%">
                    <p style="margin:0;padding:0;">{{$r->nome}}</p>
                    <div style="display:flex;margin:0;padding:0;justify-content:space-between;">
                        <p style="margin:0;padding:0;">Meta: {{$r->meta_total}} Vidas</p>
                        <p style="margin:0;padding:0;">Total: {{$r->total_vidas}} Vidas</p>
                    </div>
                    <div style="display:flex;align-items:center;">
                        <div class="progress" style="flex-grow: 1; margin-right: 10px; position: relative; background-color: #e0e0e0; height: 20px; border-radius: 10px;">
                            @php
                                if($r->meta_total >= 1 && $r->total_vidas >= 1) {
                                    $porcentagem = ($r->total_vidas / $r->meta_total) * 100;
                                } else {
                                    $porcentagem = 0;
                                }
                            @endphp
                            <div class="progress-bar bg-orange progress-bar-striped" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: {{$porcentagem}}%; background-color: #FFA500; height: 100%; border-radius: 10px;">
                            </div>
                        </div>
                        <!-- Percentual ao lado direito -->
                        <span style="color: #FFF; font-weight: bold; margin-left: 10px;">{{number_format($porcentagem,2)}}%</span>
                    </div>
                    <div style="margin:0;padding:0;">
                        <p style="margin:0;padding:0;">Faltam: {{$r->meta_total - $r->total_vidas}} Vidas</p>
                    </div>
                </div>

                <!-- 4º Div: Encostado do lado direito -->
                <div style="flex: 3;text-align: right;padding:2px;color:#FFF;font-size:0.875em;">
                    <p style="margin:0;padding:0;">Individual:  <span class="individual_concessionaria_ranking"> {{$r->individual}}</span> vidas</p>
                    <p style="margin:0;padding:0;">Super Simples: <span class="supersimples_concessionaria_ranking"> {{$r->super_simples}}</span> vidas</p>
                    <p style="margin:0;padding:0;">PME: <span class="pme_concessionaria_ranking">{{$r->pme}}</span> vidas</p>
                    <p style="margin:0;padding:0;">Adesao: <span class="adesao_concessionaria_ranking">{{$r->adesao}}</span> vidas</p>
                    <p style="margin:0;padding:0;">Total: <span class="total_concessionaria_ranking">{{$r->total_vidas}}</span> vidas</p>
                </div>
            </section>
                @if (($i + 1) % 6 === 0)
            </div>
        @endif
@endforeach
