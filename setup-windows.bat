@echo off
setlocal
cd /d %~dp0

echo == Sistem Prediksi IPK ANN: Setup ==

where php >nul 2>nul || (echo ERROR: PHP belum tersedia di PATH.& exit /b 1)
where composer >nul 2>nul || (echo ERROR: Composer belum tersedia di PATH.& exit /b 1)
where node >nul 2>nul || (echo ERROR: Node.js belum tersedia di PATH.& exit /b 1)
where npm >nul 2>nul || (echo ERROR: npm belum tersedia di PATH.& exit /b 1)

if not exist .env copy .env.example .env >nul

call composer install || exit /b 1
call npm install || exit /b 1
php artisan key:generate --force || exit /b 1
php scripts\create_database.php || exit /b 1
php artisan thesis:setup --fresh --force || exit /b 1
call npm run build || exit /b 1

echo.
echo Setup selesai. Jalankan: php artisan serve
echo Admin    : admin / password123
echo Operator : operator / password123
pause
