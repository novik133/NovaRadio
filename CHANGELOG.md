# NovaRadio Changelog

All notable changes to NovaRadio project.

## [2.0.3] - 2026-03-05

### New Features
- **Enhanced Media Library**: Complete media management system with database integration
  - All images now stored in `public/images` and tracked in database
  - Media Library panel in admin with upload, browse, and delete functionality
  - Support for folders and file organization
  - Image metadata (alt text, title, caption) stored in database
  - Copy URL functionality for easy image insertion
  
### Improvements
- **Local Image Storage**: All Unsplash images now downloaded and stored locally
  - About page images (studio, equipment, DJ)
  - Contact page image
  - Privacy Policy page image
  - Terms of Service page image
  - Cookie Policy page image
  - Event images (jazz night, summer festival)
  - Article welcome image
  - All images automatically registered in media library during seeding
  
- **Database Integration**: MediaController now uses `media` table instead of filesystem only
  - Files tracked with metadata (filename, path, size, mime type)
  - Proper foreign key relationships
  - Soft deletes support

- **Admin Panel UI Consistency**: Unified styling across all admin views
  - Schedule views redesigned to match other admin panels
  - Events views redesigned with image preview and better layout
  - Articles views updated with consistent styling
  - All views now use same card, table, and button styles
  
- **Image Display Fixes**: Added `asset()` helper to all image paths
  - Team member photos now display correctly on all pages
  - DJ profile photos fixed on individual DJ pages
  - Event images display properly in listings
  - Article featured images show correctly
  
### Bug Fixes
- Fixed migration order issues with foreign key constraints
- Fixed `articles` table migration to explicitly reference `categories` and `tags` tables
- Fixed DatabaseSeeder to download images to `public/images` instead of storage
- Fixed team member photo paths in DatabaseSeeder (alex-chen.jpg instead of alex.jpg)
- Fixed article welcome image download from Unsplash
- Fixed avatar upload to save in `public/images/avatars` and register in media library
- Fixed installer to use correct GitHub repository name `novik133/NovaRadio` instead of `NovaRadia`

### Technical Changes
- Updated MediaController to work with `public/images` directory
- Enhanced MediaSeeder to register all existing images including page images
- Added `downloadImage()` method to DatabaseSeeder for automatic image downloads
- Updated media routes to include update endpoint for metadata editing
- ProfileController now registers uploaded avatars in media library
- All view templates updated to use `asset()` for image URLs
- **UpdateService now reads version from composer.json** instead of hardcoded value
  - Version is automatically updated with each release
  - No need to manually update version in code

## [2.0.2] - 2026-03-05

### New Features
- **Media Library System**: Added comprehensive media management system
  - New `media` table for storing image metadata
  - Media model with file size formatting and URL generation
  - MediaSeeder automatically registers existing images in library
  - Images remain in `public/images` but are tracked in database
  
- **Dynamic Hero Section**: Hero background now displays album art from currently playing track
  - Real-time updates every 30 seconds
  - Smooth fade transitions between album covers
  - "Now Playing" card showing current track info with album art
  - Automatic fallback to default hero image when no album art available
  - Blurred background effect for better text readability

### Improvements
- **Privacy Compliance**: Removed Unsplash CDN references from Privacy Policy and Cookie Policy
  - All images now served locally
  - Reduced third-party data processors
  - Improved GDPR compliance

### Technical Changes
- Added `Media` model with relationships and helper methods
- Created migration for `media` table with folder organization
- Enhanced JavaScript to update hero background dynamically
- Improved AzuraCast integration for album art display

## [2.0.1-1] - 2026-03-05

### Update System Improvements
- **Tarball Support**: Changed from zipball_url to tarball_url for more reliable downloads from GitHub
- **Automatic Rollback**: System now automatically restores backup if update fails
- **PHP Syntax Validation**: All PHP files are validated before installation to prevent corrupted files
- **Automatic Cache Clearing**: After successful update, system automatically runs:
  - `php artisan cache:clear`
  - `php artisan config:clear`
  - `php artisan view:clear`
  - `php artisan route:clear`
- **Enhanced Logging**: Each update step (download, validate, backup, install, migrate, cache, rollback) is now logged separately
- **Rollback Cache Clearing**: Cache is also cleared after rollback to ensure clean state

### Bug Fixes
- Fixed corrupted Article.php file that caused frontend errors after update

## [2.0.1] - 2026-03-05

### Bug Fixes
- **Update System**: Fixed incorrect GitHub repository name in update configuration
  - Changed from `novik133/NovaRadia` to `novik133/NovaRadio` in UpdateService
  - Updated default repository name in config/services.php
  - Updated .env.example with correct repository name
  - This fix enables the automatic update system to properly check for and install updates from GitHub releases

## [2.0.0] - 2026-03-04

### Overview
Complete migration from vanilla PHP to Laravel 12 framework. This is a major architectural overhaul that transforms NovaRadio into a modern, maintainable, and extensible CMS.

