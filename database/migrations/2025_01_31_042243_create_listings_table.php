<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['buy', 'sell'])->default('buy');
            $table->integer('price');
            $table->integer('quantity');
            $table->uuid('token');
            $table->string('username');
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
