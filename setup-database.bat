@echo off
chcp 65001 >nul
title MediCare+ EMR - Database Setup

echo.
echo ╔══════════════════════════════════════════════════════════════╗
echo ║         🏥 MediCare+ EMR - Database Setup Utility           ║
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

:: Get database connection details
echo 📋 Please enter your MySQL database connection details:
echo.

set /p db_host="📍 Database Host [default: 127.0.0.1]: "
if "%db_host%"=="" set db_host=127.0.0.1

set /p db_port="🔌 Database Port [default: 3306]: "
if "%db_port%"=="" set db_port=3306

set /p db_username="👤 Database Username [default: root]: "
if "%db_username%"=="" set db_username=root

set /p db_password="🔒 Database Password (press Enter for none): "

set /p db_name="🗃️  Database Name [default: medicare_emr]: "
if "%db_name%"=="" set db_name=medicare_emr

echo.
echo 🔍 Testing database connection...

:: Test database connection
mysql -h %db_host% -P %db_port% -u %db_username% -p%db_password% -e "SELECT 1;" >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ ERROR: Could not connect to database
    echo Please check your connection details and try again.
    echo.
    pause
    exit /b 1
)

echo ✅ Database connection successful!
echo.

:: Create database if it doesn't exist
echo 📦 Creating database '%db_name%'...
mysql -h %db_host% -P %db_port% -u %db_username% -p%db_password% -e "CREATE DATABASE IF NOT EXISTS %db_name% CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul
if %errorlevel% neq 0 (
    echo ❌ ERROR: Could not create database
    echo Please check database permissions.
    echo.
    pause
    exit /b 1
)

echo ✅ Database created successfully!
echo.

:: Update .env file
echo ⚙️  Updating environment configuration...
if exist ".env" (
    copy ".env" ".env.backup" >nul 2>&1
)

:: Create or update .env file
echo APP_NAME="MediCare+ EMR" > .env
echo APP_ENV=local >> .env
echo APP_KEY= >> .env
echo APP_DEBUG=true >> .env
echo APP_URL=http://localhost:8000 >> .env
echo. >> .env
echo DB_CONNECTION=mysql >> .env
echo DB_HOST=%db_host% >> .env
echo DB_PORT=%db_port% >> .env
echo DB_DATABASE=%db_name% >> .env
echo DB_USERNAME=%db_username% >> .env
echo DB_PASSWORD=%db_password% >> .env
echo. >> .env
echo BROADCAST_DRIVER=log >> .env
echo CACHE_DRIVER=file >> .env
echo FILESYSTEM_DISK=local >> .env
echo QUEUE_CONNECTION=sync >> .env
echo SESSION_DRIVER=file >> .env
echo SESSION_LIFETIME=120 >> .env
echo. >> .env
echo MEMCACHED_HOST=127.0.0.1 >> .env
echo. >> .env
echo REDIS_HOST=127.0.0.1 >> .env
echo REDIS_PASSWORD=null >> .env
echo REDIS_PORT=6379 >> .env
echo. >> .env
echo MAIL_MAILER=smtp >> .env
echo MAIL_HOST=mailpit >> .env
echo MAIL_PORT=1025 >> .env
echo MAIL_USERNAME=null >> .env
echo MAIL_PASSWORD=null >> .env
echo MAIL_ENCRYPTION=null >> .env
echo MAIL_FROM_ADDRESS="hello@example.com" >> .env
echo MAIL_FROM_NAME="${APP_NAME}" >> .env

echo ✅ Environment configuration updated!
echo.

:: Generate application key
echo 🔐 Generating application key...
php artisan key:generate --force >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ ERROR: Could not generate application key
    echo.
    pause
    exit /b 1
)

echo ✅ Application key generated!
echo.

:: Clear cache and config
echo 🧹 Clearing cache and configuration...
php artisan config:clear >nul 2>&1
php artisan cache:clear >nul 2>&1
php artisan view:clear >nul 2>&1

:: Run migrations
echo 🗃️  Running database migrations...
php artisan migrate --force >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ ERROR: Database migration failed
    echo Please check the error message above.
    echo.
    pause
    exit /b 1
)

echo ✅ Database migrations completed!
echo.

:: Seed database with sample data
echo 🌱 Seeding database with sample data...
php artisan db:seed --force >nul 2>&1
if %errorlevel% neq 0 (
    echo ⚠️  WARNING: Database seeding failed (optional)
) else (
    echo ✅ Database seeding completed!
)

echo.
echo ╔══════════════════════════════════════════════════════════════╗
echo ║                    🎉 SETUP COMPLETED!                     ║
echo ╠══════════════════════════════════════════════════════════════╣
echo ║  Database: %db_name%                                        ║
echo ║  Host: %db_host%:%db_port%                                  ║
echo ║  Username: %db_username%                                     ║
echo ║                                                              ║
echo ║  Default Accounts:                                           ║
echo ║  ┌─────────────────────────────────────────────────────────┐ ║
echo ║  │ Admin:    admin@hospital.com    │ Password: password │ ║
echo ║  │ Doctor:   rajesh@hospital.com   │ Password: password │ ║
echo ║  │ Patient:  amit@hospital.com     │ Password: password │ ║
echo ║  └─────────────────────────────────────────────────────────┘ ║
echo ║                                                              ║
echo ║  Next Steps:                                                 ║
echo ║  1. Run 'run-application.bat' to start the server           ║
echo ║  2. Open http://localhost:8000 in your browser              ║
echo ╚══════════════════════════════════════════════════════════════╝
echo.

pause
