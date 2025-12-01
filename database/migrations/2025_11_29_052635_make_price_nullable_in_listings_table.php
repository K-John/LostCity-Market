<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->integer('price')
                ->nullable()
                ->change()
                ->comment('Deprecated: replaced by listing_offers + listing_offer_items');
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->integer('price')
                ->nullable(false)
                ->change()
                ->comment(null);
        });
    }
};
