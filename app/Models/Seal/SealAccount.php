<?php

namespace App\Models\Seal;

use Illuminate\Database\Eloquent\Model;

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
     * Verify password (MD5 hash — sesuai Seal Online standar)
     */
    public function verifyPassword(string $inputPassword): bool
    {
        return $this->passed === md5($inputPassword)
            || $this->passed === strtolower(md5($inputPassword))
            || $this->passed === $inputPassword; // fallback plain (jangan pakai di produksi)
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
