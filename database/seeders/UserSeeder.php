<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $count = User::all()->count();

        if ($count == 0) {
            User::factory()->create([
                'name' => 'Renato Lucena',
                'email' => 'cpdrenato@gmail.com',
                'username' => 'cpdrenato'
            ]);
        }
    }
}
