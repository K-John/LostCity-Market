<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update invalid highwayman mask listings to the correct item
        DB::table('listings')
            ->whereIn('item_id', function ($query) {
                $query->select('id')
                    ->from('items')
                    ->where('game_id', 979);
            })
            ->update(['item_id' => DB::table('items')->where('game_id', 2631)->value('id')]);

        // Delete the old highwayman mask item
        DB::table('items')
            ->where('game_id', 979)
            ->delete();
    }
};
