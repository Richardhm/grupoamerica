<div id="atualizarModal" class="fixed inset-0 flex items-center justify-center z-50 hidden bg-black bg-opacity-50">
    <div class="bg-white rounded-lg overflow-hidden w-full max-w-lg mx-auto">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center">
                <h5 class="text-xl font-medium text-gray-900">Upload</h5>
                <button type="button" class="text-gray-900 close-modal">&times;</button>
            </div>
        </div>
        <div class="px-6 py-4">
            <form action="" method="POST" name="formulario_atualizar" id="formulario_atualizar" enctype="multipart/form-data">
                @csrf
                <div class="flex items-center mb-4">
                    <div class="w-full mr-2">
                        <label for="arquivo" class="block text-sm font-medium text-gray-700">Arquivo:</label>
                        <input type="file" name="arquivo_atualizar" id="arquivo_atualizar" class="form-input mt-1 block w-full">
                    </div>
                    <button type="button" class="text-red-600 close-modal ml-2">
                        <i class="fas fa-window-close fa-lg"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
