<?php

namespace Database\Seeders;

use App\Models\PC;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PCSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PC::create([
            "id" => 1,
            'pc_name' => 'PC-01',
            'lab_id' => 1,
        ]);
        PC::create([
            "id" => 2,
            'pc_name' => 'PC-02',
            'lab_id' => 1,
        ]);
        PC::create([
            "id" => 3,
            'pc_name' => 'PC-03',
            'lab_id' => 1,
        ]);
        PC::create([
            "id" => 4,
            'pc_name' => 'PC-04',
            'lab_id' => 1,
        ]);
        PC::create([
            "id" => 5,
            'pc_name' => 'PC-01',
            'lab_id' => 2,
        ]);
        PC::create([
            "id" => 6,
            'pc_name' => 'PC-02',
            'lab_id' => 2,
        ]);
        PC::create([
            "id" => 7,
            'pc_name' => 'PC-03',
            'lab_id' => 2,
        ]);
        PC::create([
            "id" => 8,
            'pc_name' => 'PC-01',
            'lab_id' => 3,
        ]);
        PC::create([
            "id" => 9,
            'pc_name' => 'PC-02',
            'lab_id' => 3,
        ]);
        PC::create([
            "id" => 10,
            'pc_name' => 'PC-03',
            'lab_id' => 3,
        ]);
    }
}
