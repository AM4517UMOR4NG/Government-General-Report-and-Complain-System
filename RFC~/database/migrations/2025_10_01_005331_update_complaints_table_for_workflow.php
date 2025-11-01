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
        Schema::table('complaints', function (Blueprint $table) {
            // Add workflow fields
            $table->string('ticket_no')->unique()->after('id');
            $table->enum('status', ['submitted', 'verified', 'rejected', 'assigned', 'in_progress', 'awaiting_info', 'resolved', 'pending_approval', 'closed', 'escalated'])->default('submitted')->change();
            $table->timestamp('sla_due_at')->nullable()->after('resolved_at');
            $table->boolean('is_escalated')->default(false)->after('sla_due_at');
            $table->integer('reassign_count')->default(0)->after('is_escalated');
            $table->timestamp('last_activity_at')->nullable()->after('reassign_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn(['ticket_no', 'sla_due_at', 'is_escalated', 'reassign_count', 'last_activity_at']);
            $table->enum('status', ['pending', 'investigating', 'resolved', 'dismissed'])->default('pending')->change();
        });
    }
};
