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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

    $table->text('content')->nullable();
    $table->string('image')->nullable();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    // counters (performance)
    $table->unsignedBigInteger('likes_count')->default(0);
    $table->unsignedBigInteger('comments_count')->default(0);
    $table->timestamps();
    $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
