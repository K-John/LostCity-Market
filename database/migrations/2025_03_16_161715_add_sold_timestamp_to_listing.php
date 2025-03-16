<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('listings', function (Blueprint $table) {
            $table->timestamp('sold_at')->nullable()->after('updated_at');
        });

        // Convert the deleted_at column to sold_at
        DB::statement('UPDATE listings SET sold_at = deleted_at, deleted_at = NULL WHERE deleted_at IS NOT NULL');
    }

    public function down(): void
    {
        // Convert the sold_at column back to deleted_at
        DB::statement('UPDATE listings SET deleted_at = sold_at, sold_at = NULL WHERE sold_at IS NOT NULL');

        Schema::table('listings', function (Blueprint $table) {
            $table->dropColumn('sold_at');
        });
    }
};
