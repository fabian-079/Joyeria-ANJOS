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
        Schema::create('customizations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('jewelry_type');
            $table->string('design');
            $table->string('stones');
            $table->string('finish');
            $table->string('color');
            $table->string('material');
            $table->string('engraving')->nullable();
            $table->text('special_instructions')->nullable();
            $table->decimal('estimated_price', 10, 2)->nullable();
            $table->string('status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customizations');
    }
};

