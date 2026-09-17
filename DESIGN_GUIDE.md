# Pingsmith Monitor - Design Guide

## 🎨 Color Palette

### Primary: Indigo (#6366F1)
**Usage**: Main brand color, primary CTAs, active states, key UI elements

| Shade | Hex     | RGB           | Usage                          |
|-------|---------|---------------|--------------------------------|
| 50    | #eef2ff | 238,242,255   | Very light backgrounds         |
| 100   | #e0e7ff | 224,231,255   | Light backgrounds              |
| 200   | #c7d2fe | 199,210,254   | Hover states, borders          |
| 300   | #a5b4fc | 165,180,252   | Disabled states                |
| 400   | #818cf8 | 129,140,248   | Icons, secondary text          |
| **500** | **#6366f1** | **99,102,241** | **Main brand color**      |
| 600   | #4f46e5 | 79,70,229     | Hover states for buttons       |
| 700   | #4338ca | 67,56,202     | Active states                  |
| 800   | #3730a3 | 55,48,163     | Dark accents                   |
| 900   | #312e81 | 49,46,129     | Very dark accents              |

**Examples**:
- Primary buttons
- Active navigation items
- Links
- Progress bars
- Focus states

---

### Secondary: Teal (#14B8A6)
**Usage**: Success states, UP status, positive metrics, confirmation actions

| Shade | Hex     | RGB           | Usage                          |
|-------|---------|---------------|--------------------------------|
| 50    | #f0fdfa | 240,253,250   | Success backgrounds (light)    |
| 100   | #ccfbf1 | 204,251,241   | Success backgrounds            |
| 200   | #99f6e4 | 153,246,228   | Success borders                |
| 300   | #5eead4 | 94,234,212    | Success highlights             |
| 400   | #2dd4bf | 45,212,191    | Success icons                  |
| **500** | **#14b8a6** | **20,184,166** | **Success primary**       |
| 600   | #0d9488 | 13,148,136    | Success hover                  |
| 700   | #0f766e | 15,118,110    | Success active                 |
| 800   | #115e59 | 17,94,89      | Success dark                   |
| 900   | #134e4a | 19,78,74      | Success very dark              |

**Examples**:
- "UP" status badges
- Success messages
- Positive metrics
- Uptime indicators
- Operational status

---

### Accent: Amber (#F59E0B)
**Usage**: Warnings, highlights, maintenance mode, important information

| Shade | Hex     | RGB           | Usage                          |
|-------|---------|---------------|--------------------------------|
| 50    | #fffbeb | 255,251,235   | Warning backgrounds (light)    |
| 100   | #fef3c7 | 254,243,199   | Warning backgrounds            |
| 200   | #fde68a | 253,230,138   | Warning borders                |
| 300   | #fcd34d | 252,211,77    | Warning highlights             |
| 400   | #fbbf24 | 251,191,36    | Warning icons                  |
| **500** | **#f59e0b** | **245,158,11** | **Warning primary**       |
| 600   | #d97706 | 217,119,6     | Warning hover                  |
| 700   | #b45309 | 180,83,9      | Warning active                 |
| 800   | #92400e | 146,64,14     | Warning dark                   |
| 900   | #78350f | 120,53,15     | Warning very dark              |

**Examples**:
- Warning messages
- Maintenance mode indicators
- Important highlights
- Pending states
- Attention-grabbing elements

---

### Neutral: Slate
**Usage**: Text, backgrounds, borders, neutral states

| Shade | Hex     | RGB           | Usage                          |
|-------|---------|---------------|--------------------------------|
| 50    | #f8fafc | 248,250,252   | Very light backgrounds         |
| 100   | #f1f5f9 | 241,245,249   | Light mode backgrounds         |
| 200   | #e2e8f0 | 226,232,240   | Borders (light)                |
| 300   | #cbd5e1 | 203,213,225   | Borders                        |
| 400   | #94a3b8 | 148,163,184   | Secondary text                 |
| 500   | #64748b | 100,116,139   | Body text                      |
| 600   | #475569 | 71,85,105     | Dark text                      |
| 700   | #334155 | 51,65,85      | Card backgrounds               |
| 800   | #1e293b | 30,41,59      | Dark backgrounds               |
| 900   | #0f172a | 15,23,42      | Darkest backgrounds            |

---

## 🎯 Usage Guidelines

### Color Hierarchy

1. **Primary (Indigo)**: Use for the most important actions and brand elements
2. **Secondary (Teal)**: Use for positive feedback and success states
3. **Accent (Amber)**: Use sparingly for warnings and highlights
4. **Neutral (Slate)**: Use for text, backgrounds, and structural elements

### Dos and Don'ts

