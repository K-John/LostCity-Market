<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listing_offer_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('listing_offer_id');
            $table->foreignId('item_id');
            $table->integer('quantity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listing_offer_items');
    }
};
