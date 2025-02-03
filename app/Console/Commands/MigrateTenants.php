<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;



class MigrateTenants extends Command
{
    protected $signature = 'tenants:migrate'; // Comando Artisan
    protected $description = 'Roda as migrations para todos os tenants';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tenants = Tenant::all(); // Busca todos os tenants
        if ($tenants->isEmpty()) {
            $this->warn("Nenhum tenant encontrado.");
            return;
        }
        foreach ($tenants as $tenant) {
            $this->info("Rodando migrations para o tenant: {$tenant->subdominio}");

            // Configura a conexão para o banco do tenant
            Config::set('database.connections.tenant.database', $tenant->database);
            DB::purge('tenant');
            DB::reconnect('tenant');

            // Executa as migrations na conexão "tenant"
            Artisan::call('migrate', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenants', // Use se tiver migrations separadas
                '--force' => true, // Força a execução sem confirmação
            ]);

            $this->info("Migrations aplicadas para o tenant: {$tenant->subdominio}");
        }
        $this->info("✅ Migrations aplicadas para todos os tenants!");
    }
}
