<?php
/**
 * NovaRadio CMS Installer
 */
session_start();

// Check if already installed
if (file_exists('config.php')) {
    $config = file_get_contents('config.php');
    if (strpos($config, 'DB_HOST') !== false && strpos($config, "''") === false) {
        header('Location: index.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    $data = json_decode(file_get_contents('php://input'), true);
    
    if ($data['action'] === 'check_requirements') {
        $checks = [
            'php_version' => ['name' => 'PHP 8.0+', 'ok' => version_compare(PHP_VERSION, '8.0.0', '>='), 'value' => PHP_VERSION],
            'pdo' => ['name' => 'PDO Extension', 'ok' => extension_loaded('pdo'), 'value' => extension_loaded('pdo') ? 'Loaded' : 'Missing'],
            'pdo_mysql' => ['name' => 'PDO MySQL', 'ok' => extension_loaded('pdo_mysql'), 'value' => extension_loaded('pdo_mysql') ? 'Loaded' : 'Missing'],
            'curl' => ['name' => 'cURL Extension', 'ok' => extension_loaded('curl'), 'value' => extension_loaded('curl') ? 'Loaded' : 'Missing'],
            'json' => ['name' => 'JSON Extension', 'ok' => extension_loaded('json'), 'value' => extension_loaded('json') ? 'Loaded' : 'Missing'],
            'session' => ['name' => 'Session Support', 'ok' => extension_loaded('session'), 'value' => extension_loaded('session') ? 'Loaded' : 'Missing'],
            'mbstring' => ['name' => 'Mbstring Extension', 'ok' => extension_loaded('mbstring'), 'value' => extension_loaded('mbstring') ? 'Loaded' : 'Optional'],
            'uploads_writable' => ['name' => 'Uploads Writable', 'ok' => is_writable('.') || @mkdir('uploads', 0755), 'value' => is_writable('.') ? 'Writable' : 'Check permissions'],
            'config_writable' => ['name' => 'Config Writable', 'ok' => is_writable('.') || !file_exists('config.php'), 'value' => is_writable('.') ? 'Writable' : 'Check permissions'],
        ];
        $allOk = true;
        foreach (['php_version', 'pdo', 'pdo_mysql', 'curl', 'json', 'session', 'config_writable'] as $req) {
            if (!$checks[$req]['ok']) $allOk = false;
        }
        echo json_encode(['checks' => $checks, 'ok' => $allOk]);
        exit;
    }
    
    if ($data['action'] === 'test_db') {
        try {
            $pdo = new PDO("mysql:host={$data['host']};charset=utf8mb4", $data['user'], $data['pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$data['name']}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
    
    if ($data['action'] === 'install') {
        try {
            $config = "<?php\ndefine('DB_HOST', '{$data['db_host']}');\ndefine('DB_NAME', '{$data['db_name']}');\ndefine('DB_USER', '{$data['db_user']}');\ndefine('DB_PASS', '{$data['db_pass']}');\ndefine('SITE_NAME', '{$data['site_name']}');\ndefine('SITE_URL', '{$data['site_url']}');\ndefine('INSTALLED', true);\n";
            file_put_contents('config.php', $config);
            $pdo = new PDO("mysql:host={$data['db_host']};dbname={$data['db_name']};charset=utf8mb4", $data['db_user'], $data['db_pass'], [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
            $pdo->exec(file_get_contents('schema.sql'));
            $pdo->prepare("INSERT INTO admins (username, email, password, role) VALUES (?, ?, ?, 'super_admin')")->execute([$data['admin_user'], $data['admin_email'] ?? '', password_hash($data['admin_pass'], PASSWORD_ARGON2ID)]);
            $pdo->prepare("INSERT INTO stations (name, slug, genre, azuracast_url, api_key, station_id, stream_url, is_default, active) VALUES (?, ?, ?, ?, ?, ?, ?, 1, 1)")->execute([$data['station_name'] ?? 'Main Station', $data['station_slug'] ?? 'main', $data['station_genre'] ?? 'Electronic', $data['azuracast_url'] ?? '', $data['azuracast_key'] ?? '', $data['azuracast_station_id'] ?? 1, $data['stream_url'] ?? '']);
            $pdo->prepare("UPDATE settings SET value = ? WHERE `key` = 'site_name'")->execute([$data['site_name']]);
            $pdo->prepare("UPDATE settings SET value = ? WHERE `key` = 'site_url'")->execute([$data['site_url']]);
            @mkdir('uploads', 0755, true);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install NovaRadio CMS</title>
    <style>
        *{margin:0;padding:0;box-sizing:border-box}body{font-family:system-ui,sans-serif;background:linear-gradient(135deg,#0a0a0f,#1a1a2e);color:#f1f1f1;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:2rem}.installer{background:#12121a;border-radius:16px;padding:2.5rem;width:100%;max-width:600px;border:1px solid #2a2a3a}h1{text-align:center;color:#6366f1;margin-bottom:.3rem}.subtitle{text-align:center;color:#888;margin-bottom:1.5rem;font-size:.9rem}.steps{display:flex;justify-content:center;gap:.5rem;margin-bottom:1.5rem}.step-dot{width:10px;height:10px;border-radius:50%;background:#2a2a3a}.step-dot.active{background:#6366f1}.step-dot.done{background:#22c55e}.form-group{margin-bottom:.9rem}.form-group label{display:block;margin-bottom:.3rem;font-size:.8rem;color:#aaa}.form-group input{width:100%;padding:.6rem .8rem;background:#1e1e28;border:1px solid #2a2a3a;border-radius:6px;color:#f1f1f1;font-size:.9rem}.form-group input:focus{outline:none;border-color:#6366f1}.form-row{display:grid;grid-template-columns:1fr 1fr;gap:.8rem}.btn{width:100%;padding:.7rem;background:#6366f1;border:none;border-radius:6px;color:#fff;font-weight:600;cursor:pointer;margin-top:.4rem;font-size:.9rem}.btn:hover{background:#4f46e5}.btn:disabled{background:#333;cursor:not-allowed}.btn-outline{background:0 0;border:1px solid #2a2a3a;color:#ccc}.error{color:#ef4444;font-size:.8rem;margin-top:.5rem;text-align:center}h3{margin:1.2rem 0 .8rem;font-size:.9rem;color:#6366f1;border-bottom:1px solid #2a2a3a;padding-bottom:.4rem}.step{display:none}.step.active{display:block}.complete-icon{font-size:3.5rem;text-align:center;margin-bottom:.8rem}.license-box{background:#0a0a0f;border:1px solid #2a2a3a;border-radius:6px;padding:.8rem;height:220px;overflow-y:auto;font-size:.65rem;line-height:1.4;color:#888;font-family:monospace;white-space:pre-wrap}.license-accept{display:flex;align-items:center;gap:.5rem;margin:1rem 0}.license-accept input{width:16px;height:16px;cursor:pointer}.license-accept label{font-size:.85rem;color:#ccc;cursor:pointer}.req-list{background:#0a0a0f;border-radius:6px;padding:1rem}.req-item{display:flex;justify-content:space-between;padding:.4rem 0;border-bottom:1px solid #1a1a2a}.req-item:last-child{border:none}.req-ok{color:#22c55e}.req-fail{color:#ef4444}.req-warn{color:#f59e0b}.pwd-strength{height:4px;border-radius:2px;margin-top:.3rem;transition:all .3s}.pwd-weak{background:#ef4444;width:33%}.pwd-medium{background:#f59e0b;width:66%}.pwd-strong{background:#22c55e;width:100%}.pwd-hint{font-size:.7rem;color:#888;margin-top:.2rem}
    </style>
</head>
<body>
<div class="installer">
    <h1>📻 NovaRadio CMS</h1>
    <p class="subtitle">Installation Wizard v0.1.0</p>
    <div class="steps"><div class="step-dot active"></div><div class="step-dot"></div><div class="step-dot"></div><div class="step-dot"></div><div class="step-dot"></div><div class="step-dot"></div></div>
    
    <div class="step active" data-step="1">
        <h3>🔍 System Requirements</h3>
        <div class="req-list" id="req-list"><p style="color:#888">Checking requirements...</p></div>
        <button class="btn" id="req-btn" onclick="goStep(2)" disabled>Continue</button>
        <p id="req-error" class="error"></p>
    </div>
    
    <div class="step" data-step="2">
        <h3>📜 License Agreement</h3>
        <div class="license-box">GNU GENERAL PUBLIC LICENSE
Version 3, 29 June 2007

Copyright (C) 2007 Free Software Foundation, Inc.
https://fsf.org/

Everyone is permitted to copy and distribute verbatim copies
of this license document, but changing it is not allowed.

PREAMBLE

The GNU General Public License is a free, copyleft license for
software and other kinds of works.

TERMS AND CONDITIONS

0. Definitions.
"This License" refers to version 3 of the GNU General Public License.
"The Program" refers to any copyrightable work licensed under this License.

1. Source Code.
You may copy and distribute verbatim copies of the Program's source
code as you receive it, in any medium.

2. Basic Permissions.
All rights granted under this License are granted for the term of
copyright on the Program, and are irrevocable provided the stated
conditions are met.

END OF TERMS AND CONDITIONS

NovaRadio CMS - Copyright (C) 2025-2026 Kamil 'Novik' Nowicki
Website: noviktech.com | Email: novik@noviktech.com

This program is free software under GNU GPL v3.0.
Full license: https://www.gnu.org/licenses/gpl-3.0.html</div>
        <div class="license-accept"><input type="checkbox" id="accept_license" onchange="document.getElementById('license-btn').disabled=!this.checked"><label for="accept_license">I accept the GNU General Public License v3.0</label></div>
        <button class="btn" id="license-btn" onclick="goStep(3)" disabled>Accept & Continue</button>
        <button class="btn btn-outline" onclick="goStep(1)">Back</button>
    </div>
    
    <div class="step" data-step="3">
        <h3>🗄️ Database</h3>
        <div class="form-row"><div class="form-group"><label>Host</label><input type="text" id="db_host" value="localhost"></div><div class="form-group"><label>Database</label><input type="text" id="db_name" value="novaradio"></div></div>
        <div class="form-row"><div class="form-group"><label>Username</label><input type="text" id="db_user" value="root"></div><div class="form-group"><label>Password</label><input type="password" id="db_pass"></div></div>
        <button class="btn" onclick="testDB()">Test & Continue</button>
        <button class="btn btn-outline" onclick="goStep(2)">Back</button>
        <p id="db-error" class="error"></p>
    </div>
    
    <div class="step" data-step="4">
        <h3>📻 Station</h3>
        <div class="form-row"><div class="form-group"><label>Name</label><input type="text" id="station_name" value="Main Station"></div><div class="form-group"><label>Slug</label><input type="text" id="station_slug" value="main"></div></div>
        <div class="form-group"><label>Genre</label><input type="text" id="station_genre" value="Electronic"></div>
        <div class="form-group"><label>AzuraCast URL</label><input type="url" id="azuracast_url" placeholder="https://radio.example.com"></div>
        <div class="form-row"><div class="form-group"><label>API Key</label><input type="text" id="azuracast_key"></div><div class="form-group"><label>Station ID</label><input type="number" id="azuracast_station_id" value="1"></div></div>
        <div class="form-group"><label>Stream URL</label><input type="url" id="stream_url" placeholder="https://radio.example.com/listen/main/radio.mp3"></div>
        <button class="btn" onclick="goStep(5)">Continue</button>
        <button class="btn btn-outline" onclick="goStep(3)">Back</button>
    </div>
    
    <div class="step" data-step="5">
        <h3>🌐 Site</h3>
        <div class="form-row"><div class="form-group"><label>Site Name</label><input type="text" id="site_name" value="NovaRadio"></div><div class="form-group"><label>Site URL</label><input type="url" id="site_url" value="<?= (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['REQUEST_URI']) ?>"></div></div>
        <h3>👤 Super Admin</h3>
        <div class="form-row"><div class="form-group"><label>Username</label><input type="text" id="admin_user" value="admin"></div><div class="form-group"><label>Email</label><input type="email" id="admin_email"></div></div>
        <div class="form-group">
            <label>Password</label>
            <input type="password" id="admin_pass" oninput="checkPassword()">
            <div class="pwd-strength" id="pwd-strength"></div>
            <div class="pwd-hint" id="pwd-hint">Min 8 chars, uppercase, lowercase, number</div>
        </div>
        <div class="form-group">
            <label>Confirm Password</label>
            <input type="password" id="admin_pass2" oninput="checkPassword()">
            <div class="pwd-hint" id="pwd-match"></div>
        </div>
        <button class="btn" id="install-btn" onclick="install()" disabled>Install Now</button>
        <button class="btn btn-outline" onclick="goStep(4)">Back</button>
        <p id="install-error" class="error"></p>
    </div>
    
    <div class="step" data-step="6">
        <div class="complete-icon">✅</div>
        <h3 style="text-align:center;color:#22c55e;border:none">Installation Complete!</h3>
        <p style="text-align:center;color:#888;margin:1rem 0;font-size:.85rem">Delete install.php for security.</p>
        <a href="admin.php" class="btn" style="display:block;text-align:center;text-decoration:none">Admin Panel</a>
        <a href="index.php" class="btn btn-outline" style="display:block;text-align:center;text-decoration:none;margin-top:.5rem">View Site</a>
    </div>
</div>
<script>
const $=id=>document.getElementById(id);

// Check requirements on load
(async function(){
    const r=await fetch('install.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'check_requirements'})}).then(r=>r.json());
    let html='';
    for(let k in r.checks){
        const c=r.checks[k];
        html+=`<div class="req-item"><span>${c.name}</span><span class="${c.ok?'req-ok':'req-fail'}">${c.value}</span></div>`;
    }
    $('req-list').innerHTML=html;
    if(r.ok){$('req-btn').disabled=false}else{$('req-error').textContent='Please fix the requirements above'}
})();

function goStep(n){document.querySelectorAll('.step').forEach((e,i)=>e.classList.toggle('active',i+1===n));document.querySelectorAll('.step-dot').forEach((e,i)=>{e.classList.remove('active','done');if(i+1<n)e.classList.add('done');if(i+1===n)e.classList.add('active')})}

async function testDB(){
    const r=await fetch('install.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({action:'test_db',host:$('db_host').value,name:$('db_name').value,user:$('db_user').value,pass:$('db_pass').value})}).then(r=>r.json());
    r.success?goStep(4):$('db-error').textContent=r.error;
}

function checkPassword(){
    const p=$('admin_pass').value;
    const p2=$('admin_pass2').value;
    const str=$('pwd-strength');
    const hint=$('pwd-hint');
    const match=$('pwd-match');
    
    // Strength check
    let score=0;
    if(p.length>=8)score++;
    if(/[a-z]/.test(p)&&/[A-Z]/.test(p))score++;
    if(/\d/.test(p))score++;
    if(/[^a-zA-Z0-9]/.test(p))score++;
    
    str.className='pwd-strength';
    if(p.length===0){str.style.width='0'}
    else if(score<=1){str.classList.add('pwd-weak');hint.textContent='Weak password'}
    else if(score<=2){str.classList.add('pwd-medium');hint.textContent='Medium password'}
    else{str.classList.add('pwd-strong');hint.textContent='Strong password'}
    
    // Match check
    if(p2.length===0){match.textContent='';match.style.color='#888'}
    else if(p===p2){match.textContent='✓ Passwords match';match.style.color='#22c55e'}
    else{match.textContent='✗ Passwords do not match';match.style.color='#ef4444'}
    
    // Enable button
    $('install-btn').disabled=!(p.length>=8&&p===p2&&score>=2);
}

async function install(){
    const p=$('admin_pass').value;
    const p2=$('admin_pass2').value;
    if(p!==p2){$('install-error').textContent='Passwords do not match';return}
    if(p.length<8){$('install-error').textContent='Password must be at least 8 characters';return}
    
    const r=await fetch('install.php',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({
        action:'install',db_host:$('db_host').value,db_name:$('db_name').value,db_user:$('db_user').value,db_pass:$('db_pass').value,
        station_name:$('station_name').value,station_slug:$('station_slug').value,station_genre:$('station_genre').value,
        azuracast_url:$('azuracast_url').value,azuracast_key:$('azuracast_key').value,azuracast_station_id:$('azuracast_station_id').value,stream_url:$('stream_url').value,
        site_name:$('site_name').value,site_url:$('site_url').value,admin_user:$('admin_user').value,admin_email:$('admin_email').value,admin_pass:p
    })}).then(r=>r.json());
    r.success?goStep(6):$('install-error').textContent=r.error;
}
</script>
</body>
</html>
