@echo off
echo ========================================
echo  Starting Short URL Manager
echo ========================================
echo.

REM Check if setup was run
if not exist "vendor" (
    echo [X] Dependencies not installed!
    echo Please run setup.bat first
    echo.
    pause
    exit /b 1
)

REM Check if migrations were run
if not exist "database\database.sqlite" (
    echo [!] Database not initialized
    echo Creating database file...
    type nul > database\database.sqlite
    echo.
    echo Running migrations...
    call php artisan migrate --force
    echo.
)

echo Starting Laravel development server...
echo.
echo ========================================
echo  Server is running!
echo ========================================
echo.
echo Access the application at:
echo - Main site: http://localhost:8000
echo - Login page: http://localhost:8000/login
echo - Password: G666
echo.
echo Press Ctrl+C to stop the server
echo ========================================
echo.

call php artisan serve