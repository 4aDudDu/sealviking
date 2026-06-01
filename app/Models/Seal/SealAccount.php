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
     * Verify password menggunakan algoritma MySQL OLD_PASSWORD()
     * Game Seal Online menyimpan password dalam format OLD_PASSWORD (16 char hex)
     * Contoh: password "bontot123" → "539efffa33704599"
     */
    public function verifyPassword(string $inputPassword): bool
    {
        $storedHash = strtolower(trim($this->passed));
        $computedHash = strtolower(self::mysqlOldPassword($inputPassword));

        // Bandingkan hash
        if ($storedHash === $computedHash) {
            return true;
        }

        // Fallback: coba bandingkan langsung (plain text)
        if ($this->passed === $inputPassword) {
            return true;
        }

        return false;
    }

    /**
     * Implementasi algoritma MySQL OLD_PASSWORD() di PHP
     * Fungsi OLD_PASSWORD() sudah dihapus di MySQL 8.0+,
     * jadi kita hitung sendiri di PHP agar kompatibel semua versi.
     */
    public static function mysqlOldPassword(string $password): string
    {
        $nr  = 1345345333;
        $add = 7;
        $nr2 = 0x12345671;

        $len = strlen($password);
        for ($i = 0; $i < $len; $i++) {
            $c = ord($password[$i]);
            if ($c === 32 || $c === 9) {
                continue; // Skip spasi dan tab
            }

            $nr  ^= ((($nr & 63) + $add) * $c) + ($nr << 8);
            $nr  &= 0x7FFFFFFF;
            $nr2 += ($nr2 << 8) ^ $nr;
            $nr2 &= 0x7FFFFFFF;
            $add += $c;
        }

        return sprintf('%08x%08x', $nr, $nr2);
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
