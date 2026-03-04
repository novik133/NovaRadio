@extends('admin.layout')

@section('title', 'Media Library')

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-images"></i> Media Library</h1>
        <p style="color: var(--text-muted); margin-top: 4px;">Manage your images and files</p>
    </div>
    <div style="display: flex; gap: 12px;">
        <button class="btn btn-secondary" onclick="createFolder()">
            <i class="fas fa-folder-plus"></i> New Folder
        </button>
        <button class="btn btn-primary" onclick="document.getElementById('file-input').click()">
            <i class="fas fa-upload"></i> Upload
        </button>
        <input type="file" id="file-input" multiple style="display: none;" onchange="uploadFiles(this.files)">
    </div>
</div>

{{-- Breadcrumb --}}
<div class="breadcrumb">
    @foreach($breadcrumb as $index => $crumb)
        @if($index < count($breadcrumb) - 1)
            <a href="{{ route('admin.media.index', ['folder' => $crumb['path']]) }}">{{ $crumb['name'] }}</a>
            <i class="fas fa-chevron-right"></i>
        @else
            <span>{{ $crumb['name'] }}</span>
        @endif
    @endforeach
</div>

{{-- Media Grid --}}
<div class="media-container">
    {{-- Folders --}}
    @forelse($folders as $folderItem)
    <div class="media-item folder-item" data-path="{{ $folderItem['path'] }}">
        <a href="{{ route('admin.media.index', ['folder' => $folderItem['path']]) }}" class="item-link">
            <div class="item-icon folder-icon">
                <i class="fas fa-folder"></i>
                <span class="item-count">{{ $folderItem['items'] }}</span>
            </div>
            <div class="item-name" title="{{ $folderItem['name'] }}">{{ $folderItem['name'] }}</div>
        </a>
        <button class="item-actions-btn" onclick="showFolderActions('{{ $folderItem['path'] }}', event)">
            <i class="fas fa-ellipsis-v"></i>
        </button>
    </div>
    @empty
    @endforelse
    
    {{-- Files --}}
    @forelse($files as $file)
    <div class="media-item file-item" data-path="{{ $file['path'] }}" data-url="{{ $file['url'] }}">
        <div class="item-preview" onclick="selectFile('{{ $file['url'] }}', '{{ $file['name'] }}')">
            @if($file['is_image'])
                <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" loading="lazy">
            @else
                <div class="file-icon-ext">
                    <i class="fas fa-file"></i>
                    <span>{{ strtoupper($file['extension']) }}</span>
                </div>
            @endif
        </div>
        <div class="item-info">
            <div class="item-name" title="{{ $file['name'] }}">{{ $file['name'] }}</div>
            <div class="item-meta">{{ $file['size'] }} • {{ $file['modified'] }}</div>
        </div>
        <button class="item-actions-btn" onclick="showFileActions('{{ $file['path'] }}', '{{ $file['name'] }}', event)">
            <i class="fas fa-ellipsis-v"></i>
        </button>
        <button class="item-select" onclick="copyUrl('{{ $file['url'] }}')">
            <i class="fas fa-link"></i>
        </button>
    </div>
    @empty
    @if(empty($folders))
    <div class="empty-state" style="grid-column: 1 / -1;">
        <i class="fas fa-folder-open"></i>
        <h3>Folder is empty</h3>
        <p>Upload files or create folders to get started</p>
    </div>
    @endif
    @endforelse
</div>

{{-- Context Menu --}}
<div id="context-menu" class="context-menu">
    <div class="context-item" onclick="renameItem()">
        <i class="fas fa-edit"></i> Rename
    </div>
    <div class="context-item" onclick="copyItemUrl()">
        <i class="fas fa-link"></i> Copy URL
    </div>
    <div class="context-divider"></div>
    <div class="context-item danger" onclick="deleteItem()">
        <i class="fas fa-trash"></i> Delete
    </div>
</div>

{{-- Upload Progress --}}
<div id="upload-progress" class="upload-progress" style="display: none;">
    <div class="progress-header">
        <span><i class="fas fa-upload"></i> Uploading...</span>
        <button onclick="document.getElementById('upload-progress').style.display='none'">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <div class="progress-bar">
        <div class="progress-fill" id="progress-fill"></div>
    </div>
    <div class="progress-text" id="progress-text">0%</div>
</div>

@push('styles')
<style>
.breadcrumb {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
    padding: 12px 16px;
    background: white;
    border-radius: 8px;
    font-size: 14px;
}

.breadcrumb a {
    color: var(--primary-color);
    text-decoration: none;
}

.breadcrumb a:hover {
    text-decoration: underline;
}

.breadcrumb i {
    font-size: 12px;
    color: var(--text-muted);
}

.breadcrumb span {
    color: var(--text-color);
    font-weight: 500;
}

.media-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 16px;
}

.media-item {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.2s;
    position: relative;
}

.media-item:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.folder-item {
    padding: 16px;
}

