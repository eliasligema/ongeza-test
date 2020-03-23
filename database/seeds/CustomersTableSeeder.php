<?php

use Faker\Factory;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Gender;
use App\Models\User;

class CustomersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Customer::class, 11)->create();
    }    
}
