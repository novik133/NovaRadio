[![Support me on Ko-fi](https://img.shields.io/badge/Support%20me%20on%20Ko--fi-F16061?style=for-the-badge&logo=ko-fi&logoColor=white)](https://ko-fi.com/novadesktop)
# NovaRadio CMS

A professional Content Management System for internet radio stations with AzuraCast integration.

**Version:** 0.1.0  
**Author:** Kamil 'Novik' Nowicki  
**Email:** novik@noviktech.com  
**Website:** [noviktech.com](https://noviktech.com)  
**GitHub:** [github.com/novik133](https://github.com/novik133)  
**License:** GPL-3.0

---

## Table of Contents

1. [Features](#features)
2. [Requirements](#requirements)
3. [Installation](#installation)
4. [Configuration](#configuration)
5. [Admin Panel Guide](#admin-panel-guide)
6. [Frontend Pages](#frontend-pages)
7. [API Endpoints](#api-endpoints)
8. [Database Schema](#database-schema)
9. [Customization](#customization)
10. [Troubleshooting](#troubleshooting)
11. [License](#license)

---

## Features

### 📻 Multi-Station Support
- Unlimited radio stations
- Individual AzuraCast connection per station
- Separate API keys and stream URLs
- Station switcher on frontend
- Default station selection
- Now Playing with real-time updates
- Listener count display
- Recently played history

### 🎵 Content Management

#### Shows
- Create and manage radio shows
- Assign to specific stations
- Add images, genres, descriptions
- Active/inactive status

#### DJs
- DJ profiles with bios
- Profile images
- Social media links (JSON storage)
- Link DJs to scheduled shows
- DJ login accounts with permissions
- Auto-OP status in chat rooms

#### Schedule
- Weekly programming schedule
- Per-station scheduling
- Assign shows and DJs to time slots
- Day of week and time range

#### Events
- Upcoming events management
- Event dates and locations
- Event images and descriptions
- Automatic filtering of past events

#### Pages
- Custom static pages
- SEO-friendly slugs
- Meta descriptions
- Rich content support

#### Sliders
- Homepage banner management
- Multiple slides with sorting
- Title, subtitle, image, link
- Active/inactive control

### 📝 Blog System
- Full blog/news functionality
- Categories and tags
- Featured posts
- Post excerpts
- View counting
- Related posts
- SEO-friendly URLs
- Publish date scheduling

### 💬 Comments
- Comment system for blog posts
- Author name and email
- Moderation queue
- Approve/reject workflow
- Nested replies support

### 🎙️ Podcasts
- Podcast series management
- Episode management
- Audio file URLs
- Duration tracking
- Download counting
- Publish date scheduling
- Category and author info

### 📷 Galleries
- Photo gallery albums
- Multiple images per gallery
- Image captions
- Lightbox viewer
- Cover image selection
- Drag-and-drop upload

### 📊 Polls
- Create polls with multiple options
- Single or multiple choice
- End date scheduling
- IP-based vote tracking
- Real-time results
- Widget display

### 📢 Advertising
- Banner/ad management
- Multiple positions (header, sidebar, footer, popup)
- Image or custom HTML ads
- Click tracking
- Impression tracking
- CTR statistics
- Start/end date scheduling

### 💬 Real-Time Chat
- AJAX-based live chat
- Main room chat
- Private messaging
- User registration (email/password)
- Guest login with random username
- Admin can enable/disable chat
- Operator (OP) system
- User banning
- Message deletion
- Online users list

### 📧 Newsletter
- Email subscriber collection
- Subscriber management
- CSV export
- Confirmation status

### 🎶 Song Requests
- Listener song request form
- Artist and title fields
- Optional message
- Status management (Pending/Approved/Played/Rejected)
- Per-station requests

### 💝 Dedications
- Send song dedications to someone special
- From/To name fields
- Personal message
- Optional song request
- Status management

### 🏆 Contests & Giveaways
- Create contests with prizes
- Entry form with custom fields
- Start/end date scheduling
- Random winner selection
- Entry export to CSV
- Rules and terms display

### 📊 Music Charts
- Weekly/Monthly top tracks
- Position tracking
- Movement indicators (up/down/same)
- Weeks on chart counter
- Per-station charts

### 🎤 Artist Profiles
- Featured artist pages
- Bio and images
- Social media links
- Genre categorization
- Website links

### 📡 Special Broadcasts
- Schedule special live events
- Assign DJs to broadcasts
- Live indicator
- Start/end times
- Event descriptions

### 🔴 Live Status
- Real-time live indicator
- Go Live / Off Air toggle
- Current show/DJ display
- Live since timestamp
- Frontend live badge

### 📜 Song History
- Full track history page
- Album artwork display
- Played time stamps
- Per-station history

### ❤️ Favorites System
- Like shows, DJs, podcasts
- Cookie-based for visitors
- User-based for registered
- Toggle on/off

### 📢 Shoutbox
- Quick message widget
- Real-time updates
- Moderation support
- Name and message

### 🎧 Stream Quality
- Multiple stream mounts
- Different bitrates (64k, 128k, 320k)
- Format options (MP3, AAC, OGG)
- Per-station configuration

### ✉️ Contact Messages
- Contact form submissions
- Read/unread status
- Message management

### ⭐ Testimonials
- Customer/listener testimonials
- Star ratings (1-5)
- Profile images
- Sort order control

### 👥 Team Members
- Staff/team profiles
- Role/title display
- Bio and social links
- Profile images

### 🤝 Sponsors & Partners
- Sponsor management
- Tier levels (Platinum, Gold, Silver, Bronze, Partner)
- Logo and website links
- Description support

### ❓ FAQ System
- Question and answer management
- Category grouping
- Sort order control
- Active/inactive status

### 📥 Downloads
- Downloadable files (mixes, podcasts, etc.)
- File categories
- DJ attribution
- Download counter
- Cover images

### 🛒 Shop/Merchandise
- Product management
- Price and sale price
- Stock tracking
- SKU support
- Product categories
- Featured products
- Product images

### 📦 Orders
- Order management
- Order status tracking (Pending, Processing, Shipped, Delivered, Cancelled)
- Payment status (Pending, Paid, Refunded)
- Customer information
- Shipping address
- Order notes

### 🎫 Support Tickets
- Ticket submission system
- Categories (General, Technical, Billing, Feedback)
- Priority levels (Low, Medium, High, Urgent)
- Status tracking (Open, In Progress, Waiting, Resolved, Closed)
- Threaded replies
- Staff responses

### 🔀 URL Redirects
- 301/302 redirect management
- Source and target URLs
- Hit counter
- Active/inactive status

### 📧 Email Templates
- Customizable email templates
- Variable placeholders
- Template slugs for code reference

### 📋 Activity Log
- Admin action tracking
- User, action, entity logging
- IP address recording
- Timestamp tracking

### 🍪 GDPR / Cookie Consent
- Cookie consent popup
- Enable/disable from admin
- Customizable title and message
- Accept/Decline button text
- Privacy Policy link
- Cookie Policy link
- Position options (top/bottom)
- Style options (bar/box)
- Stores consent in localStorage
- Live preview in admin

### 📢 Notification Popup
- Announcement/welcome popup
- Enable/disable from admin
- Customizable title and message
- Optional image
- Button text and URL
- Delay before showing (seconds)
- Show once per user option
- Popup ID to reset "show once"
- Live preview in admin

### 🎨 Appearance Customization

#### Branding
- Site logo upload
- Logo text fallback
- Favicon
- Primary color picker

#### Menus
- Header navigation menu
- Footer navigation menu
- Custom links with targets
- Sort order control

#### Widgets
- Footer widgets (3 columns)
- Widget types: text, HTML, social, menu
- Sort order control

#### Theme
- Dark/Light mode toggle
- CSS custom properties
- Responsive design

### ⚙️ System Features

#### Admin Panel
- Modern dark theme interface
- Dashboard with statistics
- AJAX-powered operations
- Image upload system
- Full AzuraCast control
- Media upload to AzuraCast
- Playlist management
- Streamer/DJ management
- Queue control

#### DJ Panel
- Full AzuraCast control without AzuraCast login
- Now Playing with skip track control
- Live streaming credentials display
- Disconnect streamer / Restart station
- Playlist management (create, enable/disable)
- Media library browser with upload
- Queue management with clear queue
- Song requests management (approve/reject/played)
- Listener statistics and reports
- Auto-OP in chat when logged in

#### User Management
- Role-based access control
- Super Admin (full access)
- Admin (manage content & users)
- Moderator (chat & comments)
- Editor (content only)
- Granular permissions system
- Chat OP integration
- Secure password hashing (Argon2)
- Session management

#### Analytics
- Page view tracking
- Daily statistics
- Per-page breakdown

#### SEO
- Meta descriptions
- Custom head code injection
- Custom footer code injection
- Google Analytics integration

#### Maintenance Mode
- Enable/disable site
- Custom maintenance message
- Admin bypass

### 🔧 Technical Features
- PHP 8.4+ compatible
- MariaDB/MySQL database
- PDO with prepared statements
- AJAX-powered admin
- Responsive design
- cURL for API calls
- JSON data storage
- XSS protection

---

## Requirements

- **PHP:** 8.4 or higher
- **Database:** MariaDB 10.6+ or MySQL 8.0+
- **Web Server:** Apache with mod_rewrite or Nginx
- **PHP Extensions:**
  - PDO with MySQL driver
  - cURL
  - JSON
  - Session
- **AzuraCast:** Instance for radio features (optional)

---

## Installation

### Step 1: Upload Files
Upload all files to your web server document root or subdirectory.

```
/your-website/
├── admin/
├── assets/
├── dj/
├── includes/
├── uploads/
├── admin.php
├── dj.php
├── index.php
├── install.php
├── config.php
└── ... other files
```

### Step 2: Create Database
Create an empty MySQL/MariaDB database:

```sql
CREATE DATABASE novaradio CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'novaradio'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON novaradio.* TO 'novaradio'@'localhost';
FLUSH PRIVILEGES;
```

### Step 3: Run Installer
Navigate to `http://yourdomain.com/install.php` in your browser (or visit any page - you'll be redirected automatically).

The installer will guide you through 6 steps:
1. **System Requirements** - Checks PHP version, extensions, permissions
2. **License Agreement** - Accept GPL-3.0 license
3. **Database Configuration** - Test and connect to database
4. **Station Setup** - Configure your first radio station
5. **Site & Admin** - Set site name, URL, and create super admin
6. **Complete** - Installation finished

#### System Requirements Checked:
- PHP 8.0+ version
- PDO and PDO MySQL extensions
- cURL extension
- JSON extension
- Session support
- Mbstring extension (optional)
- Writable directories

#### Password Requirements:
- Minimum 8 characters
- Must contain uppercase and lowercase letters
- Must contain numbers
- Password confirmation must match
- Strength indicator (weak/medium/strong)

### Step 4: Install Additional Features
After main installation, run these SQL files for extra features:

```bash
mysql -u novaradio -p novaradio < schema_chat.sql
mysql -u novaradio -p novaradio < schema_features.sql
```

Or import via phpMyAdmin.

### Step 5: Post-Installation
1. **Delete `install.php`** for security
2. Set proper permissions:
   ```bash
   chmod 755 uploads/
   chmod 644 config.php
   ```
3. Access admin panel at `http://yourdomain.com/admin.php`

### Step 6: Configure AzuraCast (Optional)
1. Go to Admin → Stations
2. Add your station with:
   - AzuraCast URL (e.g., `https://radio.example.com`)
   - API Key (from AzuraCast admin)
   - Station ID (usually 1)
   - Stream URL for player

---

## Configuration

### config.php
After installation, `config.php` contains:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_NAME', 'novaradio');
define('DB_USER', 'novaradio');
define('DB_PASS', 'your_password');
define('SITE_NAME', 'NovaRadio');
```

### Settings (Admin Panel)
All other settings are managed via Admin → Settings:

| Setting | Description |
|---------|-------------|
| `site_name` | Website name |
| `site_description` | Site tagline |
| `site_url` | Full website URL |
| `logo_url` | Logo image path |
| `logo_text` | Text when no logo |
| `favicon_url` | Favicon path |
| `primary_color` | Theme accent color |
| `copyright_text` | Footer copyright |
| `google_analytics` | GA tracking ID |
| `custom_head_code` | Code before `</head>` |
| `custom_footer_code` | Code before `</body>` |
| `chat_enabled` | Enable/disable chat |
| `comments_enabled` | Enable/disable comments |
| `comments_moderation` | Require approval |
| `newsletter_enabled` | Enable newsletter |
| `polls_enabled` | Enable polls |
| `maintenance_mode` | Enable maintenance |
| `maintenance_message` | Maintenance text |

---

## Admin Panel Guide

Access: `http://yourdomain.com/admin.php`

### Dashboard
Overview of:
- Total stations, shows, DJs
- Pending requests count
- Unread messages count
- Recent activity

### Content Section

#### Stations
1. Click "Add Station"
2. Fill in:
   - Name and slug
   - AzuraCast URL and API key
   - Station ID and stream URL
   - Set as default (optional)
3. Save

#### Shows
1. Click "Add Show"
2. Enter name, description, genre
3. Upload image
4. Assign to station (optional)
5. Save

#### DJs
1. Click "Add DJ"
2. Enter name and bio
3. Upload profile image
4. Add social links
5. Save

#### Schedule
1. Select station filter
2. Click time slot or "Add"
3. Select show and DJ
4. Set day and time range
5. Save

#### Events
1. Click "Add Event"
2. Enter title, description
3. Set date/time and location
4. Upload image
5. Save

#### Pages
1. Click "Add Page"
2. Enter title (slug auto-generates)
3. Add content
4. Set meta description
5. Save

Access pages at: `page.php?slug=your-slug`

#### Sliders
1. Click "Add Slider"
2. Enter title and subtitle
3. Upload image
4. Add link (optional)
5. Set sort order
6. Save

#### Blog Posts
1. Click "Add Post"
2. Enter title (slug auto-generates)
3. Select category, add tags
4. Write excerpt and content
5. Upload featured image
6. Set publish date
7. Mark as featured (optional)
8. Save

#### Podcasts
1. Click "Add Podcast"
2. Enter title, author, category
3. Add description
4. Upload cover image
5. Save
6. Click "Episodes" to add episodes

#### Episodes
1. Select podcast
2. Click "Add Episode"
3. Enter title and description
4. Add audio URL
5. Set duration (seconds)
6. Set publish date
7. Save

#### Galleries
1. Click "Add Gallery"
2. Enter title (slug auto-generates)
3. Add description
4. Upload cover image
5. Save
6. Click "Images" to upload photos

### Appearance Section

#### Menus
1. Select location (Header/Footer)
2. Click "Add Item"
3. Enter label and URL
4. Set target (_self/_blank)
5. Set sort order
6. Save

#### Widgets
1. Select location (footer_1/footer_2/footer_3)
2. Click "Add Widget"
3. Enter title
4. Select type and add content
5. Save

#### Branding
1. Upload logo image
2. Set logo text fallback
3. Upload favicon
4. Pick primary color
5. Set copyright text
6. Save

### Communication Section

#### Chat
- Toggle chat on/off
- View all chat users
- Give/remove OP status
- Ban/unban users
- Delete users

#### Requests
- View song requests
- Change status (Pending → Approved → Played)
- Reject requests
- Delete requests

#### Messages
- View contact form submissions
- Mark as read
- Delete messages

### System Section

#### Polls
1. Click "Add Poll"
2. Enter question
3. Add options (minimum 2)
4. Set end date (optional)
5. Allow multiple votes (optional)
6. Save

#### Ads
1. Click "Add Ad"
2. Enter name
3. Select position
4. Upload image or add HTML
5. Set link URL
6. Set start/end dates
7. Save

#### Subscribers
- View newsletter subscribers
- Export to CSV
- Delete subscribers

#### Comments
- View all comments
- Approve pending comments
- Delete comments

#### Analytics
- View page views
- Filter by date
- See popular pages

#### Users
1. Click "Add User"
2. Enter username and password
3. Select role (Admin/Editor)
4. Save

#### Settings
- General settings
- Social media links
- SEO settings
- Advanced code injection

---

## Frontend Pages

| URL | Description |
|-----|-------------|
| `index.php` | Homepage with slider, shows, DJs, events |
| `schedule.php` | Weekly schedule grid |
| `shows.php` | All shows listing |
| `djs.php` | All DJs listing |
| `events.php` | Upcoming events |
| `blog.php` | Blog posts listing |
| `post.php?slug=X` | Single blog post |
| `podcasts.php` | All podcasts |
| `podcast.php?id=X` | Single podcast with episodes |
| `galleries.php` | All galleries |
| `gallery.php?slug=X` | Single gallery with lightbox |
| `chat.php` | Live chat room |
| `request.php` | Song request form |
| `dedications.php` | Song dedication form |
| `history.php` | Recently played tracks |
| `charts.php` | Weekly top 20 |
| `contests.php` | Active contests |
| `contest.php?id=X` | Contest entry form |
| `specials.php` | Special broadcasts |
| `artists.php` | Featured artists |
| `artist.php?slug=X` | Artist profile |
| `queue.php` | Request queue |
| `trivia.php` | Music trivia game |
| `contact.php` | Contact form |
| `page.php?slug=X` | Custom static page |
| `admin.php` | Admin panel |
| `dj.php` | DJ panel (AzuraCast control) |

---

## API Endpoints

### Public API (`api.php`)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `?action=nowplaying` | GET | Current track info with live status |
| `?action=nowplaying&station=ID` | GET | Track for specific station |
| `?action=history` | GET | Recently played (default 20, max 100) |
| `?action=history&limit=N` | GET | Recently played with custom limit |
| `?action=stations` | GET | All active stations with stream mounts |
| `?action=schedule` | GET | Today's schedule |
| `?action=schedule&day=N` | GET | Schedule for specific day (1=Mon, 7=Sun) |
| `?action=shows` | GET | All active shows |
| `?action=djs` | GET | All active DJs |
| `?action=events` | GET | Upcoming events (max 20) |
| `?action=specials` | GET | Special broadcasts with DJ info |
| `?action=charts` | GET | Weekly top 20 |
| `?action=charts&period=monthly` | GET | Monthly top 20 |
| `?action=contests` | GET | Active contests |
| `?action=podcasts` | GET | All podcasts with episode count |
| `?action=episodes&podcast=ID` | GET | Episodes for podcast |
| `?action=posts` | GET | Recent blog posts (default 10, max 50) |
| `?action=posts&limit=N` | GET | Blog posts with custom limit |
| `?action=shoutbox` | GET | Shoutbox messages (50 latest) |
| `?action=live` | GET | Live status with show/DJ info |
| `?action=stats` | GET | Listener statistics |
| `?action=testimonials` | GET | Active testimonials |
| `?action=team` | GET | Team members |
| `?action=sponsors` | GET | Sponsors by tier |
| `?action=faq` | GET | FAQ by category |
| `?action=downloads` | GET | Available downloads |
| `?action=products` | GET | Shop products |

### AJAX API (`ajax.php`)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `action=subscribe` | POST | Newsletter signup (email, name) |
| `action=vote` | POST | Poll vote (poll_id, option_id) |
| `action=poll_results&poll_id=X` | GET | Poll results with percentages |
| `action=track_ad` | POST | Track ad click/impression (ad_id, type) |
| `action=track_download` | POST | Track file download (download_id) |
| `action=track_episode` | POST | Track episode download (episode_id) |
| `action=shoutbox_send` | POST | Send shoutbox message (name, message) |
| `action=shoutbox_get` | GET | Get shoutbox messages (30 latest) |
| `action=favorite` | POST | Toggle favorite (item_type, item_id) |
| `action=is_favorite` | GET | Check if favorited (item_type, item_id) |
| `action=share_count` | POST | Track social share (page) |
| `action=track_reaction` | POST | Like/dislike track (reaction, artist, title, station_id) |
| `action=request_queue` | GET | Get request queue (station_id) |
| `action=add_to_queue` | POST | Add song to queue (artist, title, name, station_id) |
| `action=trivia_question` | GET | Get random trivia question |
| `action=trivia_answer` | POST | Submit trivia answer (question_id, answer) |
| `action=trivia_leaderboard` | GET | Get trivia top 10 scores |
| `action=set_reminder` | POST | Set show reminder (schedule_id, email) |

### Chat API (`chat_api.php`)

| Endpoint | Method | Description |
|----------|--------|-------------|
| `action=register` | POST | Register chat user (username, email, password) |
| `action=login` | POST | Login chat user (username, password) |
| `action=guest` | POST | Login as guest (auto-generated username) |
| `action=logout` | POST | Logout current user |
| `action=send` | POST | Send room message (message, room_id) |
| `action=send_private` | POST | Send private message (to_user, message) |
| `action=messages` | GET | Get room messages (room_id, last_id) |
| `action=private_messages` | GET | Get private messages (with_user, last_id) |
| `action=online` | GET | Get online users (active in last 2 min) |
| `action=rooms` | GET | Get all active chat rooms |
| `action=op` | POST | Give/remove OP status (user_id, op) - admin only |
| `action=ban` | POST | Ban/unban user (user_id, ban) - admin/OP |
| `action=delete` | POST | Delete message (msg_id) - admin/OP |

### Response Format
All APIs return JSON:

```json
{
  "success": true,
  "data": { }
}
```

Or on error:
```json
{
  "error": "Error message"
}
```

---

## Database Schema

### Core Tables
- `admins` - Admin users
- `stations` - Radio stations
- `shows` - Radio shows
- `djs` - DJ profiles
- `schedule` - Programming schedule
- `settings` - Site settings
- `pages` - Static pages
- `sliders` - Homepage sliders
- `messages` - Contact messages
- `menu_items` - Navigation menus
- `widgets` - Footer widgets
- `events` - Events
- `requests` - Song requests
- `analytics` - Page views

### Blog Tables
- `posts` - Blog posts
- `comments` - Post comments

### Podcast Tables
- `podcasts` - Podcast series
- `episodes` - Podcast episodes

### Gallery Tables
- `galleries` - Photo galleries
- `gallery_images` - Gallery images

### Poll Tables
- `polls` - Polls
- `poll_options` - Poll options
- `poll_votes` - Vote records

### Chat Tables
- `chat_rooms` - Chat rooms
- `chat_users` - Chat users (linked to DJs via dj_id)
- `chat_messages` - Room messages
- `chat_private` - Private messages

### Other Tables
- `subscribers` - Newsletter subscribers
- `ads` - Advertisements
- `song_history` - Played tracks history
- `listener_stats` - Listener statistics
- `favorites` - User favorites/likes
- `special_broadcasts` - Special live events
- `dedications` - Song dedications
- `stream_mounts` - Stream quality options
- `contests` - Contests/giveaways
- `contest_entries` - Contest entries
- `artists` - Artist profiles
- `charts` - Music charts
- `live_status` - Live show status
- `trivia` - Trivia questions
- `trivia_scores` - Trivia leaderboard
- `track_reactions` - Track likes/dislikes
- `tips` - DJ tips/donations
- `request_queue` - Live request queue
- `listening_history` - User listening history
- `show_reminders` - Show reminder subscriptions
- `audio_messages` - Voice dedications
- `notifications` - User notifications
- `app_tokens` - Mobile app tokens
- `embed_widgets` - Embeddable widgets

### Website Tables
- `testimonials` - Customer testimonials
- `team` - Team member profiles
- `sponsors` - Sponsors and partners
- `faq` - Frequently asked questions
- `downloads` - Downloadable files

### Shop Tables
- `products` - Merchandise products
- `orders` - Shop orders
- `promo_codes` - Discount codes

### Support Tables
- `tickets` - Support tickets
- `ticket_replies` - Ticket conversation threads

### System Tables
- `redirects` - URL redirects
- `activity_log` - Admin activity log
- `backups` - Backup records
- `cron_jobs` - Scheduled tasks
- `email_templates` - Email templates
- `email_log` - Sent email log
- `social_feed` - Social media cache

### User Content Tables
- `user_playlists` - User-created playlists
- `playlist_tracks` - Playlist tracks
- `lyrics` - Song lyrics

---

## Customization

### CSS Variables
Edit `assets/css/style.css`:

```css
:root {
  --primary: #6366f1;
  --bg: #0f0f1a;
  --card-bg: #1a1a2e;
  --text: #ffffff;
  --muted: #888888;
  --border: #333333;
}
```

### Adding Menu Items
Via database or admin panel:

```sql
INSERT INTO menu_items (location, label, url, sort_order) 
VALUES ('header', 'New Page', '/page.php?slug=new', 10);
```

### Custom Widgets
Widget types:
- `text` - Plain text
- `html` - Raw HTML
- `social` - Social links
- `menu` - Link list

### Template Modification
Main templates in `includes/`:
- `header.php` - Site header, navigation, player
- `footer.php` - Site footer, widgets, scripts

### Adding New Pages
1. Create `newpage.php`
2. Include header/footer:
```php
<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';
trackPageView('newpage');
?>
<!DOCTYPE html>
<html>
<head>
    <title>New Page - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container">
        <!-- Your content -->
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
```

---

## Troubleshooting

### Common Issues

**"Database connection failed"**
- Check credentials in `config.php`
- Verify MySQL is running
- Check user permissions

**"AzuraCast not working"**
- Verify AzuraCast URL (no trailing slash)
- Check API key is valid
- Confirm station ID is correct
- Test URL: `{azuracast_url}/api/nowplaying/{station_id}`

**"Images not uploading"**
- Check `uploads/` folder permissions (755)
- Verify PHP `upload_max_filesize`
- Check `post_max_size` in php.ini

**"Chat not working"**
- Run `schema_chat.sql`
- Check `chat_enabled` setting
- Verify JavaScript console for errors

**"Styles broken"**
- Clear browser cache
- Check CSS file paths
- Verify `.htaccess` for Apache

### Error Logging
Enable PHP errors for debugging:

```php
// Add to config.php temporarily
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

### Support
- GitHub Issues: [github.com/novik133/novaradio](https://github.com/novik133)
- Email: novik@noviktech.com

---

## License

This project is licensed under the **GNU General Public License v3.0**.

You are free to:
- Use commercially
- Modify
- Distribute
- Use privately

Under conditions:
- Disclose source
- License and copyright notice
- Same license
- State changes

See [LICENSE](LICENSE) file for full text.

---

## Credits

**NovaRadio CMS** © 2025-2026 Kamil 'Novik' Nowicki

- Website: [noviktech.com](https://noviktech.com)
- GitHub: [github.com/novik133](https://github.com/novik133)
- Email: novik@noviktech.com

### Technologies Used
- PHP 8.4+
- MariaDB/MySQL
- JavaScript (Vanilla)
- CSS3 with Custom Properties
- AzuraCast API

---

*Thank you for using NovaRadio CMS!*
