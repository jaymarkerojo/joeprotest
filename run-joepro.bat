@echo off
title JoePro Adventures
cd /d "%~dp0"
echo Starting JoePro Adventures...
echo.
if not exist vendor\autoload.php (
  echo Installing PHP dependencies...
  call composer install
)
if not exist public\build\manifest.json (
  echo Building Tailwind assets...
  call npm install
  call npm run build
)
php artisan optimize:clear
php artisan serve --host=127.0.0.1 --port=8000
