<?php
/**
 * Script sementara untuk menjalankan php artisan dari browser
 * HAPUS file ini setelah selesai digunakan!
 */

$basePath = dirname(__DIR__);
$_SERVER['argv'] = ['artisan'];
$_ENV['APP_ENV'] = 'local';

// Jalankan artisan command dari parameter URL
$command = $_GET['cmd'] ?? 'route:list';
$allowed = ['migrate', 'migrate:fresh', 'migrate:fresh --seed', 'db:seed', 'storage:link', 'config:clear', 'cache:clear', 'route:clear', 'optimize:clear', 'migrate --seed'];

if (!in_array($command, $allowed)) {
    die('<h2>❌ Command tidak diizinkan: ' . htmlspecialchars($command) . '</h2>');
}

echo '<pre style="background:#1e293b;color:#94a3b8;padding:20px;font-family:monospace;font-size:13px;">';
echo '<h2 style="color:#60a5fa;margin:0 0 10px">🚀 Running: php artisan ' . htmlspecialchars($command) . '</h2>';
flush();

$descriptorspec = [
    0 => ['pipe', 'r'],
    1 => ['pipe', 'w'],
    2 => ['pipe', 'w'],
];

$process = proc_open(
    '"' . PHP_BINARY . '" artisan ' . $command . ' --no-interaction --ansi',
    $descriptorspec,
    $pipes,
    $basePath
);

if (is_resource($process)) {
    fclose($pipes[0]);
    $output = stream_get_contents($pipes[1]);
    $errors = stream_get_contents($pipes[2]);
    fclose($pipes[1]);
    fclose($pipes[2]);
    $returnCode = proc_close($process);

    // Strip ANSI codes for display
    $output = preg_replace('/\x1b\[[0-9;]*m/', '', $output);
    $errors = preg_replace('/\x1b\[[0-9;]*m/', '', $errors);

    echo htmlspecialchars($output);
    if ($errors) echo '<span style="color:#f87171">' . htmlspecialchars($errors) . '</span>';
    echo "\n\n";
    echo $returnCode === 0 
        ? '<span style="color:#4ade80">✅ Selesai! Exit code: 0</span>'
        : '<span style="color:#f87171">❌ Error! Exit code: ' . $returnCode . '</span>';
} else {
    echo '<span style="color:#f87171">❌ Gagal membuka proses.</span>';
}

echo '</pre>';
echo '<br><p style="font-family:sans-serif">Akses perintah lain:<br>';
$cmds = ['migrate', 'migrate:fresh', 'migrate --seed', 'db:seed', 'storage:link', 'config:clear', 'optimize:clear'];
foreach ($cmds as $cmd) {
    echo '<a href="?cmd=' . urlencode($cmd) . '" style="display:inline-block;margin:5px;padding:8px 16px;background:#1d4ed8;color:white;border-radius:6px;text-decoration:none;font-family:sans-serif;font-size:12px">php artisan ' . $cmd . '</a>';
}
echo '</p>';
