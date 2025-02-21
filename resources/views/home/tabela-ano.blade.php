<thead>

<tr>

    <th colspan="4" class="bg-warning">
        <select name="ranking_ano" id="ranking_ano" class="ranking_ano text-center rounded" style="border:none;background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);padding:0;width:90%;">
            <option value="">Ano</option>
            <option value="2024" {{$ano == 2024 ? 'selected' : ''}}>2024</option>
            <option value="2025" {{$ano == 2025 ? 'selected' : ''}}>2025</option>
        </select>
    </th>
</tr>

</thead>
<tbody>
@php
    $i=0;
@endphp
@foreach($ranking as $r)
    @php
        $parts = explode(' ', $r->usuario);
        $nome_abreviado = $parts[0] . ' ' . ($parts[1] ?? '');
    @endphp
    <tr>
        <td>{{$loop->iteration}}</td>
        <td>{{$nome_abreviado}}</td>
        <td>{{$r->quantidade}}</td>
        <td>{{number_format($r->valor,2,",",".")}}</td>
    </tr>
@endforeach
</tbody>
