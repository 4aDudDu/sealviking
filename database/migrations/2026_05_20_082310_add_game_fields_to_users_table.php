<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah kolom untuk link akun website ke akun game Seal Online
 *
 * game_id   : ID akun game player (sama dengan idtable1.id di seal_member)
 * char_name : Nama karakter aktif player di game
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('game_id', 50)->nullable()->unique()->after('diamonds')
                ->comment('Link ke idtable1.id di database seal_member');
            $table->string('char_name', 50)->nullable()->after('game_id')
                ->comment('Nama karakter aktif player');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['game_id', 'char_name']);
        });
    }
};


