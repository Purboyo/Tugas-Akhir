<?php

namespace Database\Seeders;

use App\Models\Form_question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function PHPSTORM_META\type;

class FormQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Form_question::create([
            'id' => 1,
            'form_id'=> 1,
            'question_text' => 'Apakah kondisi perangkat untuk keperluan praktikum (RJ 45, Kabel Lan, Cable Tester, dan Crimping Tools) berjalan dengan lancar ?',
            'type'=> 'radio',
            'is_required' => 1,
            'options' => "[\"Baik\",\"Buruk\"]",
            'is_editable' => 1,
        ]);
    }
}
