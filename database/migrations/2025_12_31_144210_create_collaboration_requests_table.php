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
        Schema::create('collaboration_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('from_lecturer_id')
                ->constrained('lecturers')
                ->cascadeOnDelete();

            $table->foreignId('to_lecturer_id')
                ->constrained('lecturers')
                ->cascadeOnDelete();

            $table->string("status")->default('pending'); // 'pending', 'accepted', 'rejected'
            $table->text('message')->nullable();

            $table->foreignId('collaboration_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignId('paper_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collaboration_requests');
    }
};
