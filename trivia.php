<?php
session_start();
require_once 'config.php';
require_once 'includes/functions.php';

if (getSetting('trivia_enabled', '1') !== '1') { header('Location: index.php'); exit; }
trackPageView('trivia');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Music Trivia - <?= e(SITE_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div class="container page-content">
        <h1 class="page-title">🎯 Music Trivia</h1>
        
        <div class="trivia-container">
            <div class="trivia-game">
                <div id="trivia-score" class="trivia-score">Score: <span id="score">0</span></div>
                <div id="trivia-question" class="trivia-question">
                    <button onclick="loadQuestion()" class="btn btn-primary btn-lg">Start Game</button>
                </div>
                <div id="trivia-answers" class="trivia-answers"></div>
                <div id="trivia-result" class="trivia-result"></div>
            </div>
            
            <div class="trivia-leaderboard">
                <h3>🏆 Leaderboard</h3>
                <div id="leaderboard"></div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script>
    let currentQuestion = null;
    let score = 0;
    
    async function loadQuestion() {
        document.getElementById('trivia-result').innerHTML = '';
        const res = await fetch('ajax.php?action=trivia_question');
        const data = await res.json();
        if (data.error) {
            document.getElementById('trivia-question').innerHTML = '<p>' + data.error + '</p>';
            return;
        }
        currentQuestion = data;
        document.getElementById('trivia-question').innerHTML = '<h2>' + data.question + '</h2>';
        document.getElementById('trivia-answers').innerHTML = data.answers.map(a => 
            `<button class="trivia-answer-btn" onclick="submitAnswer('${a.replace(/'/g, "\\'")}')">${a}</button>`
        ).join('');
    }
    
    async function submitAnswer(answer) {
        if (!currentQuestion) return;
        document.querySelectorAll('.trivia-answer-btn').forEach(b => b.disabled = true);
        
        const res = await fetch('ajax.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: `action=trivia_answer&question_id=${currentQuestion.id}&answer=${encodeURIComponent(answer)}`
        });
        const data = await res.json();
        
        const resultEl = document.getElementById('trivia-result');
        if (data.correct) {
            score += data.points;
            document.getElementById('score').textContent = score;
            resultEl.innerHTML = '<div class="correct">✅ Correct! +' + data.points + ' points</div>';
        } else {
            resultEl.innerHTML = '<div class="wrong">❌ Wrong! The answer was: ' + data.correct_answer + '</div>';
        }
        
        document.querySelectorAll('.trivia-answer-btn').forEach(b => {
            if (b.textContent === data.correct_answer) b.classList.add('correct');
            else if (b.textContent === answer && !data.correct) b.classList.add('wrong');
        });
        
        setTimeout(loadQuestion, 2000);
        loadLeaderboard();
    }
    
    async function loadLeaderboard() {
        const res = await fetch('ajax.php?action=trivia_leaderboard');
        const data = await res.json();
        document.getElementById('leaderboard').innerHTML = data.length ? data.map((u, i) => 
            `<div class="leader-item"><span class="leader-rank">#${i+1}</span><span class="leader-name">${u.username || 'Player'}</span><span class="leader-score">${u.score} pts</span></div>`
        ).join('') : '<p class="text-muted">No scores yet</p>';
    }
    
    loadLeaderboard();
    </script>
</body>
</html>
