# Changelog

All notable changes to Pingsmith Monitor will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [2.0.0] - 2026-09-17

### 🎉 Major Release: UI/UX Overhaul & New Features

This release brings a complete UI/UX redesign with a consistent 3-color palette and several powerful new features for better monitoring and customer communication.

### ✨ Added

#### Public Status Pages
- **Status Page System**: Create public-facing status pages for your services
  - Customizable slug URLs (e.g., `/status/your-company`)
  - 90-day uptime history visualization with color-coded bars
  - Real-time status updates
  - Recent incidents timeline
  - Mobile-responsive design
  - Custom branding support (colors, logo)
  - Monitor grouping on status pages
  - Overall system status indicator

#### Analytics Dashboard
- **Analytics System**: Comprehensive insights into your monitoring data
  - Uptime percentage tracking
  - Average response time calculations
  - Total checks and failed checks statistics
  - Response time trends (line chart with Chart.js)
  - Daily availability heatmap (bar chart)
  - Incidents by monitor table
  - Time period filters (24h, 7d, 30d, 90d)
  - Interactive charts with hover details

#### Maintenance Mode
- **Maintenance Scheduling**: Plan and communicate maintenance windows
  - Enable/disable maintenance mode per monitor
  - Schedule maintenance start and end times
  - Automatic maintenance detection method
  - Visual indicators on status pages
  - Alert suppression during maintenance
  - Maintenance badge display

#### Monitor Groups
- **Organization System**: Categorize and organize monitors
  - Create custom monitor groups
  - Assign colors to groups
  - Sort order control
  - Grouped display on status pages
  - Description support
  - Team-based isolation

#### Enhanced Monitor Management
- New monitor fields:
  - `show_on_status_page` - Control public visibility
  - `is_maintenance` - Maintenance mode flag
  - `maintenance_starts_at` - Scheduled start time
  - `maintenance_ends_at` - Scheduled end time
  - `group` - Group assignment
  - `sort_order` - Custom display order

