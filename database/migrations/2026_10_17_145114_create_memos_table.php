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
        Schema::create('memos', function (Blueprint $table) {
            $table->id();
            $table->string('memo_number', 100)->nullable()->unique()->comment('Nm. YYYY-MM(Romawi)-DD DEPT-asal-tujuan');
            $table->foreignId('sender_user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('origin_department_id')->constrained('departments')->onDelete('restrict');
            $table->string('subject', 255);
            $table->longText('content');
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->enum('status', ['draft', 'in_progress', 'completed', 'rejected', 'archived'])->default('draft');
            $table->date('published_date')->nullable();
            $table->unsignedInteger('sequence_number')->default(1);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['published_date', 'origin_department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memos');
    }
};
