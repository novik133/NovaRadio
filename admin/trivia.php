<?php
requireLogin();
$questions = fetchAll("SELECT * FROM trivia ORDER BY created_at DESC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add'])) {
        $wrong = array_filter([$_POST['wrong1'], $_POST['wrong2'], $_POST['wrong3']]);
        insert('trivia', [
            'question' => $_POST['question'],
            'correct_answer' => $_POST['correct'],
            'wrong_answers' => json_encode($wrong),
            'category' => $_POST['category'],
            'points' => (int)$_POST['points'] ?: 10
        ]);
        redirect('admin.php?page=trivia&msg=added');
    }
    if (isset($_POST['delete'])) {
        delete('trivia', 'id = ?', [(int)$_POST['id']]);
        redirect('admin.php?page=trivia');
    }
    if (isset($_POST['toggle'])) {
        $q = fetch("SELECT active FROM trivia WHERE id = ?", [(int)$_POST['id']]);
        update('trivia', ['active' => $q['active'] ? 0 : 1], 'id = ?', [(int)$_POST['id']]);
        redirect('admin.php?page=trivia');
    }
}
$questions = fetchAll("SELECT * FROM trivia ORDER BY created_at DESC");
?>
<div class="admin-header">
    <h1>Trivia Questions</h1>
</div>

<?php if (isset($_GET['msg'])): ?><div class="alert alert-success">Question added!</div><?php endif; ?>

<div class="card" style="margin-bottom:1rem">
    <div class="card-header">Add Question</div>
    <div class="card-body">
        <form method="post">
            <div class="form-group"><label>Question</label><input type="text" name="question" required></div>
            <div class="form-row">
                <div class="form-group"><label>Correct Answer</label><input type="text" name="correct" required></div>
                <div class="form-group"><label>Category</label><input type="text" name="category" placeholder="Music"></div>
                <div class="form-group"><label>Points</label><input type="number" name="points" value="10"></div>
            </div>
            <div class="form-row">
                <div class="form-group"><label>Wrong Answer 1</label><input type="text" name="wrong1" required></div>
                <div class="form-group"><label>Wrong Answer 2</label><input type="text" name="wrong2" required></div>
                <div class="form-group"><label>Wrong Answer 3</label><input type="text" name="wrong3"></div>
            </div>
            <button name="add" class="btn btn-primary">Add Question</button>
        </form>
    </div>
</div>

<table class="table">
    <thead><tr><th>Question</th><th>Answer</th><th>Category</th><th>Points</th><th>Status</th><th>Actions</th></tr></thead>
    <tbody>
    <?php foreach ($questions as $q): ?>
    <tr>
        <td><?= e(substr($q['question'], 0, 50)) ?>...</td>
        <td><?= e($q['correct_answer']) ?></td>
        <td><?= e($q['category'] ?: '-') ?></td>
        <td><?= $q['points'] ?></td>
        <td><span class="badge <?= $q['active'] ? 'badge-success' : 'badge-secondary' ?>"><?= $q['active'] ? 'Active' : 'Inactive' ?></span></td>
        <td>
            <form method="post" style="display:inline">
                <input type="hidden" name="id" value="<?= $q['id'] ?>">
                <button name="toggle" class="btn btn-sm"><?= $q['active'] ? 'Disable' : 'Enable' ?></button>
                <button name="delete" class="btn btn-sm btn-danger" onclick="return confirm('Delete?')">Delete</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
