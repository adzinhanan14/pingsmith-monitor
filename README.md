# Pingsmith Monitor 🚀

> **Modern, beautiful uptime monitoring with public status pages and advanced analytics**

Laravel 12 application for multi-tenant uptime and latency monitoring. Features HTTP/HTTPS, TCP port, DNS, and SSL checks, incidents tracking, team roles, and multi-channel alerts (Email/Telegram/Discord).

[![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)](https://laravel.com)
[![License](https://img.shields.io/badge/license-MIT-blue.svg)](LICENSE)

---

## ✨ Features

### Core Monitoring
- ✅ **Multi-tenant architecture** with team-based isolation
- ✅ **Multiple check types**: HTTP/HTTPS, TCP, DNS, SSL certificate
- ✅ **Flexible scheduling**: Custom intervals per monitor
- ✅ **Smart alerting**: Consecutive failure detection
- ✅ **Incident tracking**: Automatic incident creation and resolution

### 🆕 New Features (September 2026)

#### 📊 Public Status Pages
- Beautiful, shareable status pages for your services
- 90-day uptime history visualization
- Custom branding with your colors and logo
- Real-time status updates
- Mobile-responsive design
- [Learn more →](FEATURES.md#1-public-status-page-)

#### 📈 Analytics Dashboard
- Comprehensive uptime statistics
- Response time trends with interactive charts
- Daily availability heatmap
- Incidents tracking and analysis
- Multiple time periods (24h, 7d, 30d, 90d)
- [Learn more →](FEATURES.md#2-analytics-dashboard-)

#### 🔧 Maintenance Mode
- Schedule maintenance windows
- Prevent false alerts during planned downtime
- Automatic status display
- Time-based activation
- [Learn more →](FEATURES.md#3-maintenance-mode-)

#### 📁 Monitor Groups
- Organize monitors by category
- Custom colors per group
- Sorted display on status pages
- Better management and visualization
- [Learn more →](FEATURES.md#4-monitor-groups-)

#### 🎨 Modern UI/UX
- Consistent 3-color design system
- Glass-morphism effects
- Smooth animations and transitions
- Responsive mobile design
- Accessibility-focused
- [View Design Guide →](DESIGN_GUIDE.md)

---

## 🎨 Color Palette

The application uses a beautiful, consistent color palette:

- **Primary (Indigo)** `#6366F1` - Main actions, navigation, key elements
- **Secondary (Teal)** `#14B8A6` - Success states, UP status, positive metrics
- **Accent (Amber)** `#F59E0B` - Warnings, highlights, important information

[Full Design System →](DESIGN_GUIDE.md)

---

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM (for assets)
- PostgreSQL or SQLite
- Redis (recommended for production)

### Installation

1. **Clone and setup environment**
   ```bash
   git clone <repository>
   cd pingsmith-monitor
   cp .env.example.pingsmith .env
   php artisan key:generate
   ```

2. **Configure database**
   ```bash
   # For PostgreSQL (recommended)
   createdb pingsmith
   # Update .env with your database credentials
   
   # For SQLite (development)
   touch database/database.sqlite
   # Set DB_CONNECTION=sqlite in .env
   ```

3. **Install dependencies**
   ```bash
   composer install
   npm install && npm run build
   ```

4. **Run migrations**
   ```bash
   php artisan migrate --seed
   ```

5. **Start services**
   ```bash
   # Terminal 1: Web server
   php artisan serve
   
   # Terminal 2: Queue worker
   php artisan queue:work
   
   # Terminal 3: Scheduler
   php artisan schedule:work
   ```

6. **Seed demo data** (optional)
   ```bash
   php artisan db:seed --class=StatusPageSeeder
   ```

7. **Access the application**
   - Dashboard: `http://localhost:8000`
   - Demo login: `demo@pingsmith.test` / `password`
   - Status page: `http://localhost:8000/status/demo-status`
   - Analytics: `http://localhost:8000/analytics`

[Detailed Quick Start Guide →](QUICK_START.md)

---

## 📖 Documentation

- **[Quick Start Guide](QUICK_START.md)** - Get up and running in minutes
- **[Features Documentation](FEATURES.md)** - Detailed feature descriptions and usage
- **[Design Guide](DESIGN_GUIDE.md)** - UI/UX guidelines and color system
- **[API Documentation](#api)** - RESTful API reference

---

## 🏗️ Architecture

### Operational Design

- **Scheduler**: Every minute, the Laravel scheduler queues due monitor checks
- **Queue Workers**: Process checks in parallel for optimal performance
- **Smart Detection**: Monitors go DOWN after 2 consecutive failures
- **Incident Management**: Automatic incident creation, tracking, and resolution
- **Multi-channel Alerts**: Email, Telegram, Discord notifications

### Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Tailwind CSS 3, Alpine.js (ready)
- **Database**: PostgreSQL (recommended) or SQLite
- **Queue**: Redis (recommended) or database
- **Charts**: Chart.js
- **Icons**: Heroicons (SVG)

---

## 🔌 API

### Authentication

```bash
# Register
POST /api/register
{
  "name": "Your Name",
  "email": "you@example.com",
  "password": "secure_password",
  "password_confirmation": "secure_password"
}

# Login
POST /api/login
{
  "email": "you@example.com",
  "password": "secure_password"
}

# Use token in subsequent requests
Authorization: Bearer <your-token>
```

### Core Endpoints

#### Teams
```bash
GET    /api/teams
POST   /api/teams
POST   /api/teams/{team}/invitations
POST   /api/invitations/{token}/accept
```

#### Monitors
```bash
GET    /api/teams/{team}/monitors
POST   /api/teams/{team}/monitors
GET    /api/teams/{team}/monitors/{monitor}
PATCH  /api/teams/{team}/monitors/{monitor}
DELETE /api/teams/{team}/monitors/{monitor}
POST   /api/teams/{team}/monitors/{monitor}/test
GET    /api/teams/{team}/monitors/{monitor}/logs
GET    /api/teams/{team}/monitors/{monitor}/incidents
```

#### Alert Channels
```bash
GET    /api/teams/{team}/alert-channels
POST   /api/teams/{team}/alert-channels
PATCH  /api/teams/{team}/alert-channels/{channel}
DELETE /api/teams/{team}/alert-channels/{channel}
```

#### Analytics
```bash
GET    /api/teams/{team}/uptime
GET    /api/teams/{team}/incidents
```

### Monitor Configuration

```json
{
  "name": "My Website",
  "type": "http",
  "url": "https://example.com",
  "method": "GET",
  "interval_seconds": 60,
  "timeout_seconds": 10,
  "expected_status_codes": [200, 201, 301, 302],
  "headers": {
    "User-Agent": "Pingsmith Monitor"
  },
  "group": "Web Applications",
  "show_on_status_page": true,
  "is_maintenance": false
}
```

### Alert Channel Configuration

```json
{
  "name": "Email Alerts",
  "type": "email",
  "config": {
    "email": "alerts@example.com"
  },
  "is_enabled": true
}
```

---

## 🎯 Usage Examples

### Create a Monitor Group

```php
use App\Models\MonitorGroup;

MonitorGroup::create([
    'team_id' => $team->id,
    'name' => 'API Services',
    'description' => 'Backend API endpoints',
    'color' => '#6366f1',
    'sort_order' => 1,
]);
```

### Schedule Maintenance

```php
$monitor->update([
    'is_maintenance' => true,
    'maintenance_starts_at' => now(),
    'maintenance_ends_at' => now()->addHours(4),
]);
```

### Create a Status Page

```php
use App\Models\StatusPage;

StatusPage::create([
    'team_id' => $team->id,
    'slug' => 'company-status',
    'title' => 'Company Status Page',
    'description' => 'Real-time status monitoring',
    'is_public' => true,
    'branding' => [
        'primary_color' => '#6366f1',
        'secondary_color' => '#14b8a6',
        'accent_color' => '#f59e0b',
    ],
]);
```

---

## 🔒 Security

### Data Protection
- Encrypted sensitive config data at rest (Laravel encryption)
- Password hashing with bcrypt
- Sanctum token authentication for API
- CSRF protection on web routes

### Alerts Configuration
Supported alert channel types:

1. **Email**: `{ "email": "alerts@example.com" }`
2. **Telegram**: `{ "bot_token": "xxx", "chat_id": "xxx" }`
3. **Discord**: `{ "webhook_url": "https://..." }`

All config data is stored encrypted in PostgreSQL JSONB columns.

---

## 🚢 Production Deployment

### Recommended Setup

1. **Web Server**: Nginx or Apache with PHP-FPM
2. **Database**: PostgreSQL 15+ with regular backups
3. **Queue**: Redis with supervisor for workers
4. **Cache**: Redis for application cache
5. **SSL**: TLS certificate (Let's Encrypt)
6. **Monitoring**: Setup health checks for the monitor itself!

### Environment Configuration

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_DATABASE=pingsmith
DB_USERNAME=your-user
DB_PASSWORD=your-password

REDIS_HOST=your-redis-host
REDIS_PASSWORD=your-password

QUEUE_CONNECTION=redis
CACHE_DRIVER=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
```

### Supervisor Configuration

```ini
[program:pingsmith-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
numprocs=8
redirect_stderr=true
stdout_logfile=/path/to/worker.log
stopwaitsecs=3600

[program:pingsmith-scheduler]
process_name=%(program_name)s
command=php /path/to/artisan schedule:work
autostart=true
autorestart=true
redirect_stderr=true
stdout_logfile=/path/to/scheduler.log
```

### Security Considerations

⚠️ **Important**: Restrict worker egress or use isolated probe nodes before exposing monitor creation publicly, since monitoring necessarily makes outbound requests.

---

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run with coverage
php artisan test --coverage

# Run specific test
php artisan test --filter MonitorTest
```

---

## 📊 Database Schema

### New Tables (v2.0)

#### `status_pages`
- Public status page configuration
- Custom branding and domains
- Team isolation

#### `monitor_groups`
- Monitor categorization
- Custom colors and sorting
- Team-based organization

### Updated Tables

#### `monitors` (new fields)
- `show_on_status_page` - Visibility control
- `is_maintenance` - Maintenance flag
- `maintenance_starts_at` - Schedule start
- `maintenance_ends_at` - Schedule end
- `group` - Group association
- `sort_order` - Display order

---

## 🤝 Contributing

Contributions are welcome! Please follow these guidelines:

1. Fork the repository
2. Create a feature branch
3. Follow the established color palette and design system
4. Add tests for new features
5. Update documentation
6. Submit a pull request

### Code Style

- Follow PSR-12 coding standards
- Use Laravel best practices
- Maintain UI consistency with design guide
- Add appropriate comments

---

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

---

## 🙏 Acknowledgments

- Built with [Laravel](https://laravel.com)
- Styled with [Tailwind CSS](https://tailwindcss.com)
- Charts by [Chart.js](https://www.chartjs.org)
- Icons from [Heroicons](https://heroicons.com)

---

## 📞 Support

- **Documentation**: Check the [documentation files](QUICK_START.md)
- **Issues**: Open an issue on GitHub
- **Email**: support@pingsmith.test (example)

---

## 🗺️ Roadmap

### Planned Features
- [ ] Custom domain support for status pages
- [ ] Email subscriptions to status updates
- [ ] Incident timeline and postmortems
- [ ] SLA tracking and reporting
- [ ] Multi-language support
- [ ] Dark/Light theme toggle
- [ ] Status page embeds
- [ ] RSS feeds for incidents
- [ ] Advanced webhook notifications
- [ ] Bulk operations UI
- [ ] Monitor templates
- [ ] API rate limiting dashboard
- [ ] Mobile apps (iOS/Android)

### Recent Updates (v2.0 - September 2026)
- ✅ Public status pages
- ✅ Analytics dashboard
- ✅ Maintenance mode
- ✅ Monitor groups
- ✅ New UI/UX with 3-color palette
- ✅ 90-day uptime history
- ✅ Interactive charts
- ✅ Responsive design

---

<div align="center">

**[Get Started](QUICK_START.md)** • **[Features](FEATURES.md)** • **[Design](DESIGN_GUIDE.md)**

Made with ❤️ by the Pingsmith team

</div>
