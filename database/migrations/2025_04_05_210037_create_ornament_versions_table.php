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
        Schema::create('ornament_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ornament_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('image_url');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ornament_versions');
    }
};
