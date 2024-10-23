<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Folder;
use Illuminate\Support\Facades\DB;

class FolderSeeder extends Seeder
{
    public function run()
    {
        DB::table('folders')->insert([
            ['name' => 'Root Folder', 'parent_id' => null],
            ['name' => 'Subfolder 1', 'parent_id' => 1],
            ['name' => 'Subfolder 2', 'parent_id' => 1],
            ['name' => 'Subfolder 1.1', 'parent_id' => 2],
            ['name' => 'Subfolder 1.2', 'parent_id' => 2],
            ['name' => 'Subfolder 2.1', 'parent_id' => 3],
        ]);
    }
}
