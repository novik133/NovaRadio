# NovaRadio

![Version](https://img.shields.io/badge/version-2.0-blue?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.2%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![License](https://img.shields.io/badge/license-NovaRadio%20Freeware-yellow?style=for-the-badge)
[![PayPal](https://img.shields.io/badge/Support-PayPal-00457C?style=for-the-badge&logo=paypal&logoColor=white)](https://paypal.me/novik133)

Professional internet radio CMS built with Laravel 12. A complete solution for managing your online radio station with AzuraCast integration, modern admin panel, content management, PWA support, and full GDPR compliance.

[🌐 Website](https://kamilnowicki.com) · [📧 Contact](mailto:kamil@kamilnowicki.com) · [💻 GitHub](https://github.com/novik133/NovaRadia)

---

## ✨ Features

### Core Features
- **🎵 AzuraCast Integration** - Real-time now playing, song history, listener count, station info
- **🎧 HTML5 Audio Player** - Responsive player with volume control and play/pause
- **⚡ Web Installer** - Modern installation wizard with requirements check
- **🎨 Theme System** - WordPress-like theme management with upload, preview, activation
- **📝 Page Management** - Create and edit pages with TinyMCE rich text editor
- **👥 Team Management** - Add team members with photos, roles, and social links
- **📅 Schedule System** - Display radio show schedules by day

### Content Management
- **📰 Articles/News System** - Full blog functionality with categories and tags
- **🎤 DJ Profiles** - Extended DJ profiles with bio, genre, equipment, social links
- **🎫 Events/Gigs** - Event management with dates, venues, tickets
- **📁 Media Manager** - Upload, organize images in folders (like WordPress)
- **🏷️ Categories & Tags** - Organize content with taxonomy system
- **✍️ TinyMCE Editor** - Rich text editing in all content fields

### Admin Panel Features
- **⚙️ Settings Panel** - Multi-tab settings (General, Contact, Social, SEO, Appearance, Streaming)
- **🖼️ Hero Image Upload** - Customize homepage hero background
- **🎨 Branding Controls** - Upload logos, favicon, set theme colors
- **🔔 Update Notifications** - Badge in admin header when new version available
- **👤 My Profile Page** - DJs can manage their own profiles and photos
- **📊 Dashboard** - Overview of all content and statistics

### Legal & Compliance
- **⚖️ Legal Pages** - Privacy Policy, Terms of Service, Cookie Policy (GDPR compliant)
- **🍪 Cookie Consent** - Modern granular cookie banner with toggle switches
- **👤 Data Controller Info** - Administrator details in legal pages

### Technical Features
- **🔍 Full SEO** - Meta tags, Open Graph, Twitter Cards, friendly URLs
- **📱 PWA Support** - Web app manifest and service worker
- **� Update System** - GitHub-based update checking with changelog
- **🔐 Admin Panel** - Role-based access (admin, editor, moderator)
- **📊 Dashboard** - Overview of pages, team, shows, and users

---

## 📋 Requirements

- **PHP 8.2+** (tested on PHP 8.5)
- **MySQL 8.0+** or **MariaDB 10.6+**
- **Composer 2.5+**
- **Nginx** or **Apache** with mod_rewrite
- **SSL Certificate** (recommended for production)
- **AzuraCast** server (for streaming features)

### PHP Extensions Required
- `pdo` and `pdo_mysql`
- `mbstring`
- `openssl`
- `tokenizer`
- `xml`
- `curl`
- `zip` (for theme uploads)
- `fileinfo`
- `json`
- `gd` or `imagick` (for image processing)
- `exif` (for image metadata)

---

## 🚀 Installation

### Method 1: Web Installer (Recommended)

1. **Upload files** to your web server (public_html or wwwroot)

2. **Create database** in MySQL/MariaDB:
   ```sql
   CREATE DATABASE novaradio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

3. **Set permissions**:
   ```bash
   chmod -R 755 storage
   chmod -R 755 bootstrap/cache
   mkdir -p storage/framework/{views,cache,sessions,testing}
   mkdir -p storage/logs storage/app/public
   ```

4. **Install dependencies**:
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

5. **Run installer** - Visit `https://your-domain.com/install`

6. **Follow wizard**:
   - Enter database credentials
   - Create admin account
   - (Optional) Configure AzuraCast connection

### Method 2: Manual Installation

```bash
# 1. Clone or upload files to server
cd /var/www/novaradio

# 2. Install dependencies
composer install --no-dev --optimize-autoloader

# 3. Create environment file
cp .env.example .env

# 4. Set permissions
chmod -R 755 storage bootstrap/cache
mkdir -p storage/framework/{views,cache,sessions,testing}

# 5. Generate app key
php artisan key:generate

# 6. Configure database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=novaradio
# DB_USERNAME=root
# DB_PASSWORD=yourpassword

# 7. Run migrations and seeders
php artisan migrate --force
php artisan db:seed --force

# 8. Set APP_INSTALLED=true in .env
```

---

## 📁 Project Structure

```
NovaRadio/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/        # Admin panel controllers
│   │   │   │   ├── ArticleController.php
│   │   │   │   ├── CategoryController.php
│   │   │   │   ├── DjProfileController.php
│   │   │   │   ├── EventController.php
│   │   │   │   ├── MediaController.php
│   │   │   │   ├── SettingsController.php
│   │   │   │   ├── TagController.php
│   │   │   │   ├── TeamController.php
│   │   │   │   ├── ThemeController.php
│   │   │   │   └── UpdateController.php
│   │   │   ├── Api/          # API controllers
│   │   │   ├── Install/      # Installer controller
│   │   │   └── ...           # Frontend controllers
│   │   └── Middleware/
│   │       └── AdminMiddleware.php
│   ├── Models/               # Eloquent models
│   │   ├── Article.php
│   │   ├── Category.php
│   │   ├── DjProfile.php
│   │   ├── Event.php
│   │   ├── Page.php
│   │   ├── Setting.php
│   │   ├── Tag.php
│   │   ├── TeamMember.php
│   │   └── User.php
│   ├── Providers/
│   │   └── AppServiceProvider.php
│   └── Services/             # Business logic
│       ├── AzuraCastService.php
│       ├── SeoService.php
│       └── UpdateService.php
├── bootstrap/
├── config/
├── database/
│   ├── migrations/           # All database migrations
│   └── seeders/
│       └── DatabaseSeeder.php # Sample data
├── public/                   # Web root
│   ├── images/               # Local images storage
│   │   ├── articles/
│   │   ├── events/
│   │   ├── hero/
│   │   └── team/
│   ├── js/tinymce/           # Self-hosted TinyMCE
│   ├── pwa/                  # PWA manifest and sw.js
│   └── themes/
│       └── default/          # Frontend theme
│           ├── css/
│           └── js/
├── resources/
│   └── views/
│       ├── admin/            # Admin panel views
│       ├── install/          # Installer views
│       └── themes/           # Frontend themes
├── routes/
│   ├── web.php               # Web routes
│   └── console.php
├── storage/                  # Cache, logs, sessions
│   └── app/public/media      # Media Manager uploads
└── vendor/                   # Composer dependencies
```

---

## ⚙️ Configuration

### AzuraCast Integration

Add to your `.env` file:
```env
AZURACAST_BASE_URL=https://your-azuracast.com
AZURACAST_API_KEY=your_api_key_here
AZURACAST_STATION_ID=1
```

To get API key:
1. Log in to AzuraCast admin panel
2. Go to My Account → API Keys
3. Generate new key

### GitHub Updates

Add to your `.env` file:
```env
GITHUB_REPO=novik133/NovaRadia
GITHUB_TOKEN=your_github_token (optional, for private repos)
```

---

## 🔒 Security

- **CSRF Protection** - All forms protected by default
- **XSS Protection** - Blade escaping enabled
- **SQL Injection** - Query builder and Eloquent ORM
- **Password Hashing** - Bcrypt with proper rounds
- **Admin Middleware** - Role-based access control

---

## 🆘 Troubleshooting

### Permission Denied Errors
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Cache Path Error
```bash
mkdir -p storage/framework/{views,cache,sessions,testing}
chmod -R 777 storage/framework/views
```

### 500 Error After Installation
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### Composer Install Fails
```bash
# Clear composer cache
composer clear-cache

# Install without scripts first
composer install --no-scripts

# Then run scripts manually
php artisan package:discover
```

---

## 🔄 Updating

The system includes a built-in update checker:

1. Go to **Admin Panel → Updates**
2. Click **Check for Updates**
3. If update available, review changelog
4. Click **Install Update**

**Note:** Updates are pulled from GitHub releases. Create a backup before updating.

---

## 🔒 License

**NovaRadio Freeware License**

Copyright (c) 2025-2026 Kamil 'Novik' Nowicki

**Permissions:**
- ✅ Free to use for personal and commercial projects
- ✅ Install on unlimited servers
- ✅ Create multiple radio stations

**Restrictions:**
- ❌ Modification of source code is **NOT allowed**
- ❌ Redistribution is **NOT allowed**
- ❌ Removal of copyright notices is **NOT allowed**

**Premium Add-ons:**
The author reserves the right to introduce paid add-ons, themes, and extensions for NovaRadio.

For licensing inquiries: [kamil@kamilnowicki.com](mailto:kamil@kamilnowicki.com)

---

## 👤 Author

**Kamil 'Novik' Nowicki**

- 🌐 Website: [kamilnowicki.com](https://kamilnowicki.com)
- 📧 Email: [kamil@kamilnowicki.com](mailto:kamil@kamilnowicki.com)
- 💻 GitHub: [github.com/novik133](https://github.com/novik133)

---

## 🙏 Credits

Built with:
- [Laravel 12](https://laravel.com) - PHP Framework
- [AzuraCast](https://azuracast.com) - Radio Management
- [TinyMCE](https://www.tiny.cloud) - Rich Text Editor (self-hosted)
- [Font Awesome 6](https://fontawesome.com) - Icons

---

## 📸 Image Management

NovaRadio includes a complete media management system with role-based access:

| Feature | Who Can Access | Location |
|---------|---------------|----------|
| **Hero Image** | Admin only | Settings → Appearance |
| **Logos/Favicon** | Admin only | Settings → Appearance |
| **DJ/Team Photos** | Admin + Self | Team → Edit OR My Profile |
| **Article Images** | Authors + Admin | Articles → Browse Media |
| **Media Library** | All admins | Media Library menu |
| **Theme Screenshots** | Auto-generated | Theme directory |

### Storage Structure
```
public/images/
├── hero/           # Homepage hero image (admin only)
├── team/           # DJ/Team member photos
├── articles/       # Article featured images
├── events/         # Event/gig images
└── default/        # Default theme assets

storage/app/public/
└── media/          # Media Manager uploads (organized by date)
```

---

<p align="center">Made with ❤️ in Poland</p>
