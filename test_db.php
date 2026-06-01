<?php
/**
 * Script tes koneksi ke database seal_member
 * Jalankan: php test_db.php
 * atau buka di browser: http://localhost/sealviking/test_db.php
 */

$host = '127.0.0.1';  // Ganti ke 202.83.123.196 jika web dijalankan di luar VNC
$port = 3306;
$db   = 'seal_member';
$user = 'gebe';
$pass = 'nopgg2024@';

echo "=== TES KONEKSI DATABASE SEAL ===\n\n";

// 1. Tes koneksi
echo "[1] Mencoba koneksi ke $host:$port ...\n";
try {
    $conn = new mysqli($host, $user, $pass, $db, $port);
    if ($conn->connect_error) {
        die("❌ GAGAL konek: " . $conn->connect_error . "\n");
    }
    echo "✅ Koneksi BERHASIL!\n\n";
} catch (Exception $e) {
    die("❌ GAGAL konek: " . $e->getMessage() . "\n");
}

// 2. Cek tabel apa saja yang ada
echo "[2] Daftar tabel di database '$db':\n";
$tables = $conn->query("SHOW TABLES");
while ($row = $tables->fetch_row()) {
    echo "   - " . $row[0] . "\n";
}
echo "\n";

// 3. Cek struktur kolom di idtable1
echo "[3] Struktur kolom idtable1:\n";
$columns = $conn->query("DESCRIBE idtable1");
if ($columns) {
    while ($col = $columns->fetch_assoc()) {
        echo "   - " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }
} else {
    echo "   ❌ Tabel idtable1 tidak ditemukan!\n";
}
echo "\n";

// 4. Cari akun gm09 dan bontot
$testIds = ['gm09', 'gmm09', 'bontot', 'Gm09', 'Bontot'];
echo "[4] Mencari akun di semua idtable (1-5):\n";
for ($i = 1; $i <= 5; $i++) {
    $tableName = "idtable" . $i;
    $check = $conn->query("SELECT COUNT(*) as cnt FROM information_schema.tables WHERE table_schema='$db' AND table_name='$tableName'");
    $exists = $check->fetch_assoc()['cnt'];
    
    if (!$exists) {
        echo "   [$tableName] — Tabel tidak ada\n";
        continue;
    }
    
    // Tampilkan 5 data pertama dari tabel ini
    $result = $conn->query("SELECT * FROM `$tableName` LIMIT 5");
    $rowCount = $conn->query("SELECT COUNT(*) as cnt FROM `$tableName`")->fetch_assoc()['cnt'];
    echo "   [$tableName] — Total: $rowCount baris. Contoh 5 baris pertama:\n";
    
    if ($result && $result->num_rows > 0) {
        $fieldNames = [];
        $fields = $result->fetch_fields();
        foreach ($fields as $f) {
            $fieldNames[] = $f->name;
        }
        echo "     Kolom: " . implode(', ', $fieldNames) . "\n";
        
        $result->data_seek(0);
        while ($row = $result->fetch_assoc()) {
            // Tampilkan max 3 kolom pertama saja biar rapi
            $preview = array_slice($row, 0, 4);
            $parts = [];
            foreach ($preview as $k => $v) {
                $parts[] = "$k=" . ($v ?? 'NULL');
            }
            echo "     " . implode(' | ', $parts) . "\n";
        }
    }
    echo "\n";
}

// 5. Cek apakah OLD_PASSWORD() berfungsi
echo "[5] Tes fungsi OLD_PASSWORD():\n";
$testPass = $conn->query("SELECT OLD_PASSWORD('bontot123') as hashed");
if ($testPass) {
    $hash = $testPass->fetch_assoc()['hashed'];
    echo "   OLD_PASSWORD('bontot123') = $hash\n";
    echo "   Yang diharapkan:            539efffa33704599\n";
    echo "   Cocok: " . ($hash === '539efffa33704599' ? '✅ YA' : '❌ TIDAK') . "\n";
} else {
    echo "   ❌ Fungsi OLD_PASSWORD() tidak tersedia di MySQL ini.\n";
}

echo "\n=== SELESAI ===\n";

$conn->close();
?>
