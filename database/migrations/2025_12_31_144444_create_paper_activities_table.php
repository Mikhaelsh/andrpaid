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
        Schema::create('paper_activities', function (Blueprint $table) {
            $table->id();
            $table->text("description");
            $table->foreignId('paper_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('type')->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paper_activities');
    }
};
