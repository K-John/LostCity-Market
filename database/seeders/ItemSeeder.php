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
        $items = json_decode(file_get_contents(storage_path('app/private/obj.json')), true);
        $itemIds = array_column($items, 'id');

        DB::table('items')->whereNotIn('game_id', $itemIds)->delete();
        foreach ($items as $item) {
            DB::table('items')->updateOrInsert(
                ['game_id' => $item['id']],
                ['slug' => $item['debugname'], 'name' => $item['name']]
            );
        }
    }
}
