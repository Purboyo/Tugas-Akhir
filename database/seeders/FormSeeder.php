<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Form;

class FormSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Form::create([
            'id' => 1,
            'title' => 'Form Laboratorium Jaringan',
            'lab_id' => 1,
            'is_default' => 1,
        ]);
    }
}
