<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;

class RefreshTenantMigrations extends Command
{
    protected $signature = 'tenants:migrate-refresh
                            {--seed : Executar seeders após as migrations}';
    protected $description = 'Refaz todas as migrations para todos os tenants e o banco central';

    public function handle()
    {
        // 1. Resetar o banco central (grupoamerica)
        $this->info('Resetando migrations do banco central...');
        $this->call('migrate:refresh', [
            '--database' => 'mysql', // Nome da conexão central
            '--force' => true,
        ]);

        // 2. Resetar os bancos dos tenants
        Tenant::all()->each(function (Tenant $tenant) {
            $this->info("Resetando migrations para o tenant: {$tenant->id}");

            // Configurar a conexão do tenant dinamicamente
            Config::set('database.connections.tenant.database', $tenant->database);
            DB::purge('tenant'); // Limpa a conexão existente
            DB::reconnect('tenant'); // Reconecta com o novo banco

            // Executar migrate:fresh
            $this->call('migrate:fresh', [
                '--database' => 'tenant',
                '--path' => 'database/migrations/tenants', // Caminho das migrations dos tenants
                '--force' => true,
            ]);

            // Opcional: Rodar seeders
            if ($this->option('seed')) {
                $this->call('db:seed', [
                    '--database' => 'tenant',
                    '--class' => 'TenantDatabaseSeeder',
                ]);
            }

            // Voltar para a conexão padrão
            Config::set('database.default', 'mysql');
        });

        $this->info('Todas as migrations foram resetadas com sucesso!');
    }
}
