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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('app_name',128);
            $table->string('copyright',128);
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('wallpaper')->nullable();
            $table->enum('is_active', ['0', '1']);
            $table->enum('is_photo', ['0', '1']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }

    public function up()
{
    Schema::table('applications', function (Blueprint $table) {
        $table->string('wallpaper')->nullable();
    });
}

public function down()
{
    Schema::table('applications', function (Blueprint $table) {
        $table->dropColumn('wallpaper');
    });
}

};
