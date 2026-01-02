<?php
$footerMenu = getMenuItems('footer');
$widgets1 = getWidgets('footer_1');
$widgets2 = getWidgets('footer_2');
$widgets3 = getWidgets('footer_3');
$socials = array_filter([
    'facebook' => getSetting('social_facebook'),
    'instagram' => getSetting('social_instagram'),
    'twitter' => getSetting('social_twitter'),
    'youtube' => getSetting('social_youtube'),
    'soundcloud' => getSetting('social_soundcloud'),
    'mixcloud' => getSetting('social_mixcloud'),
    'tiktok' => getSetting('social_tiktok'),
    'discord' => getSetting('social_discord'),
]);
$socialIcons = ['facebook'=>'FB','instagram'=>'IG','twitter'=>'X','youtube'=>'YT','soundcloud'=>'SC','mixcloud'=>'MX','tiktok'=>'TT','discord'=>'DC'];
?>
<footer class="footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand">
                <a href="index.php" class="logo">
                    <?php if ($logo = logoUrl()): ?>
                        <img src="<?= e($logo) ?>" alt="<?= e(siteName()) ?>">
                    <?php else: ?>
                        <span class="logo-text"><?= e(logoText() ?: siteName()) ?></span>
                    <?php endif; ?>
                </a>
                <p><?= e(getSetting('site_description', 'Your home for the best music 24/7. Tune in and enjoy non-stop entertainment.')) ?></p>
                <?php if (!empty($socials)): ?>
                <div class="footer-social">
                    <?php foreach ($socials as $name => $url): ?>
                    <a href="<?= e($url) ?>" target="_blank" title="<?= ucfirst($name) ?>"><?= $socialIcons[$name] ?? strtoupper(substr($name,0,2)) ?></a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="footer-col">
                <h4>Quick Links</h4>
                <?php if (!empty($footerMenu)): ?>
                    <?php foreach ($footerMenu as $item): ?>
                    <a href="<?= e($item['url']) ?>"><?= e($item['label']) ?></a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <a href="schedule.php">Schedule</a>
                    <a href="shows.php">Shows</a>
                    <a href="djs.php">DJs</a>
                    <a href="events.php">Events</a>
                    <a href="blog.php">Blog</a>
                <?php endif; ?>
            </div>
            
            <div class="footer-col">
                <h4>Listen</h4>
                <a href="request.php">Song Request</a>
                <a href="dedications.php">Dedications</a>
                <a href="history.php">Recently Played</a>
                <a href="charts.php">Charts</a>
                <a href="podcasts.php">Podcasts</a>
            </div>
            
            <div class="footer-col">
                <h4>Connect</h4>
                <a href="chat.php">Live Chat</a>
                <a href="contact.php">Contact Us</a>
                <?php if ($email = getSetting('contact_email')): ?>
                <a href="mailto:<?= e($email) ?>"><?= e($email) ?></a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p><?= copyright() ?></p>
            <div class="footer-links">
                <a href="page.php?slug=privacy">Privacy Policy</a>
                <a href="page.php?slug=terms">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>

<audio id="radio-player"></audio>

<?php if (getSetting('gdpr_enabled', '1') === '1'): ?>
<div id="gdpr-popup" class="gdpr-popup gdpr-<?= e(getSetting('gdpr_position', 'bottom')) ?> <?= getSetting('gdpr_style', 'bar') === 'box' ? 'gdpr-box' : '' ?>" style="display:none">
    <div class="gdpr-content">
        <div class="gdpr-text">
            <strong><?= e(getSetting('gdpr_title', 'Cookie Consent')) ?></strong>
            <p><?= e(getSetting('gdpr_message', 'We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.')) ?></p>
            <div class="gdpr-links">
                <?php if ($privacy = getSetting('gdpr_privacy_url')): ?><a href="<?= e($privacy) ?>">Privacy Policy</a><?php endif; ?>
                <?php if ($cookies = getSetting('gdpr_cookies_url')): ?><a href="<?= e($cookies) ?>">Cookie Policy</a><?php endif; ?>
            </div>
        </div>
        <div class="gdpr-buttons">
            <button onclick="gdprAccept()" class="gdpr-btn gdpr-accept"><?= e(getSetting('gdpr_accept_text', 'Accept')) ?></button>
            <button onclick="gdprDecline()" class="gdpr-btn gdpr-decline"><?= e(getSetting('gdpr_decline_text', 'Decline')) ?></button>
        </div>
    </div>
</div>
<script>
if(!localStorage.getItem('gdpr_consent')){document.getElementById('gdpr-popup').style.display='flex'}
function gdprAccept(){localStorage.setItem('gdpr_consent','accepted');document.getElementById('gdpr-popup').style.display='none'}
function gdprDecline(){localStorage.setItem('gdpr_consent','declined');document.getElementById('gdpr-popup').style.display='none'}
</script>
<?php endif; ?>

<?php if (getSetting('popup_enabled', '0') === '1'): $popupId = getSetting('popup_id', '1'); ?>
<div id="notification-popup" class="notif-popup" style="display:none">
    <div class="notif-overlay" onclick="closePopup()"></div>
    <div class="notif-content">
        <button class="notif-close" onclick="closePopup()">×</button>
        <?php if ($img = getSetting('popup_image')): ?><img src="<?= e($img) ?>" class="notif-image"><?php endif; ?>
        <h3><?= e(getSetting('popup_title', 'Welcome!')) ?></h3>
        <p><?= e(getSetting('popup_message')) ?></p>
        <?php $btnUrl = getSetting('popup_button_url'); ?>
        <?php if ($btnUrl): ?>
        <a href="<?= e($btnUrl) ?>" class="notif-btn"><?= e(getSetting('popup_button_text', 'Learn More')) ?></a>
        <?php else: ?>
        <button onclick="closePopup()" class="notif-btn"><?= e(getSetting('popup_button_text', 'Got it')) ?></button>
        <?php endif; ?>
    </div>
</div>
<script>
(function(){
    var pid='popup_<?= $popupId ?>';
    var once=<?= getSetting('popup_show_once', '1') === '1' ? 'true' : 'false' ?>;
    if(once && localStorage.getItem(pid))return;
    setTimeout(function(){document.getElementById('notification-popup').style.display='flex'},<?= (int)getSetting('popup_delay', '3') ?>*1000);
})();
function closePopup(){document.getElementById('notification-popup').style.display='none';localStorage.setItem('popup_<?= $popupId ?>','1')}
</script>
<?php endif; ?>

<script src="assets/js/app.js" defer></script>
<?= getSetting('custom_footer_code') ?>
