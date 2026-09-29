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
        Schema::create('absensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->date('date');
            $table->time('entry_time', precision: 0)->nullable();
            $table->time('out_time', precision: 0)->nullable();
            $table->string('description')->nullable();
            $table->text('notes')->nullable();
            $table->text('activity')->nullable();
            $table->string('latitude', 128)->nullable(); 
            $table->string('longitude', 128)->nullable();
            $table->string('picture')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi');
    }
};
