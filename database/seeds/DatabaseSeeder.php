<?php
        
        
require_once __DIR__.'/CustomersTableSeeder.php';
require_once __DIR__.'/GendersTableSeeder.php';
require_once __DIR__.'/UsersTableSeeder.php';

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CustomersTableSeeder::class);
        $this->call(GendersTableSeeder::class);
        $this->call(UsersTableSeeder::class);
    }
}