### Infrastructure Changes
- **Framework**: Migrated from vanilla PHP to Laravel 12
- **PHP Version**: Now requires PHP 8.2+
- **Architecture**: Implemented MVC pattern with Eloquent ORM
- **Routing**: Centralized route management with Laravel routing system
- **Database**: Full database abstraction with migrations and seeders
- **Authentication**: Laravel's built-in authentication system
- **Middleware**: Admin authorization and request handling

### Database Schema Improvements
- Added migrations for all tables
- Implemented foreign key constraints
- Added soft deletes for categories and tags
- Created proper indexes for performance
- Added event_dj pivot table for event management
- Database seeder with idempotent `firstOrCreate` to prevent duplicates

### Admin Panel Enhancements
- Complete redesign with modern UI
- **Articles Management**: Full CRUD with categories, tags, featured images
- **Categories & Tags**: Taxonomy system for content organization
- **DJ Profiles**: Extended profiles with bio, genre, equipment, social links
- **Events/Gigs**: Event management with venues, dates, ticket pricing
- **Media Manager**: Upload, organize images in folders
- **Schedule**: Weekly show schedule management with day/time slots
- **Team Management**: Team member profiles with photo uploads
- **Settings Panel**: Multi-tab settings (General, Contact, Social, SEO, Appearance, Streaming)
- **Theme System**: WordPress-like theme upload and activation
- **Update Notifications**: Version checking and update badges

### Content Management System
- **Pages**: Static page management with TinyMCE editor
- **Articles/News**: Blog functionality with categories and tags
- **Rich Text Editor**: TinyMCE integration for all content fields
- **Media Manager**: Drag-and-drop uploads, folder organization
- **Image Uploads**: 
  - Hero images (admin only)
  - Logos and favicon (admin only)
  - DJ/Team photos (admin + self-service)
  - Article images (authors + admin)

### API & Integrations
- **AzuraCast Integration**: Real-time now playing, song history, listener count
- **Now Playing API**: JSON endpoints for player integration
- **PWA Support**: Service worker and manifest for mobile installation

### Legal & Compliance
- **GDPR Compliance**: 
  - Privacy Policy with data controller information
  - Terms of Service
  - Cookie Policy with granular consent
  - Cookie consent banner with toggle switches
- **Legal Pages**: Auto-generated with admin info

### Security Improvements
- CSRF protection on all forms
- Laravel's built-in security features
- XSS protection middleware
- Secure file uploads with validation
- Role-based access control (admin, editor, DJ)

### Developer Features
- **Migrations**: Full database version control
- **Seeders**: Sample data with idempotent creation
- **Service Layer**: AzuraCastService, SeoService, UpdateService
- **Middleware**: AdminMiddleware for route protection
- **Blade Templates**: Component-based view system

### File Structure Changes
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Admin/          # Admin panel controllers
│   │   ├── Api/            # API controllers
│   │   └── Install/        # Installer controller
│   └── Middleware/
        └── AdminMiddleware.php
├── Models/                 # Eloquent models
├── Services/              # Business logic
├── Providers/
config/                    # Laravel configuration
database/
├── migrations/            # All database migrations
└── seeders/
    └── DatabaseSeeder.php # Sample data
resources/
└── views/
    ├── admin/            # Admin panel views
    ├── install/          # Installer views
    └── themes/           # Frontend themes
routes/
└── web.php               # All web routes
```

### Bug Fixes from v1.x
- Fixed permission issues on shared hosting
- Resolved duplicate entry errors during seeding
- Fixed model relationship conflicts
- Corrected namespace issues in admin controllers
- Fixed missing view files for new features

### Breaking Changes
- **Database**: Complete schema changes - not compatible with v1.x database
- **Config**: Configuration now in `.env` file instead of PHP arrays
- **Themes**: New theme structure incompatible with old themes
- **Uploads**: New storage structure requires path updates

### Migration Guide from v1.x
1. Export content from v1.x database
2. Install NovaRadio 2.0 fresh (Migration from version 0.x-1.x is not possible and requires a clean installation)
3. Manually recreate content (no automated migration available)
4. Re-upload images to new storage locations
5. Reconfigure settings in admin panel

### Requirements
- PHP 8.2 or higher
- MySQL 5.7+ or MariaDB 10.3+
- Extensions: pdo, pdo_mysql, mbstring, openssl, tokenizer, xml, curl, zip, fileinfo, json, gd/imagick, exif
- Composer for dependency management

### Credits
Built with:
- Laravel 12 - PHP Framework
- AzuraCast - Radio Management
- TinyMCE - Rich Text Editor (self-hosted)
- Font Awesome 6 - Icons

---

## [1.x.x] - Legacy (Pre-Laravel)

### Original Version
Vanilla PHP implementation with basic functionality:
- HTML5 Audio Player
- Basic AzuraCast integration
- Simple admin panel
- Manual configuration
- File-based storage
