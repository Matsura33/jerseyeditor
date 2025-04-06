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
        Schema::create('jersey_ornaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jersey_id')->constrained()->onDelete('cascade');
            $table->foreignId('ornament_id')->constrained()->onDelete('cascade');
            $table->integer('position_x');
            $table->integer('position_y');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jersey_ornaments');
    }
};
