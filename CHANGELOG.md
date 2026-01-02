# Changelog

All notable changes to NovaRadio CMS will be documented in this file.

## [0.1.1] - 2026-01-02

### Changed

#### Frontend Redesign - Professional Radio Station Theme
- Complete CSS overhaul with modern glassmorphism design
- New color scheme with gradient accents and glow effects
- Animated background with radial gradients
- Improved typography using Inter font family
- Smooth scroll behavior and transitions
- Custom scrollbar styling

#### Navigation & Header
- Transparent navbar with scroll-triggered background
- Improved mobile navigation with slide-down menu
- Request button in header for quick access
- Better station switcher with pill-style tabs

#### Player Bar
- Redesigned sticky player with blur backdrop
- Larger play button with gradient and glow
- Improved track info display
- Animated live badge with pulse effect
- Better volume slider styling
- Listener count with animated dot

#### Homepage
- Hero section with animated glow effect
- Stats bar showing listeners, shows, DJs count
- Section badges for visual hierarchy
- Improved card hover effects with lift animation
- Newsletter signup section with gradient background
- Testimonials section integration

#### Page Templates Updated
- Shows page with genre badges and station info
- DJs page with social links and hover effects
- Schedule page with live indicator for current shows
- Events page with featured next event display
- Blog page with featured post hero
- Contact page with two-column layout
- Request page with guidelines and recent requests

#### Cards & Components
- Glass-effect cards with border glow on hover
- DJ cards with circular avatars and social links
- Event cards with floating date badges
- Post cards with category badges
- Testimonial cards with star ratings

#### Footer
- Four-column layout with brand section
- Social media icons with hover effects
- Quick links organized by category
- Bottom bar with copyright and legal links

### Fixed
- Consistent page padding for player bar
- Mobile responsive improvements
- Better contrast for text readability

---

## [0.1.0] - 2026-01-01

### Added

#### Core System
- Initial release of NovaRadio CMS
- PHP 8.4+ compatibility
- MariaDB/MySQL database support
- PDO with prepared statements
- Admin panel with dark theme
- DJ panel with full AzuraCast control
- User management (Admin/Editor roles)
- Argon2 password hashing
- Session-based authentication
- Maintenance mode with admin bypass

#### Installation Wizard
- Auto-redirect to installer if not configured
- 6-step guided installation process
- System requirements checker (PHP, extensions, permissions)
- GPL-3.0 license acceptance required
- Database connection testing
- Password strength meter (weak/medium/strong)
- Password confirmation with match validation
- Minimum 8 characters, uppercase, lowercase, numbers required
- Auto-detect site URL
- Creates super admin account

#### Multi-Station Support
- Unlimited radio stations
- Individual AzuraCast connection per station
- Separate API keys and stream URLs
- Station switcher on frontend
- Default station selection
- Now Playing with real-time AJAX updates
- Listener count display
- Recently played history
- Multiple stream mounts per station (bitrate/format options)

#### Content Management
- Shows management with images, genres, descriptions
- DJ profiles with bios and social links
- DJ login accounts with panel access
- DJ permissions (stream, upload, playlist)
- Weekly programming schedule per station
- Events management with dates and locations
- Custom static pages with SEO meta descriptions
- Homepage slider/banner management

#### Blog System
- Blog posts with categories and tags
- Featured posts highlighting
- Post excerpts and view counting
- Related posts display
- SEO-friendly URLs with slugs
- Publish date scheduling

#### Comments System
- Comments on blog posts
- Author name and email fields
- Moderation queue with approve/reject
- Admin toggle for comments

#### Podcasts
- Podcast series management
- Episode management with audio URLs
- Duration and download tracking
- Publish date scheduling
- Category and author info

#### Photo Galleries
- Gallery albums management
- Multiple images per gallery
- Image captions and lightbox viewer
- Cover image selection

#### Polls
- Multiple choice polls
- Single or multiple choice voting
- End date scheduling
- IP-based vote tracking
- Real-time results display

#### Advertising System
- Banner/ad management
- Positions: header, sidebar, footer, popup
- Image or custom HTML ads
- Click and impression tracking
- CTR statistics
- Start/end date scheduling

#### Real-Time Chat
- AJAX-based live chat (2-second polling)
- Unlimited chat rooms with topics
- Private messaging between users
- User registration with email/password
- Guest login with random username
- Admin can enable/disable chat
- Operator (OP) system for moderation
- User banning and message deletion
- Online users list
- DJ accounts auto-linked as OPs

#### Newsletter
- Email subscriber collection
- Subscriber management
- CSV export functionality

#### Song Requests
- Listener song request form
- Artist and title fields
- Status management: Pending/Approved/Played/Rejected
- Per-station request filtering

#### Dedications
- Song dedication form
- From/To name fields
- Personal message
- Optional song request
- Status management

#### Contests & Giveaways
- Contest creation with prizes
- Entry form management
- Start/end date scheduling
- Winner selection
- Entry export to CSV
- Rules display

#### Music Charts
- Weekly top tracks
- Position and movement tracking
- Weeks on chart counter
- Per-station charts

#### Artist Profiles
- Featured artist pages
- Bio, images, genre
- Social media links
- Website links

#### Special Broadcasts
- Schedule special live events
- Assign DJs to broadcasts
- Live indicator
- Start/end times

#### Live Status
- Real-time live indicator
- Go Live / Off Air toggle
- Current show/DJ display
- Live since timestamp

#### Song History
- Full track history page
- Album artwork display
- Played timestamps
- Per-station history

