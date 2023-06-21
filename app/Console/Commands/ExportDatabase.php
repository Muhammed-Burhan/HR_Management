<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportDatabase extends Command
{
   
    protected $signature = 'database:export';
    protected $description = 'Export the database';
    

    /**
     * Execute the console command.
     */
    public function handle()
    {
         $connection = config('database.default');

        // Get the database configuration
        $config = config("database.connections.$connection");

        // Create a filename for the export
        $filename = 'database_' . date('Y_m_d_His') . '.sql';

        // Generate the mysqldump command
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
            $config['username'],
            $config['password'],
            $config['host'],
            $config['port'],
            $config['database'],
            storage_path('app/' . $filename)
        );

        // Execute the mysqldump command
        exec($command);

        $this->info("Database exported to $filename");
    }
}
