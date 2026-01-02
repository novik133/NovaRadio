<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

$success = false;
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (!$name || !$email || !$message) {
        $error = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        insert('messages', [
            'name' => $name,
            'email' => $email,
            'subject' => $subject,
            'message' => $message
        ]);
        $success = true;
    }
}
trackPageView('contact');

$contactEmail = getSetting('contact_email');
$contactPhone = getSetting('contact_phone');
$socials = array_filter([
    'facebook' => getSetting('social_facebook'),
    'instagram' => getSetting('social_instagram'),
    'twitter' => getSetting('social_twitter'),
    'discord' => getSetting('social_discord'),
]);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact - <?= e(SITE_NAME) ?></title>
    <meta name="description" content="Get in touch with <?= e(SITE_NAME) ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <main class="page-content">
        <div class="container">
            <div class="page-header">
                <span class="section-badge">Get in Touch</span>
                <h1 class="page-title">Contact Us</h1>
                <p class="page-subtitle">Have a question, suggestion, or just want to say hi? We'd love to hear from you!</p>
            </div>
            
            <div class="grid grid-2" style="gap:3rem;max-width:1000px;margin:0 auto">
                <div>
                    <?php if ($success): ?>
                    <div class="alert alert-success">
                        <span style="font-size:1.5rem">✓</span>
                        <div>
                            <strong>Message Sent!</strong><br>
                            Thank you for reaching out. We'll get back to you as soon as possible.
                        </div>
                    </div>
                    <a href="contact.php" class="btn btn-outline">Send Another Message</a>
                    <?php else: ?>
                    
                    <?php if ($error): ?>
                    <div class="alert alert-danger"><?= e($error) ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" class="form" style="max-width:none">
                        <div class="grid grid-2" style="gap:1rem">
                            <div class="form-group">
                                <label>Your Name *</label>
                                <input type="text" name="name" required placeholder="John Doe" value="<?= e($_POST['name'] ?? '') ?>">
                            </div>
                            <div class="form-group">
                                <label>Email Address *</label>
                                <input type="email" name="email" required placeholder="john@example.com" value="<?= e($_POST['email'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Subject</label>
                            <select name="subject">
                                <option value="General Inquiry">General Inquiry</option>
                                <option value="Song Request">Song Request</option>
                                <option value="DJ Application">DJ Application</option>
                                <option value="Advertising">Advertising</option>
                                <option value="Technical Issue">Technical Issue</option>
                                <option value="Feedback">Feedback</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Message *</label>
                            <textarea name="message" rows="6" required placeholder="Write your message here..."><?= e($_POST['message'] ?? '') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg" style="width:100%">
                            Send Message →
                        </button>
                    </form>
                    <?php endif; ?>
                </div>
                
                <div>
                    <div class="card" style="padding:2rem;margin-bottom:1.5rem">
                        <h3 style="margin-bottom:1.5rem">Other Ways to Reach Us</h3>
                        
                        <?php if ($contactEmail): ?>
                        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem">
                            <div style="width:48px;height:48px;background:rgba(99,102,241,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.25rem">📧</div>
                            <div>
                                <div style="font-size:0.85rem;color:var(--text-muted)">Email</div>
                                <a href="mailto:<?= e($contactEmail) ?>" style="color:var(--primary);text-decoration:none"><?= e($contactEmail) ?></a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($contactPhone): ?>
                        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1.25rem">
                            <div style="width:48px;height:48px;background:rgba(99,102,241,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.25rem">📞</div>
                            <div>
                                <div style="font-size:0.85rem;color:var(--text-muted)">Phone</div>
                                <span><?= e($contactPhone) ?></span>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div style="display:flex;align-items:center;gap:1rem">
                            <div style="width:48px;height:48px;background:rgba(99,102,241,0.1);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.25rem">💬</div>
                            <div>
                                <div style="font-size:0.85rem;color:var(--text-muted)">Live Chat</div>
                                <a href="chat.php" style="color:var(--primary);text-decoration:none">Join our chat room</a>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($socials)): ?>
                    <div class="card" style="padding:2rem">
                        <h3 style="margin-bottom:1rem">Follow Us</h3>
                        <p style="color:var(--text-muted);margin-bottom:1.5rem">Stay connected on social media for updates, behind-the-scenes content, and more.</p>
                        <div class="footer-social" style="justify-content:flex-start">
                            <?php foreach ($socials as $name => $url): ?>
                            <a href="<?= e($url) ?>" target="_blank" title="<?= ucfirst($name) ?>"><?= strtoupper(substr($name, 0, 2)) ?></a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
