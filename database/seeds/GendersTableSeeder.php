<?php

use Faker\Factory;
use Illuminate\Database\Seeder;
use App\Models\Gender;
use App\Models\Customer;
use App\Models\User;

class GendersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Gender::class, 11)->create();
    }    
}
