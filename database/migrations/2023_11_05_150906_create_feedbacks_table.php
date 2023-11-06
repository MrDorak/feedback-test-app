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
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->text("text");
            $table->tinyInteger("rating")->nullable();
            $table->dateTime("start_date")->nullable();
            $table->string("address")->nullable();
            $table->string("appartments", 100)->nullable();
            $table->string("source", 50)->nullable();
            $table->boolean("is_imported")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feedbacks');
    }
};
