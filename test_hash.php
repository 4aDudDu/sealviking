<?php
/**
 * Tes verifikasi algoritma OLD_PASSWORD di PHP
 * Jalankan: php test_hash.php
 */

function mysqlOldPassword(string $password): string
{
    $nr  = 1345345333;
    $add = 7;
    $nr2 = 0x12345671;

    $len = strlen($password);
    for ($i = 0; $i < $len; $i++) {
        $c = ord($password[$i]);
        if ($c === 32 || $c === 9) {
            continue;
        }

        $nr  ^= ((($nr & 63) + $add) * $c) + ($nr << 8);
        $nr  &= 0x7FFFFFFF;
        $nr2 += ($nr2 << 8) ^ $nr;
        $nr2 &= 0x7FFFFFFF;
        $add += $c;
    }

    return sprintf('%08x%08x', $nr, $nr2);
}

echo "=== TES HASH OLD_PASSWORD ===\n\n";

// Tes 1: bontot123 harus menghasilkan 539efffa33704599
$hash1 = mysqlOldPassword('bontot123');
echo "Password: bontot123\n";
echo "Hash PHP:  $hash1\n";
echo "Hash Game: 539efffa33704599\n";
echo "Cocok: " . ($hash1 === '539efffa33704599' ? '✅ YA!' : '❌ TIDAK') . "\n\n";

// Tes 2: botakkontol harus menghasilkan 697304be042d53b8
$hash2 = mysqlOldPassword('botakkontol');
echo "Password: botakkontol\n";
echo "Hash PHP:  $hash2\n";
echo "Hash Game: 697304be042d53b8\n";
echo "Cocok: " . ($hash2 === '697304be042d53b8' ? '✅ YA!' : '❌ TIDAK') . "\n\n";

echo "=== SELESAI ===\n";
?>
