<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateItems extends Command
{
    protected $signature = 'items:update';

    protected $description = 'Update the items table by comparing two obj.json files';

    public function handle(): void
    {
        $json = file_get_contents(storage_path('app/private/obj-old.json'));
        $oldItems = $this->mapToItemSchema(json_decode($json, true));

        $json = file_get_contents(storage_path('app/private/obj.json'));
        $newItems = $this->mapToItemSchema(json_decode($json, true));

        foreach ($newItems as $newItem) {
            $oldItem = null;

            foreach ($oldItems as $item) {
                if ($item['game_id'] === $newItem['game_id']) {
                    $oldItem = $item;
                    continue;
                }
            }

            if ($oldItem === null) {
                if (!str_contains($newItem['slug'], 'unidentified')) {

                    $this->info("Adding new item: {$newItem['slug']}");
                    DB::table('items')->upsert($newItem, ['game_id']);
                }
                continue;
            }

            if ($newItem['cost'] <> $oldItem['cost']) {

                $this->info("Updating item: {$newItem['slug']}");
                DB::table('items')->upsert($newItem, ['game_id']);
            }

            if ($newItem['description'] !== $oldItem['description']) {

                $this->info("Updating item description: {$newItem['slug']}");
                DB::table('items')->upsert($newItem, ['game_id']);
            }

            if ($newItem['name'] !== $oldItem['name']) {

                $this->info("Updating item name: {$newItem['slug']}");
                DB::table('items')->upsert($newItem, ['game_id']);
            }
        }
    }

    private function mapToItemSchema($items)
    {
        return array_map(function ($item) {
            return [
                'game_id' => $item['id'],
                'name' => $item['name'],
                'slug' => $item['debugname'],
                'cost' => $item['cost'],
                'description' => $item['desc'],
            ];
        }, $items);
    }
}
