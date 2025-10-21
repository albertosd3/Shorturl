@echo off
echo ========================================
echo  Short URL Manager - Installation Check
echo ========================================
echo.

REM Check PHP
echo [1/3] Checking PHP installation...
where php >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [X] PHP not found!
    echo.
    echo Please install PHP first:
    echo - Download XAMPP: https://www.apachefriends.org/download.html
    echo - OR download PHP: https://windows.php.net/download/
    echo.
    echo After installation, add PHP to your PATH and run this script again.
    pause
    exit /b 1
) else (
    php --version
    echo [OK] PHP is installed
)
echo.

REM Check Composer
echo [2/3] Checking Composer installation...
where composer >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [X] Composer not found!
    echo.
    echo Please install Composer:
    echo - Download from: https://getcomposer.org/download/
    echo - Run Composer-Setup.exe
    echo.
    echo After installation, restart this terminal and run this script again.
    pause
    exit /b 1
) else (
    composer --version
    echo [OK] Composer is installed
)
echo.

REM Check if vendor exists
echo [3/3] Checking project dependencies...
if not exist "vendor" (
    echo [!] Dependencies not installed yet
    echo.
    echo Installing dependencies... This may take a few minutes.
    echo.
    call composer install
    if %ERRORLEVEL% NEQ 0 (
        echo [X] Failed to install dependencies!
        pause
        exit /b 1
    )
) else (
    echo [OK] Dependencies already installed
)
echo.

REM Check if .env exists
if not exist ".env" (
    echo [!] .env file not found, copying from .env.example
    copy .env.example .env
)

REM Check if APP_KEY is set
findstr /C:"APP_KEY=" .env | findstr /C:"APP_KEY=$" >nul
if %ERRORLEVEL% EQU 0 (
    echo [!] Generating application key...
    call php artisan key:generate
)

REM Check database file
if not exist "database\database.sqlite" (
    echo [!] Creating SQLite database file...
    type nul > database\database.sqlite
)

echo.
echo ========================================
echo  Installation Complete!
echo ========================================
echo.
echo Next steps:
echo 1. Run migrations: php artisan migrate
echo 2. Start server:   php artisan serve
echo 3. Open browser:   http://localhost:8000
echo 4. Login with password: G666
echo.
pause