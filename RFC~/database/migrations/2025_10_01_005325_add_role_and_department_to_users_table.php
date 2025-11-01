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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['citizen', 'admin', 'department_head', 'staff'])->default('citizen')->after('password');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null')->after('role');
            $table->string('phone')->nullable()->after('department_id');
            $table->text('address')->nullable()->after('phone');
            $table->string('id_number')->nullable()->after('address');
            $table->boolean('is_active')->default(true)->after('id_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['role', 'department_id', 'phone', 'address', 'id_number', 'is_active']);
        });
    }
};
