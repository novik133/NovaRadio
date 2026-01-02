<?php
/**
 * Admin Panel
 */
session_start();
require_once 'config.php';
require_once 'includes/functions.php';
require_once 'includes/azuracast.php';

$page = $_GET['page'] ?? 'dashboard';

// Login
if ($page === 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user = fetch("SELECT * FROM admins WHERE username = ?", [$_POST['username'] ?? '']);
        if ($user && password_verify($_POST['password'] ?? '', $user['password'])) {
            $_SESSION['admin_id'] = $user['id'];
            redirect('admin.php');
        }
        $error = 'Invalid credentials';
    }
    include 'admin/login.php';
    exit;
}

if ($page === 'logout') { session_destroy(); redirect('admin.php?page=login'); }

requireLogin();

// AJAX handlers
if (isset($_GET['ajax'])) {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true) ?? $_POST;
    
    switch ($_GET['ajax']) {
        // Stations
        case 'save-station':
            $fields = [
                'name' => $data['name'], 'slug' => $data['slug'], 'description' => $data['description'] ?? '',
                'genre' => $data['genre'] ?? '', 'image' => $data['image'] ?? '',
                'azuracast_url' => $data['azuracast_url'] ?? '', 'api_key' => $data['api_key'] ?? '',
                'station_id' => $data['station_id'] ?? 1, 'stream_url' => $data['stream_url'] ?? '',
                'is_default' => $data['is_default'] ?? 0, 'active' => $data['active'] ?? 1, 'sort_order' => $data['sort_order'] ?? 0
            ];
            if ($fields['is_default']) query("UPDATE stations SET is_default = 0");
            if (!empty($data['id'])) { update('stations', $fields, 'id = ?', [$data['id']]); $id = $data['id']; }
            else { $id = insert('stations', $fields); }
            echo json_encode(['success' => true, 'id' => $id]);
            break;
        case 'delete-station':
            delete('stations', 'id = ?', [$data['id']]);
            echo json_encode(['success' => true]);
            break;
            
        // Shows
        case 'save-show':
            $fields = ['station_id' => $data['station_id'] ?: null, 'name' => $data['name'], 'description' => $data['description'] ?? '', 'image' => $data['image'] ?? '', 'genre' => $data['genre'] ?? '', 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('shows', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('shows', $fields)]); }
            break;
        case 'delete-show': delete('shows', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // DJs
        case 'save-dj':
            $fields = [
                'name' => $data['name'],
                'bio' => $data['bio'] ?? '',
                'image' => $data['image'] ?? '',
                'email' => $data['email'] ?? null,
                'azuracast_dj_id' => $data['azuracast_dj_id'] ?: null,
                'azuracast_username' => $data['azuracast_username'] ?? '',
                'azuracast_password' => $data['azuracast_password'] ?? '',
                'can_stream' => $data['can_stream'] ?? 1,
                'can_upload' => $data['can_upload'] ?? 1,
                'can_playlist' => $data['can_playlist'] ?? 1,
                'social_links' => json_encode($data['social_links'] ?? []),
                'active' => $data['active'] ?? 1
            ];
            if (!empty($data['password'])) {
                $fields['password'] = password_hash($data['password'], PASSWORD_ARGON2ID);
            }
            if (!empty($data['id'])) {
                update('djs', $fields, 'id = ?', [$data['id']]);
                echo json_encode(['success' => true, 'id' => $data['id']]);
            } else {
                echo json_encode(['success' => true, 'id' => insert('djs', $fields)]);
            }
            break;
        case 'delete-dj': delete('djs', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Schedule
        case 'save-schedule':
            $fields = ['station_id' => $data['station_id'] ?: null, 'show_id' => $data['show_id'], 'dj_id' => $data['dj_id'] ?: null, 'day_of_week' => $data['day_of_week'], 'start_time' => $data['start_time'], 'end_time' => $data['end_time']];
            if (!empty($data['id'])) { update('schedule', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('schedule', $fields)]); }
            break;
        case 'delete-schedule': delete('schedule', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Pages
        case 'save-page':
            $fields = ['title' => $data['title'], 'slug' => $data['slug'], 'content' => $data['content'] ?? '', 'meta_description' => $data['meta_description'] ?? '', 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('pages', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('pages', $fields)]); }
            break;
        case 'delete-page': delete('pages', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Sliders
        case 'save-slider':
            $fields = ['title' => $data['title'] ?? '', 'subtitle' => $data['subtitle'] ?? '', 'image' => $data['image'] ?? '', 'link' => $data['link'] ?? '', 'sort_order' => $data['sort_order'] ?? 0, 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('sliders', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('sliders', $fields)]); }
            break;
        case 'delete-slider': delete('sliders', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Menu
        case 'save-menu':
            $fields = ['location' => $data['location'] ?? 'header', 'label' => $data['label'], 'url' => $data['url'], 'target' => $data['target'] ?? '_self', 'sort_order' => $data['sort_order'] ?? 0, 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('menu_items', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('menu_items', $fields)]); }
            break;
        case 'delete-menu': delete('menu_items', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Posts
        case 'save-post':
            $fields = ['title' => $data['title'], 'slug' => $data['slug'], 'excerpt' => $data['excerpt'] ?? '', 'content' => $data['content'] ?? '', 'image' => $data['image'] ?? '', 'author_id' => $data['author_id'] ?? null, 'category' => $data['category'] ?? '', 'tags' => $data['tags'] ?? '', 'featured' => $data['featured'] ?? 0, 'active' => $data['active'] ?? 1, 'published_at' => $data['published_at'] ?: null];
            if (!empty($data['id'])) { update('posts', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('posts', $fields)]); }
            break;
        case 'delete-post': delete('posts', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Podcasts
        case 'save-podcast':
            $fields = ['title' => $data['title'], 'description' => $data['description'] ?? '', 'image' => $data['image'] ?? '', 'author' => $data['author'] ?? '', 'category' => $data['category'] ?? '', 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('podcasts', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('podcasts', $fields)]); }
            break;
        case 'delete-podcast': delete('podcasts', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Episodes
        case 'save-episode':
            $fields = ['podcast_id' => $data['podcast_id'], 'title' => $data['title'], 'description' => $data['description'] ?? '', 'audio_url' => $data['audio_url'] ?? '', 'duration' => $data['duration'] ?? 0, 'published_at' => $data['published_at'] ?: null, 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('episodes', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('episodes', $fields)]); }
            break;
        case 'delete-episode': delete('episodes', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Galleries
        case 'save-gallery':
            $fields = ['title' => $data['title'], 'slug' => $data['slug'], 'description' => $data['description'] ?? '', 'cover_image' => $data['cover_image'] ?? '', 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('galleries', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('galleries', $fields)]); }
            break;
        case 'delete-gallery': delete('galleries', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        case 'save-gallery-image': echo json_encode(['success' => true, 'id' => insert('gallery_images', ['gallery_id' => $data['gallery_id'], 'image' => $data['image'], 'caption' => $data['caption'] ?? ''])]); break;
        case 'update-gallery-image': update('gallery_images', ['caption' => $data['caption']], 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Polls
        case 'save-poll':
            $fields = ['question' => $data['question'], 'multiple' => $data['multiple'] ?? 0, 'ends_at' => $data['ends_at'] ?: null, 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('polls', $fields, 'id = ?', [$data['id']]); $pollId = $data['id']; delete('poll_options', 'poll_id = ?', [$pollId]); }
            else { $pollId = insert('polls', $fields); }
            foreach ($data['options'] ?? [] as $opt) if (trim($opt)) insert('poll_options', ['poll_id' => $pollId, 'option_text' => $opt]);
            echo json_encode(['success' => true, 'id' => $pollId]);
            break;
        case 'delete-poll': delete('polls', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Ads
        case 'save-ad':
            $fields = ['name' => $data['name'], 'position' => $data['position'] ?? 'sidebar', 'content' => $data['content'] ?? '', 'image' => $data['image'] ?? '', 'link' => $data['link'] ?? '', 'starts_at' => $data['starts_at'] ?: null, 'ends_at' => $data['ends_at'] ?: null, 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('ads', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('ads', $fields)]); }
            break;
        case 'delete-ad': delete('ads', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Contests
        case 'save-contest':
            $fields = ['title' => $data['title'], 'description' => $data['description'] ?? '', 'image' => $data['image'] ?? '', 'prize' => $data['prize'] ?? '', 'rules' => $data['rules'] ?? '', 'start_date' => $data['start_date'] ?: null, 'end_date' => $data['end_date'] ?: null, 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('contests', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('contests', $fields)]); }
            break;
        case 'delete-contest': delete('contests', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Special Broadcasts
        case 'save-special':
            $fields = ['station_id' => $data['station_id'] ?: null, 'title' => $data['title'], 'description' => $data['description'] ?? '', 'image' => $data['image'] ?? '', 'dj_id' => $data['dj_id'] ?: null, 'start_time' => $data['start_time'], 'end_time' => $data['end_time'], 'is_live' => $data['is_live'] ?? 0];
            if (!empty($data['id'])) { update('special_broadcasts', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('special_broadcasts', $fields)]); }
            break;
        case 'delete-special': delete('special_broadcasts', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Artists
        case 'save-artist':
            $fields = ['name' => $data['name'], 'slug' => $data['slug'], 'bio' => $data['bio'] ?? '', 'image' => $data['image'] ?? '', 'website' => $data['website'] ?? '', 'social_links' => json_encode($data['social_links'] ?? []), 'genre' => $data['genre'] ?? '', 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('artists', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('artists', $fields)]); }
            break;
        case 'delete-artist': delete('artists', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Widgets
        case 'save-widget':
            $fields = ['location' => $data['location'], 'title' => $data['title'] ?? '', 'content' => $data['content'] ?? '', 'widget_type' => $data['widget_type'] ?? 'text', 'sort_order' => $data['sort_order'] ?? 0, 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('widgets', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('widgets', $fields)]); }
            break;
        case 'delete-widget': delete('widgets', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Events
        case 'save-event':
            $fields = ['title' => $data['title'], 'description' => $data['description'] ?? '', 'image' => $data['image'] ?? '', 'event_date' => $data['event_date'], 'location' => $data['location'] ?? '', 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) { update('events', $fields, 'id = ?', [$data['id']]); echo json_encode(['success' => true, 'id' => $data['id']]); }
            else { echo json_encode(['success' => true, 'id' => insert('events', $fields)]); }
            break;
        case 'delete-event': delete('events', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Requests & Messages
        case 'update-request': update('requests', ['status' => $data['status']], 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        case 'delete-request': delete('requests', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        case 'mark-read': update('messages', ['is_read' => 1], 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        case 'delete-message': delete('messages', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Users
        case 'save-user':
            $fields = [
                'username' => $data['username'],
                'email' => $data['email'] ?? null,
                'role' => $data['role'] ?? 'editor',
                'permissions' => json_encode($data['permissions'] ?? []),
                'is_op' => $data['is_op'] ?? 0
            ];
            if (!empty($data['password'])) $fields['password'] = password_hash($data['password'], PASSWORD_ARGON2ID);
            if (!empty($data['id'])) {
                update('admins', $fields, 'id = ?', [$data['id']]);
                // Link/create chat user if OP
                if ($data['is_op'] && $data['email']) {
                    $chatUser = fetch("SELECT id FROM chat_users WHERE email = ?", [$data['email']]);
                    if ($chatUser) {
                        query("UPDATE chat_users SET is_op = 1 WHERE id = ?", [$chatUser['id']]);
                    }
                }
                echo json_encode(['success' => true, 'id' => $data['id']]);
            } else {
                $fields['password'] = password_hash($data['password'], PASSWORD_ARGON2ID);
                echo json_encode(['success' => true, 'id' => insert('admins', $fields)]);
            }
            break;
        case 'delete-user': if ($data['id'] != $_SESSION['admin_id']) delete('admins', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Settings
        case 'save-settings':
            foreach ($data as $key => $value) setSetting($key, $value);
            echo json_encode(['success' => true]);
            break;
        
        // Upload
        case 'upload':
            if (isset($_FILES['image']) && in_array($_FILES['image']['type'], ['image/jpeg','image/png','image/webp','image/gif','image/svg+xml'])) {
                $name = bin2hex(random_bytes(16)) . '.' . pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                if (move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $name)) {
                    echo json_encode(['success' => true, 'url' => 'uploads/' . $name]); break;
                }
            }
            echo json_encode(['success' => false, 'error' => 'Upload failed']);
            break;
        
        // AzuraCast Full Control
        case 'ac-nowplaying':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_getNowPlaying($station) : []);
            break;
        case 'ac-playlists':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_getPlaylists($station) : []);
            break;
        case 'ac-files':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_getFiles($station, $_GET['path'] ?? '') : []);
            break;
        case 'ac-queue':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_getQueue($station) : []);
            break;
        case 'ac-mounts':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_getMounts($station) : []);
            break;
        case 'ac-streamers':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_getStreamers($station) : []);
            break;
        case 'ac-toggle-playlist':
            require_once 'includes/azuracast.php';
            $station = getStation($data['station'] ?? $_GET['station'] ?? 0);
            echo json_encode($station ? ac_togglePlaylist($station, $data['playlist_id'] ?? $_POST['playlist_id']) : ['error' => 'No station']);
            break;
        case 'ac-create-playlist':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_createPlaylist($station, $data) : ['error' => 'No station']);
            break;
        case 'ac-delete-playlist':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_deletePlaylist($station, $data['playlist_id']) : ['error' => 'No station']);
            break;
        case 'ac-create-streamer':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_createStreamer($station, $data) : ['error' => 'No station']);
            break;
        case 'ac-delete-streamer':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_deleteStreamer($station, $data['streamer_id']) : ['error' => 'No station']);
            break;
        case 'ac-skip':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_skipSong($station) : ['error' => 'No station']);
            break;
        case 'ac-disconnect':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_disconnectStreamer($station) : ['error' => 'No station']);
            break;
        case 'ac-restart':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_restart($station) : ['error' => 'No station']);
            break;
        case 'ac-clear-queue':
            require_once 'includes/azuracast.php';
            $station = getStation($_GET['station'] ?? 0);
            echo json_encode($station ? ac_clearQueue($station) : ['error' => 'No station']);
            break;
        
        // Testimonials
        case 'save-testimonial':
            $fields = ['name' => $data['name'], 'role' => $data['role'] ?? '', 'content' => $data['content'], 'image' => $data['image'] ?? '', 'rating' => $data['rating'] ?? 5, 'sort_order' => $data['sort_order'] ?? 0, 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) update('testimonials', $fields, 'id = ?', [$data['id']]);
            else insert('testimonials', $fields);
            echo json_encode(['success' => true]);
            break;
        case 'delete-testimonial': delete('testimonials', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Team
        case 'save-team':
            $fields = ['name' => $data['name'], 'role' => $data['role'] ?? '', 'bio' => $data['bio'] ?? '', 'image' => $data['image'] ?? '', 'email' => $data['email'] ?? '', 'social_links' => json_encode($data['social_links'] ?? []), 'sort_order' => $data['sort_order'] ?? 0, 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) update('team', $fields, 'id = ?', [$data['id']]);
            else insert('team', $fields);
            echo json_encode(['success' => true]);
            break;
        case 'delete-team': delete('team', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Sponsors
        case 'save-sponsor':
            $fields = ['name' => $data['name'], 'logo' => $data['logo'] ?? '', 'website' => $data['website'] ?? '', 'description' => $data['description'] ?? '', 'tier' => $data['tier'] ?? 'partner', 'sort_order' => $data['sort_order'] ?? 0, 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) update('sponsors', $fields, 'id = ?', [$data['id']]);
            else insert('sponsors', $fields);
            echo json_encode(['success' => true]);
            break;
        case 'delete-sponsor': delete('sponsors', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // FAQ
        case 'save-faq':
            $fields = ['question' => $data['question'], 'answer' => $data['answer'], 'category' => $data['category'] ?? '', 'sort_order' => $data['sort_order'] ?? 0, 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) update('faq', $fields, 'id = ?', [$data['id']]);
            else insert('faq', $fields);
            echo json_encode(['success' => true]);
            break;
        case 'delete-faq': delete('faq', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Downloads
        case 'save-download':
            $fields = ['title' => $data['title'], 'description' => $data['description'] ?? '', 'file_url' => $data['file_url'], 'image' => $data['image'] ?? '', 'category' => $data['category'] ?? '', 'dj_id' => $data['dj_id'] ?: null, 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) update('downloads', $fields, 'id = ?', [$data['id']]);
            else insert('downloads', $fields);
            echo json_encode(['success' => true]);
            break;
        case 'delete-download': delete('downloads', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Products
        case 'save-product':
            $fields = ['name' => $data['name'], 'slug' => $data['slug'], 'description' => $data['description'] ?? '', 'price' => $data['price'], 'sale_price' => $data['sale_price'] ?: null, 'image' => $data['image'] ?? '', 'category' => $data['category'] ?? '', 'stock' => $data['stock'] ?? 0, 'sku' => $data['sku'] ?? '', 'active' => $data['active'] ?? 1, 'featured' => $data['featured'] ?? 0];
            if (!empty($data['id'])) update('products', $fields, 'id = ?', [$data['id']]);
            else insert('products', $fields);
            echo json_encode(['success' => true]);
            break;
        case 'delete-product': delete('products', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Orders
        case 'update-order':
            update('orders', ['status' => $data['status']], 'id = ?', [$data['id']]);
            echo json_encode(['success' => true]);
            break;
        
        // Tickets
        case 'update-ticket':
            update('tickets', ['status' => $data['status'], 'priority' => $data['priority']], 'id = ?', [$data['id']]);
            echo json_encode(['success' => true]);
            break;
        case 'ticket-reply':
            insert('ticket_replies', ['ticket_id' => $data['ticket_id'], 'admin_id' => $_SESSION['admin_id'], 'message' => $data['message'], 'is_staff' => 1]);
            update('tickets', ['status' => 'in_progress', 'updated_at' => date('Y-m-d H:i:s')], 'id = ?', [$data['ticket_id']]);
            echo json_encode(['success' => true]);
            break;
        
        // Redirects
        case 'save-redirect':
            $fields = ['source_url' => $data['source_url'], 'target_url' => $data['target_url'], 'redirect_type' => $data['redirect_type'] ?? '301', 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) update('redirects', $fields, 'id = ?', [$data['id']]);
            else insert('redirects', $fields);
            echo json_encode(['success' => true]);
            break;
        case 'delete-redirect': delete('redirects', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
        
        // Email Templates
        case 'save-email-template':
            $fields = ['name' => $data['name'], 'slug' => $data['slug'], 'subject' => $data['subject'], 'body' => $data['body'], 'active' => $data['active'] ?? 1];
            if (!empty($data['id'])) update('email_templates', $fields, 'id = ?', [$data['id']]);
            else insert('email_templates', $fields);
            echo json_encode(['success' => true]);
            break;
        case 'delete-email_template': delete('email_templates', 'id = ?', [$data['id']]); echo json_encode(['success' => true]); break;
    }
    exit;
}

$user = fetch("SELECT * FROM admins WHERE id = ?", [$_SESSION['admin_id']]);
$unreadMessages = fetch("SELECT COUNT(*) as c FROM messages WHERE is_read = 0")['c'] ?? 0;
$pendingRequests = fetch("SELECT COUNT(*) as c FROM requests WHERE status = 'pending'")['c'] ?? 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - <?= e(siteName()) ?></title>
    <link rel="stylesheet" href="assets/css/admin.css">
</head>
<?php
// Define menu groups and their pages
$menuGroups = [
    'radio' => ['stations','station-edit','azuracast','media','shows','show-edit','djs','dj-edit','schedule','live','specials','special-edit'],
    'content' => ['pages','page-edit','sliders','slider-edit','posts','post-edit','podcasts','podcast-edit','episodes','episode-edit','galleries','gallery-edit','gallery-images','downloads','download-edit','artists','artist-edit'],
    'website' => ['testimonials','testimonial-edit','team','team-edit','sponsors','sponsor-edit','faq','faq-edit','events','event-edit'],
    'shop' => ['products','product-edit','orders','order-view'],
    'communication' => ['chat','chat-rooms','chat-room-edit','requests','queue','dedications','tickets','ticket-view','messages'],
    'engagement' => ['contests','contest-edit','contest-entries','polls','poll-edit','trivia','ads','ad-edit','subscribers','comments'],
    'appearance' => ['menu','widgets','widget-edit','branding'],
    'system' => ['users','user-edit','redirects','redirect-edit','emails','email-edit','activity','analytics','gdpr','popup','settings'],
];
$currentGroup = 'dashboard';
foreach ($menuGroups as $group => $pages) {
    if (in_array($page, $pages)) { $currentGroup = $group; break; }
}
?>
<body>
    <aside class="sidebar">
        <div class="sidebar-header"><a href="admin.php"><?= e(siteName()) ?></a></div>
        <nav class="sidebar-nav">
            <a href="admin.php" class="<?= $page === 'dashboard' ? 'active' : '' ?>">📊 Dashboard</a>
            
            <div class="nav-group <?= $currentGroup === 'radio' ? 'open' : '' ?>">
                <div class="nav-group-title" onclick="toggleGroup(this)">📻 Radio <span class="arrow">›</span></div>
                <div class="nav-group-items">
                    <a href="admin.php?page=stations" class="<?= in_array($page, ['stations','station-edit']) ? 'active' : '' ?>">Stations</a>
                    <a href="admin.php?page=azuracast" class="<?= $page === 'azuracast' ? 'active' : '' ?>">AzuraCast Control</a>
                    <a href="admin.php?page=media" class="<?= $page === 'media' ? 'active' : '' ?>">Media Upload</a>
                    <a href="admin.php?page=shows" class="<?= in_array($page, ['shows','show-edit']) ? 'active' : '' ?>">Shows</a>
                    <a href="admin.php?page=djs" class="<?= in_array($page, ['djs','dj-edit']) ? 'active' : '' ?>">DJs</a>
                    <a href="admin.php?page=schedule" class="<?= $page === 'schedule' ? 'active' : '' ?>">Schedule</a>
                    <a href="admin.php?page=live" class="<?= $page === 'live' ? 'active' : '' ?>">Live Status</a>
                    <a href="admin.php?page=specials" class="<?= in_array($page, ['specials','special-edit']) ? 'active' : '' ?>">Special Broadcasts</a>
                </div>
            </div>
            
            <div class="nav-group <?= $currentGroup === 'content' ? 'open' : '' ?>">
                <div class="nav-group-title" onclick="toggleGroup(this)">📝 Content <span class="arrow">›</span></div>
                <div class="nav-group-items">
                    <a href="admin.php?page=pages" class="<?= in_array($page, ['pages','page-edit']) ? 'active' : '' ?>">Pages</a>
                    <a href="admin.php?page=sliders" class="<?= in_array($page, ['sliders','slider-edit']) ? 'active' : '' ?>">Sliders</a>
                    <a href="admin.php?page=posts" class="<?= in_array($page, ['posts','post-edit']) ? 'active' : '' ?>">Blog Posts</a>
                    <a href="admin.php?page=podcasts" class="<?= in_array($page, ['podcasts','podcast-edit','episodes','episode-edit']) ? 'active' : '' ?>">Podcasts</a>
                    <a href="admin.php?page=galleries" class="<?= in_array($page, ['galleries','gallery-edit','gallery-images']) ? 'active' : '' ?>">Galleries</a>
                    <a href="admin.php?page=downloads" class="<?= in_array($page, ['downloads','download-edit']) ? 'active' : '' ?>">Downloads</a>
                    <a href="admin.php?page=artists" class="<?= in_array($page, ['artists','artist-edit']) ? 'active' : '' ?>">Artists</a>
                </div>
            </div>
            
            <div class="nav-group <?= $currentGroup === 'website' ? 'open' : '' ?>">
                <div class="nav-group-title" onclick="toggleGroup(this)">🌐 Website <span class="arrow">›</span></div>
                <div class="nav-group-items">
                    <a href="admin.php?page=events" class="<?= in_array($page, ['events','event-edit']) ? 'active' : '' ?>">Events</a>
                    <a href="admin.php?page=testimonials" class="<?= in_array($page, ['testimonials','testimonial-edit']) ? 'active' : '' ?>">Testimonials</a>
                    <a href="admin.php?page=team" class="<?= in_array($page, ['team','team-edit']) ? 'active' : '' ?>">Team</a>
                    <a href="admin.php?page=sponsors" class="<?= in_array($page, ['sponsors','sponsor-edit']) ? 'active' : '' ?>">Sponsors</a>
                    <a href="admin.php?page=faq" class="<?= in_array($page, ['faq','faq-edit']) ? 'active' : '' ?>">FAQ</a>
                </div>
            </div>
            
            <div class="nav-group <?= $currentGroup === 'shop' ? 'open' : '' ?>">
                <div class="nav-group-title" onclick="toggleGroup(this)">🛒 Shop <span class="arrow">›</span></div>
                <div class="nav-group-items">
                    <a href="admin.php?page=products" class="<?= in_array($page, ['products','product-edit']) ? 'active' : '' ?>">Products</a>
                    <a href="admin.php?page=orders" class="<?= in_array($page, ['orders','order-view']) ? 'active' : '' ?>">Orders</a>
                </div>
            </div>
            
            <div class="nav-group <?= $currentGroup === 'communication' ? 'open' : '' ?>">
                <div class="nav-group-title" onclick="toggleGroup(this)">💬 Communication <span class="arrow">›</span><?= ($pendingRequests + $unreadMessages) ? "<span class='badge-count'>".($pendingRequests + $unreadMessages)."</span>" : '' ?></div>
                <div class="nav-group-items">
                    <a href="admin.php?page=chat" class="<?= in_array($page, ['chat','chat-rooms','chat-room-edit']) ? 'active' : '' ?>">Chat</a>
                    <a href="admin.php?page=requests" class="<?= $page === 'requests' ? 'active' : '' ?>">Requests <?= $pendingRequests ? "<span class='badge-count'>$pendingRequests</span>" : '' ?></a>
                    <a href="admin.php?page=queue" class="<?= $page === 'queue' ? 'active' : '' ?>">Queue</a>
                    <a href="admin.php?page=dedications" class="<?= $page === 'dedications' ? 'active' : '' ?>">Dedications</a>
                    <a href="admin.php?page=tickets" class="<?= in_array($page, ['tickets','ticket-view']) ? 'active' : '' ?>">Tickets</a>
                    <a href="admin.php?page=messages" class="<?= $page === 'messages' ? 'active' : '' ?>">Messages <?= $unreadMessages ? "<span class='badge-count'>$unreadMessages</span>" : '' ?></a>
                </div>
            </div>
            
            <div class="nav-group <?= $currentGroup === 'engagement' ? 'open' : '' ?>">
                <div class="nav-group-title" onclick="toggleGroup(this)">🎯 Engagement <span class="arrow">›</span></div>
                <div class="nav-group-items">
                    <a href="admin.php?page=contests" class="<?= in_array($page, ['contests','contest-edit','contest-entries']) ? 'active' : '' ?>">Contests</a>
                    <a href="admin.php?page=polls" class="<?= in_array($page, ['polls','poll-edit']) ? 'active' : '' ?>">Polls</a>
                    <a href="admin.php?page=trivia" class="<?= $page === 'trivia' ? 'active' : '' ?>">Trivia</a>
                    <a href="admin.php?page=ads" class="<?= in_array($page, ['ads','ad-edit']) ? 'active' : '' ?>">Ads</a>
                    <a href="admin.php?page=subscribers" class="<?= $page === 'subscribers' ? 'active' : '' ?>">Subscribers</a>
                    <a href="admin.php?page=comments" class="<?= $page === 'comments' ? 'active' : '' ?>">Comments</a>
                </div>
            </div>
            
            <div class="nav-group <?= $currentGroup === 'appearance' ? 'open' : '' ?>">
                <div class="nav-group-title" onclick="toggleGroup(this)">🎨 Appearance <span class="arrow">›</span></div>
                <div class="nav-group-items">
                    <a href="admin.php?page=menu" class="<?= $page === 'menu' ? 'active' : '' ?>">Menus</a>
                    <a href="admin.php?page=widgets" class="<?= in_array($page, ['widgets','widget-edit']) ? 'active' : '' ?>">Widgets</a>
                    <a href="admin.php?page=branding" class="<?= $page === 'branding' ? 'active' : '' ?>">Branding</a>
                </div>
            </div>
            
            <div class="nav-group <?= $currentGroup === 'system' ? 'open' : '' ?>">
                <div class="nav-group-title" onclick="toggleGroup(this)">⚙️ System <span class="arrow">›</span></div>
                <div class="nav-group-items">
                    <a href="admin.php?page=users" class="<?= in_array($page, ['users','user-edit']) ? 'active' : '' ?>">Users</a>
                    <a href="admin.php?page=redirects" class="<?= in_array($page, ['redirects','redirect-edit']) ? 'active' : '' ?>">Redirects</a>
                    <a href="admin.php?page=emails" class="<?= in_array($page, ['emails','email-edit']) ? 'active' : '' ?>">Email Templates</a>
                    <a href="admin.php?page=activity" class="<?= $page === 'activity' ? 'active' : '' ?>">Activity Log</a>
                    <a href="admin.php?page=analytics" class="<?= $page === 'analytics' ? 'active' : '' ?>">Analytics</a>
                    <a href="admin.php?page=gdpr" class="<?= $page === 'gdpr' ? 'active' : '' ?>">GDPR</a>
                    <a href="admin.php?page=popup" class="<?= $page === 'popup' ? 'active' : '' ?>">Popup</a>
                    <a href="admin.php?page=settings" class="<?= $page === 'settings' ? 'active' : '' ?>">Settings</a>
                </div>
            </div>
        </nav>
        <div class="sidebar-footer">
            <a href="index.php" target="_blank">View Site</a>
            <a href="admin.php?page=logout">Logout</a>
        </div>
    </aside>
    <main class="admin-main">
        <header class="admin-header">
            <h1><?= ucfirst(str_replace('-', ' ', $page)) ?></h1>
            <span><?= e($user['username']) ?></span>
        </header>
        <div class="admin-content">
            <?php include "admin/{$page}.php"; ?>
        </div>
    </main>
    <script src="assets/js/admin.js"></script>
    <script>
    function toggleGroup(el){el.parentElement.classList.toggle('open')}
    </script>
</body>
</html>
