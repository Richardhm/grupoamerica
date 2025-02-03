<div class="p-1 rounded mt-2 bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] border hidden w-full lg:w-[22%]" id="planos">
    <button id="back-button" class="py-1.5 w-full px-1 me-2 mb-2 text-sm font-medium text-white bg-white rounded-lg border border-gray-200 bg-gray-500 bg-opacity-10 hidden">
        Voltar
    </button>
    <button id="show-planos-button" class="py-1.5 w-full px-1 me-2 mb-2 text-sm font-medium text-white bg-white rounded-lg border border-gray-200 bg-gray-500 bg-opacity-10">
        Planos
    </button>
    @foreach($planos as $p)
        <div data-plano="{{$p->id}}" class="py-1 w-full px-1 me-2 mb-2 text-sm font-medium text-white focus:outline-none bg-white rounded-lg border border-gray-200 focus:z-10 bg-gray-500 bg-opacity-10 dark:hover:text-gray-900">
            <label class="flex justify-between items-center">
                <div class="flex w-[100%] p-3">
                    <input id="radio_planos_{{$p->id}}" type="radio" value="{{$p->id}}" name="planos-radio" class="w-4 h-4 text-purple-600 bg-gray-100 border-gray-300 focus:ring-purple-500 dark:focus:ring-purple-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                    <span for="radio_planos_{{$p->id}}" class="ms-2 text-white flex justify-between text-sm font-medium text-gray-900 dark:text-gray-300">{{$p->nome}}</span>
                </div>
            </label>
        </div>
    @endforeach
</div>
