<?php
/**
 * Direct Artisan Runner - Runs Laravel migrations and seeders inline.
 * DELETE THIS FILE after use!
 * 
 * Access: http://localhost/sistem%20binaa/public/direct-setup.php
 */

// Bootstrap Laravel
require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo '<!DOCTYPE html><html lang="id"><head>
<meta charset="UTF-8">
<title>Setup E-Learning SMK Binaa</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:system-ui,sans-serif; background:#0f172a; color:#e2e8f0; min-height:100vh; padding:40px; }
h1 { color:#60a5fa; font-size:24px; margin-bottom:6px; }
.sub { color:#94a3b8; font-size:14px; margin-bottom:24px; }
.card { background:#1e293b; border:1px solid #334155; border-radius:12px; padding:24px; margin-bottom:20px; }
.card h2 { color:#f8fafc; font-size:16px; margin-bottom:16px; border-bottom:1px solid #334155; padding-bottom:10px; }
.output { background:#0f172a; border-radius:8px; padding:16px; font-family:monospace; font-size:13px; white-space:pre-wrap; word-break:break-all; max-height:300px; overflow-y:auto; }
.success { color:#4ade80; }
.error { color:#f87171; }
.warning { color:#fbbf24; }
.info { color:#60a5fa; }
.btn { display:inline-flex; align-items:center; gap:8px; padding:10px 20px; background:#2563eb; color:white; border-radius:8px; text-decoration:none; font-size:14px; font-weight:600; margin:6px 4px 0 0; }
.btn:hover { background:#1d4ed8; }
.btn.green { background:#16a34a; }
.btn.red { background:#dc2626; }
</style>
</head><body>';

echo '<h1>🚀 Setup E-Learning SMK Binaa</h1>';
echo '<p class="sub">Tool konfigurasi database dan sistem</p>';

$action = $_GET['action'] ?? '';
$output = '';

if ($action === 'migrate_fresh_seed') {
    echo '<div class="card"><h2>⚙️ Running: migrate:fresh --seed</h2><div class="output">';
    
    // Capture output
    $exitCode = Artisan::call('migrate:fresh', ['--seed' => true, '--force' => true]);
    $output = Artisan::output();
    echo htmlspecialchars($output);
    echo $exitCode === 0 
        ? '<span class="success">✅ Berhasil! Exit Code: 0</span>' 
        : '<span class="error">❌ Error! Exit Code: ' . $exitCode . '</span>';
    echo '</div></div>';
    
} elseif ($action === 'migrate') {
    echo '<div class="card"><h2>⚙️ Running: migrate --seed</h2><div class="output">';
    $exitCode = Artisan::call('migrate', ['--seed' => true, '--force' => true]);
    $output = Artisan::output();
    echo htmlspecialchars($output);
    echo $exitCode === 0 
        ? '<span class="success">✅ Berhasil! Exit Code: 0</span>' 
        : '<span class="error">❌ Error! Exit Code: ' . $exitCode . '</span>';
    echo '</div></div>';

} elseif ($action === 'storage_link') {
    echo '<div class="card"><h2>⚙️ Running: storage:link</h2><div class="output">';
    // Manual storage link
    $publicPath = __DIR__ . '/storage';
    $storagePath = __DIR__ . '/../storage/app/public';
    
    if (is_link($publicPath)) {
        echo '<span class="warning">⚠️ Link sudah ada: ' . $publicPath . '</span>' . "\n";
    } else {
        if (symlink($storagePath, $publicPath)) {
            echo '<span class="success">✅ Storage link berhasil dibuat!</span>' . "\n";
            echo 'Link: ' . $publicPath . "\n=> " . $storagePath;
        } else {
            // Try artisan way
            $exitCode = Artisan::call('storage:link');
            $output = Artisan::output();
            echo htmlspecialchars($output);
            echo $exitCode === 0 
                ? '<span class="success">✅ Berhasil via Artisan!</span>' 
                : '<span class="error">❌ Error! Buat symlink manual atau: mklink /D "' . $publicPath . '" "' . $storagePath . '"</span>';
        }
    }
    echo '</div></div>';

} elseif ($action === 'check') {
    echo '<div class="card"><h2>📊 Status Database</h2><div class="output">';
    try {
        $tables = DB::select('SHOW TABLES');
        $colKey = 'Tables_in_' . env('DB_DATABASE', 'laravel');
        echo '<span class="success">✅ Koneksi database berhasil!</span>' . "\n\n";
        echo '<span class="info">Database: ' . env('DB_DATABASE') . '</span>' . "\n";
        echo 'Tabel yang ada (' . count($tables) . ')' . ":\n";
        foreach ($tables as $table) {
            $tableName = $table->$colKey;
            $count = DB::table($tableName)->count();
            echo "  • {$tableName}: <span class='success'>{$count} baris</span>\n";
        }
    } catch (\Exception $e) {
        echo '<span class="error">❌ Error koneksi: ' . htmlspecialchars($e->getMessage()) . '</span>';
    }
    echo '</div></div>';

} elseif ($action === 'config_clear') {
    echo '<div class="card"><h2>⚙️ Clearing Cache</h2><div class="output">';
    Artisan::call('optimize:clear');
    echo htmlspecialchars(Artisan::output());
    echo '<span class="success">✅ Cache dibersihkan!</span>';
    echo '</div></div>';
}

// Default buttons
echo '<div class="card"><h2>🛠️ Aksi Tersedia</h2>';
echo '<p style="color:#94a3b8;font-size:13px;margin-bottom:12px;">Pilih aksi yang ingin dijalankan:</p>';
echo '<a href="?action=check" class="btn">📊 Cek Status Database</a>';
echo '<a href="?action=migrate" class="btn green">✅ Migrate + Seed Data</a>';
echo '<a href="?action=migrate_fresh_seed" class="btn red" onclick="return confirm(\'⚠️ Ini akan MENGHAPUS semua data dan membuat ulang tabel! Lanjutkan?\')">🔄 Fresh Migrate (Reset DB)</a>';
echo '<a href="?action=storage_link" class="btn">🔗 Buat Storage Link</a>';
echo '<a href="?action=config_clear" class="btn">🧹 Clear Cache</a>';
echo '<br><br><p style="color:#f87171;font-size:12px;">⚠️ <strong>Penting:</strong> Hapus file <code style="color:#fbbf24">public/direct-setup.php</code> setelah selesai konfigurasi!</p>';
echo '</div>';

// Links
echo '<div class="card"><h2>🔗 Akses Sistem</h2>';
echo '<a href="/sistem%20binaa/public/index.php" class="btn">🏠 Buka Aplikasi</a>';
echo '</div>';

echo '</body></html>';
