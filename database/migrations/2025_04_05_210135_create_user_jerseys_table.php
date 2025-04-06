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
        Schema::create('user_jerseys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('jersey_id')->constrained()->onDelete('cascade');
            $table->string('texture_url');
            $table->float('resize_value');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::create('user_jersey_ornaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_jersey_id')->constrained()->onDelete('cascade');
            $table->foreignId('ornament_id')->constrained()->onDelete('cascade');
            $table->foreignId('ornament_version_id')->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('user_jersey_ornaments');
        Schema::dropIfExists('user_jerseys');
    }
};
