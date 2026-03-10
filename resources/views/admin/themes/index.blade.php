@extends('admin.layout')

@section('title', __('admin.themes.title'))

@section('content')
<div class="page-header">
    <div>
        <h1><i class="fas fa-palette"></i> {{ __('admin.themes.title') }}</h1>
    </div>
    <button class="btn btn-primary" onclick="document.getElementById('theme-upload-modal').classList.add('show')">
        <i class="fas fa-upload"></i> {{ __('admin.themes.upload_theme') }}
    </button>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
    <div class="alert alert-error">{{ session('error') }}</div>
@endif

<div class="themes-grid">
    @forelse($themes as $theme)
    <div class="theme-card {{ $theme['active'] ? 'active' : '' }}">
        <div class="theme-screenshot">
            @if($theme['screenshot'])
                <img src="{{ $theme['screenshot'] }}" alt="{{ $theme['name'] }}">
            @else
                <div class="no-screenshot">
                    <i class="fas fa-image"></i>
                    <span>{{ __('admin.themes.no_preview') }}</span>
                </div>
            @endif
            
            @if($theme['active'])
                <div class="active-badge">
                    <i class="fas fa-check"></i> {{ __('admin.themes.active') }}
                </div>
            @endif
            
            <div class="theme-overlay">
                @if(!$theme['active'])
                    <form method="POST" action="{{ route('admin.themes.activate', $theme['name']) }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn-activate">
                            <i class="fas fa-check"></i> {{ __('admin.themes.activate') }}
                        </button>
                    </form>
                @endif
                
                <button type="button" class="btn-preview" onclick="previewTheme('{{ $theme['name'] }}')">
                    <i class="fas fa-eye"></i> {{ __('admin.themes.preview') }}
                </button>
            </div>
        </div>
        
        <div class="theme-info">
            <h3>{{ ucfirst($theme['name']) }}</h3>
            <p class="theme-meta">
                <span>v{{ $theme['version'] }}</span>
                <span class="dot">•</span>
                <span>by {{ $theme['author'] }}</span>
            </p>
            <p class="theme-desc">{{ Str::limit($theme['description'], 60) }}</p>
            
            <div class="theme-tags">
                @if($theme['has_css'])
                    <span class="tag"><i class="fab fa-css3"></i> CSS</span>
                @endif
                @if($theme['has_js'])
                    <span class="tag"><i class="fab fa-js"></i> JS</span>
                @endif
            </div>
            
            @if(!$theme['active'] && $theme['name'] !== 'default')
                <form method="POST" action="{{ route('admin.themes.delete', $theme['name']) }}" 
                      style="display: inline;"
                      onsubmit="return confirm('{{ __('admin.themes.confirm_delete_theme') }}');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn-delete">
                        <i class="fas fa-trash"></i> {{ __('admin.actions.delete') }}
                    </button>
                </form>
            @endif
        </div>
    </div>
    @empty
    <div class="empty-state">
        <i class="fas fa-palette"></i>
        <h3>{{ __('admin.themes.no_themes') }}</h3>
    </div>
    @endforelse
</div>

{{-- Upload Modal --}}
<div id="theme-upload-modal" class="modal">
    <div class="modal-backdrop" onclick="this.parentElement.classList.remove('show')"></div>
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-upload"></i> {{ __('admin.themes.upload_theme') }}</h3>
            <button type="button" class="btn-close" onclick="document.getElementById('theme-upload-modal').classList.remove('show')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST" action="{{ route('admin.themes.upload') }}" enctype="multipart/form-data" id="theme-upload-form">
            @csrf
            
            <div class="upload-area" id="upload-area">
                <div class="upload-icon">
                    <i class="fas fa-cloud-upload-alt"></i>
                </div>
                <p class="upload-text">{{ __('admin.themes.drag_drop_theme') }}</p>
                <p class="upload-or">{{ __('admin.themes.or_text') }}</p>
                <label for="theme-file" class="btn btn-secondary">
                    <i class="fas fa-folder-open"></i> {{ __('admin.themes.browse_files') }}
                </label>
                <input type="file" id="theme-file" name="theme" accept=".zip" required style="display: none;">
                <p class="upload-hint">{{ __('admin.themes.max_file_size') }}</p>
            </div>
            
            <div class="file-info" id="file-info" style="display: none;">
                <div class="file-selected">
                    <i class="fas fa-file-archive"></i>
                    <span id="filename">{{ __('admin.themes.no_file_selected') }}</span>
                    <button type="button" class="btn-remove" onclick="clearFile()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('theme-upload-modal').classList.remove('show')">
                    {{ __('admin.actions.cancel') }}
                </button>
                <button type="submit" class="btn btn-primary" id="upload-btn" disabled>
                    <i class="fas fa-upload"></i> {{ __('admin.themes.upload_theme') }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Preview Modal --}}
