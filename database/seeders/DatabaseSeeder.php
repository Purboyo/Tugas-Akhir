<?php

namespace Database\Seeders;

use App\Models\Form_question;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            LaboratorySeeder::class,
            PCSeeder::class,
            FormSeeder::class,
            FormQuestionSeeder::class,

        ]);
    }
}
