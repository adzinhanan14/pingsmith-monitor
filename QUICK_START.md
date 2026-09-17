# 🚀 Quick Start Guide - Pingsmith Monitor

## ✨ What's New?

### New Features Added:
1. **📊 Public Status Page** - Share your uptime status with customers
2. **📈 Analytics Dashboard** - Deep insights with charts and trends
3. **🔧 Maintenance Mode** - Schedule maintenance windows
4. **📁 Monitor Groups** - Organize monitors by category
5. **🎨 New UI/UX** - Modern design with consistent color palette

### Color Palette:
- **Primary (Indigo)**: #6366F1 - Main actions, navigation
- **Secondary (Teal)**: #14B8A6 - Success states, UP status
- **Accent (Amber)**: #F59E0B - Warnings, highlights

---

## 📦 Installation (Already Done!)

Migrations have been run and database is ready to use.

---

## 🎯 Getting Started

### 1. Access the Application

- **Dashboard**: `http://your-domain/`
- **Analytics**: `http://your-domain/analytics`
- **Status Page**: `http://your-domain/status/demo-status`

### 2. Create Demo Data (Optional)

```bash
php artisan db:seed --class=StatusPageSeeder
```

This will create:
- Monitor groups (API Services, Web Applications, Infrastructure)
- A public status page with slug "demo-status"
- Assign existing monitors to groups

### 3. Create Your First Monitor

1. Go to Dashboard (`/`)
2. Click "**+ Add Monitor**"
3. Fill in the form:
   - **Name**: e.g., "My Website"
   - **Type**: HTTP/HTTPS or Ping
   - **URL**: e.g., "https://example.com"
   - **Interval**: 60 seconds (recommended)
   - **Timeout**: 10 seconds
4. Click "**Create Monitor**"

### 4. Set Up Monitor Groups

#### Via Database:
```php
use App\Models\MonitorGroup;

MonitorGroup::create([
    'team_id' => $yourTeam->id,
    'name' => 'API Services',
    'description' => 'Backend API endpoints',
    'color' => '#6366f1',  // Indigo
    'sort_order' => 1,
]);
```

#### Assign Monitor to Group:
```php
$monitor->update(['group' => 'API Services']);
```

### 5. Create a Status Page

```php
use App\Models\StatusPage;

StatusPage::create([
    'team_id' => $yourTeam->id,
    'slug' => 'your-company',  // Will be accessible at /status/your-company
    'title' => 'Your Company Status',
    'description' => 'Real-time service status',
    'is_public' => true,
    'branding' => [
        'primary_color' => '#6366f1',
        'secondary_color' => '#14b8a6',
        'accent_color' => '#f59e0b',
    ],
]);
```

Then visit: `http://your-domain/status/your-company`

### 6. Schedule Maintenance

```php
$monitor->update([
    'is_maintenance' => true,
    'maintenance_starts_at' => now(),
    'maintenance_ends_at' => now()->addHours(2),
]);
```

The monitor will show as "Under Maintenance" on the status page and won't trigger alerts.

### 7. View Analytics

Go to `/analytics` to see:
- Uptime percentage
- Average response time
- Response time trends (chart)
- Daily availability (chart)
- Incidents by monitor

Filter by: 24h, 7d, 30d, or 90d

---

## 🎨 Using the New UI

### Dashboard Features:

1. **Modern Sidebar Navigation**
   - Live status indicator
   - Menu items with icons
   - Hover animations

2. **Stats Cards**
   - Total Monitors
   - Services Online
   - Uptime Rate
   - With hover effects

3. **Monitor Management**
   - Add monitors with inline form
   - Delete monitors
   - View status at a glance
   - Color-coded status badges

4. **Quick Access**
   - Recent incidents sidebar
   - Alert channels management
   - Collapsible forms

### Status Page Features:

1. **Public Access**
   - No authentication required
   - Professional appearance
   - Mobile responsive

2. **Visual Elements**
   - Overall system status badge
   - Statistics cards
   - Grouped monitors
   - 90-day uptime history bars
   - Recent incidents timeline

3. **Uptime Bars**
   - Hover to see details
   - Color-coded by uptime %:
     - Green (Teal): 100% uptime
     - Amber: 95-99% uptime
     - Orange: 80-94% uptime
     - Red: <80% uptime

### Analytics Features:

