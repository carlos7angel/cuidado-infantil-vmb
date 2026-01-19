<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->string('municipality')->nullable()->after('city');
        });

        Schema::table('child_social_records', function (Blueprint $table) {
            $table->text('incident_history')->nullable()->after('professional_assessment');
            $table->text('pets')->nullable()->after('incident_history');
        });

        Schema::table('child_medical_records', function (Blueprint $table) {
            $table->text('outstanding_skills')->nullable()->after('other_observations');
            $table->text('nutritional_problems')->nullable()->after('outstanding_skills');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('children', function (Blueprint $table) {
            $table->dropColumn('municipality');
        });

        Schema::table('child_social_records', function (Blueprint $table) {
            $table->dropColumn(['incident_history', 'pets']);
        });

        Schema::table('child_medical_records', function (Blueprint $table) {
            $table->dropColumn(['outstanding_skills', 'nutritional_problems']);
        });
    }
};
