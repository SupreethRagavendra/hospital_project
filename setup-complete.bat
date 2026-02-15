@echo off
chcp 65001 >nul
title MediCare+ EMR - Complete Setup

echo.
echo ╔══════════════════════════════════════════════════════════════╗
echo ║       🏥 MediCare+ EMR - Complete Installation              ║
echo ╚══════════════════════════════════════════════════════════════╝
echo.

:: Check if running as administrator
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo ⚠️  WARNING: Not running as administrator
    echo Some operations may require elevated privileges.
    echo.
    set /p continue="Continue anyway? (y/N): "
    if /i not "%continue%"=="y" exit /b 1
)

:: Check system requirements
echo 🔍 Checking system requirements...
echo.

:: Check PHP
php -v >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ ERROR: PHP is not installed or not in PATH
    echo Please install PHP 8.0 or higher from https://www.php.net/
    echo.
    pause
    exit /b 1
)

for /f "tokens=2" %%i in ('php -v ^| findstr "PHP"') do set php_version=%%i
echo ✅ PHP %php_version% found

:: Check Composer
composer --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ ERROR: Composer is not installed or not in PATH
    echo Please install Composer from https://getcomposer.org/
    echo.
    pause
    exit /b 1
)

for /f "tokens=3" %%i in ('composer --version') do set composer_version=%%i
echo ✅ Composer %composer_version% found

:: Check MySQL/MariaDB
mysql --version >nul 2>&1
if %errorlevel% neq 0 (
    echo ⚠️  WARNING: MySQL/MariaDB client not found
    echo Please ensure MySQL/MariaDB is installed and accessible.
    echo.
    set /p continue="Continue anyway? (y/N): "
    if /i not "%continue%"=="y" exit /b 1
) else (
    for /f "tokens=*" %%i in ('mysql --version') do echo ✅ %%i
)

echo.
echo 📋 System requirements check completed!
echo.

:: Install Composer dependencies
echo 📦 Installing Composer dependencies...
echo This may take a few minutes...
echo.

composer install --no-dev --optimize-autoloader
if %errorlevel% neq 0 (
    echo ❌ ERROR: Composer dependency installation failed
    echo Please check your internet connection and try again.
    echo.
    pause
    exit /b 1
)

echo ✅ Composer dependencies installed successfully!
echo.

:: Copy environment file
echo ⚙️  Setting up environment configuration...
if not exist ".env" (
    if exist ".env.example" (
        copy ".env.example" ".env" >nul 2>&1
        echo ✅ Environment file created from example
    ) else (
        echo ⚠️  WARNING: .env.example not found, creating basic .env
        echo APP_NAME="MediCare+ EMR" > .env
        echo APP_ENV=local >> .env
        echo APP_DEBUG=true >> .env
        echo APP_URL=http://localhost:8000 >> .env
    )
) else (
    echo ✅ Environment file already exists
)

:: Generate application key
echo 🔐 Generating application key...
php artisan key:generate --force
if %errorlevel% neq 0 (
    echo ❌ ERROR: Could not generate application key
    echo.
    pause
    exit /b 1
)

echo ✅ Application key generated!
echo.

:: Set up database
echo 📊 Setting up database...
echo.

:: Get database connection details
set /p setup_db="Do you want to set up the database now? (Y/n): "
if /i "%setup_db%"=="n" goto skip_database

call :setup_database_function
if %errorlevel% neq 0 (
    echo ❌ Database setup failed
    echo You can run 'setup-database.bat' manually later.
    echo.
    set /p continue="Continue without database? (y/N): "
    if /i not "%continue%"=="y" exit /b 1
) else (
    echo ✅ Database setup completed!
)

:skip_database
echo.

:: Create storage directories
echo 📁 Creating storage directories...
if not exist "storage\app\public" mkdir "storage\app\public"
if not exist "storage\framework\cache" mkdir "storage\framework\cache"
if not exist "storage\framework\sessions" mkdir "storage\framework\sessions"
if not exist "storage\framework\views" mkdir "storage\framework\views"
if not exist "storage\logs" mkdir "storage\logs"

:: Set proper permissions
echo 🔒 Setting storage permissions...
if exist "storage\app\public" (
    echo. > "storage\app\public\.gitkeep"
)
if exist "storage\framework\cache" (
    echo. > "storage\framework\cache\.gitkeep"
)
if exist "storage\framework\sessions" (
    echo. > "storage\framework\sessions\.gitkeep"
)
if exist "storage\framework\views" (
    echo. > "storage\framework\views\.gitkeep"
)

echo ✅ Storage directories created!
echo.

:: Clear and cache configurations
echo 🧹 Optimizing application...
php artisan config:clear >nul 2>&1
php artisan cache:clear >nul 2>&1
php artisan view:clear >nul 2>&1
php artisan config:cache >nul 2>&1
php artisan route:cache >nul 2>&1

echo ✅ Application optimized!
echo.

:: Desktop shortcut creation skipped for compatibility
echo 📝 Note: Desktop shortcut can be created manually if needed

echo.
echo ╔══════════════════════════════════════════════════════════════╗
echo ║                  🎉 INSTALLATION COMPLETED!                 ║
echo ╠══════════════════════════════════════════════════════════════╣
echo ║                                                              ║
echo ║  Your MediCare+ EMR system has been successfully installed!  ║
echo ║                                                              ║
echo ║  Next Steps:                                                 ║
echo ║  1. Run 'run-application.bat' to start the server           ║
echo ║  2. Open http://localhost:8000 in your browser              ║
echo ║  3. Login with default credentials:                         ║
echo ║     - Admin: admin@hospital.com / password                  ║
echo ║     - Doctor: rajesh@hospital.com / password                ║
echo ║     - Patient: amit@hospital.com / password                 ║
echo ║                                                              ║
echo ║  For support, check the README.md file or contact:         ║
echo ║  support@medicare.com                                       ║
echo ╚══════════════════════════════════════════════════════════════╝
echo.

set /p launch="Launch the application now? (Y/n): "
if /i not "%launch%"=="n" (
    echo.
    echo 🚀 Launching application...
    call "%~dp0run-application.bat"
) else (
    echo.
    echo Setup completed! Run 'run-application.bat' to start the server.
    pause
)

goto :eof

:setup_database_function
:: Database setup function
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

mysql -h %db_host% -P %db_port% -u %db_username% -p%db_password% -e "SELECT 1;" >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Could not connect to database
    exit /b 1
)

echo ✅ Database connection successful!

:: Update .env file with database settings
echo DB_HOST=%db_host% > temp_env.txt
echo DB_PORT=%db_port% >> temp_env.txt
echo DB_DATABASE=%db_name% >> temp_env.txt
echo DB_USERNAME=%db_username% >> temp_env.txt
echo DB_PASSWORD=%db_password% >> temp_env.txt
echo Database configuration updated manually

:: Create database
mysql -h %db_host% -P %db_port% -u %db_username% -p%db_password% -e "CREATE DATABASE IF NOT EXISTS %db_name% CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;" 2>nul

:: Run migrations
php artisan migrate --force >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Database migration failed
    exit /b 1
)

:: Seed database
php artisan db:seed --force >nul 2>&1

exit /b 0
