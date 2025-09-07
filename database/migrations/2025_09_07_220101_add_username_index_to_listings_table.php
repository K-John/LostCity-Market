<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->index(
                ['username', 'sold_at', 'paused_at', 'deleted_at', 'updated_at', 'id'],
                'idx_listings_username_active_updated_id'
            );
        });
    }

    public function down(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->dropIndex('idx_listings_username_active_updated_id');
        });
    }
};
