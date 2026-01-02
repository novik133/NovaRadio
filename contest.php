<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$id = (int)($_GET['id'] ?? 0);
$contest = fetch("SELECT * FROM contests WHERE id = ? AND active = 1", [$id]);
if (!$contest) { header('Location: contests.php'); exit; }

$entered = false;
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $answer = trim($_POST['answer'] ?? '');
    $ip = $_SERVER['REMOTE_ADDR'];
    
    if (!$name || !$email) {
        $error = 'Name and email are required';
    } elseif (fetch("SELECT id FROM contest_entries WHERE contest_id = ? AND (email = ? OR ip_address = ?)", [$id, $email, $ip])) {
        $error = 'You have already entered this contest';
    } else {
        insert('contest_entries', ['contest_id' => $id, 'name' => $name, 'email' => $email, 'phone' => $phone, 'answer' => $answer, 'ip_address' => $ip]);
        $success = 'Entry submitted! Good luck!';
        $entered = true;
    }
}

trackPageView('contest-' . $id);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($contest['title']) ?> - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container">
        <div class="contest-single">
            <?php if ($contest['image']): ?><img src="<?= e($contest['image']) ?>" class="contest-hero"><?php endif; ?>
            <h1><?= e($contest['title']) ?></h1>
            <div class="contest-meta">
                <span>🎁 Prize: <strong><?= e($contest['prize']) ?></strong></span>
                <span>⏰ Ends: <?= date('M j, Y g:i A', strtotime($contest['end_date'])) ?></span>
            </div>
            <div class="contest-description"><?= nl2br(e($contest['description'])) ?></div>
            
            <?php if ($contest['rules']): ?>
            <div class="contest-rules">
                <h3>Rules</h3>
                <?= nl2br(e($contest['rules'])) ?>
            </div>
            <?php endif; ?>
            
            <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
            <?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
            
            <?php if (!$entered && strtotime($contest['end_date']) > time()): ?>
            <form method="post" class="contest-form">
                <h3>Enter Contest</h3>
                <div class="form-row">
                    <div class="form-group"><label>Name *</label><input type="text" name="name" required></div>
                    <div class="form-group"><label>Email *</label><input type="email" name="email" required></div>
                </div>
                <div class="form-group"><label>Phone</label><input type="tel" name="phone"></div>
                <div class="form-group"><label>Your Answer / Message</label><textarea name="answer" rows="3"></textarea></div>
                <button type="submit" class="btn btn-primary">Submit Entry</button>
            </form>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
</body>
</html>
