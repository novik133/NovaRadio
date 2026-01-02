<div class="card">
    <div class="card-header">
        <h3>📁 Media Library</h3>
        <div class="header-actions">
            <input type="file" id="file-upload" multiple accept="audio/*" style="display:none" onchange="uploadFiles(this.files)">
            <button onclick="document.getElementById('file-upload').click()" class="btn btn-primary">⬆️ Upload Files</button>
        </div>
    </div>
    <div class="card-body">
        <div class="breadcrumb" id="breadcrumb">
            <a href="#" onclick="loadFiles('')">📁 Root</a>
        </div>
        <div id="upload-progress" style="display:none">
            <div class="progress-bar"><div id="upload-bar" class="progress-fill"></div></div>
            <span id="upload-status">Uploading...</span>
        </div>
        <div id="files-list" class="files-grid"></div>
    </div>
</div>

<script>
let currentPath = '';

async function loadFiles(path = '') {
    currentPath = path;
    updateBreadcrumb(path);
    
    const files = await fetch('dj.php?ajax=files&station='+STATION_ID+'&path='+encodeURIComponent(path)).then(r=>r.json());
    
    if (Array.isArray(files)) {
        document.getElementById('files-list').innerHTML = files.map(f => {
            if (f.is_dir) {
                return `<div class="file-item folder" onclick="loadFiles('${f.path}')">
                    <span class="file-icon">📁</span>
                    <span class="file-name">${f.name}</span>
                </div>`;
            } else {
                return `<div class="file-item">
                    <span class="file-icon">🎵</span>
                    <div class="file-info">
                        <span class="file-name">${f.media?.title || f.name}</span>
                        <span class="file-meta">${f.media?.artist || ''} ${f.media?.length_text ? '• '+f.media.length_text : ''}</span>
                    </div>
                    <div class="file-actions">
                        <button onclick="deleteFile('${f.id}')" class="btn btn-sm btn-danger">🗑️</button>
                    </div>
                </div>`;
            }
        }).join('') || '<p class="text-muted">No files in this directory</p>';
    } else if (files.error) {
        document.getElementById('files-list').innerHTML = '<p class="text-muted">'+files.error+'</p>';
    }
}

function updateBreadcrumb(path) {
    const parts = path.split('/').filter(p => p);
    let html = '<a href="#" onclick="loadFiles(\'\')">📁 Root</a>';
    let currentPath = '';
    parts.forEach(part => {
        currentPath += '/' + part;
        html += ` / <a href="#" onclick="loadFiles('${currentPath}')">${part}</a>`;
    });
    document.getElementById('breadcrumb').innerHTML = html;
}

async function uploadFiles(files) {
    const progress = document.getElementById('upload-progress');
    const bar = document.getElementById('upload-bar');
    const status = document.getElementById('upload-status');
    progress.style.display = 'block';
    
    for (let i = 0; i < files.length; i++) {
        status.textContent = `Uploading ${i+1}/${files.length}: ${files[i].name}`;
        bar.style.width = ((i/files.length)*100)+'%';
        
        const fd = new FormData();
        fd.append('file', files[i]);
        fd.append('directory', currentPath);
        
        await fetch('dj.php?ajax=upload&station='+STATION_ID, { method: 'POST', body: fd });
    }
    
    bar.style.width = '100%';
    status.textContent = 'Upload complete!';
    setTimeout(() => { progress.style.display = 'none'; loadFiles(currentPath); }, 1500);
}

async function deleteFile(fileId) {
    if (!confirm('Delete this file?')) return;
    await fetch('dj.php?ajax=delete-file&station='+STATION_ID, {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'file_id='+fileId
    });
    loadFiles(currentPath);
}

loadFiles();
</script>
