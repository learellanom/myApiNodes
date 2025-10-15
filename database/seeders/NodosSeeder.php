<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use \Illuminate\Support\Facades\DB;
use \App\Models\Nodos;

class NodosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Nodos::create([
            'parent' => null,
            'title' => 'one',
        ]);

        Nodos::create([
            'parent' => null,
            'title' => 'two',
        ]);

        Nodos::create([
            'parent' => null,
            'title' => 'three',
        ]);

        Nodos::create([
            'parent' => null,
            'title' => 'four',
        ]);
    }
}
