<style>
    table {
        border-collapse: collapse;
        width:80%;
    }
    tbody td {
        border: 1px solid black;
        padding:5px;
    }
    thead th {
        border: 1px solid black;
        padding:5px;
    }
    .table-container {
        text-align: center;
        margin:0 auto;

    }
    .table-container table {
        text-align: center;
        margin:0 auto;
    }
</style>
@php
    $total_somado = 0;
@endphp


<div class="table-container">
    <h3>Resumo do Mês {{$mes}}</h3>
    <div class="d-flex justify-content-center">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Comissão</th>
                    <th>Salario</th>
                    <th>Premiação</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dados as $d)

                    @php
                        $total_somado += $d->total;
                    @endphp

                    <tr>
                        <td>{{$d->user}}</td>
                        <td>{{number_format($d->valor_comissao,2,",",".")}}</td>
                        <td>{{number_format($d->valor_salario,2,",",".")}}</td>
                        <td>{{number_format($d->valor_premiacao,2,",",".")}}</td>
                        <td>{{number_format($d->total,2,",",".")}}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;">Total:</td>
                    <td>{{number_format($total_somado,2,",",".")}}</td>
                </tr>
            </tfoot>



        </table>
    </div>





</div>


