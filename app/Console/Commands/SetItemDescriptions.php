<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Class SetItemDescriptions
 *
 * This command is a one-time use utility designed to set the descriptions
 * for existing items in the database.
 *
 * This command is primarily intended for use on the live server where items
 * were created before their descriptions were available.
 */
class SetItemDescriptions extends Command
{
    protected $signature = 'descriptions:set';

    protected $description = 'Sets the description for all items in the database.';

    public function handle(): void
    {
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
            DB::table('items')->upsert($chunk, ['game_id'], ['description']);
        }

        $this->info('All item descriptions have been successfully set!');
    }
}
