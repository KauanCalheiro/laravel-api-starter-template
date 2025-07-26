<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class DropAllDatabaseObjects extends Command
{
    protected $signature   = 'db:clear';
    protected $description = 'Drop all tables, views, materialized views, and foreign tables from the database (only in local env)';

    public function handle(): void
    {
        if (!App::isLocal()) {
            $this->error('❌ Operation not allowed. This command can only be run in the local environment.');
            return;
        }

        $this->warn('⚠ Dropping all objects in the database (tables, views, materialized views, foreign tables)...');

        $this->dropAll('materialized view');
        $this->dropAll('view');
        $this->dropAll('foreign table');
        $this->dropAll('table');

        $this->info('✅ All database objects dropped.');
    }

    protected function dropAll(string $type): void
    {
        $query = match ($type) {
            'table'             => "SELECT tablename AS name FROM pg_tables WHERE schemaname = 'public';",
            'view'              => "SELECT table_name AS name FROM information_schema.views WHERE table_schema = 'public';",
            'materialized view' => "SELECT matviewname AS name FROM pg_matviews WHERE schemaname = 'public';",
            'foreign table'     => "SELECT foreign_table_name AS name FROM information_schema.foreign_tables WHERE foreign_table_schema = 'public';",
            default             => throw new \InvalidArgumentException("Invalid type: {$type}"),
        };

        $objects = DB::select($query);

        foreach ($objects as $object) {
            $name = $object->name;
            try {
                DB::statement("DROP {$type} IF EXISTS \"{$name}\" CASCADE;");
                $this->line("✔ Dropped {$type}: {$name}");
            } catch (\Throwable $e) {
                $this->error("⚠ Failed to drop {$type} {$name}: " . $e->getMessage());
            }
        }
    }
}
