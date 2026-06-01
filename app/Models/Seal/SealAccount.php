<?php

namespace App\Models\Seal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Model untuk tabel idtable1 di database seal_member
 * Ini adalah data akun player Seal Online
 */
class SealAccount extends Model
{
    protected $connection = 'seal_member';
    protected $table     = 'idtable1';
    protected $primaryKey = 'id';
    public    $keyType   = 'string';
    public    $timestamps = false;

    protected $fillable = [
        'id',
        'passed',
        'char_name',
        'userLevel',
        'gameserver_bumho',
        'game_block',
    ];

    protected $hidden = ['passed'];

    /**
     * Verify password menggunakan MySQL OLD_PASSWORD()
     * Game Seal Online menyimpan password dalam format OLD_PASSWORD (16 char hex)
     * Contoh: password "bontot123" → "539efffa33704599"
     */
    public function verifyPassword(string $inputPassword): bool
    {
        // Hitung hash menggunakan fungsi OLD_PASSWORD() dari MySQL
        $result = DB::connection('seal_member')
            ->selectOne("SELECT OLD_PASSWORD(?) as hashed", [$inputPassword]);

        $oldPasswordHash = $result->hashed ?? '';

        // Bandingkan hash OLD_PASSWORD dengan yang tersimpan di database
        return strtolower($this->passed) === strtolower($oldPasswordHash);
    }

    /**
     * Cek apakah akun di-block
     */
    public function isBlocked(): bool
    {
        return !empty($this->game_block) && $this->game_block != '0';
    }

    /**
     * Cari semua karakter dari satu akun (idtable1 ~ idtable5)
     * Karena Seal Online bisa punya multiple karakter per akun
     */
    public static function findAccountAcrossTables(string $accountId): ?self
    {
        $tables = ['idtable1', 'idtable2', 'idtable3', 'idtable4', 'idtable5'];
        foreach ($tables as $table) {
            $account = self::on('seal_member')->from($table)->where('id', $accountId)->first();
            if ($account) {
                $account->setTable($table);
                return $account;
            }
        }
        return null;
    }
}
