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
        Schema::create('temps', function (Blueprint $table) {
            $table->id();
            $table->string('name', '100')->nullable();
            $table->string('email', '255')->nullable();
            $table->string('phone', '10')->nullable();
            $table->string('city', '50')->nullable();
            $table->string('state', '50')->nullable();
            $table->string('school', '255')->nullable();
            $table->string('grade', '10')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temps');
    }
};
