<?php

namespace App\Console\Commands;

use App\Models\Item;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver as ImagickDriver;

class GenerateItemSprites extends Command
{
    protected $signature = 'sprites:generate';

    protected $description = 'Generate individual item sprites from the sprite sheet';

    protected int $spriteSize = 32;
    protected int $itemsPerRow = 64;

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $spriteSheetPath = storage_path("app/private/items.png");

        if (!file_exists($spriteSheetPath)) {
            $this->error("Sprite sheet not found: {$spriteSheetPath}");
            return;
        }

        $imageManager = new ImageManager(new ImagickDriver());

        $items = Item::all();

        foreach ($items as $item) {
            $index = $item->game_id;
            $x = ($index % $this->itemsPerRow) * $this->spriteSize;
            $y = floor($index / $this->itemsPerRow) * $this->spriteSize;

            // Extract and save the sprite
            $sprite = $imageManager->read($spriteSheetPath)
                ->crop($this->spriteSize, $this->spriteSize, $x, $y)
                ->toWebp(80);

                $savePath = public_path("img/items/{$item->slug}.webp");

                if (!file_exists(dirname($savePath))) {
                    mkdir(dirname($savePath), 0755, true);
                }
    
                file_put_contents($savePath, $sprite->toString());

            $this->info("Generated sprite for {$item->slug}: {$savePath}");
        }

        $this->info('All sprites have been successfully generated!');
    }
}
