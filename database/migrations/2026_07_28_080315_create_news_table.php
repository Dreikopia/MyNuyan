<?php

use App\Models\Admin;
use App\Models\NewsCategory;
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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Admin::class)->constrained()->cascadeOnDelete(); // the author
            $table->foreignIdFor(NewsCategory::class)->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('image_path')->nullable();
            $table->string('status')->default('draft');
            $table->text('description');
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
