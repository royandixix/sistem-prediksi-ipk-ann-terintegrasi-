<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$envPath = $root.'/.env';

if (! is_file($envPath)) {
    fwrite(STDERR, ".env belum tersedia. Salin .env.example menjadi .env terlebih dahulu.\n");
    exit(1);
}

$values = [];
foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [] as $line) {
    $line = trim($line);
    if ($line === '' || str_starts_with($line, '#') || ! str_contains($line, '=')) {
        continue;
    }

    [$key, $value] = explode('=', $line, 2);
    $values[trim($key)] = trim(trim($value), "\"'");
}

$connection = $values['DB_CONNECTION'] ?? 'mysql';

if ($connection === 'sqlite') {
    $database = $values['DB_DATABASE'] ?? 'database/database.sqlite';
    $path = str_starts_with($database, '/') ? $database : $root.'/'.$database;
    if (! is_dir(dirname($path))) {
        mkdir(dirname($path), 0775, true);
    }
    if (! is_file($path)) {
        touch($path);
    }
    echo "Database SQLite siap: {$path}\n";
    exit(0);
}

if ($connection !== 'mysql') {
    fwrite(STDERR, "Script otomatis hanya mendukung mysql atau sqlite.\n");
    exit(1);
}

if (! extension_loaded('pdo_mysql')) {
    fwrite(STDERR, "Ekstensi PHP pdo_mysql belum aktif. Aktifkan melalui XAMPP/Laragon.\n");
    exit(1);
}

$host = $values['DB_HOST'] ?? '127.0.0.1';
$port = $values['DB_PORT'] ?? '3306';
$db = $values['DB_DATABASE'] ?? 'sistem_prediksi_ipk_ann';
$user = $values['DB_USERNAME'] ?? 'root';
$pass = $values['DB_PASSWORD'] ?? '';
$socket = $values['DB_SOCKET'] ?? '';

if (! preg_match('/^[A-Za-z0-9_]+$/', $db)) {
    fwrite(STDERR, "Nama database hanya boleh berisi huruf, angka, dan underscore.\n");
    exit(1);
}

$dsn = $socket !== ''
    ? "mysql:unix_socket={$socket};charset=utf8mb4"
    : "mysql:host={$host};port={$port};charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);
    $pdo->exec(
        "CREATE DATABASE IF NOT EXISTS `{$db}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
    );
    echo "Database MySQL siap: {$db}\n";
} catch (Throwable $exception) {
    fwrite(STDERR, "Gagal membuat database: {$exception->getMessage()}\n");
    fwrite(STDERR, "Pastikan MySQL pada XAMPP/Laragon sudah menyala dan konfigurasi .env benar.\n");
    exit(1);
}
