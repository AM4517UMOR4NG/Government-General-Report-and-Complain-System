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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->morphs('commentable'); // commentable_id, commentable_type (reports/complaints)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('content');
            $table->json('attachments')->nullable();
            $table->boolean('is_internal')->default(false); // internal notes vs public comments
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
