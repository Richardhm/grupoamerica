<?php

namespace App\Jobs;

use App\Models\ComissoesCorretoresLancadas;
use App\Models\ValoresCorretoresLancados;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class MudarStatusParaNaoPagoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data; // Dados recebidos da requisição
    }

    public function handle()
    {
        $ca = ComissoesCorretoresLancadas::where("id", $this->data['id'])->first();
        $ca->status_apto_pagar = 0;
        $ca->status_comissao = 0;
        $ca->finalizado = 0;
        $ca->data_baixa_finalizado = null;
        $ca->data_antecipacao = null;
        $ca->save();

        $va = ValoresCorretoresLancados::where("user_id", $this->data['user_id'])
            ->whereMonth("data", $this->data['mes'])
            ->first();

        if ($va) {
            $va->valor_total = str_replace([".", ","], ["", "."], $this->data['total']);
            $va->valor_desconto = str_replace([".", ","], ["", "."], $this->data['desconto']);
            $va->valor_premiacao = str_replace([".", ","], ["", "."], $this->data['premiacao']);
            $va->valor_comissao = str_replace([".", ","], ["", "."], $this->data['comissao']);
            $va->valor_salario = str_replace([".", ","], ["", "."], $this->data['salario']);
            $va->save();
        }

        // Caso o valor total seja 0
        if ($va->valor_total == 0) {
            $user_name = User::find($this->data['user_id'])->name;
            ValoresCorretoresLancados::where("user_id", $this->data['user_id'])
                ->whereMonth("data", $this->data['mes'])
                ->delete();
        }
    }
}
