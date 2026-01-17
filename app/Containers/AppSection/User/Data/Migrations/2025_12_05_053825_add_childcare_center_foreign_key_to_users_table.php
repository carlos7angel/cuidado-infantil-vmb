<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->foreign('childcare_center_id')
                  ->references('id')
                  ->on('childcare_centers')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->dropForeign(['childcare_center_id']);
        });
    }
};
