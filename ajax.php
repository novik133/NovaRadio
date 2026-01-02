<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'subscribe':
        if (getSetting('newsletter_enabled', '1') !== '1') die(json_encode(['error' => 'Newsletter disabled']));
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        $name = trim($_POST['name'] ?? '');
        if (!$email) die(json_encode(['error' => 'Invalid email']));
        if (fetch("SELECT id FROM subscribers WHERE email = ?", [$email])) die(json_encode(['error' => 'Already subscribed']));
        $token = bin2hex(random_bytes(32));
        insert('subscribers', ['email' => $email, 'name' => $name, 'token' => $token]);
        echo json_encode(['success' => true, 'message' => 'Subscribed successfully!']);
        break;

    case 'vote':
        if (getSetting('polls_enabled', '1') !== '1') die(json_encode(['error' => 'Polls disabled']));
        $pollId = (int)($_POST['poll_id'] ?? 0);
        $optionId = (int)($_POST['option_id'] ?? 0);
        $ip = $_SERVER['REMOTE_ADDR'];
        $poll = fetch("SELECT * FROM polls WHERE id = ? AND active = 1", [$pollId]);
        if (!$poll) die(json_encode(['error' => 'Poll not found']));
        if ($poll['ends_at'] && strtotime($poll['ends_at']) < time()) die(json_encode(['error' => 'Poll ended']));
        if (fetch("SELECT id FROM poll_votes WHERE poll_id = ? AND ip_address = ?", [$pollId, $ip])) die(json_encode(['error' => 'Already voted']));
        insert('poll_votes', ['poll_id' => $pollId, 'option_id' => $optionId, 'ip_address' => $ip]);
        query("UPDATE poll_options SET votes = votes + 1 WHERE id = ?", [$optionId]);
        echo json_encode(['success' => true]);
        break;

    case 'poll_results':
        $pollId = (int)($_GET['poll_id'] ?? 0);
        $options = fetchAll("SELECT * FROM poll_options WHERE poll_id = ?", [$pollId]);
        $total = array_sum(array_column($options, 'votes'));
        foreach ($options as &$o) $o['percent'] = $total ? round($o['votes'] / $total * 100) : 0;
        echo json_encode(['options' => $options, 'total' => $total]);
        break;

    case 'track_ad':
        $adId = (int)($_POST['ad_id'] ?? 0);
        $type = $_POST['type'] ?? 'impression';
        if ($type === 'click') query("UPDATE ads SET clicks = clicks + 1 WHERE id = ?", [$adId]);
        else query("UPDATE ads SET impressions = impressions + 1 WHERE id = ?", [$adId]);
        echo json_encode(['success' => true]);
        break;

    case 'track_download':
        $episodeId = (int)($_POST['episode_id'] ?? 0);
        query("UPDATE episodes SET downloads = downloads + 1 WHERE id = ?", [$episodeId]);
        echo json_encode(['success' => true]);
        break;

    case 'shoutbox_send':
        if (getSetting('shoutbox_enabled', '1') !== '1') die(json_encode(['error' => 'Shoutbox disabled']));
        $name = trim($_POST['name'] ?? '');
        $message = trim($_POST['message'] ?? '');
        if (!$name || !$message || strlen($message) > 255) die(json_encode(['error' => 'Invalid input']));
        insert('shoutbox', ['name' => $name, 'message' => $message, 'ip_address' => $_SERVER['REMOTE_ADDR']]);
        echo json_encode(['success' => true]);
        break;

    case 'shoutbox_get':
        $messages = fetchAll("SELECT name, message, created_at FROM shoutbox WHERE approved = 1 ORDER BY created_at DESC LIMIT 30");
        echo json_encode($messages);
        break;

    case 'favorite':
        if (getSetting('favorites_enabled', '1') !== '1') die(json_encode(['error' => 'Favorites disabled']));
        $itemType = $_POST['item_type'] ?? '';
        $itemId = (int)($_POST['item_id'] ?? 0);
        $visitorId = $_COOKIE['visitor_id'] ?? bin2hex(random_bytes(16));
        setcookie('visitor_id', $visitorId, time() + 86400 * 365, '/');
        if (!in_array($itemType, ['show','dj','podcast','track'])) die(json_encode(['error' => 'Invalid type']));
        $exists = fetch("SELECT id FROM favorites WHERE visitor_id = ? AND item_type = ? AND item_id = ?", [$visitorId, $itemType, $itemId]);
        if ($exists) {
            delete('favorites', 'id = ?', [$exists['id']]);
            echo json_encode(['success' => true, 'favorited' => false]);
        } else {
            insert('favorites', ['visitor_id' => $visitorId, 'item_type' => $itemType, 'item_id' => $itemId]);
            echo json_encode(['success' => true, 'favorited' => true]);
        }
        break;

    case 'is_favorite':
        $itemType = $_GET['item_type'] ?? '';
        $itemId = (int)($_GET['item_id'] ?? 0);
        $visitorId = $_COOKIE['visitor_id'] ?? '';
        $exists = $visitorId ? fetch("SELECT id FROM favorites WHERE visitor_id = ? AND item_type = ? AND item_id = ?", [$visitorId, $itemType, $itemId]) : null;
        echo json_encode(['favorited' => (bool)$exists]);
        break;

    case 'share_count':
        $page = $_POST['page'] ?? '';
        query("INSERT INTO analytics (page, views, date) VALUES (?, 1, CURDATE()) ON DUPLICATE KEY UPDATE views = views + 1", ['share_' . $page]);
        echo json_encode(['success' => true]);
        break;

    case 'track_reaction':
        if (getSetting('track_reactions', '1') !== '1') die(json_encode(['error' => 'Disabled']));
        $reaction = $_POST['reaction'] ?? '';
        $artist = trim($_POST['artist'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $stationId = (int)($_POST['station_id'] ?? 0);
        $ip = $_SERVER['REMOTE_ADDR'];
        if (!in_array($reaction, ['like', 'dislike']) || !$artist) die(json_encode(['error' => 'Invalid']));
        $exists = fetch("SELECT id FROM track_reactions WHERE station_id = ? AND artist = ? AND title = ? AND ip_address = ?", [$stationId, $artist, $title, $ip]);
        if ($exists) die(json_encode(['error' => 'Already reacted']));
        insert('track_reactions', ['station_id' => $stationId, 'artist' => $artist, 'title' => $title, 'reaction' => $reaction, 'ip_address' => $ip]);
        echo json_encode(['success' => true]);
        break;

    case 'request_queue':
        $stationId = (int)($_GET['station_id'] ?? 0);
        $queue = fetchAll("SELECT * FROM request_queue WHERE station_id = ? AND status = 'queued' ORDER BY position, created_at LIMIT 10", [$stationId]);
        echo json_encode($queue);
        break;

    case 'add_to_queue':
        if (getSetting('request_queue_enabled', '1') !== '1') die(json_encode(['error' => 'Disabled']));
        $artist = trim($_POST['artist'] ?? '');
        $title = trim($_POST['title'] ?? '');
        $name = trim($_POST['name'] ?? 'Anonymous');
        $stationId = (int)($_POST['station_id'] ?? 0);
        if (!$artist || !$title) die(json_encode(['error' => 'Artist and title required']));
        $position = (fetch("SELECT MAX(position) as p FROM request_queue WHERE station_id = ? AND status = 'queued'", [$stationId])['p'] ?? 0) + 1;
        insert('request_queue', ['station_id' => $stationId, 'artist' => $artist, 'title' => $title, 'requested_by' => $name, 'position' => $position]);
        echo json_encode(['success' => true, 'position' => $position]);
        break;

    case 'trivia_question':
        if (getSetting('trivia_enabled', '1') !== '1') die(json_encode(['error' => 'Disabled']));
        $q = fetch("SELECT id, question, correct_answer, wrong_answers, points FROM trivia WHERE active = 1 ORDER BY RAND() LIMIT 1");
        if (!$q) die(json_encode(['error' => 'No questions']));
        $answers = array_merge([$q['correct_answer']], json_decode($q['wrong_answers'], true) ?: []);
        shuffle($answers);
        echo json_encode(['id' => $q['id'], 'question' => $q['question'], 'answers' => $answers, 'points' => $q['points']]);
        break;

    case 'trivia_answer':
        $questionId = (int)($_POST['question_id'] ?? 0);
        $answer = trim($_POST['answer'] ?? '');
        $visitorId = $_COOKIE['visitor_id'] ?? '';
        $q = fetch("SELECT correct_answer, points FROM trivia WHERE id = ?", [$questionId]);
        if (!$q) die(json_encode(['error' => 'Invalid question']));
        $correct = strtolower($answer) === strtolower($q['correct_answer']);
        if ($correct && $visitorId) {
            $score = fetch("SELECT * FROM trivia_scores WHERE visitor_id = ?", [$visitorId]);
            if ($score) query("UPDATE trivia_scores SET score = score + ?, games_played = games_played + 1 WHERE id = ?", [$q['points'], $score['id']]);
            else insert('trivia_scores', ['visitor_id' => $visitorId, 'score' => $q['points'], 'games_played' => 1]);
        }
        echo json_encode(['correct' => $correct, 'correct_answer' => $q['correct_answer'], 'points' => $correct ? $q['points'] : 0]);
        break;

    case 'trivia_leaderboard':
        $leaders = fetchAll("SELECT username, score, games_played FROM trivia_scores ORDER BY score DESC LIMIT 10");
        echo json_encode($leaders);
        break;

    case 'set_reminder':
        $scheduleId = (int)($_POST['schedule_id'] ?? 0);
        $email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
        if (!$scheduleId || !$email) die(json_encode(['error' => 'Invalid input']));
        if (fetch("SELECT id FROM show_reminders WHERE schedule_id = ? AND email = ?", [$scheduleId, $email])) die(json_encode(['error' => 'Already set']));
        insert('show_reminders', ['schedule_id' => $scheduleId, 'email' => $email]);
        echo json_encode(['success' => true]);
        break;

    default:
        echo json_encode(['error' => 'Invalid action']);
}
