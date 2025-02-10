<ul style="list-style:none;margin:0;padding:0;" id="list_user_historico">
    @php $iix = 0;@endphp
    @foreach($users as $uu)
        @php $iix++;@endphp
        <li class="flex justify-between text-white w-100 py-1 {{ $iix % 2 == 0 ? 'user_destaque_impar' : '' }}" data-user="{{$uu->user_id}}">
            <span class="user_nome user_destaque_historico" data-id="{{ $uu->user_id }}">
                @php echo Illuminate\Support\Str::limit($uu->user,20,""); @endphp
            </span>
            <span class="user_total total_pagamento_finalizado user_destaque_historico" data-id="{{ $uu->user_id }}">{{ number_format($uu->total, 2, ",", ".") }}</span>
        </li>
    @endforeach
</ul>
