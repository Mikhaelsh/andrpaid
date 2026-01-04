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
        Schema::create('inboxes', function (Blueprint $table) {
            $table->id();
            $table->uuid('inboxId');
            $table->string("subject")->nullable();
            $table->longText("body")->nullable();
            $table->string("externalUrl")->nullable();
            $table->longText("externalUrlMessage")->nullable();
            $table->boolean("is_sent")->default(false); // from_user
            $table->boolean("marked_read")->default(false); // to_user
            $table->boolean("is_starred")->default(false); // to_user
            $table->foreignId('from_user_id')
                ->constrained('users')
                ->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignId('to_user_id')->nullable()
                ->constrained('users')
                ->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inboxes');
    }
};
