<?php

namespace Database\Seeders;

use App\Models\LoanTerm;
use Illuminate\Database\Seeder;

class LoanTermSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        LoanTerm::factory()->count(10)->create();
    }
}
