<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    // Import items from JSON file to items table
    public function up(): void
    {
        // Check if items table has items in it. If it doesn't don't import
        if (DB::table('items')->count() > 0) {
            return;
        }

        $json = file_get_contents(storage_path('app/private/obj.json'));

        $items = json_decode($json, true);
        $items = array_map(function ($item) {
            return [
                'game_id' => $item['id'],
                'name' => $item['name'],
                'slug' => $item['debugname'],
                'cost' => $item['cost'],
                'description' => $item['desc'],
            ];
        }, $items);

        $items = array_chunk($items, 1000);

        foreach ($items as $chunk) {
            DB::table('items')->insert($chunk);
        }
    }
};
