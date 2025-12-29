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
        Schema::create('papers', function (Blueprint $table) {
            $table->id();
            $table->uuid('paperId');
            $table->string('title');
            $table->longText('description')->nullable();
            $table->string('visibility'); // publlic, private
            $table->string('status')->default("draft"); // draft, finalized

            $table->string('externalLink')->nullable();

            $table->string('filePath')->nullable(); //for file
            $table->string('originalFilename')->nullable();

            $table->boolean("openCollaboration");

            $table->foreignId('lecturer_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('paper_type_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('papers');
    }
};
