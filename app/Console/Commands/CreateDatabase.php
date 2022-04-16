<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:database {name} {--connection=} {--charset=} {--collation=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{

            $driver = DB::connection()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME);
            $name = $this->argument('name');
            $connection = $this->option('connection');
            $statement = $this->getStatement($name);

            if ($connection) {
                $driver = $connection;
            }

            $isDbExist = DB::connection($driver)->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = "."'".$name."'");
   
            if(empty($isDbExist)) {

                DB::connection($driver)->statement($statement);

                $this->info("Database '$name' created with '$driver' connection");

            } else {

                $this->info("Database '$name' already exists with '$driver' connection");

            }

        } catch (\Exception $e){

            $this->error($e->getMessage());
            
        }
    }

    private function getStatement( $name )
    {
        $statement = 'CREATE DATABASE';
        $charset = $this->option('charset');
        $collation = $this->option('collation');
        
        $statement .= ' '. $name;

        if ($charset) {
            $statement .= " CHARACTER SET $charset";
        }

        if ($collation) {
            $statement .= " COLLATE $collation";
        }

        return $statement;
    }
}
