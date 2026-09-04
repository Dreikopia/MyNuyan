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
        Schema::create('hotline_numbers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotline_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('number');
            $table->enum('type', ['telephone', 'mobile']);
            $table->string('label')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotline_numbers');
    }
};
