<x-app-layout>
    <div class="flex flex-col items-center justify-center mt-6">
        <!-- Card de edição -->
        <div class="p-6 rounded-lg shadow-md w-[50%] bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
            <h2 class="text-2xl font-bold mb-4 text-center text-white border-b-2">Editar Perfil</h2>

            @if (session('success'))
                <div class="bg-blue-400 text-center text-white p-4 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif


            @if ($errors->any())
                <div class="bg-red-500 text-white p-4 rounded-lg mb-4">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Formulário de edição -->
            <form action="{{route('profile.alterar')}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="flex items-center mb-4">
                    <!-- Imagem atual -->
                    <div class="w-[30%] h-[30%] rounded-full overflow-hidden mr-4">
                        <img id="profileImage"
                             src="{{ auth()->user()->image ? asset(auth()->user()->image) : 'https://via.placeholder.com/150' }}"
                             alt="Foto do perfil"
                             class="w-full h-full object-cover">
                    </div>
                    <!-- Input de upload -->
                    <div class="w-full ml-2 mr-2">
                        <label for="file_input" class="block font-medium text-white">Foto</label>
                        <input name="image" class="block w-full text-lg text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] focus:outline-none"
                               id="file_input"
                               type="file"
                               accept="image/*">
                    </div>
                </div>

                <!-- Nome -->
                <div class="mb-4">
                    <label for="name" class="block font-medium text-white">Nome</label>
                    <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                </div>

                <!-- E-mail (somente leitura) -->
                <div class="mb-4">
                    <label for="email" class="block font-medium text-white">E-mail</label>
                    <input type="email" id="email" name="email" value="{{ auth()->user()->email }}" class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 focus:outline-none cursor-not-allowed" readonly>
                </div>

                <!-- Celular -->
                <div class="mb-4">
                    <label for="celular" class="block font-medium text-white">Celular</label>
                    <input type="text" id="celular" name="celular" value="{{ auth()->user()->celular }}" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                </div>

                <!-- Nova senha -->
                <div class="mb-4">
                    <label for="password" class="block font-medium text-white">Nova Senha</label>
                    <input type="password" id="password" name="password" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                </div>

                <!-- Confirmar senha -->
                <div class="mb-4">
                    <label for="password_confirmation" class="block font-medium text-white">Confirmar Nova Senha</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px]">
                </div>

                <!-- Botão de editar -->
                <div class="text-center">
                    <button type="submit" class="bg-[rgba(254,254,254,0.18)] backdrop-blur-[15px] text-white py-2 w-full px-4 rounded-lg hover:bg-blue-600 transition">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('file_input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImage').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });



    </script>

</x-app-layout>
