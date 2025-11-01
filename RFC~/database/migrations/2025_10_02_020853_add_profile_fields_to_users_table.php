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
            // Skip phone and address as they already exist
            $table->string('avatar')->nullable()->after('address');
            $table->date('birth_date')->nullable()->after('avatar');
            $table->enum('gender', ['male', 'female'])->nullable()->after('birth_date');
            $table->string('employee_id')->nullable()->after('gender');
            $table->string('position')->nullable()->after('employee_id');
            $table->text('bio')->nullable()->after('position');
            $table->json('settings')->nullable()->after('bio');
            $table->timestamp('last_login_at')->nullable()->after('settings');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar', 'birth_date', 'gender', 
                'employee_id', 'position', 'bio', 'settings', 
                'last_login_at', 'last_login_ip'
            ]);
        });
    }
};