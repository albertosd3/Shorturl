<<<<<<< HEAD
# Shorturl
=======
# Short URL Manager

A modern, elegant short URL application built with Laravel PHP 8.4 and SQLite. Features a dark, classy UI design with comprehensive visitor analytics and link rotation capabilities.

## Features

### 🔗 URL Management
- **Short URLs**: Create custom short links with optional expiration
- **Link Rotators**: Create rotating link groups with multiple rotation strategies:
  - Random rotation
  - Sequential rotation  
  - Weighted rotation

### 📊 Analytics Dashboard
- Real-time visitor statistics (daily, weekly, monthly, yearly)
- Device type tracking (Desktop, Mobile, Tablet)
- Browser analytics
- Country and geographic tracking
- Bot detection and blocking statistics

### 🛡️ Security & Protection
- **StopBot.net Integration**: Advanced bot detection and IP blocking
- **IP Lookup**: Geographic location data for visitors
- **User Agent Analysis**: Detailed device, browser, and OS detection

### 🎨 User Interface
- Dark, elegant theme with glass morphism effects
- Responsive design optimized for all devices
- Simple authentication (Password: G666)
- Interactive charts and data visualizations

## Installation

### Prerequisites
- PHP 8.4+
- Composer
- SQLite extension for PHP

### Setup

1. **Install Dependencies**
   ```bash
   composer install
   ```

2. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **Database Setup**
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```

4. **Storage Permissions**
   ```bash
   chmod -R 775 storage bootstrap/cache
   ```

5. **Run the Application**
   ```bash
   php artisan serve
   ```

6. **Access the Application**
   - Main site: http://localhost:8000
   - Admin login: http://localhost:8000/login
   - Password: G666

## Configuration

### Environment Variables

Key configurations in `.env`:

```env
# Database
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite

# StopBot.net API
STOPBOT_API_KEY=a4b14a7c137b0f5f384206940fa11cee
STOPBOT_BLOCKER_URL=https://stopbot.net/api/blocker
STOPBOT_IPLOOKUP_URL=https://stopbot.net/api/iplookup

# Admin Authentication
ADMIN_PASSWORD=G666
```

## Usage

### Creating Short URLs
1. Login to the admin dashboard
2. Navigate to URL Management
3. Enter the original URL and optional custom code
4. Short URL is generated instantly

### Creating Link Rotators
1. Access URL Management
2. Fill in rotator details (name, type, URLs)
3. Add multiple destination URLs
4. Configure rotation strategy

### Viewing Analytics
- Dashboard shows comprehensive visitor statistics
- Charts display daily click trends
- Tables show top countries, browsers, and devices
- Real-time bot detection statistics

## API Integration

### StopBot.net Features

The application integrates with StopBot.net for:

1. **Bot Blocking**: `https://stopbot.net/api/blocker`
   - Detects and blocks malicious bots
   - User agent analysis
   - IP reputation checking

2. **IP Lookup**: `https://stopbot.net/api/iplookup`  
   - Geographic location data
   - ISP information
   - Timezone detection

## Architecture

### Models
- **ShortUrl**: Individual short links
- **RotatorGroup**: Link rotation containers
- **RotatorUrl**: URLs within rotator groups
- **Click**: Visitor tracking and analytics

### Controllers
- **AuthController**: Simple password authentication
- **DashboardController**: Analytics and statistics
- **UrlController**: URL management and redirection

### Services
- **StopBotService**: API integration for bot detection
- **UserAgentParser**: Device and browser detection

## Security Features

- Password-based admin authentication
- CSRF protection on all forms
- Bot detection and blocking
- IP tracking and geolocation
- User agent analysis
- Secure redirect handling

## Performance

- SQLite database for lightweight deployment
- Optimized database queries with proper indexing
- Caching for frequently accessed data
- Minimal external dependencies

## Development

### Project Structure
```
├── app/
│   ├── Http/Controllers/
│   ├── Models/
│   ├── Services/
│   └── Providers/
├── database/
│   └── migrations/
├── resources/views/
│   ├── auth/
│   ├── dashboard/
│   └── layouts/
└── routes/
```

### Testing
The application is designed for immediate testing:
- No complex setup required
- SQLite database for portability
- Comprehensive error handling

## Deployment

### Ubuntu 24.04 Deployment

1. **System Requirements**
   ```bash
   sudo apt update
   sudo apt install php8.4 php8.4-cli php8.4-sqlite3 php8.4-curl php8.4-mbstring php8.4-xml composer nginx
   ```

2. **Clone and Setup**
   ```bash
   git clone <repository>
   cd short-url-manager
   composer install --optimize-autoloader --no-dev
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Web Server Configuration**
   Configure nginx to point to the `public` directory

## License

MIT License - feel free to use for any purpose.

## Support

This application is designed to be self-contained and maintenance-free. All necessary components are included and configured for immediate use.
>>>>>>> 624099c (Shorturl - first commit)
