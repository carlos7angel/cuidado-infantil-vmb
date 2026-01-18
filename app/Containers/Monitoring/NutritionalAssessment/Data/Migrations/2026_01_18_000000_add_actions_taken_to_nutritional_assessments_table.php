<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::table('nutritional_assessments', static function (Blueprint $table) {
            $table->text('actions_taken')->nullable()->after('recommendations');
        });
    }

    public function down(): void
    {
        Schema::table('nutritional_assessments', static function (Blueprint $table) {
            $table->dropColumn('actions_taken');
        });
    }
};

