# Pingsmith Monitor - New Features & UI/UX

## 🎨 Color Palette

The application now uses a consistent 3-color palette throughout the interface:

### Primary Color: **Indigo** (#6366F1)
- Used for: Main CTAs, active navigation, primary buttons
- Represents: Reliability, trust, professionalism
- Shades: 50-900 (from lightest to darkest)

### Secondary Color: **Teal** (#14B8A6)
- Used for: Success states, UP status, positive metrics
- Represents: Health, activity, success
- Shades: 50-900

### Accent Color: **Amber** (#F59E0B)
- Used for: Warnings, highlights, maintenance mode
- Represents: Attention, important information
- Shades: 50-900

## ✨ New Features

### 1. Public Status Page 📊

**Description**: Share your monitoring status with customers and stakeholders through a beautiful public status page.

**Features**:
- Public URL with custom slug (e.g., `/status/your-company`)
- Real-time monitor status display
- 90-day uptime history visualization
- Recent incidents timeline
- Grouped monitors by category
- Customizable branding (colors, logo)
- Mobile-responsive design

**Access**: `/status/{slug}`

**How to Create**:
```php
StatusPage::create([
    'team_id' => $team->id,
    'slug' => 'your-company',
    'title' => 'Your Company Status',
    'description' => 'Real-time status and uptime monitoring',
    'is_public' => true,
    'branding' => [
        'primary_color' => '#6366f1',
        'secondary_color' => '#14b8a6',
        'accent_color' => '#f59e0b',
    ],
]);
```

### 2. Analytics Dashboard 📈

**Description**: Get deep insights into your monitoring data with comprehensive analytics and visualizations.

**Features**:
- **Uptime Statistics**: Overall uptime percentage, total checks, failed checks
- **Response Time Trends**: Line chart showing average latency over time
- **Availability Heatmap**: Daily uptime percentage visualization
- **Incidents by Monitor**: Table showing monitors with most incidents
- **Time Period Filters**: 24h, 7d, 30d, 90d views
- **Chart.js Integration**: Interactive, responsive charts

**Access**: `/analytics`

**Metrics Tracked**:
- Uptime percentage
- Average response time
- Total checks performed
- Failed checks count
- Response time trends
- Daily availability
- Incident frequency by monitor

### 3. Maintenance Mode 🔧

**Description**: Schedule maintenance windows to prevent false alerts during planned downtime.

**Features**:
- Enable/disable maintenance mode per monitor
- Schedule maintenance start and end times
- Automatic status display during maintenance
- Excludes maintenance periods from uptime calculations
- Visual indicators on status pages

**Database Fields**:
- `is_maintenance` (boolean)
- `maintenance_starts_at` (timestamp)
- `maintenance_ends_at` (timestamp)

**Usage**:
```php
$monitor->update([
    'is_maintenance' => true,
    'maintenance_starts_at' => now(),
    'maintenance_ends_at' => now()->addHours(4),
]);

// Check if monitor is in maintenance
if ($monitor->isInMaintenance()) {
    // Skip checks or handle differently
}
```

### 4. Monitor Groups 📁

**Description**: Organize monitors into logical groups for better management and visualization.

**Features**:
- Group monitors by category (e.g., "API Services", "Infrastructure")
- Assign custom colors to groups
- Sort order control
- Grouped display on status pages
- Filter and manage by group

**Model**: `MonitorGroup`

**Database Fields**:
- `name` - Group name
- `description` - Optional description
- `color` - Hex color code
- `sort_order` - Display order

**Usage**:
```php
// Create a group
$group = MonitorGroup::create([
    'team_id' => $team->id,
    'name' => 'API Services',
    'description' => 'Backend API endpoints',
    'color' => '#6366f1',
    'sort_order' => 1,
]);

// Assign monitor to group
$monitor->update(['group' => 'API Services']);
```

### 5. Enhanced Monitor Management

**New Monitor Fields**:
- `show_on_status_page` - Control visibility on public status page
- `is_maintenance` - Maintenance mode flag
- `maintenance_starts_at` - Scheduled maintenance start
- `maintenance_ends_at` - Scheduled maintenance end
- `group` - Group name (foreign key to monitor_groups.name)
- `sort_order` - Custom ordering

**Features**:
- Bulk operations support (ready for implementation)
- Pause/resume monitoring
- Custom sorting
- Group assignment
- Status page visibility control

## 🎨 UI/UX Improvements

### Design System

1. **Consistent Color Usage**
   - Primary: Main actions, navigation highlights
   - Secondary: Success states, positive indicators
   - Accent: Warnings, important highlights

2. **Typography**
   - Clear hierarchy with font sizes
   - Consistent font weights
   - Proper line heights for readability

3. **Spacing**
   - Consistent padding and margins
   - Proper whitespace for breathing room
   - Responsive grid system

