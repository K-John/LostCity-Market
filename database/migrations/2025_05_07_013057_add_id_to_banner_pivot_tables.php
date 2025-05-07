<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banner_item', function (Blueprint $table) {
            $table->id()->first();
        });

        Schema::table('banner_user', function (Blueprint $table) {
            $table->id()->first();
        });
    }
};
