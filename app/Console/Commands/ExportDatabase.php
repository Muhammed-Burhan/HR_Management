<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ExportDatabase extends Command
{
   
    protected $signature = 'db:backup';
    protected $description = 'Export the database and back it up';
    

    /**
     * Execute the console command.
     */
    public function handle()
    {    //you can checkout config/database this will return mysql
         $connection = config('database.default');

        // Get the database configuration (mysql array will be returned)
        $config = config("database.connections.$connection");

        // Create a filename for the exporting the database backup
        $filename = 'database_' . date('Y_m_d_His') . '.sql';

        // Generate the mySqlDump command
        $command = sprintf(
            'mysqldump --user=%s --password=%s --host=%s --port=%s %s > %s',
            $config['username'],
            $config['password'],
            $config['host'],
            $config['port'],
            $config['database'],
            storage_path('app/' . $filename)
        );
        

        // Execute the mySqlDump command
        exec($command);

        //the message after running the command
        $this->info("Database exported to $filename");
    }
}
