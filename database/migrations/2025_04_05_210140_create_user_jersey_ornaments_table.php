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
        Schema::create('user_jersey_ornaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_jersey_id')->constrained()->onDelete('cascade');
            $table->foreignId('ornament_version_id')->constrained()->onDelete('cascade');
            $table->integer('position_x')->default(0);
            $table->integer('position_y')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_jersey_ornaments');
    }
}; 