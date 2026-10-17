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
        Schema::create('memo_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')->constrained('memos')->onDelete('cascade');
            $table->string('file_path', 255);
            $table->string('original_name', 255);
            $table->string('file_mime', 100);
            $table->unsignedBigInteger('file_size');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo_attachments');
    }
};
