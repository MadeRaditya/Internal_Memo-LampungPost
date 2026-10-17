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
        Schema::create('memo_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('memo_id')->constrained('memos')->onDelete('cascade');
            $table->foreignId('target_department_id')->constrained('departments')->onDelete('restrict');
            $table->foreignId('parent_recipient_id')->nullable()->constrained('memo_recipients')->onDelete('cascade')->comment('Rujukan jika status forwarded');
            $table->enum('status', ['pending', 'read', 'in_review', 'approved', 'rejected', 'forwarded', 'completed'])->default('pending');
            $table->timestamp('received_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->text('response_notes')->nullable();
            $table->foreignId('processed_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            $table->index(['memo_id', 'target_department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('memo_recipients');
    }
};
