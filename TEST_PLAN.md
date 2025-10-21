# Short URL Manager - Comprehensive Testing Plan

## Test Status: PENDING
**Date:** 2024
**Tester:** System Check

---

## 1. ENVIRONMENT SETUP TESTS

### 1.1 Prerequisites Check
- [ ] **PHP Version**: Verify PHP 8.4+ is installed
  - Command: `php -v`
  - Expected: PHP 8.4.x or higher
  
- [ ] **Composer**: Verify Composer is installed
  - Command: `composer --version`
  - Expected: Composer version 2.x
  
- [ ] **PHP Extensions**: Check required extensions
  - [ ] SQLite: `php -m | findstr sqlite`
  - [ ] cURL: `php -m | findstr curl`
  - [ ] mbstring: `php -m | findstr mbstring`
  - [ ] OpenSSL: `php -m | findstr openssl`
  - [ ] PDO: `php -m | findstr pdo`
  - [ ] JSON: `php -m | findstr json`

### 1.2 Project Setup
- [ ] **Dependencies Installation**
  - Command: `composer install`
  - Expected: vendor/ directory created with all packages
  - Status: ❌ FAILED - vendor/ directory missing
  
- [ ] **Environment File**
  - Check: `.env` file exists
  - Status: ✅ PASSED - .env file found
  - Verify required variables:
    - [ ] APP_KEY
    - [ ] DB_CONNECTION=sqlite
    - [ ] DB_DATABASE path
    - [ ] STOPBOT_API_KEY
    - [ ] ADMIN_PASSWORD
  
- [ ] **Application Key**
  - Command: `php artisan key:generate`
  - Expected: APP_KEY set in .env
  
- [ ] **Database File**
  - Check: `database/database.sqlite` exists
  - Status: ✅ PASSED - database.sqlite found
  
- [ ] **Storage Permissions**
  - Directories writable:
    - [ ] storage/framework/cache
    - [ ] storage/framework/sessions
    - [ ] storage/framework/views
    - [ ] storage/logs

### 1.3 Database Setup
- [ ] **Run Migrations**
  - Command: `php artisan migrate`
  - Expected: All 4 migration files executed successfully
  - Tables to verify:
    - [ ] short_urls
    - [ ] rotator_groups
    - [ ] rotator_urls
    - [ ] clicks

---

## 2. ARTISAN COMMANDS TESTS

### 2.1 Basic Artisan Commands
- [ ] `php artisan --version` - Check Laravel version
- [ ] `php artisan list` - List all available commands
- [ ] `php artisan route:list` - Display all registered routes
- [ ] `php artisan config:cache` - Cache configuration
- [ ] `php artisan config:clear` - Clear configuration cache
- [ ] `php artisan cache:clear` - Clear application cache
- [ ] `php artisan view:clear` - Clear compiled views

### 2.2 Database Commands
- [ ] `php artisan migrate:status` - Check migration status
- [ ] `php artisan migrate:fresh` - Fresh migration (destructive)
- [ ] `php artisan db:show` - Show database information
- [ ] `php artisan schema:dump` - Dump database schema

### 2.3 Development Server
- [ ] `php artisan serve` - Start development server
  - Expected: Server running on http://127.0.0.1:8000
  - Test access to homepage

---

## 3. ROUTE TESTS

### 3.1 Public Routes
- [ ] **GET /login** - Login page
  - Expected: 200 OK, login form displayed
  - Controller: AuthController@showLogin
  
- [ ] **POST /login** - Login submission
  - Test with correct password (G666)
  - Test with incorrect password
  - Expected: Redirect to dashboard on success
  
- [ ] **POST /logout** - Logout
  - Expected: Session cleared, redirect to login

### 3.2 Short URL Redirect
- [ ] **GET /{shortCode}** - Redirect to original URL
  - Test with valid short code (6 alphanumeric chars)
  - Test with invalid short code
  - Test with expired URL
  - Test with inactive URL
  - Expected: 302 redirect or 404 error

