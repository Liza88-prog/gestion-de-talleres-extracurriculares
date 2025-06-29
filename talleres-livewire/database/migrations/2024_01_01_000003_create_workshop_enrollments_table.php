<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workshop_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('workshop_id')->constrained()->onDelete('cascade');
            $table->timestamp('enrollment_date')->useCurrent();
            $table->enum('status', ['active', 'dropped', 'completed'])->default('active');
            $table->timestamps();

            $table->unique(['user_id', 'workshop_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workshop_enrollments');
    }
};