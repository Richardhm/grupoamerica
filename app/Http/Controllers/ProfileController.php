<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function listar()
    {
        return view('profile.index');
    }

    public function alterarUser(Request $request)
    {
        $user_id = $request->id;
        $ativo = $request->ativo == 'true' ? 1 : 0;

        $user = User::find($user_id);
        $user->ativo = $ativo;
        $user->save();
    }

    public function alterarUserCLT(Request $request)
    {
        $id = $request->id;
        $user = User::find($id);

        if($user->corretora_id == 1 && $request->ativo == 1) {
            DB::table('comissoes_corretores_configuracoes')->where('user_id', $id)->delete();
            $user->clt = 1;
            $user->save();
        } elseif($user->corretora_id == 1 && $request->ativo == 0) {

            $configs = DB::table('comissoes_corretores_configuracoes')->where('user_id', 198)->get();
            foreach ($configs as $config) {
                DB::table('comissoes_corretores_configuracoes')->insert([
                    'plano_id' => $config->plano_id,
                    'user_id' => $id, // Atualiza para o novo user_id
                    'administradora_id' => $config->administradora_id,
                    'tabela_origens_id' => $config->tabela_origens_id,
                    'valor' => $config->valor,
                    'parcela' => $config->parcela,
                ]);
            }
            $user->clt = 0;
            $user->save();
        } elseif($user->corretora_id == 2 && $request->ativo == 1) {
            DB::table('comissoes_corretores_configuracoes')->where('user_id', $id)->delete();
            $user->clt = 1;
            $user->save();
        } elseif($user->corretora_id == 2 && $request->ativo == 0) {

            foreach ([1, 5] as $plano_id) {
                for ($parcela = 1; $parcela <= 6; $parcela++) {
                    DB::table('comissoes_corretores_configuracoes')->insert([
                        'plano_id' => $plano_id,
                        'user_id' => $id,
                        'administradora_id' => 4,
                        'tabela_origens_id' => 2,
                        'valor' => $parcela === 1 ? 100 : 0,
                        'parcela' => $parcela,
                    ]);
                }
            }

            // Inserções para planos coletivos
            foreach ([1, 2, 3] as $administradora_id) {
                for ($parcela = 1; $parcela <= 7; $parcela++) {
                    DB::table('comissoes_corretores_configuracoes')->insert([
                        'plano_id' => 3,
                        'user_id' => $id,
                        'administradora_id' => $administradora_id,
                        'tabela_origens_id' => 2,
                        'valor' => $parcela === 1 ? 100 : 0,
                        'parcela' => $parcela,
                    ]);
                }
            }

            $user->clt = 0;
            $user->save();
        }
    }



    public function listUser(Request $request)
    {
        //if($request->ajax()) {



        $users = User::select('name', 'id', 'image', 'email', 'celular', 'ativo', 'clt')

            ->whereNotNull('name')
            ->where('name', '!=', '')
            ->orderByDesc('ativo') // Ordena ativo=1 antes de ativo=0
            //->orderBy('name') // Ordenação secundária por nome
            ->get();

        return response()->json($users);
        //}

    }

    public function destroyUser(Request $request)
    {
        $request->validate([
            'id' => 'required|integer|exists:users,id'  // Substitua "users" pelo nome correto da tabela
        ]);
        $user = User::find($request->id); // Substitua "User" pelo modelo correto, se necessário
        if ($user) {
            if ($user->image) {
                $imagePath = public_path($user->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            $user->delete();
            return true;
        }
        return response()->json(['message' => 'Usuário não encontrado.'], 404);
    }




    public function storeUser(Request $request)
    {
        $logo = null;

        // Processa a imagem caso exista
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $location = 'users';
            $file->move($location, $filename);
            $logo = $location . '/' . $filename;
        }

        // Define os dados do corretor
        $data = [
            'name' => $request->nome,
            'email' => $request->email,
            'celular' => $request->celular,
            'codigo_vendedor' => $request->codigo_vendedor,
            'cargo_id' => 2, // Ajuste conforme necessário
            'password' => bcrypt('12345678'), // senha padrão, você pode alterar conforme necessário
        ];

        // Adiciona a imagem ao array de dados caso exista
        if ($logo) {
            $data['image'] = $logo;
        }

        // Usa updateOrCreate para criar ou atualizar o corretor com base no email
        $user = User::updateOrCreate(
            ['email' => $request->email], // Critério para identificar o usuário existente
            $data
        );

        return response()->json([
            'message' => $user->wasRecentlyCreated ? 'Usuário cadastrado com sucesso!' : 'Usuário atualizado com sucesso!',
            'user' => $user
        ]);

    }

    public function perfil(Request $request)
    {
        return view('profile.atualizar');
    }

    public function alterar(Request $request)
    {

        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'celular' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'password' => 'nullable|string|min:8|confirmed',
        ]);


        // Atualizar a imagem, se enviada
        if ($request->hasFile('image')) {
            // Remove a imagem antiga, se existir
            if ($user->image && file_exists(public_path($user->image))) {
                unlink(public_path($user->image));
            }

            // Salva a nova imagem e atualiza o caminho no banco de dados
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = 'users/' . $imageName;

            $image->move(public_path('users'), $imageName);

            $user->image = $imagePath;
        }

        // Atualizar os campos de texto
        $user->name = $request->input('name');
        $user->celular = $request->input('celular');

        // Atualizar a senha, se fornecida
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Perfil atualizado com sucesso!');
    }

}
