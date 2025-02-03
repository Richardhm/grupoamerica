<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Obtém o subdomínio ou outro identificador do tenant
        $host = $request->getHost(); // Exemplo: tenant1.seusistema.com
        //dd($host);
        //$subdomain = explode('.', $host); // Captura o "tenant1" do domínio
        //dd($subdomain);
        // Busca o banco de dados do tenant no banco central
        $tenant = Tenant::where('domain', $host)->first();

        if (!$tenant) {
            abort(404, "Tenant não encontrado.");
        }

        // Configura dinamicamente a conexão do tenant
        Config::set('database.connections.tenant.database', $tenant->database);
        DB::purge('tenant'); // Limpa a conexão ativa para aplicar a mudança
        DB::reconnect('tenant'); // Reestabelece a conexão

        return $next($request);
    }
}
