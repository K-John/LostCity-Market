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

        // Filter out items where slug contains "cert"
        $items = array_filter($items, function ($item) {
            return strpos($item['slug'], 'cert') === false;
        });

        // Re-index the array to prevent gaps
        $items = array_values($items);

        // Find any items in $items array that have a matching id, slug, or name
        DB::table('items')->insert($items);
    }
}
