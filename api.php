<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$action = $_GET['action'] ?? '';
$stationId = $_GET['station'] ?? null;
$station = $stationId ? getStation($stationId) : getDefaultStation();

switch ($action) {
    case 'nowplaying':
        if ($station) {
            $np = getNowPlaying($station);
            $live = fetch("SELECT * FROM live_status WHERE station_id = ? AND is_live = 1", [$station['id']]);
            echo json_encode(['nowplaying' => $np, 'live' => $live ?: null, 'station' => ['id' => $station['id'], 'name' => $station['name']]]);
        } else {
            echo json_encode(['error' => 'No station configured']);
        }
        break;
        
    case 'history':
        $limit = min((int)($_GET['limit'] ?? 20), 100);
        if ($station) {
            $history = fetchAll("SELECT * FROM song_history WHERE station_id = ? ORDER BY played_at DESC LIMIT ?", [$station['id'], $limit]);
            if (empty($history)) $history = getHistory($station, $limit);
            echo json_encode($history);
        } else {
            echo json_encode([]);
        }
        break;
        
    case 'stations':
        $stations = getStations();
        foreach ($stations as &$s) {
            $s['mounts'] = fetchAll("SELECT * FROM stream_mounts WHERE station_id = ? ORDER BY sort_order", [$s['id']]);
        }
        echo json_encode($stations);
        break;
        
    case 'schedule':
        $day = $_GET['day'] ?? date('N');
        $schedule = fetchAll("SELECT s.*, sh.name as show_name, sh.image as show_image, d.name as dj_name, d.image as dj_image FROM schedule s LEFT JOIN shows sh ON s.show_id = sh.id LEFT JOIN djs d ON s.dj_id = d.id WHERE s.day_of_week = ? " . ($stationId ? "AND s.station_id = ?" : "") . " ORDER BY s.start_time", $stationId ? [$day, $stationId] : [$day]);
        echo json_encode($schedule);
        break;
        
    case 'shows':
        $shows = fetchAll("SELECT * FROM shows WHERE active = 1 ORDER BY name");
        echo json_encode($shows);
        break;
        
    case 'djs':
        $djs = fetchAll("SELECT * FROM djs WHERE active = 1 ORDER BY name");
        echo json_encode($djs);
        break;
        
    case 'events':
        $events = fetchAll("SELECT * FROM events WHERE active = 1 AND event_date >= NOW() ORDER BY event_date LIMIT 20");
        echo json_encode($events);
        break;
        
    case 'specials':
        $specials = fetchAll("SELECT sb.*, d.name as dj_name FROM special_broadcasts sb LEFT JOIN djs d ON sb.dj_id = d.id WHERE sb.end_time > NOW() ORDER BY sb.start_time");
        echo json_encode($specials);
        break;
        
    case 'charts':
        $period = $_GET['period'] ?? 'weekly';
        $charts = fetchAll("SELECT * FROM charts WHERE station_id = ? AND period = ? ORDER BY chart_date DESC, position LIMIT 20", [$station['id'] ?? 0, $period]);
        echo json_encode($charts);
        break;
        
    case 'contests':
        $contests = fetchAll("SELECT id, title, description, image, prize, start_date, end_date FROM contests WHERE active = 1 AND end_date > NOW() ORDER BY end_date");
        echo json_encode($contests);
        break;
        
    case 'podcasts':
        $podcasts = fetchAll("SELECT p.*, (SELECT COUNT(*) FROM episodes WHERE podcast_id = p.id AND active = 1) as episode_count FROM podcasts p WHERE p.active = 1 ORDER BY p.created_at DESC");
        echo json_encode($podcasts);
        break;
        
    case 'episodes':
        $podcastId = (int)($_GET['podcast'] ?? 0);
        $episodes = fetchAll("SELECT * FROM episodes WHERE podcast_id = ? AND active = 1 ORDER BY published_at DESC", [$podcastId]);
        echo json_encode($episodes);
        break;
        
    case 'posts':
        $limit = min((int)($_GET['limit'] ?? 10), 50);
        $posts = fetchAll("SELECT id, title, slug, excerpt, image, category, published_at FROM posts WHERE active = 1 ORDER BY published_at DESC LIMIT ?", [$limit]);
        echo json_encode($posts);
        break;
        
    case 'shoutbox':
        $messages = fetchAll("SELECT name, message, created_at FROM shoutbox WHERE approved = 1 ORDER BY created_at DESC LIMIT 50");
        echo json_encode($messages);
        break;
        
    case 'live':
        $live = fetch("SELECT ls.*, s.name as station_name, sh.name as show_name, d.name as dj_name FROM live_status ls LEFT JOIN stations s ON ls.station_id = s.id LEFT JOIN shows sh ON ls.show_id = sh.id LEFT JOIN djs d ON ls.dj_id = d.id WHERE ls.station_id = ?", [$station['id'] ?? 0]);
        echo json_encode($live ?: ['is_live' => false]);
        break;
        
    case 'stats':
        $stats = fetch("SELECT * FROM listener_stats WHERE station_id = ? ORDER BY recorded_at DESC LIMIT 1", [$station['id'] ?? 0]);
        echo json_encode($stats ?: ['listeners' => 0]);
        break;
        
    case 'testimonials':
        echo json_encode(fetchAll("SELECT name, role, image, content, rating FROM testimonials WHERE active = 1 ORDER BY sort_order"));
        break;
        
    case 'team':
        echo json_encode(fetchAll("SELECT name, role, bio, image, social_links FROM team WHERE active = 1 ORDER BY sort_order"));
        break;
        
    case 'sponsors':
        echo json_encode(fetchAll("SELECT name, logo, website, description, tier FROM sponsors WHERE active = 1 ORDER BY FIELD(tier,'platinum','gold','silver','bronze','partner'), sort_order"));
        break;
        
    case 'faq':
        echo json_encode(fetchAll("SELECT question, answer, category FROM faq WHERE active = 1 ORDER BY category, sort_order"));
        break;
        
    case 'downloads':
        echo json_encode(fetchAll("SELECT d.id, d.title, d.description, d.file_url, d.image, d.category, d.download_count, dj.name as dj_name FROM downloads d LEFT JOIN djs dj ON d.dj_id = dj.id WHERE d.active = 1 ORDER BY d.created_at DESC"));
        break;
        
    case 'products':
        echo json_encode(fetchAll("SELECT id, name, slug, description, price, sale_price, image, category, stock FROM products WHERE active = 1 ORDER BY featured DESC, created_at DESC"));
        break;
        
    default:
        echo json_encode(['error' => 'Invalid action', 'available' => ['nowplaying','history','stations','schedule','shows','djs','events','specials','charts','contests','podcasts','episodes','posts','shoutbox','live','stats']]);
}