.folder-item .item-link {
    text-decoration: none;
    color: inherit;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.item-icon {
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 48px;
    color: #fbbf24;
    position: relative;
}

.folder-icon .item-count {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background: var(--primary-color);
    color: white;
    font-size: 10px;
    padding: 2px 6px;
    border-radius: 10px;
    font-weight: 600;
}

.file-item .item-preview {
    height: 120px;
    background: var(--bg-light);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    overflow: hidden;
}

.file-item .item-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.file-icon-ext {
    text-align: center;
    color: var(--text-muted);
}

.file-icon-ext i {
    font-size: 32px;
    display: block;
    margin-bottom: 4px;
}

.file-icon-ext span {
    font-size: 12px;
    font-weight: 600;
}

.item-info {
    padding: 12px;
}

.item-name {
    font-size: 13px;
    font-weight: 500;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    color: var(--text-color);
}

.item-meta {
    font-size: 11px;
    color: var(--text-muted);
    margin-top: 4px;
}

.item-actions-btn {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 32px;
    height: 32px;
    background: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.media-item:hover .item-actions-btn {
    opacity: 1;
}

.item-select {
    position: absolute;
    top: 8px;
    left: 8px;
    width: 32px;
    height: 32px;
    background: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.2s;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.media-item:hover .item-select {
    opacity: 1;
}

/* Context Menu */
.context-menu {
    position: fixed;
    background: white;
    border-radius: 8px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    padding: 8px 0;
    min-width: 160px;
    z-index: 9999;
    display: none;
}

.context-item {
    padding: 10px 16px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.context-item:hover {
    background: var(--bg-light);
}

.context-item.danger {
    color: #ef4444;
}

.context-item.danger:hover {
    background: #fee2e2;
}

.context-divider {
    height: 1px;
    background: var(--border-color);
    margin: 8px 0;
}

/* Upload Progress */
.upload-progress {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: white;
    padding: 16px 20px;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.15);
    width: 300px;
    z-index: 9999;
}

.progress-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.progress-header button {
    background: none;
    border: none;
    cursor: pointer;
    color: var(--text-muted);
}

.progress-bar {
    height: 6px;
    background: var(--bg-light);
    border-radius: 3px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: var(--primary-color);
    width: 0%;
    transition: width 0.3s;
}

.progress-text {
    text-align: center;
    font-size: 13px;
    color: var(--text-muted);
    margin-top: 8px;
}
</style>
@endpush

@push('scripts')
<script>
let currentItemPath = null;
let currentItemName = null;

function uploadFiles(files) {
    const progressDiv = document.getElementById('upload-progress');
    const progressFill = document.getElementById('progress-fill');
    const progressText = document.getElementById('progress-text');
    
    progressDiv.style.display = 'block';
    
    const formData = new FormData();
    for (let file of files) {
        formData.append('files[]', file);
    }
    formData.append('folder', '{{ $folder }}');
    
    const xhr = new XMLHttpRequest();
    
    xhr.upload.addEventListener('progress', (e) => {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            progressFill.style.width = percent + '%';
            progressText.textContent = percent + '%';
        }
    });
    
    xhr.addEventListener('load', () => {
        const response = JSON.parse(xhr.responseText);
        if (response.success) {
            location.reload();
        } else {
            alert(response.message || 'Upload failed');
        }
    });
    
    xhr.addEventListener('error', () => {
        alert('Upload failed. Please try again.');
        progressDiv.style.display = 'none';
    });
    
    xhr.open('POST', '{{ route("admin.media.upload") }}');
    xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
    xhr.send(formData);
}

function createFolder() {
    const name = prompt('Enter folder name:');
    if (!name) return;
    
    fetch('{{ route("admin.media.create-folder") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            name: name,
            current_folder: '{{ $folder }}'
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}

function showFolderActions(path, event) {
    event.preventDefault();
    currentItemPath = path;
    currentItemName = path.split('/').pop();
    showContextMenu(event);
}

function showFileActions(path, name, event) {
    event.stopPropagation();
    currentItemPath = path;
    currentItemName = name;
    showContextMenu(event);
}

function showContextMenu(event) {
    const menu = document.getElementById('context-menu');
    menu.style.display = 'block';
    menu.style.left = event.pageX + 'px';
    menu.style.top = event.pageY + 'px';
    
    document.addEventListener('click', hideContextMenu, { once: true });
}

function hideContextMenu() {
    document.getElementById('context-menu').style.display = 'none';
}

function renameItem() {
    const newName = prompt('Enter new name:', currentItemName);
    if (!newName || newName === currentItemName) return;
    
    fetch('{{ route("admin.media.rename") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            path: currentItemPath,
            new_name: newName
        })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}

function deleteItem() {
    if (!confirm('Are you sure you want to delete "' + currentItemName + '"?')) return;
    
    fetch('{{ route("admin.media.delete") }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ path: currentItemPath })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message);
        }
    });
}

function copyItemUrl() {
    const item = document.querySelector('[data-path="' + currentItemPath + '"]');
    const url = item?.dataset?.url;
    if (url) {
        copyUrl(url);
    }
}

function copyUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        // Show toast
        const toast = document.createElement('div');
        toast.style.cssText = 'position:fixed;bottom:20px;left:50%;transform:translateX(-50%);background:#22c55e;color:white;padding:10px 20px;border-radius:8px;z-index:10000;';
        toast.textContent = 'URL copied!';
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 2000);
    });
}

function selectFile(url, name) {
    // This function can be called from TinyMCE media picker
    if (window.opener && window.opener.mediaPickerCallback) {
        window.opener.mediaPickerCallback(url, name);
        window.close();
    }
}

// Expose for TinyMCE
window.openMediaPicker = function(callback) {
    window.mediaPickerCallback = callback;
    const picker = window.open('{{ route("admin.media.index") }}?picker=1', 'media-picker', 'width=900,height=600');
};
</script>
@endpush
@endsection
