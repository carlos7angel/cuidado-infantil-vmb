<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('files', static function (Blueprint $table) {
            $table->id();

            $table->string('unique_code', 50);
            $table->string('file_hash', 100)->nullable();
            $table->string('name', 255);
            $table->string('original_name', 255);
            $table->text('description')->nullable();
            $table->string('mime_type', 100);
            $table->integer('size');
            $table->text('url');
            $table->text('path');
            $table->text('options')->nullable();

            $table->enum('locale_upload', ['local', 's3', 'sftp'])->default('local');
            $table->enum('status', ['created', 'submitted', 'archived'])->default('created');

            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('filleable_id')->unsigned()->nullable();
            $table->string('filleable_type')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