<div id="theme-preview-modal" class="modal">
    <div class="modal-backdrop" onclick="this.parentElement.classList.remove('show')"></div>
    <div class="modal-content modal-large">
        <div class="modal-header">
            <h3 id="preview-title">{{ __('admin.themes.theme_preview') }}</h3>
            <button type="button" class="btn-close" onclick="document.getElementById('theme-preview-modal').classList.remove('show')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="preview-content">
            <div class="preview-screenshot" id="preview-screenshot"></div>
            <div class="preview-details">
                <h4 id="preview-name"></h4>
                <p id="preview-desc"></p>
                <div class="preview-meta" id="preview-meta"></div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.themes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 24px;
}

.theme-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: all 0.2s;
    position: relative;
}

.theme-card:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.theme-card.active {
    border: 2px solid var(--primary-color);
}

.theme-screenshot {
    position: relative;
    height: 200px;
    background: var(--bg-light);
    overflow: hidden;
}

.theme-screenshot img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.no-screenshot {
    height: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: var(--text-muted);
}

.no-screenshot i {
    font-size: 48px;
    margin-bottom: 8px;
}

.active-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: var(--primary-color);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.theme-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    opacity: 0;
    transition: opacity 0.2s;
}

.theme-card:hover .theme-overlay {
    opacity: 1;
}

