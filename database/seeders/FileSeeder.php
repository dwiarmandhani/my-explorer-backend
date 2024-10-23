<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\File;
use Illuminate\Support\Facades\DB;

class FileSeeder extends Seeder
{
    public function run()
    {
        DB::table('files')->insert([
            ['name' => 'File 1.txt', 'folder_id' => 1],
            ['name' => 'File 2.txt', 'folder_id' => 2],
            ['name' => 'File 3.txt', 'folder_id' => 2],
            ['name' => 'File 4.txt', 'folder_id' => 3],
            ['name' => 'File 5.txt', 'folder_id' => 4],
        ]);
    }
}

