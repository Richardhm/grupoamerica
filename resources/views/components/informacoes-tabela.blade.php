<div class="mt-2 rounded p-1 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] border w-full lg:w-[22%]" id="container_informacoes">
    <button class="py-1.5 w-full px-1 me-2 mb-2 text-sm font-medium text-white bg-white rounded-lg border border-gray-200 bg-gray-500 bg-opacity-10">
        Tabela de Origem
    </button>

    <form>
        <div class="w-full flex">
            <div class="ml-1 w-full">
                <select id="cidade" class="py-2 text-lg bg-[rgba(254,254,254,0.18)] focus:outline-none active:outline-none hover:bg-transparent py-2 text-white focus:bg-transparent w-full text-xs px-1 me-2 mb-2 text-sm font-medium text-black rounded-lg hover:border-transparent focus:border-transparent border-transparent">
                    <option value="" class="text-center text-lg text-black">Escolher Cidade</option>
                    @foreach($cidades as $cc)
                        <option value="{{$cc->id}}" class="text-black hover:bg-transparent focus:bg-transparent focus:text-white" style="background-color:#5c636a !important;opacity:0.2;color:white;">{{$cc->nome}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </form>



</div>