### 3.3 Admin Routes (Requires Authentication)
- [ ] **GET /** - Dashboard home
  - Expected: 200 OK, dashboard displayed
  - Controller: DashboardController@index
  
- [ ] **GET /dashboard** - Dashboard
  - Expected: Same as above
  
- [ ] **GET /urls** - URL management page
  - Expected: 200 OK, list of URLs and rotators
  - Controller: UrlController@index
  
- [ ] **POST /urls/short** - Create short URL
  - Test with valid URL
  - Test with custom code
  - Test with expiration date
  - Test with invalid URL
  - Expected: JSON response with short URL
  
- [ ] **POST /urls/rotator** - Create rotator
  - Test with 2+ URLs
  - Test rotation types: sequential, random, weighted
  - Test with custom code
  - Test with invalid data
  - Expected: JSON success response
  
- [ ] **PUT /urls/{type}/{id}/toggle** - Toggle URL status
  - Test with short_url type
  - Test with rotator type
  - Expected: JSON response with new status

---

## 4. CONTROLLER TESTS

### 4.1 AuthController
- [ ] **showLogin()** - Display login form
- [ ] **login()** - Process login
  - Validate password
  - Set session
  - Redirect appropriately
- [ ] **logout()** - Clear session

### 4.2 DashboardController
- [ ] **index()** - Display dashboard
  - Load statistics
  - Display charts
  - Show recent clicks
  - Calculate analytics

### 4.3 UrlController
- [ ] **index()** - List URLs and rotators
  - Pagination working
  - Click counts displayed
  
- [ ] **createShortUrl()** - Create short URL
  - Validation working
  - Short code generation
  - Custom code handling
  - Database insertion
  
- [ ] **createRotator()** - Create rotator group
  - Validation working
  - Transaction handling
  - Multiple URLs creation
  
- [ ] **redirect()** - Handle redirects
  - Short URL lookup
  - Rotator lookup
  - Click tracking
  - Expiration check
  
- [ ] **toggleStatus()** - Toggle active status
  - Update database
  - Return correct response

---

## 5. MODEL TESTS

### 5.1 ShortUrl Model
- [ ] **Fillable attributes** - All fields can be mass assigned
- [ ] **Casts** - Boolean and datetime casts working
- [ ] **clicks() relationship** - HasMany to Click model
- [ ] **isExpired()** - Correctly checks expiration
- [ ] **incrementClicks()** - Increments click counter
- [ ] **generateShortCode()** - Generates unique 6-char code

### 5.2 RotatorGroup Model
- [ ] **Fillable attributes** - All fields can be mass assigned
- [ ] **Casts** - Boolean cast working
- [ ] **rotatorUrls() relationship** - HasMany to RotatorUrl
- [ ] **clicks() relationship** - HasMany to Click
- [ ] **getNextUrl()** - Returns next URL based on rotation type
  - [ ] Random rotation
  - [ ] Sequential rotation
  - [ ] Weighted rotation
- [ ] **incrementClicks()** - Increments click counter
- [ ] **generateShortCode()** - Generates unique code (checks both tables)

### 5.3 RotatorUrl Model
- [ ] **Fillable attributes** - All fields can be mass assigned
- [ ] **rotatorGroup() relationship** - BelongsTo RotatorGroup

### 5.4 Click Model
- [ ] **Fillable attributes** - All fields can be mass assigned
- [ ] **Casts** - JSON and boolean casts working
- [ ] **shortUrl() relationship** - BelongsTo ShortUrl
- [ ] **rotatorGroup() relationship** - BelongsTo RotatorGroup

---

## 6. SERVICE TESTS

### 6.1 StopBotService
- [ ] **Constructor** - Loads API credentials from config
- [ ] **checkBlocker()** - Bot detection API call
  - Test with valid IP and user agent
  - Test with timeout
  - Test with API failure
  - Verify error handling
  - Check logging
  
- [ ] **ipLookup()** - IP geolocation API call
  - Test with valid IP
  - Test with timeout
  - Test with API failure
  - Verify error handling
  - Check logging

### 6.2 UserAgentParser
- [ ] **parse()** - Parse user agent string
  - Test with desktop user agent
  - Test with mobile user agent
  - Test with tablet user agent
  - Test with bot user agent
  - Test with empty/null user agent
  - Verify device detection
  - Verify browser detection
  - Verify OS detection

---

## 7. MIDDLEWARE TESTS

### 7.1 AdminAuth Middleware
- [ ] **handle()** - Check authentication
  - Test without session
  - Test with valid session
  - Test session expiration
  - Verify redirect to login
  - Verify access granted

---

## 8. VIEW TESTS

### 8.1 Layout
- [ ] **layouts/app.blade.php** - Base layout renders
  - CSS loaded
  - JavaScript loaded
  - Navigation working
  - Responsive design

### 8.2 Auth Views
- [ ] **auth/login.blade.php** - Login form
  - Form displays correctly
  - CSRF token present
  - Password field masked
  - Submit button working

### 8.3 Dashboard Views
- [ ] **dashboard/index.blade.php** - Dashboard
  - Statistics displayed
  - Charts rendering
  - Tables populated
  - Responsive layout
  
- [ ] **dashboard/urls/index.blade.php** - URL management
  - Short URLs table
  - Rotators table
  - Create forms working
  - Toggle buttons functional
  - Pagination working

---

## 9. DATABASE MIGRATION TESTS

### 9.1 Migration Files
- [ ] **2024_01_01_000001_create_short_urls_table.php**
  - Table created with correct columns
  - Indexes created
  - Constraints working
  
- [ ] **2024_01_01_000002_create_rotator_groups_table.php**
  - Table created with correct columns
  - Indexes created
  
- [ ] **2024_01_01_000003_create_rotator_urls_table.php**
  - Table created with correct columns
  - Foreign key to rotator_groups
  
- [ ] **2024_01_01_000004_create_clicks_table.php**
  - Table created with correct columns
  - Foreign keys working
  - Indexes created

---

## 10. INTEGRATION TESTS

### 10.1 Complete User Flow
- [ ] **Admin Login Flow**
  1. Visit /login
  2. Enter password
  3. Submit form
  4. Redirected to dashboard
  
- [ ] **Create Short URL Flow**
  1. Login as admin
  2. Navigate to URL management
  3. Fill in URL form
  4. Submit
  5. Verify short URL created
  6. Test redirect
  
- [ ] **Create Rotator Flow**
  1. Login as admin
  2. Navigate to URL management
  3. Fill in rotator form with multiple URLs
  4. Submit
  5. Verify rotator created
  6. Test rotation working
  
- [ ] **Click Tracking Flow**
  1. Create short URL
  2. Visit short URL
  3. Verify redirect
  4. Check click recorded in database
  5. Verify analytics updated

### 10.2 API Integration
- [ ] **StopBot API Integration**
  - Test blocker API call
  - Test IP lookup API call
  - Verify data stored in clicks table
  - Check error handling for API failures

---

## 11. SECURITY TESTS

### 11.1 Authentication
- [ ] Unauthenticated users cannot access admin routes
- [ ] Session timeout working
- [ ] CSRF protection on forms
- [ ] Password not exposed in logs

### 11.2 Input Validation
- [ ] URL validation working
- [ ] Short code validation (alphanumeric, length)
- [ ] SQL injection prevention
- [ ] XSS prevention in views

### 11.3 Data Protection
- [ ] Sensitive data not logged
- [ ] API keys not exposed
- [ ] Database queries parameterized

---

## 12. PERFORMANCE TESTS

### 12.1 Database Queries
- [ ] N+1 query problems identified
- [ ] Eager loading used where appropriate
- [ ] Indexes utilized

### 12.2 Response Times
- [ ] Homepage loads < 1 second
- [ ] Dashboard loads < 2 seconds
- [ ] Redirects happen < 500ms
- [ ] API calls timeout appropriately

---

## 13. ERROR HANDLING TESTS

### 13.1 Application Errors
- [ ] 404 page for invalid routes
- [ ] 404 for invalid short codes
- [ ] 500 error handling
- [ ] Validation errors displayed properly

### 13.2 External Service Failures
- [ ] StopBot API timeout handled
- [ ] StopBot API error handled
- [ ] Application continues without external services

---

## 14. CONFIGURATION TESTS

### 14.1 Config Files
- [ ] **config/app.php** - Application config loaded
- [ ] **config/database.php** - Database config correct
- [ ] **config/services.php** - StopBot config loaded
- [ ] **config/session.php** - Session config working

### 14.2 Environment Variables
- [ ] All required .env variables present
- [ ] Default values working
- [ ] Config caching working

---

## CRITICAL ISSUES FOUND

1. ❌ **BLOCKER**: Vendor directory missing - Dependencies not installed
   - Impact: Application cannot run
   - Fix: Run `composer install`

2. ⚠️ **WARNING**: Need to verify migrations have been run
   - Impact: Database tables may not exist
   - Fix: Run `php artisan migrate`

3. ⚠️ **WARNING**: Need to verify PHP version compatibility
   - Impact: May have compatibility issues
   - Fix: Ensure PHP 8.4+ is installed

---

## TESTING PRIORITY

### Priority 1 (Critical - Must Fix)
1. Install composer dependencies
2. Verify PHP version
3. Run database migrations
4. Test basic application startup

### Priority 2 (High - Core Functionality)
1. Test authentication system
2. Test short URL creation and redirect
3. Test rotator creation and rotation
4. Test click tracking

### Priority 3 (Medium - Features)
1. Test dashboard analytics
2. Test StopBot integration
3. Test user agent parsing
4. Test all CRUD operations

### Priority 4 (Low - Polish)
1. Test UI/UX
2. Test responsive design
3. Test error messages
4. Performance optimization

---

## NEXT STEPS

1. Run `composer install` to install dependencies
2. Verify `.env` configuration
3. Run `php artisan migrate` to set up database
4. Start development server with `php artisan serve`
5. Execute manual tests for each route
6. Verify external API integrations
7. Test complete user flows
8. Document any issues found

---

## NOTES

- This is a Laravel 11 application requiring PHP 8.4+
- Uses SQLite database for simplicity
- Integrates with StopBot.net API for bot detection
- Admin password is hardcoded as "G666" (should be changed in production)
- Application uses session-based authentication (not Laravel's built-in auth)
