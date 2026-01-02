async function deleteItem(type, id) {
    if (!confirm(`Delete this ${type}?`)) return;
    await fetch(`admin.php?ajax=delete-${type}`, {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({id})
    });
    location.reload();
}
