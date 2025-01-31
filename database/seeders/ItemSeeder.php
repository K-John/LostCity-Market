<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        $filePath = storage_path('app/private/obj.pack');
        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        $items = array_map(function ($line) {
            [$id, $slug] = explode('=', $line);
            $name = ucwords(str_replace('_', ' ', trim($slug)));

            return [
                'game_id' => $id,
                'slug' => trim($slug),
                'name' => $name,
            ];
        }, $lines);

        // Find any items in $items array that have a matching id, slug, or name
        DB::table('items')->insert($items);
    }
}