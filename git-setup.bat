@echo off
echo ========================================
echo  Git Setup - Short URL Manager
echo ========================================
echo.

REM Check if Git is installed
where git >nul 2>nul
if %ERRORLEVEL% NEQ 0 (
    echo [X] Git not found!
    echo.
    echo Please install Git first:
    echo - Download from: https://git-scm.com/download/win
    echo - Run installer
    echo - Restart terminal
    echo.
    pause
    exit /b 1
) else (
    git --version
    echo [OK] Git is installed
)
echo.

REM Check if already a git repository
if exist ".git" (
    echo [!] This is already a Git repository
    echo.
    git remote -v
    echo.
    echo Current branch:
    git branch
    echo.
    pause
    exit /b 0
)

echo [1/4] Initializing Git repository...
git init
if %ERRORLEVEL% NEQ 0 (
    echo [X] Failed to initialize Git repository
    pause
    exit /b 1
)
echo [OK] Git repository initialized
echo.

echo [2/4] Adding all files to Git...
git add .
if %ERRORLEVEL% NEQ 0 (
    echo [X] Failed to add files
    pause
    exit /b 1
)
echo [OK] Files added
echo.

echo [3/4] Creating initial commit...
git commit -m "Initial commit: Laravel Short URL Manager with Analytics, Bot Detection, and Link Rotator"
if %ERRORLEVEL% NEQ 0 (
    echo [X] Failed to create commit
    pause
    exit /b 1
)
echo [OK] Initial commit created
echo.

echo [4/4] Setup GitHub repository...
echo.
echo ========================================
echo  NEXT STEPS:
echo ========================================
echo.
echo 1. Create a new repository on GitHub:
echo    https://github.com/new
echo.
echo 2. Repository settings:
echo    - Name: shorturl (atau nama lain)
echo    - Private or Public: Choose
echo    - DON'T initialize with README
echo.
echo 3. After creating, run these commands:
echo.
echo    git remote add origin https://github.com/USERNAME/REPO.git
echo    git branch -M main
echo    git push -u origin main
echo.
echo 4. Replace USERNAME/REPO with your actual repository!
echo.
echo ========================================
echo.
echo Want to add remote now? (You need the GitHub URL)
echo.
set /p add_remote="Add remote repository? (y/n): "

if /i "%add_remote%"=="y" (
    echo.
    set /p repo_url="Enter GitHub repository URL: "
    git remote add origin !repo_url!
    git branch -M main
    echo.
    echo Remote added! Now push to GitHub:
    echo   git push -u origin main
    echo.
    set /p do_push="Push now? (y/n): "
    if /i "!do_push!"=="y" (
        git push -u origin main
        echo.
        echo [OK] Code pushed to GitHub!
        echo.
        echo NEXT: Connect to Laravel Forge
        echo 1. Login to Laravel Forge
        echo 2. Connect GitHub account
        echo 3. Create site and install repository
        echo 4. Follow GIT_SETUP_GUIDE.md for complete instructions
    )
)

echo.
pause