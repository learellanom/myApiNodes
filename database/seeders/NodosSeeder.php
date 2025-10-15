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
            'title' => 'uno',
        ]);

        Nodos::create([
            'parent' => null,
            'title' => 'dos',
        ]);

        Nodos::create([
            'parent' => null,
            'title' => 'tres',
        ]);

        Nodos::create([
            'parent' => null,
            'title' => 'cuatro',
        ]);

        Nodos::create([
            'parent' => null,
            'title' => 'cinco',
        ]);

        Nodos::create([
            'parent' => null,
            'title' => 'seis',
        ]);

        Nodos::create([
            'parent' => 1,
            'title' => 'siete',
        ]);

        Nodos::create([
            'parent' => 1,
            'title' => 'ocho',
        ]);

        Nodos::create([
            'parent' => 2,
            'title' => 'nueve',
        ]);

        Nodos::create([
            'parent' => 2,
            'title' => 'diez',
        ]);

    }
}