#### ✅ DO:
- Use primary color for main CTAs and navigation
- Use secondary color for all success/operational states
- Use accent color for warnings and important information
- Maintain consistent color usage across the app
- Use lighter shades for backgrounds, darker for text
- Consider accessibility and contrast ratios

#### ❌ DON'T:
- Mix accent colors randomly
- Use more than one primary action color per section
- Use bright colors for large background areas
- Ignore contrast requirements (WCAG AA minimum)
- Use color as the only indicator (consider icons/text)

---

## 📐 Spacing System

Based on Tailwind's spacing scale (4px base unit):

| Name | Value | Pixels | Usage                    |
|------|-------|--------|--------------------------|
| 0    | 0     | 0px    | Reset                    |
| 1    | 0.25rem | 4px  | Tiny gaps                |
| 2    | 0.5rem | 8px   | Small gaps               |
| 3    | 0.75rem | 12px | Default small spacing    |
| 4    | 1rem | 16px   | Default spacing          |
| 5    | 1.25rem | 20px | Medium spacing          |
| 6    | 1.5rem | 24px  | Large spacing           |
| 8    | 2rem | 32px   | XL spacing              |
| 10   | 2.5rem | 40px  | 2XL spacing             |
| 12   | 3rem | 48px   | 3XL spacing             |

---

## 🔤 Typography

### Font Family
- **Primary**: System fonts stack for optimal performance
- **Monospace**: For code and technical content

### Font Sizes

| Class    | Size   | Line Height | Usage                  |
|----------|--------|-------------|------------------------|
| text-xs  | 0.75rem | 1rem       | Small labels, captions |
| text-sm  | 0.875rem | 1.25rem   | Body text (secondary)  |
| text-base | 1rem  | 1.5rem     | Body text (primary)    |
| text-lg  | 1.125rem | 1.75rem   | Emphasis               |
| text-xl  | 1.25rem | 1.75rem    | Section headings       |
| text-2xl | 1.5rem | 2rem       | Page headings          |
| text-3xl | 1.875rem | 2.25rem   | Hero headings          |
| text-4xl | 2.25rem | 2.5rem    | Display text           |

### Font Weights

| Class       | Weight | Usage                    |
|-------------|--------|--------------------------|
| font-normal | 400    | Body text                |
| font-medium | 500    | Emphasis, labels         |
| font-semibold | 600  | Subheadings, buttons     |
| font-bold   | 700    | Headings                 |
| font-black  | 900    | Logo, display text       |

---

## 🎭 Component Patterns

### Buttons

```html
<!-- Primary -->
<button class="btn btn-primary">Primary Action</button>

<!-- Secondary -->
<button class="btn btn-secondary">Secondary Action</button>

<!-- Accent -->
<button class="btn btn-accent">Warning Action</button>

<!-- Outline -->
<button class="btn btn-outline">Tertiary Action</button>

<!-- Danger -->
<button class="btn btn-danger">Delete</button>
```

### Cards

```html
<!-- Basic Card -->
<div class="card p-6">Content</div>

<!-- Glass Card -->
<div class="card-glass p-6">Content</div>

<!-- Gradient Cards -->
<div class="card-gradient-primary p-6">Primary themed</div>
<div class="card-gradient-secondary p-6">Success themed</div>
<div class="card-gradient-accent p-6">Warning themed</div>
```

### Status Badges

```html
<span class="badge badge-success">Operational</span>
<span class="badge badge-warning">Degraded</span>
<span class="badge badge-error">Down</span>
<span class="badge badge-info">Checking</span>
<span class="badge badge-neutral">Unknown</span>
```

### Status Indicators

```html
<span class="status-dot status-dot-success"></span>
<span class="status-dot status-dot-warning"></span>
<span class="status-dot status-dot-error"></span>
<span class="status-dot status-dot-info"></span>
```

### Icons Containers

```html
<div class="icon-container-primary">
    <svg>...</svg>
</div>
<div class="icon-container-secondary">
    <svg>...</svg>
</div>
<div class="icon-container-accent">
    <svg>...</svg>
</div>
```

### Form Inputs

```html
<input type="text" class="input" placeholder="Enter text...">
<select class="select">...</select>
<textarea class="textarea"></textarea>
```

### Alerts

```html
<div class="alert-success">Success message</div>
<div class="alert-warning">Warning message</div>
<div class="alert-error">Error message</div>
<div class="alert-info">Info message</div>
```

---

## 🌓 Dark Mode (Current Implementation)

The design is currently optimized for dark mode:

- **Background**: Slate-900 to Slate-800 gradients
- **Cards**: Semi-transparent slate with backdrop blur
- **Text**: White to Slate-400 hierarchy
- **Borders**: White with 10% opacity

### Light Mode (Future)

