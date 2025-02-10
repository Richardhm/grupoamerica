<ul style="list-style:none;margin:0;padding:0;" class="w-100">
    <p style="color:white;border-bottom:1px solid white;text-align: center;margin:0;padding: 0;font-size:0.7em;">Corretores</p>
    @php $iix = 0;@endphp
    @foreach($users as $uu)
        @php $iix++;@endphp
        <li class="flex justify-between text-white w-100 py-1 {{ $iix % 2 == 0 ? 'user_destaque_impar' : '' }}">
            <span class="user_nome user_destaque" data-id="{{ $uu->user_id }}">
                @php echo Illuminate\Support\Str::limit($uu->user,20,""); @endphp
            </span>
            <span class="user_total total_pagamento_finalizado user_destaque" data-id="{{ $uu->user_id }}">{{ number_format($uu->total, 2, ",", ".") }}</span>
        </li>
    @endforeach
</ul>
