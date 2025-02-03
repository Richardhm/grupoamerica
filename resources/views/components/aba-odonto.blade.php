<main id="aba_odonto" class="hidden">

    <section class="flex justify-between flex-wrap content-start">
        <!--COLUNA DA ESQUERDA-->
        <div class="flex flex-col text-white basis-[16%] rounded-lg">
            <div class="flex mb-1 justify-between">
                <span class="bg-[rgba(254,254,254,0.18)] create_odonto hover:cursor-pointer backdrop-blur-[15px] text-white text-sm py-2 text-center rounded w-full">
                    <a class="text-center text-white">Cadastrar</a>
                </span>
            </div>







            <select
                class="
                       w-full mt-1 rounded-lg mb-1 text-center text-sm bg-[rgba(254,254,254,0.18)]
                       active:bg-[rgba(254,254,254,0.18)] hover:bg-gray-800 py-2 mr-1 focus:bg-gray-800 w-full text-xs
                       px-1 mb-2 text-sm font-medium rounded-lg hover:border-transparent focus:border-transparent border-transparent
                       "
                style="background-color: rgba(253, 216, 53, 0.7); backdrop-filter: blur(10px);"
                id="select_usuario_odonto"
            >

            </select>
        </div>
        <!--FIM COLUNA DA ESQUERDA-->

        <!--COLUNA DA CENTRAL-->
        <div class="basis-[83%]">
            <div class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] text-white rounded-lg p-1">
                <table id="tabela_odonto" class="table-auto table-sm listardadosodonto w-full">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th>Cliente</th>
                        <th>Corretor</th>

                        <th>Valor</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!--FIM COLUNA CENTRAL-->
    </section>

</main>