When implementing light mode:
- Invert slate scales (use 50-300 for backgrounds)
- Adjust color opacity for proper contrast
- Test all components for readability
- Update border colors to darker shades

---

## ✨ Effects & Interactions

### Shadows

```css
/* Subtle */
shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05)

/* Default */
shadow: 0 1px 3px rgba(0, 0, 0, 0.1)

/* Medium */
shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1)

/* Large */
shadow-2xl: 0 25px 50px rgba(0, 0, 0, 0.25)

/* Primary Glow */
shadow-primary: 0 8px 16px rgba(99, 102, 241, 0.25)

/* Secondary Glow */
shadow-secondary: 0 8px 16px rgba(20, 184, 166, 0.25)
```

### Transitions

- **Default**: `200ms cubic-bezier(0.4, 0, 0.2, 1)`
- **Slow**: `300ms ease`
- **Fast**: `150ms ease`

### Hover States

- Scale: `hover:scale-105`
- Translate: `hover:translate-y-[-2px]`
- Brightness: `hover:brightness-110`
- Opacity: `hover:opacity-80`

---

## 📱 Responsive Design

### Breakpoints

| Name | Size  | Usage                        |
|------|-------|------------------------------|
| sm   | 640px | Mobile landscape             |
| md   | 768px | Tablets                      |
| lg   | 1024px | Desktop                     |
| xl   | 1280px | Large desktop               |
| 2xl  | 1536px | Extra large screens         |

### Mobile-First Approach

Always design for mobile first, then enhance for larger screens:

```html
<div class="text-sm md:text-base lg:text-lg">
    Responsive text
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    Responsive grid
</div>
```

---

## ♿ Accessibility

### Contrast Ratios

- **Normal text**: Minimum 4.5:1 (WCAG AA)
- **Large text**: Minimum 3:1 (WCAG AA)
- **UI components**: Minimum 3:1

### Focus States

Always provide visible focus indicators:

```html
<button class="focus-ring">Accessible Button</button>
```

### Screen Readers

Use semantic HTML and ARIA labels:

```html
<span class="sr-only">Status:</span>
<span class="badge badge-success">Operational</span>
```

---

## 🎬 Animation Guidelines

### When to Animate

- ✅ State changes (hover, active, focus)
- ✅ Loading states
- ✅ Status transitions
- ✅ Micro-interactions
- ❌ Don't animate text (readability)
- ❌ Don't over-animate (distracting)

### Duration Guidelines

- **Micro-interactions**: 150-200ms
- **State changes**: 200-300ms
- **Entrance animations**: 300-500ms
- **Complex transitions**: 400-600ms

---

## 📊 Data Visualization

### Charts

Use the established color palette:

- **Line charts**: Primary (Indigo)
- **Bar charts**: Secondary (Teal) for positive, Accent (Amber) for warnings
- **Area charts**: Primary with gradient
- **Pie charts**: Rotate through palette shades

### Status Colors

- **UP/Operational**: Secondary-500 (#14B8A6)
- **Degraded**: Accent-500 (#F59E0B)
- **DOWN**: Red-500 (#EF4444)
- **Maintenance**: Accent-400 (#FBBF24)
- **Unknown**: Slate-500 (#64748B)

---

## 🔧 Implementation Tips

### Using Tailwind Config

```javascript
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: {
                    // Indigo shades
                },
                secondary: {
                    // Teal shades
                },
                accent: {
                    // Amber shades
                }
            }
        }
    }
}
```

### CSS Variables

Use CSS variables for dynamic theming:

```css
:root {
    --primary-500: #6366f1;
    --secondary-500: #14b8a6;
    --accent-500: #f59e0b;
}
```

### Blade Components (Recommendation)

Create reusable Blade components:

```php
<x-button type="primary">Click me</x-button>
<x-badge status="success">UP</x-badge>
<x-card class="p-6">Content</x-card>
```

---

## 📖 Resources

### Color Tools
- [Coolors](https://coolors.co/) - Color palette generator
- [Contrast Checker](https://webaim.org/resources/contrastchecker/) - WCAG compliance
- [Color Hunt](https://colorhunt.co/) - Color inspiration

### Design Inspiration
- [Dribbble](https://dribbble.com/) - UI designs
- [Behance](https://www.behance.net/) - Creative work
- [Awwwards](https://www.awwwards.com/) - Web design excellence

### Documentation
- [Tailwind CSS](https://tailwindcss.com/) - Utility-first CSS
- [WCAG Guidelines](https://www.w3.org/WAI/WCAG21/quickref/) - Accessibility
- [MDN Web Docs](https://developer.mozilla.org/) - Web standards

---

**Version**: 1.0  
**Last Updated**: September 2026  
**Maintained by**: Pingsmith Team