.btn-activate, .btn-preview {
    padding: 12px 24px;
    border: none;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-activate {
    background: var(--primary-color);
    color: white;
}

.btn-activate:hover {
    background: var(--primary-dark);
}

.btn-preview {
    background: white;
    color: var(--text-color);
}

.btn-preview:hover {
    background: var(--bg-light);
}

.theme-info {
    padding: 20px;
}

.theme-info h3 {
    font-size: 18px;
    margin-bottom: 8px;
}

.theme-meta {
    font-size: 13px;
    color: var(--text-muted);
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 6px;
}

.theme-meta .dot {
    font-size: 8px;
}

.theme-desc {
    font-size: 14px;
    color: var(--text-muted);
    margin-bottom: 12px;
}

.theme-tags {
    display: flex;
    gap: 8px;
    margin-bottom: 12px;
}

.tag {
    font-size: 12px;
    padding: 4px 8px;
    background: var(--bg-light);
    border-radius: 4px;
    color: var(--text-muted);
}

.btn-delete {
    padding: 8px 12px;
    background: transparent;
    border: 1px solid #ef4444;
    color: #ef4444;
    border-radius: 6px;
    cursor: pointer;
    font-size: 13px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-delete:hover {
    background: #ef4444;
    color: white;
}

/* Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
}

.modal.show {
    display: flex;
}

.modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
}

.modal-content {
    position: relative;
    background: white;
    border-radius: 12px;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalSlideIn 0.3s ease-out;
}

.modal-large {
    max-width: 800px;
}

@keyframes modalSlideIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.modal-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h3 {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-close {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: var(--text-muted);
}

.modal-footer {
    padding: 20px 24px;
    border-top: 1px solid var(--border-color);
    display: flex;
    justify-content: flex-end;
    gap: 12px;
}

/* Upload Area */
.upload-area {
    padding: 40px;
    text-align: center;
    border: 2px dashed var(--border-color);
    border-radius: 8px;
    margin: 24px;
    transition: all 0.2s;
}

.upload-area.dragover {
    border-color: var(--primary-color);
    background: rgba(99, 102, 241, 0.05);
}

.upload-icon {
    font-size: 48px;
    color: var(--primary-color);
    margin-bottom: 16px;
}

.upload-text {
    font-size: 16px;
    font-weight: 500;
    color: var(--text-color);
    margin-bottom: 8px;
}

.upload-or {
    color: var(--text-muted);
    margin-bottom: 16px;
}

.upload-hint {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 16px;
}

.file-info {
    margin: 24px;
    padding: 16px;
    background: var(--bg-light);
    border-radius: 8px;
}

.file-selected {
    display: flex;
    align-items: center;
    gap: 12px;
}

.file-selected i {
    font-size: 24px;
    color: var(--primary-color);
}

.btn-remove {
    margin-left: auto;
    background: none;
    border: none;
    color: var(--text-muted);
    cursor: pointer;
    padding: 4px;
}

.btn-remove:hover {
    color: #ef4444;
}

/* Preview */
.preview-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    padding: 24px;
}

.preview-screenshot {
    background: var(--bg-light);
    border-radius: 8px;
    min-height: 300px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-screenshot img {
    max-width: 100%;
    max-height: 100%;
    border-radius: 8px;
}

.preview-details h4 {
    font-size: 20px;
    margin-bottom: 12px;
}

.preview-details p {
    color: var(--text-muted);
    margin-bottom: 16px;
}

.preview-meta {
    display: flex;
    flex-direction: column;
    gap: 8px;
    font-size: 14px;
}

.preview-meta span {
    display: flex;
    justify-content: space-between;
}

.preview-meta label {
    color: var(--text-muted);
}

@media (max-width: 768px) {
    .themes-grid {
        grid-template-columns: 1fr;
    }
    
    .preview-content {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script>
// File upload handling
const uploadArea = document.getElementById('upload-area');
const fileInput = document.getElementById('theme-file');
const fileInfo = document.getElementById('file-info');
const filename = document.getElementById('filename');
const uploadBtn = document.getElementById('upload-btn');

fileInput.addEventListener('change', function() {
    if (this.files.length > 0) {
        showFile(this.files[0]);
    }
});

function showFile(file) {
    filename.textContent = file.name;
    uploadArea.style.display = 'none';
    fileInfo.style.display = 'block';
    uploadBtn.disabled = false;
}

function clearFile() {
    fileInput.value = '';
    uploadArea.style.display = 'block';
    fileInfo.style.display = 'none';
    uploadBtn.disabled = true;
}

// Drag and drop
uploadArea.addEventListener('dragover', (e) => {
    e.preventDefault();
    uploadArea.classList.add('dragover');
});

uploadArea.addEventListener('dragleave', () => {
    uploadArea.classList.remove('dragover');
});

uploadArea.addEventListener('drop', (e) => {
    e.preventDefault();
    uploadArea.classList.remove('dragover');
    
    const files = e.dataTransfer.files;
    if (files.length > 0 && files[0].name.endsWith('.zip')) {
        fileInput.files = files;
        showFile(files[0]);
    }
});

// Preview theme
function previewTheme(themeName) {
    fetch(`/admin/themes/${themeName}/preview`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('preview-title').textContent = `${data.name} {{ __('admin.themes.preview') }}`;
            document.getElementById('preview-name').textContent = data.name;
            document.getElementById('preview-desc').textContent = data.description;
            document.getElementById('preview-meta').innerHTML = `
                <span><label>{{ __('admin.themes.version') }}:</label> <span>v${data.version}</span></span>
                <span><label>{{ __('admin.themes.author') }}:</label> <span>${data.author}</span></span>
                <span><label>{{ __('admin.themes.has_css') }}:</label> <span>${data.has_css ? '{{ __('admin.themes.yes') }}' : '{{ __('admin.themes.no') }}'}</span></span>
                <span><label>{{ __('admin.themes.has_js') }}:</label> <span>${data.has_js ? '{{ __('admin.themes.yes') }}' : '{{ __('admin.themes.no') }}'}</span></span>
            `;
            
            const screenshot = document.getElementById('preview-screenshot');
            if (data.screenshot) {
                screenshot.innerHTML = `<img src="${data.screenshot}" alt="${data.name}">`;
            } else {
                screenshot.innerHTML = `
                    <div style="text-align: center; color: var(--text-muted);">
                        <i class="fas fa-image" style="font-size: 64px; margin-bottom: 16px;"></i>
                        <p>{{ __('admin.themes.no_screenshot') }}</p>
                    </div>
                `;
            }
            
            document.getElementById('theme-preview-modal').classList.add('show');
        });
}
</script>
@endpush
@endsection
