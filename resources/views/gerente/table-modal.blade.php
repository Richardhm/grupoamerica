<table class="min-w-full bg-white border border-gray-200">
    <thead>
    <tr class="bg-gray-100 border-b border-gray-200">
        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Nome</th>
        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Comissão</th>
        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Premiação</th>
        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Salário</th>
        <th class="px-4 py-2 text-left text-sm font-medium text-gray-600">Total</th>
    </tr>
    </thead>
    <tbody>
    @php
        $total_valor = 0;
    @endphp
    @foreach($dados as $d)
        @php
            $total_valor += $d->total;
        @endphp
        <tr class="border-b border-gray-200">
            <td class="px-4 py-2 text-sm text-gray-700">{{ $d->user }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($d->valor_comissao,2,",",".") }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($d->valor_salario,2,",",".") }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($d->valor_premiacao,2,",",".") }}</td>
            <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($d->total,2,",",".") }}</td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr class="bg-gray-100 border-t border-gray-200">
        <th colspan="4" class="px-4 py-2 text-right text-sm font-medium text-gray-600">Total:</th>
        <th class="px-4 py-2 text-sm text-gray-700">{{ number_format($total_valor,2,",",".") }}</th>
    </tr>
    </tfoot>
</table>
