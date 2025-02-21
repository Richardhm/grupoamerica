<table class="table table-sm">
    <thead>
    <tr>
        <th>Usuario</th>
        <th class="text-center">Individual</th>
        <th class="text-center">Coletivo</th>
        <th class="text-center">Super Simples</th>
        <th class="text-center">PME</th>
        <th class="text-center">Sindipão</th>
        <th class="text-center">Sindimaco</th>
        <th class="text-center">Sincofarma</th>
        <th class="text-center">Total</th>
    </tr>
    </thead>
    <tbody>
    @php
        $total_table_individual = 0;
        $total_table_coletivo = 0;
        $total_table_super_simples = 0;
        $total_table_pme = 0;
        $total_table_sindipao = 0;
        $total_table_sindimaco = 0;
        $total_table_sincofarma = 0;
        $total_table = 0;
    @endphp
    @foreach($dados as $dt)
        <tr>
            <td>{{$dt->user_name}}</td>
            <td class="text-center">
                {{$dt->individual}}
                @php
                    $total_table_individual += $dt->individual;
                @endphp
            </td>
            <td class="text-center">
                {{$dt->coletivo}}
                @php
                    $total_table_coletivo += $dt->coletivo;
                @endphp
            </td>
            <td class="text-center">
                {{$dt->super_simples}}
                @php
                    $total_table_super_simples += $dt->super_simples;
                @endphp
            </td>
            <td class="text-center">
                {{$dt->pme}}
                @php
                    $total_table_pme += $dt->pme;
                @endphp
            </td>
            <td class="text-center">
                {{$dt->sindipao}}
                @php
                    $total_table_sindipao += $dt->sindipao;
                @endphp
            </td>
            <td class="text-center">
                {{$dt->sindimaco}}
                @php
                    $total_table_sindimaco += $dt->sindimaco;
                @endphp
            </td>
            <td class="text-center">
                {{$dt->sincofarma}}
                @php
                    $total_table_sincofarma += $dt->sincofarma;
                @endphp
            </td>
            <th class="text-center">
                {{$dt->total}}
                @php
                    $total_table += $dt->total;
                @endphp
            </th>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <th>Total</th>
        <th class="text-center">{{$total_table_individual}}</th>
        <th class="text-center">{{$total_table_coletivo}}</th>
        <th class="text-center">{{$total_table_super_simples}}</th>
        <th class="text-center">{{$total_table_pme}}</th>
        <th class="text-center">{{$total_table_sindipao}}</th>
        <th class="text-center">{{$total_table_sindimaco}}</th>
        <th class="text-center">{{$total_table_sincofarma}}</th>
        <th class="text-center">{{$total_table}}</th>
    </tr>
    </tfoot>
</table>
