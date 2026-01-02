document.addEventListener('DOMContentLoaded', function() {
    // Auth tabs
    document.querySelectorAll('.chat-auth .tab').forEach(tab => {
        tab.addEventListener('click', () => {
            document.querySelectorAll('.chat-auth .tab, .chat-auth .tab-content').forEach(el => el.classList.remove('active'));
            tab.classList.add('active');
            document.querySelector(`.tab-content[data-tab="${tab.dataset.tab}"]`).classList.add('active');
        });
    });

    // Auth forms
    ['login', 'register', 'guest'].forEach(type => {
        const form = document.getElementById(`${type}-form`);
        if (form) form.addEventListener('submit', e => {
            e.preventDefault();
            const data = new FormData(form);
            data.append('action', type);
            fetch('chat_api.php', { method: 'POST', body: data })
                .then(r => r.json())
                .then(res => {
                    if (res.success) location.reload();
                    else document.getElementById('auth-error').textContent = res.error || 'Error';
                });
        });
    });

    if (!CHAT_USER) return;

    let currentRoomId = DEFAULT_ROOM;
    let privateWith = null;
    let lastMsgId = 0;
    let lastPrivateId = 0;

    const messagesDiv = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');

    // Logout
    document.getElementById('logout-chat')?.addEventListener('click', () => {
        fetch('chat_api.php', { method: 'POST', body: new URLSearchParams({ action: 'logout' }) })
            .then(() => location.reload());
    });

    // Room switching
    document.querySelectorAll('.room-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.room-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentRoomId = parseInt(btn.dataset.room);
            lastMsgId = 0;
            messagesDiv.innerHTML = '';
            document.getElementById('current-room').textContent = btn.dataset.name;
            document.getElementById('room-topic').textContent = btn.dataset.topic || '';
            document.getElementById('current-room').style.display = 'inline';
            document.getElementById('room-topic').style.display = 'inline';
            document.getElementById('private-indicator').style.display = 'none';
            privateWith = null;
            loadMessages();
        });
    });

    // Send message
    chatForm.addEventListener('submit', e => {
        e.preventDefault();
        const msg = messageInput.value.trim();
        if (!msg) return;
        const data = new URLSearchParams();
        if (privateWith) {
            data.append('action', 'send_private');
            data.append('to_user', privateWith.id);
        } else {
            data.append('action', 'send');
            data.append('room_id', currentRoomId);
        }
        data.append('message', msg);
        fetch('chat_api.php', { method: 'POST', body: data }).then(() => {
            messageInput.value = '';
            loadMessages();
        });
    });

    // Load messages
    function loadMessages() {
        const url = privateWith 
            ? `chat_api.php?action=private_messages&with_user=${privateWith.id}&last_id=${lastPrivateId}`
            : `chat_api.php?action=messages&room_id=${currentRoomId}&last_id=${lastMsgId}`;
        fetch(url).then(r => r.json()).then(msgs => {
            if (!msgs.length) return;
            if (privateWith) lastPrivateId = Math.max(...msgs.map(m => m.id));
            else lastMsgId = Math.max(...msgs.map(m => m.id));
            msgs.forEach(m => {
                if (document.querySelector(`[data-msg-id="${m.id}"]`)) return;
                const div = document.createElement('div');
                div.className = 'chat-message';
                div.dataset.msgId = m.id;
                const isOp = m.is_op == 1;
                const authorClass = IS_ADMIN && m.user_id == CHAT_USER.id ? 'admin' : (isOp ? 'op' : '');
                const time = new Date(m.created_at).toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
                div.innerHTML = `<div class="meta"><span class="author ${authorClass}" data-user-id="${m.user_id || m.from_user}">${esc(m.username)}</span> <span class="time">${time}</span>${(IS_ADMIN || CHAT_USER.is_op) && !privateWith ? `<span class="actions"><button onclick="deleteMsg(${m.id})">delete</button></span>` : ''}</div><div class="text">${esc(m.message)}</div>`;
                messagesDiv.appendChild(div);
            });
            messagesDiv.scrollTop = messagesDiv.scrollHeight;
        });
    }

    // Load online users
    function loadOnline() {
        fetch('chat_api.php?action=online').then(r => r.json()).then(users => {
            const ul = document.getElementById('online-users');
            ul.innerHTML = users.map(u => `<li class="${u.is_op ? 'op' : ''}" data-user-id="${u.id}">${esc(u.username)}${u.is_op ? ' ★' : ''}</li>`).join('');
            ul.querySelectorAll('li').forEach(li => {
                li.addEventListener('click', () => startPrivate(users.find(u => u.id == li.dataset.userId)));
            });
        });
    }

    // Start private chat
    function startPrivate(user) {
        if (user.id == CHAT_USER.id) return;
        privateWith = user;
        lastPrivateId = 0;
        messagesDiv.innerHTML = '';
        document.getElementById('current-room').style.display = 'none';
        document.getElementById('room-topic').style.display = 'none';
        document.getElementById('private-indicator').style.display = 'inline';
        document.getElementById('private-with').textContent = user.username;
        loadMessages();
    }

    // Close private
    document.getElementById('close-private')?.addEventListener('click', () => {
        privateWith = null;
        lastMsgId = 0;
        messagesDiv.innerHTML = '';
        document.getElementById('current-room').style.display = 'inline';
        document.getElementById('room-topic').style.display = 'inline';
        document.getElementById('private-indicator').style.display = 'none';
        loadMessages();
    });

    // Click on username to start private
    messagesDiv.addEventListener('click', e => {
        if (e.target.classList.contains('author')) {
            const userId = e.target.dataset.userId;
            if (userId != CHAT_USER.id) {
                fetch('chat_api.php?action=online').then(r => r.json()).then(users => {
                    const user = users.find(u => u.id == userId);
                    if (user) startPrivate(user);
                });
            }
        }
    });

    // Delete message
    window.deleteMsg = function(id) {
        fetch('chat_api.php', { method: 'POST', body: new URLSearchParams({ action: 'delete', msg_id: id }) })
            .then(() => document.querySelector(`[data-msg-id="${id}"]`)?.remove());
    };

    function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

    // Poll
    loadMessages();
    loadOnline();
    setInterval(loadMessages, 2000);
    setInterval(loadOnline, 10000);
});