1. **Overview Stats**
   - Uptime percentage
   - Average response time
   - Total checks
   - Failed checks

2. **Charts**
   - Response time trends (line chart)
   - Daily availability (bar chart)
   - Interactive with Chart.js

3. **Incidents Table**
   - Monitors with incidents
   - Incident count
   - Current status

---

## 🔧 Configuration

### Monitor Settings

Each monitor now has additional fields:

```php
[
    'show_on_status_page' => true,  // Show on public page
    'is_maintenance' => false,       // Maintenance mode
    'maintenance_starts_at' => null, // Scheduled start
    'maintenance_ends_at' => null,   // Scheduled end
    'group' => 'API Services',       // Group name
    'sort_order' => 0,               // Display order
]
```

### Status Page Settings

```php
[
    'slug' => 'company-name',        // URL slug
    'title' => 'Company Status',     // Page title
    'description' => 'Status page',  // Description
    'is_public' => true,             // Public access
    'logo_url' => null,              // Optional logo
    'branding' => [                  // Color customization
        'primary_color' => '#6366f1',
        'secondary_color' => '#14b8a6',
        'accent_color' => '#f59e0b',
    ],
]
```

---

## 📱 Mobile Support

All pages are fully responsive:
- Dashboard adapts to mobile screens
- Status page optimized for mobile viewing
- Analytics charts resize automatically
- Touch-friendly interactions

---

## 🎯 Best Practices

### 1. Monitor Organization
- Use groups to categorize related services
- Set meaningful sort orders (lower = higher priority)
- Use clear, descriptive names

### 2. Status Page
- Choose a short, memorable slug
- Add your company logo
- Write a clear description
- Test on mobile devices

### 3. Maintenance Windows
- Schedule in advance
- Set accurate time windows
- Monitors won't send alerts during maintenance
- Status page will show "Under Maintenance"

### 4. Analytics
- Check trends weekly
- Identify patterns in incidents
- Optimize slow monitors
- Use data to improve reliability

---

## 🐛 Troubleshooting

### Status Page Not Loading?
- Check if status page exists for the slug
- Verify `is_public` is set to `true`
- Check if monitors have `show_on_status_page` enabled

### No Data in Analytics?
- Make sure monitors have been checking for a while
- Check if ping logs exist in database
- Try different time periods (7d, 30d)

### Monitors Not Showing in Groups?
- Verify monitor's `group` field matches a MonitorGroup name
- Check if MonitorGroup exists for your team
- Group field is case-sensitive

---

## 📚 Documentation

- **Features**: See `FEATURES.md` for detailed feature documentation
- **Design**: See `DESIGN_GUIDE.md` for UI/UX guidelines
- **API**: Check routes in `routes/web.php` and `routes/api.php`

---

## 🎨 Customization

### Change Colors

Edit Tailwind config in your blade files:

```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: { 500: '#YOUR_COLOR' },
                secondary: { 500: '#YOUR_COLOR' },
                accent: { 500: '#YOUR_COLOR' },
            }
        }
    }
}
```

### Add Custom CSS

Include `resources/css/design-system.css` for pre-built components:
- Buttons (`.btn`, `.btn-primary`, etc.)
- Cards (`.card`, `.card-glass`, etc.)
- Badges (`.badge-success`, `.badge-warning`, etc.)
- Status indicators (`.status-dot-success`, etc.)

---

## 🚀 Next Steps

1. ✅ Explore the new dashboard
2. ✅ Create monitor groups
3. ✅ Set up your status page
4. ✅ View analytics
5. ✅ Schedule a maintenance window
6. ✅ Share your status page with customers

---

## 💡 Tips

- Use the **Analytics** page to identify problematic monitors
- Set up **Alert Channels** to get notified of incidents
- Schedule **Maintenance Mode** during deployments
- Share your **Status Page** URL with customers
- Group related monitors for better organization
- Check the **Design Guide** for consistent styling

---

## 🤝 Support

For questions or issues:
1. Check the documentation files
2. Review the code comments
3. Inspect browser console for errors
4. Check Laravel logs (`storage/logs/laravel.log`)

---

## 🎉 Enjoy!

Your monitoring application now has:
- ✨ Beautiful new UI with consistent colors
- 📊 Public status pages
- 📈 Analytics and insights
- 🔧 Maintenance mode
- 📁 Monitor organization
- 🎨 Professional design

Happy monitoring! 🚀