#### New UI/UX Design
- **Design System**: Consistent 3-color palette
  - Primary (Indigo #6366F1): Main actions, navigation
  - Secondary (Teal #14B8A6): Success states, UP status
  - Accent (Amber #F59E0B): Warnings, highlights
  
- **Visual Improvements**:
  - Modern glass-morphism effects
  - Gradient backgrounds with radial overlays
  - Smooth transitions and animations
  - Hover effects on interactive elements
  - Consistent rounded corners (2xl, 3xl)
  - Better spacing and typography hierarchy
  - Enhanced card designs
  - Improved color contrast

- **Component Library**:
  - Reusable button styles
  - Consistent badge system
  - Status indicators with pulse animations
  - Icon containers with background colors
  - Form input styles
  - Alert message components
  - Loading states
  - Custom scrollbar styling

- **Dashboard Redesign**:
  - Modern sidebar navigation
  - Live status indicators
  - Interactive stat cards with hover effects
  - Collapsible forms for cleaner UI
  - Better mobile responsiveness
  - Quick access to features
  - Improved monitor list display

#### New Models
- `StatusPage` - Public status page configuration
- `MonitorGroup` - Monitor categorization

#### New Controllers
- `StatusPageController` - Public status page rendering
- `AnalyticsController` - Analytics data and charts

#### New Routes
- `GET /status/{slug}` - Public status page (no auth required)
- `GET /analytics` - Analytics dashboard (auth required)

#### New Migrations
- `add_status_page_and_maintenance_fields_to_monitors_table`
- `create_monitor_groups_table`
- `create_status_pages_table`

#### New Seeders
- `StatusPageSeeder` - Demo data for status pages and groups

#### Documentation
- `FEATURES.md` - Comprehensive feature documentation
- `DESIGN_GUIDE.md` - Complete UI/UX design system guide
- `QUICK_START.md` - Quick start guide for new users
- `design-system.css` - Reusable CSS components
- `.env.example.new` - Updated environment configuration
- Updated `README.md` - Enhanced with new features

### 🔄 Changed

#### Database
- Extended `monitors` table with 6 new fields
- Added database indexes for better query performance
- Improved data structure for analytics

#### UI/UX
- Complete dashboard redesign
- New color scheme applied throughout
- Improved mobile responsiveness
- Better accessibility (contrast ratios, focus states)
- Enhanced loading states
- Smoother animations

#### Controllers
- Updated `DashboardController` to use new dashboard view
- Enhanced query performance with eager loading
- Added sort order support

#### Views
- Created `dashboard-new.blade.php` with modern design
- Created `status-page/` views directory
- Created `analytics/` views directory
- Improved component organization

### 🐛 Fixed

- Monitor query performance optimization
- Mobile layout issues
- Color contrast accessibility issues
- Form validation feedback visibility

### 📦 Dependencies

- Chart.js 4.4.0 (new) - For analytics charts
- Tailwind CSS 3.x (existing, enhanced usage)
- Heroicons SVG (existing)

### 🔒 Security

- Proper authorization checks for team-based resources
- Public status page access control
- Input validation for new fields
- XSS protection in status page rendering

### ⚡ Performance

- Optimized database queries with indexes
- Eager loading for related models
- Efficient uptime calculation algorithms
- Chart data aggregation optimization
- Cached status page data (ready for implementation)

### 📱 Mobile

- Fully responsive status pages
- Mobile-optimized dashboard
- Touch-friendly interactions
- Adaptive layouts for all screen sizes

---

## [1.0.0] - 2026-08-17

### Initial Release

#### Core Features
- Multi-tenant architecture with team support
- HTTP/HTTPS monitoring
- TCP port monitoring
- DNS monitoring
- SSL certificate monitoring
- Incident tracking and management
- Alert channels (Email, Telegram, Discord)
- API with Sanctum authentication
- Queue-based monitoring system
- Scheduler for automated checks
- Smart failure detection (2 consecutive failures)
- Monitor logs and history
- Team invitations
- User authentication

#### Models
- `User` - User accounts
- `Team` - Multi-tenant teams
- `TeamMember` - Team membership
- `TeamInvitation` - Team invites
- `Monitor` - Monitor configuration
- `PingLog` - Check results
- `Incident` - Downtime tracking
- `AlertChannel` - Notification channels

#### API Endpoints
- Authentication (register, login, logout)
- Team management (CRUD, invitations)
- Monitor management (CRUD, test, logs, incidents)
- Alert channel management (CRUD)
- Uptime statistics
- Incident history

#### Tech Stack
- Laravel 12
- PHP 8.2
- PostgreSQL/SQLite
- Redis
- Sanctum
- Tailwind CSS

---

## Upgrade Guide

### From 1.x to 2.0

#### Database

Run the new migrations:

```bash
php artisan migrate
```

This will add:
- 6 new fields to `monitors` table
- `status_pages` table
- `monitor_groups` table

#### Environment

Optional new configuration in `.env`:

```env
STATUS_PAGE_ENABLED=true
ANALYTICS_RETENTION_DAYS=90
MAINTENANCE_MODE_ENABLED=true
```

#### Code Changes

If you've customized views:
- Update to use new color classes (primary-500, secondary-500, accent-500)
- Review component usage (buttons, badges, cards)
- Check mobile responsiveness

If you've extended models:
- `Monitor` model has new fillable fields
- New `isInMaintenance()` method on Monitor
- New relationships: `monitorGroup()`

#### Optional Steps

1. Seed demo data:
   ```bash
   php artisan db:seed --class=StatusPageSeeder
   ```

2. Create your first status page through the database or API

3. Organize existing monitors into groups

4. Review and apply new design system classes

#### Breaking Changes

- None! This release is backward compatible
- Old dashboard still available at `dashboard.blade.php`
- All existing API endpoints unchanged
- Database changes are additive only

---

## Links

- [Repository](https://github.com/your-org/pingsmith-monitor)
- [Documentation](README.md)
- [Feature Guide](FEATURES.md)
- [Design Guide](DESIGN_GUIDE.md)

---

## Notes

### Version 2.0 Highlights

This major version represents a significant evolution in both functionality and design:

1. **Customer Communication**: Status pages enable transparent communication with customers
2. **Data Insights**: Analytics provide actionable insights for reliability improvements  
3. **Operational Excellence**: Maintenance mode prevents false alerts
4. **Better Organization**: Monitor groups improve management at scale
5. **Professional Design**: Modern UI/UX enhances user experience

### Future Versions

See [Roadmap](README.md#-roadmap) in README for planned features.

---

**Note**: This project follows semantic versioning. Major versions may include breaking changes, minor versions add functionality, and patch versions fix bugs.
