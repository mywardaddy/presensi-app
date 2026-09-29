<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            // Tambah kolom user_id
            $table->unsignedBigInteger('user_id')->after('id');

            // Tambah kolom is_shared jika belum ada
            if (!Schema::hasColumn('jadwals', 'is_shared')) {
                $table->boolean('is_shared')->default(0)->after('moderator');
            }

            // Foreign key ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            if (Schema::hasColumn('jadwals', 'is_shared')) {
                $table->dropColumn('is_shared');
            }
        });
    }
};