4. **Components**
   - Rounded corners (xl, 2xl, 3xl) for modern look
   - Glass-morphism effects with backdrop blur
   - Smooth transitions and hover states
   - Consistent card styles

### Visual Improvements

1. **Dashboard**
   - Modern sidebar navigation
   - Live status indicators with pulse animations
   - Gradient backgrounds with radial overlays
   - Interactive stat cards with hover effects
   - Collapsible forms for cleaner UI

2. **Status Page**
   - Clean, professional public-facing design
   - 90-day uptime history bars
   - Color-coded status indicators
   - Responsive grid layout
   - Real-time status updates

3. **Analytics**
   - Interactive charts with Chart.js
   - Time period selector
   - Color-coded metrics
   - Responsive table design

### Accessibility

- High contrast text on backgrounds
- Clear focus states
- Proper ARIA labels (ready for enhancement)
- Keyboard navigation support
- Mobile-responsive design

## 🚀 Quick Start

### 1. Run Migrations

```bash
php artisan migrate
```

### 2. Seed Demo Data

```bash
php artisan db:seed --class=StatusPageSeeder
```

### 3. Access Features

- **Dashboard**: `/`
- **Analytics**: `/analytics`
- **Status Page**: `/status/demo-status`

## 📊 Database Schema

### New Tables

#### `status_pages`
```sql
- id
- team_id (foreign key)
- slug (unique)
- title
- description (nullable)
- logo_url (nullable)
- is_public (default: true)
- custom_domain (json, nullable)
- branding (json, nullable)
- timestamps
```

#### `monitor_groups`
```sql
- id
- team_id (foreign key)
- name
- description (nullable)
- color (default: '#6366f1')
- sort_order (default: 0)
- timestamps
```

### Updated Tables

#### `monitors` - New Fields
```sql
- show_on_status_page (boolean, default: true)
- is_maintenance (boolean, default: false)
- maintenance_starts_at (timestamp, nullable)
- maintenance_ends_at (timestamp, nullable)
- group (string, nullable)
- sort_order (integer, default: 0)
```

## 🔧 API Endpoints (Ready for Implementation)

### Status Pages
```
GET    /api/teams/{team}/status-pages
POST   /api/teams/{team}/status-pages
PATCH  /api/teams/{team}/status-pages/{page}
DELETE /api/teams/{team}/status-pages/{page}
```

### Monitor Groups
```
GET    /api/teams/{team}/monitor-groups
POST   /api/teams/{team}/monitor-groups
PATCH  /api/teams/{team}/monitor-groups/{group}
DELETE /api/teams/{team}/monitor-groups/{group}
```

### Maintenance Mode
```
POST   /api/teams/{team}/monitors/{monitor}/maintenance/start
POST   /api/teams/{team}/monitors/{monitor}/maintenance/end
```

## 📝 Customization

### Branding Your Status Page

```php
$statusPage->update([
    'branding' => [
        'primary_color' => '#your-color',
        'secondary_color' => '#your-color',
        'accent_color' => '#your-color',
        'custom_css' => 'body { font-family: "Your Font"; }',
    ],
    'logo_url' => 'https://your-domain.com/logo.png',
]);
```

### Custom Domain (Future Enhancement)

```php
$statusPage->update([
    'custom_domain' => [
        'domain' => 'status.yourcompany.com',
        'ssl_enabled' => true,
    ],
]);
```

## 🎯 Best Practices

1. **Monitor Organization**
   - Use groups to organize related monitors
   - Set meaningful sort orders
   - Use descriptive names

2. **Maintenance Windows**
   - Schedule maintenance in advance
   - Set accurate start/end times
   - Communicate to users via status page

3. **Status Page**
   - Keep slug simple and memorable
   - Use your brand colors
   - Add clear descriptions
   - Test on mobile devices

4. **Analytics**
   - Review trends regularly
   - Identify problematic monitors
   - Optimize based on response times

## 🔜 Future Enhancements

- [ ] Custom domain support for status pages
- [ ] Email subscriptions to status updates
- [ ] Incident management system
- [ ] SLA tracking and reporting
- [ ] Multi-language support
- [ ] Dark/Light theme toggle
- [ ] Status page embeds
- [ ] RSS feeds for incidents
- [ ] Webhook notifications
- [ ] Advanced filtering and search

## 💡 Tips

1. **Performance**: Use caching for status pages with high traffic
2. **Monitoring**: Set up alerts for critical services only
3. **Groups**: Don't over-categorize - keep it simple
4. **Analytics**: Focus on trends, not individual checks
5. **Maintenance**: Schedule during low-traffic hours

## 🤝 Contributing

When adding new features:
1. Follow the established color palette
2. Maintain UI consistency
3. Add proper documentation
4. Include database migrations
5. Create factory and seeder examples

---

**Built with Laravel 12 + Tailwind CSS**

For questions or feature requests, please open an issue on GitHub.
