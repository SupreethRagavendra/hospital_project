@echo off
chcp 65001 >nul
title MediCare+ EMR - Application Server

echo.
echo ╔══════════════════════════════════════════════════════════════╗
echo ║       🏥 MediCare+ EMR - Application Server                ║
echo ╚══════════════════════════════════════════════════════════════╝
echo.

:: Check if PHP is available
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ ERROR: PHP is not installed or not in PATH
    echo Please install PHP 8.0 or higher and try again.
    echo.
    pause
    exit /b 1
)

:: Check if Composer dependencies are installed
if not exist "vendor\autoload.php" (
    echo 📦 Installing Composer dependencies...
    composer install --no-dev --optimize-autoloader
    if %errorlevel% neq 0 (
        echo ❌ ERROR: Composer dependency installation failed
        echo Please run 'setup-complete.bat' first.
        echo.
        pause
        exit /b 1
    )
    echo ✅ Dependencies installed successfully!
    echo.
)

:: Check if .env file exists
if not exist ".env" (
    echo ⚠️  WARNING: .env file not found
    echo Please run 'setup-database.bat' first to configure the database.
    echo.
    pause
    exit /b 1
)

:: Check if application key is set
php artisan key:check >nul 2>&1
if %errorlevel% neq 0 (
    echo 🔐 Generating application key...
    php artisan key:generate --force >nul 2>&1
)

:: Clear cache
echo 🧹 Clearing application cache...
php artisan config:clear >nul 2>&1
php artisan cache:clear >nul 2>&1
php artisan view:clear >nul 2>&1

:: Check database connection
echo 🔍 Checking database connection...
php artisan migrate:status >nul 2>&1
if %errorlevel% neq 0 (
    echo ⚠️  WARNING: Database connection issue detected
    echo Please ensure your database is properly configured.
    echo.
    set /p continue="Continue anyway? (y/N): "
    if /i not "%continue%"=="y" exit /b 1
)

echo ✅ All checks passed!
echo.

:: Start the development server
echo 🚀 Starting Laravel development server...
echo 📍 Server will be available at: http://localhost:8000
echo 🌐 Opening browser in 3 seconds...
echo.

:: Wait 3 seconds before opening browser
timeout /t 3 /nobreak >nul

:: Open browser
start http://localhost:8000

:: Start PHP development server
echo.
echo ╔══════════════════════════════════════════════════════════════╗
echo ║                    🌐 SERVER RUNNING                        ║
echo ╠══════════════════════════════════════════════════════════════╣
echo ║  URL: http://localhost:8000                                 ║
echo ║  Press CTRL+C to stop the server                            ║
echo ╚══════════════════════════════════════════════════════════════╝
echo.

php artisan serve

echo.
echo 🛑 Server stopped.
pause
