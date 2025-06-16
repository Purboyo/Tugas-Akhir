<?php

namespace Database\Seeders;

use App\Models\Laboratory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LaboratorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Laboratory::create ([
            'id' => 1, 
            'lab_name' => 'Laboratorium Jaringan',
            'technician_id'=> 2,
        ]);
        Laboratory::create ([
            'id' => 2, 
            'lab_name' => 'Laboratorium Komputer',
            'technician_id'=> 2,
        ]);
        Laboratory::create ([
            'id' => 3, 
            'lab_name' => 'Laboratorium Pemrograman',
            'technician_id'=> 3,
        ]);
    }
}
