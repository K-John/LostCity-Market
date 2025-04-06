<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['default', 'success', 'error', 'warning', 'info']);
            $table->text('message');
            $table->boolean('is_active')->default(true);
            $table->date('start_at')->nullable();
            $table->date('end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('banner_item', function (Blueprint $table) {
            $table->foreignId('banner_id');
            $table->foreignId('item_id');
            $table->timestamps();

            $table->unique(['banner_id', 'item_id']);
        });

        Schema::create('banner_user', function (Blueprint $table) {
            $table->foreignId('banner_id');
            $table->foreignId('user_id');
            $table->timestamps();

            $table->unique(['banner_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banner_user');
        Schema::dropIfExists('banner_item');
        Schema::dropIfExists('banners');
    }
};
