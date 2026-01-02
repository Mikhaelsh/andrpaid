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

            $table->boolean("openCollaboration")->default(false);

            $table->foreignId('lecturer_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('paper_type_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->json('references_data')->nullable(); 
            $table->longText('synthesis_text')->nullable();
            $table->json('themes')->nullable(); 
            $table->boolean('lit_review_finalized')->default(false);
            $table->longText('methodology_xml')->nullable();
            $table->boolean('methodology_finalized')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('papers');
        Schema::table('papers', function (Blueprint $table) {
            $table->dropColumn('references_data');
            $table->dropColumn('synthesis_text');
            $table->dropColumn('themes');
            $table->dropColumn('lit_review_finalized');
            $table->dropColumn('methodology_xml');
            $table->dropColumn('methodology_finalized');
        });
    }
};