#### DJ Panel (AzuraCast Control)
- Full AzuraCast control without AzuraCast login
- Now Playing display with skip track
- Live streaming credentials display
- Disconnect current streamer
- Restart station backend
- Playlist management (view, create, enable/disable)
- Media library browser
- Upload audio files to AzuraCast
- Delete media files
- Queue management with clear queue
- Song requests management (approve/reject/played)
- View AzuraCast request queue
- Listener statistics and reports
- Stream mounts display
- Auto-OP in chat when DJ logs in

#### Admin AzuraCast Control
- Full AzuraCast control from admin panel
- Now Playing with skip track
- Disconnect live DJ
- Restart station backend
- Playlist management (create, enable/disable, delete)
- Streamer/DJ management (create, delete)
- Queue view and clear
- Media file browser
- Stream mounts display

#### Admin Media Upload
- Upload music directly to AzuraCast
- Browse AzuraCast media library
- Playlist management from admin
- Toggle playlists on/off
- Add uploaded files to playlists

#### User Management & Roles
- Role-based access control
- Super Admin (full access to everything)
- Admin (manage content, users, settings)
- Moderator (chat, comments, requests)
- Editor (content only)
- Granular permissions system
- Chat OP integration for admins
- Secure Argon2 password hashing

#### Testimonials
- Customer/listener testimonials
- Star ratings (1-5)
- Profile images
- Sort order control
- Active/inactive status

#### Team Members
- Staff/team profiles
- Role/title display
- Bio and social links
- Profile images
- Sort order control

#### Sponsors & Partners
- Sponsor management
- Tier levels (Platinum, Gold, Silver, Bronze, Partner)
- Logo and website links
- Description support
- Sort order control

#### FAQ System
- Question and answer management
- Category grouping
- Sort order control
- Active/inactive status

#### Downloads
- Downloadable files (mixes, podcasts, etc.)
- File categories
- DJ attribution
- Download counter
- Cover images

#### Shop/Merchandise
- Product management
- Name, description, SKU
- Price and sale price
- Stock tracking
- Product categories
- Featured products
- Product images

#### Orders
- Order management
- Order number generation
- Order status tracking (Pending, Processing, Shipped, Delivered, Cancelled)
- Payment status (Pending, Paid, Refunded)
- Customer information
- Shipping address
- Order notes
- Item details with quantities

#### Promo Codes
- Discount code management
- Percent or fixed discount
- Minimum order amount
- Max uses limit
- Start/end date scheduling

#### Support Tickets
- Ticket submission system
- Ticket number generation
- Categories (General, Technical, Billing, Feedback)
- Priority levels (Low, Medium, High, Urgent)
- Status tracking (Open, In Progress, Waiting, Resolved, Closed)
- Threaded replies
- Staff responses
- Customer notifications

#### URL Redirects
- 301/302 redirect management
- Source and target URLs
- Hit counter
- Active/inactive status

#### Email Templates
- Customizable email templates
- Subject and body
- Variable placeholders ({{variable}})
- Template slugs for code reference
- Active/inactive status

#### Activity Log
- Admin action tracking
- User, action, entity logging
- IP address recording
- Timestamp tracking
- Detailed action history

#### GDPR / Cookie Consent
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

#### Notification Popup
- Announcement/welcome popup
- Enable/disable from admin
- Customizable title and message
- Optional image
- Button text and URL
- Delay before showing (seconds)
- Show once per user option
- Popup ID to reset "show once"
- Live preview in admin

#### Favorites System
- Like shows, DJs, podcasts
- Cookie-based for visitors
- Toggle on/off

#### Shoutbox
- Quick message widget
- Real-time updates
- Moderation support

#### Trivia Games
- Music trivia questions
- Multiple choice answers
- Points system
- Leaderboard tracking
- Category support

#### Track Reactions
- Like/dislike tracks
- IP-based tracking
- Per-station reactions

#### User Playlists
- User-created playlists
- Public/private playlists
- Track management

#### Lyrics Database
- Song lyrics storage
- Artist/title lookup
- Source attribution

#### Appearance Customization
- Site logo and favicon upload
- Primary color picker
- Header and footer navigation menus
- Footer widgets (3 columns)
- Widget types: text, HTML, social, menu
- Dark/Light mode toggle
- Responsive design

#### SEO & Analytics
- Meta descriptions for pages/posts
- Custom head/footer code injection
- Google Analytics integration
- Page view tracking
- Daily statistics

#### API Endpoints
- Public API with CORS support
- Endpoints: nowplaying, history, stations, schedule, shows, djs, events, specials, charts, contests, podcasts, episodes, posts, shoutbox, live, stats, testimonials, team, sponsors, faq, downloads, products
- AJAX API: subscribe, vote, poll_results, track_ad, track_download, track_episode, shoutbox_send, shoutbox_get, favorite, is_favorite, share_count, track_reaction, request_queue, add_to_queue, trivia_question, trivia_answer, trivia_leaderboard, set_reminder
- Chat API: register, login, guest, logout, send, send_private, messages, private_messages, online, rooms, op, ban, delete
- DJ Panel API: nowplaying, listeners, history, queue, skip, files, upload, delete-file, playlists, playlist, toggle-playlist, create-playlist, requests, local-requests, update-request, streamer-info, disconnect, clear-queue, restart, mounts, schedule, report

#### Database
- 55+ database tables
- Foreign key relationships
- Proper indexing
- UTF8MB4 character set
- chat_users linked to djs table
- Comprehensive schema for all features

---

**NovaRadio CMS** © 2025-2026 Kamil 'Nova' Nowicki  
Website: [noviktech.com](https://noviktech.com)  
GitHub: [github.com/novik133](https://github.com/novik133)  
Email: novik@noviktech.com
