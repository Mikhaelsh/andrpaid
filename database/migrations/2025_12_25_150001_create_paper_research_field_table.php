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
        Schema::create('paper_research_field', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paper_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('research_field_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->unique(['paper_id', 'research_field_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paper_research_field');
    }
};
