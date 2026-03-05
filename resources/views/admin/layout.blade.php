<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - NovaRadio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
    <script>
        // Initialize TinyMCE for all rich-text editors
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.rich-editor')) {
                tinymce.init({
                    selector: '.rich-editor',
                    plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
                    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
                    height: 400,
                    menubar: true,
                    branding: false,
                    promotion: false,
                    images_file_types: 'jpg,svg,png,gif',
                    file_picker_callback: (callback, value, meta) => {
                        // Open media picker for TinyMCE
                        if (meta.filetype === 'image') {
                            window.openMediaPicker(function(url, id) {
                                callback(url, { alt: 'Image' });
                            });
                        }
                    },
                    setup: function(editor) {
                        editor.on('change', function() {
                            editor.save();
                        });
                    }
                });
            }
        });
    </script>
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-dark: #4f46e5;
            --bg-light: #f8fafc;
            --bg-hover: #f1f5f9;
            --border-color: #e2e8f0;
            --text-color: #0f172a;
            --text-muted: #64748b;
        }
        
        /* Toast Notifications */
        .toast-container {
            position: fixed;
            top: 80px;
            right: 24px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            max-width: 400px;
        }
        
        .toast {
            background: white;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: slideIn 0.3s ease-out;
            border-left: 4px solid;
            min-width: 320px;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .toast:hover {
            transform: translateX(-4px);
        }
        
        .toast.toast-success {
            border-left-color: #22c55e;
        }
        
        .toast.toast-error {
            border-left-color: #ef4444;
        }
        
        .toast.toast-warning {
            border-left-color: #f59e0b;
        }
        
        .toast.toast-info {
            border-left-color: #3b82f6;
        }
        
        .toast-icon {
            font-size: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }
        
        .toast-success .toast-icon {
            color: #22c55e;
        }
        
        .toast-error .toast-icon {
            color: #ef4444;
        }
        
        .toast-warning .toast-icon {
            color: #f59e0b;
        }
        
        .toast-info .toast-icon {
            color: #3b82f6;
        }
        
        .toast-content {
            flex: 1;
        }
        
        .toast-title {
            font-weight: 600;
            font-size: 14px;
            color: var(--text-color);
            margin-bottom: 4px;
        }
        
        .toast-message {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.5;
        }
        
        .toast-close {
            background: none;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 18px;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.2s;
            flex-shrink: 0;
        }
        
        .toast-close:hover {
            background: var(--bg-light);
            color: var(--text-color);
        }
        
        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        
        .toast.removing {
            animation: slideOut 0.3s ease-out forwards;
        }
        
        /* Media Picker Modal */
        .media-picker-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .media-picker-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
        }
        
        .media-picker-content {
            position: relative;
            background: white;
            border-radius: 16px;
            width: 90%;
            max-width: 900px;
            max-height: 85vh;
            display: flex;
            flex-direction: column;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: modalSlideIn 0.3s ease-out;
        }
        
        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(20px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        .media-picker-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
        }
        
        .media-picker-header h3 {
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 0;
        }
        
        .btn-close {
            background: none;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.2s;
        }
        
        .btn-close:hover {
            background: var(--bg-light);
            color: var(--text-color);
        }
        
        .media-picker-tabs {
            display: flex;
            gap: 4px;
            padding: 16px 24px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .tab-btn {
            padding: 10px 20px;
            background: none;
            border: none;
            border-bottom: 2px solid transparent;
            color: var(--text-muted);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .tab-btn:hover {
            color: var(--text-color);
        }
        
        .tab-btn.active {
            color: var(--primary-color);
            border-bottom-color: var(--primary-color);
        }
        
        .media-picker-body {
            flex: 1;
            overflow: hidden;
            position: relative;
        }
        
        .media-tab {
            display: none;
            height: 100%;
            overflow-y: auto;
            padding: 20px 24px;
        }
        
        .media-tab.active {
            display: block;
        }
        
        .media-picker-search {
            position: relative;
            margin-bottom: 20px;
        }
        
        .media-picker-search input {
            width: 100%;
            padding: 10px 40px 10px 14px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            font-size: 14px;
        }
        
        .media-picker-search i {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
        }
        
        .media-picker-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 16px;
            min-height: 300px;
        }
        
        .media-picker-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.2s;
        }
        
        .media-picker-item:hover {
            border-color: var(--primary-color);
            transform: scale(1.05);
        }
        
        .media-picker-item.selected {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        
        .media-picker-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .media-picker-item .check-icon {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 24px;
            height: 24px;
            background: var(--primary-color);
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
        }
        
        .media-picker-item.selected .check-icon {
            display: flex;
        }
        
        .upload-area {
            border: 2px dashed var(--border-color);
            border-radius: 12px;
            padding: 60px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }
        
        .upload-area:hover {
            border-color: var(--primary-color);
            background: var(--bg-light);
        }
        
        .upload-area.drag-over {
            border-color: var(--primary-color);
            background: rgba(99, 102, 241, 0.05);
        }
        
        .upload-area i {
            font-size: 48px;
            color: var(--text-muted);
            margin-bottom: 16px;
        }
        
        .upload-area h4 {
            font-size: 16px;
            margin-bottom: 8px;
            color: var(--text-color);
        }
        
        .upload-area p {
            color: var(--text-muted);
            font-size: 14px;
        }
        
        .upload-area input[type="file"] {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }
        
        .upload-progress {
            margin-top: 20px;
        }
        
        .progress-bar {
            height: 8px;
            background: var(--bg-light);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 8px;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--primary-color);
            transition: width 0.3s;
            width: 0%;
        }
        
        .progress-text {
            text-align: center;
            font-size: 14px;
            color: var(--text-muted);
        }
        
        .loading-spinner {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            color: var(--text-muted);
            font-size: 14px;
            gap: 10px;
        }
        
        .media-picker-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            padding: 16px 24px;
            border-top: 1px solid var(--border-color);
        }
        
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #f1f5f9; }
        
        /* Admin Header */
        .admin-header { 
            background: #0f172a; 
            color: white; 
            padding: 0 24px; 
            height: 64px; 
            display: flex; 
            align-items: center; 
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .admin-header h1 { font-size: 20px; display: flex; align-items: center; gap: 12px; }
        
        .header-actions { 
            display: flex; 
            gap: 16px; 
            align-items: center; 
        }
        
        /* Update Badge */
        .update-badge {
            position: relative;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            background: #fef3c7;
            color: #92400e;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .update-badge:hover {
            background: #fde68a;
        }
        
        .update-badge i {
            font-size: 12px;
        }
        
        .update-pulse {
            position: absolute;
            top: -2px;
            right: -2px;
            width: 10px;
            height: 10px;
            background: #ef4444;
            border-radius: 50%;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); opacity: 0.8; }
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .user-name {
            font-size: 14px;
            color: #94a3b8;
        }
        
        .admin-layout { display: flex; min-height: calc(100vh - 64px); }
        
        /* Sidebar */
        .admin-sidebar { 
            width: 260px; 
            background: white; 
            border-right: 1px solid #e2e8f0; 
            padding: 16px 0;
            position: fixed;
            height: calc(100vh - 64px);
            overflow-y: auto;
        }
        
        .sidebar-section {
            padding: 8px 0;
            border-bottom: 1px solid var(--border-color);
        }
        
        .sidebar-section:last-child {
            border-bottom: none;
        }
        
        .sidebar-title {
            padding: 8px 24px;
            font-size: 11px;
            text-transform: uppercase;
            color: var(--text-muted);
            font-weight: 600;
            letter-spacing: 0.5px;
        }
        
        .admin-sidebar a { 
            display: flex; 
            align-items: center; 
            gap: 12px; 
            padding: 10px 24px; 
            color: #475569; 
            text-decoration: none; 
            font-size: 14px; 
            transition: all 0.2s;
            position: relative;
        }
        
        .admin-sidebar a:hover, .admin-sidebar a.active { 
            background: #f8fafc; 
            color: #6366f1; 
        }
        
        .admin-sidebar a.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: var(--primary-color);
        }
        
        .admin-sidebar i { width: 20px; text-align: center; }
        
        /* Main Content */
        .admin-content { 
            flex: 1; 
            padding: 32px; 
            overflow-y: auto;
            margin-left: 260px;
        }
        
        /* Cards */
        .card { 
            background: white; 
            border-radius: 12px; 
            padding: 24px; 
            margin-bottom: 24px; 
            box-shadow: 0 1px 3px rgba(0,0,0,0.1); 
        }
        .card h2 { font-size: 18px; margin-bottom: 20px; color: #0f172a; }
        
        /* Buttons */
        .btn { 
            display: inline-flex; 
            align-items: center; 
            gap: 8px; 
            padding: 10px 20px; 
            border-radius: 8px; 
            font-size: 14px; 
            font-weight: 500; 
            text-decoration: none; 
            border: none; 
            cursor: pointer; 
            transition: all 0.2s; 
        }
        .btn-primary { background: #6366f1; color: white; }
        .btn-primary:hover { background: #4f46e5; }
        .btn-danger { background: #ef4444; color: white; }
        .btn-secondary { background: #f1f5f9; color: #475569; }
        .btn-success { background: #22c55e; color: white; }
        .btn-sm { padding: 6px 12px; font-size: 12px; }
        
        /* Alerts */
        .alert { padding: 16px; border-radius: 8px; margin-bottom: 24px; }
        .alert-success { background: #dcfce7; color: #166534; }
        .alert-error { background: #fee2e2; color: #991b1b; }
        .alert-warning { background: #fef3c7; color: #92400e; }
        .alert-info { background: #dbeafe; color: #1e40af; }
        
        /* Tables */
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #e2e8f0; font-size: 14px; }
        th { background: #f8fafc; font-weight: 600; color: #475569; font-size: 12px; text-transform: uppercase; }
        
        /* Badges */
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 500; }
        .badge-published { background: #dcfce7; color: #166534; }
        .badge-draft { background: #fef3c7; color: #92400e; }
        
        /* Forms */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; margin-bottom: 6px; font-size: 14px; font-weight: 500; color: #374151; }
        .form-group input, .form-group textarea, .form-group select { 
            width: 100%; 
            padding: 10px 14px; 
            border: 1px solid #d1d5db; 
            border-radius: 8px; 
            font-size: 14px; 
        }
        .form-group input:focus, .form-group textarea:focus { 
            outline: none; 
            border-color: #6366f1; 
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        
        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }
        
        .page-header h1 {
            font-size: 24px;
            color: var(--text-color);
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 48px;
            color: var(--text-muted);
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 16px;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }
            .admin-content {
                margin-left: 0;
            }
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <header class="admin-header">
        <h1><i class="fas fa-broadcast-tower"></i> NovaRadio Admin</h1>
        <div class="header-actions">
            {{-- Update Notification Badge --}}
            @php
                $updateAvailable = session('update_available', false);
                $newVersion = session('new_version', '');
            @endphp
            @if($updateAvailable)
                <a href="{{ route('admin.updates.index') }}" class="update-badge">
                    <i class="fas fa-arrow-up"></i>
                    <span>Update v{{ $newVersion }} available</span>
                    <span class="update-pulse"></span>
                </a>
            @endif
            
            <div class="user-menu">
                <span class="user-name">{{ auth()->user()?->name ?? 'Guest' }}</span>
                <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-sign-out-alt"></i></button>
                </form>
            </div>
        </div>
    </header>

    <div class="admin-layout">
        <aside class="admin-sidebar">
            <div class="sidebar-section">
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-title">Content</div>
                <a href="{{ route('admin.pages.index') }}" class="{{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
                    <i class="fas fa-file-alt"></i> Pages
                </a>
                <a href="{{ route('admin.articles.index') }}" class="{{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i> Articles
                </a>
                <a href="{{ route('admin.categories.index') }}" class="{{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                    <i class="fas fa-folder"></i> Categories
                </a>
                <a href="{{ route('admin.tags.index') }}" class="{{ request()->routeIs('admin.tags.*') ? 'active' : '' }}">
                    <i class="fas fa-tags"></i> Tags
                </a>
                <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-check"></i> Events
                </a>
                <a href="{{ route('admin.team.index') }}" class="{{ request()->routeIs('admin.team.*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> Team / DJs
                </a>
                <a href="{{ route('admin.schedule.index') ?? '#' }}" class="{{ request()->routeIs('admin.schedule.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i> Schedule
                </a>
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-title">Media & Design</div>
                <a href="{{ route('admin.media.index') ?? '#' }}" class="{{ request()->routeIs('admin.media.*') ? 'active' : '' }}">
                    <i class="fas fa-images"></i> Media Library
                </a>
                <a href="{{ route('admin.themes.index') ?? '#' }}" class="{{ request()->routeIs('admin.themes.*') ? 'active' : '' }}">
                    <i class="fas fa-palette"></i> Themes
                </a>
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-title">Account</div>
                <a href="{{ route('admin.profile.edit') }}" class="{{ request()->routeIs('admin.profile.*') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i> My Profile
                </a>
            </div>
            
            <div class="sidebar-section">
                <div class="sidebar-title">System</div>
                <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <a href="{{ route('admin.updates.index') }}" class="{{ request()->routeIs('admin.updates.*') ? 'active' : '' }}">
                    <i class="fas fa-sync"></i> Updates
                    @if($updateAvailable)
                        <span class="badge badge-published" style="margin-left: auto; font-size: 10px;">NEW</span>
                    @endif
                </a>
            </div>
            
            <div class="sidebar-section">
                <a href="{{ route('home') }}" target="_blank">
                    <i class="fas fa-external-link-alt"></i> View Site
                </a>
            </div>
        </aside>

        <main class="admin-content">
            @yield('content')
        </main>
    </div>
    
    <!-- Toast Notification Container -->
    <div class="toast-container" id="toast-container"></div>
    
    <!-- Media Picker Modal -->
    <div id="media-picker-modal" class="media-picker-modal" style="display: none;">
        <div class="media-picker-overlay" onclick="closeMediaPicker()"></div>
        <div class="media-picker-content">
            <div class="media-picker-header">
                <h3><i class="fas fa-images"></i> Select Media</h3>
                <button class="btn-close" onclick="closeMediaPicker()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="media-picker-tabs">
                <button class="tab-btn active" onclick="switchMediaTab('library')">
                    <i class="fas fa-folder-open"></i> Media Library
                </button>
                <button class="tab-btn" onclick="switchMediaTab('upload')">
                    <i class="fas fa-upload"></i> Upload New
                </button>
            </div>
            
            <div class="media-picker-body">
                <!-- Library Tab -->
                <div id="media-tab-library" class="media-tab active">
                    <div class="media-picker-search">
                        <input type="text" id="media-search" placeholder="Search images..." onkeyup="filterMediaItems()">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="media-picker-grid" id="media-picker-grid">
                        <div class="loading-spinner">
                            <i class="fas fa-spinner fa-spin"></i> Loading media...
                        </div>
                    </div>
                </div>
                
                <!-- Upload Tab -->
                <div id="media-tab-upload" class="media-tab">
                    <div class="upload-area" id="upload-area">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <h4>Drag & Drop files here</h4>
                        <p>or click to browse</p>
                        <input type="file" id="media-picker-file-input" multiple accept="image/*" onchange="handleMediaUpload(this.files)">
                    </div>
                    <div id="upload-progress" class="upload-progress" style="display: none;">
                        <div class="progress-bar">
                            <div class="progress-fill" id="upload-progress-fill"></div>
                        </div>
                        <div class="progress-text" id="upload-progress-text">0%</div>
                    </div>
                </div>
            </div>
            
            <div class="media-picker-footer">
                <button class="btn btn-secondary" onclick="closeMediaPicker()">Cancel</button>
                <button class="btn btn-primary" id="media-select-btn" onclick="confirmMediaSelection()" disabled>
                    <i class="fas fa-check"></i> Select
                </button>
            </div>
        </div>
    </div>
    
    <script>
        // Toast Notification System
        window.showToast = function(message, type = 'info', title = null) {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className = `toast toast-${type}`;
            
            const icons = {
                success: 'fa-circle-check',
                error: 'fa-circle-xmark',
                warning: 'fa-triangle-exclamation',
                info: 'fa-circle-info'
            };
            
            const titles = {
                success: title || 'Success',
                error: title || 'Error',
                warning: title || 'Warning',
                info: title || 'Info'
            };
            
            toast.innerHTML = `
                <i class="fas ${icons[type]} toast-icon"></i>
                <div class="toast-content">
                    <div class="toast-title">${titles[type]}</div>
                    <div class="toast-message">${message}</div>
                </div>
                <button class="toast-close" onclick="this.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            `;
            
            // Click anywhere on toast to close
            toast.addEventListener('click', function(e) {
                if (!e.target.closest('.toast-close')) {
                    toast.classList.add('removing');
                    setTimeout(() => toast.remove(), 300);
                }
            });
            
            container.appendChild(toast);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.add('removing');
                    setTimeout(() => toast.remove(), 300);
                }
            }, 5000);
        };
        
        // Override native alert
        window.alert = function(message) {
            window.showToast(message, 'info');
        };
        
        // Show Laravel session messages as toasts
        @if(session('success'))
            window.showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            window.showToast('{{ session('error') }}', 'error');
        @endif
        
        @if(session('warning'))
            window.showToast('{{ session('warning') }}', 'warning');
        @endif
        
        @if(session('info'))
            window.showToast('{{ session('info') }}', 'info');
        @endif
        
        // Show validation errors as toasts
        @if($errors->any())
            @foreach($errors->all() as $error)
                window.showToast('{{ $error }}', 'error', 'Validation Error');
            @endforeach
        @endif
        
        // Media Picker System
        let mediaPickerCallback = null;
        let selectedMediaUrl = null;
        let selectedMediaId = null;
        
        window.openMediaPicker = function(callback) {
            mediaPickerCallback = callback;
            selectedMediaUrl = null;
            selectedMediaId = null;
            document.getElementById('media-picker-modal').style.display = 'flex';
            document.getElementById('media-select-btn').disabled = true;
            loadMediaLibrary();
        };
        
        window.closeMediaPicker = function() {
            document.getElementById('media-picker-modal').style.display = 'none';
            mediaPickerCallback = null;
            selectedMediaUrl = null;
            selectedMediaId = null;
        };
        
        window.switchMediaTab = function(tab) {
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.media-tab').forEach(t => t.classList.remove('active'));
            
            event.target.closest('.tab-btn').classList.add('active');
            document.getElementById('media-tab-' + tab).classList.add('active');
        };
        
        window.loadMediaLibrary = function() {
            const grid = document.getElementById('media-picker-grid');
            grid.innerHTML = '<div class="loading-spinner"><i class="fas fa-spinner fa-spin"></i> Loading media...</div>';
            
            fetch('{{ route("admin.media.api.list") }}')
                .then(r => r.json())
                .then(data => {
                    if (data.success) {
                        renderMediaGrid(data.files);
                    } else {
                        grid.innerHTML = '<div class="loading-spinner"><i class="fas fa-exclamation-circle"></i> Failed to load media</div>';
                    }
                })
                .catch(err => {
                    console.error('Failed to load media:', err);
                    grid.innerHTML = '<div class="loading-spinner"><i class="fas fa-exclamation-circle"></i> Failed to load media</div>';
                });
        };
        
        window.renderMediaGrid = function(files) {
            const grid = document.getElementById('media-picker-grid');
            
            if (files.length === 0) {
                grid.innerHTML = '<div class="loading-spinner"><i class="fas fa-folder-open"></i> No images found</div>';
                return;
            }
            
            grid.innerHTML = files.map(file => `
                <div class="media-picker-item" data-id="${file.id}" data-url="${file.url}" onclick="selectMediaItem(this)">
                    <img src="${file.url}" alt="${file.name}" loading="lazy">
                    <div class="check-icon"><i class="fas fa-check"></i></div>
                </div>
            `).join('');
        };
        
        window.selectMediaItem = function(element) {
            document.querySelectorAll('.media-picker-item').forEach(item => item.classList.remove('selected'));
            element.classList.add('selected');
            
            selectedMediaUrl = element.dataset.url;
            selectedMediaId = element.dataset.id;
            document.getElementById('media-select-btn').disabled = false;
        };
        
        window.confirmMediaSelection = function() {
            if (selectedMediaUrl && mediaPickerCallback) {
                mediaPickerCallback(selectedMediaUrl, selectedMediaId);
                closeMediaPicker();
            }
        };
        
        window.filterMediaItems = function() {
            const search = document.getElementById('media-search').value.toLowerCase();
            document.querySelectorAll('.media-picker-item').forEach(item => {
                const img = item.querySelector('img');
                const alt = img ? img.alt.toLowerCase() : '';
                item.style.display = alt.includes(search) ? 'block' : 'none';
            });
        };
        
        window.handleMediaUpload = function(files) {
            if (!files || files.length === 0) return;
            
            const progressDiv = document.getElementById('upload-progress');
            const progressFill = document.getElementById('upload-progress-fill');
            const progressText = document.getElementById('upload-progress-text');
            
            progressDiv.style.display = 'block';
            progressFill.style.width = '0%';
            progressText.textContent = '0%';
            
            const formData = new FormData();
            for (let file of files) {
                formData.append('files[]', file);
            }
            formData.append('folder', 'images');
            
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
                    showToast('Files uploaded successfully!', 'success');
                    progressDiv.style.display = 'none';
                    
                    // Switch to library tab and reload
                    switchMediaTab('library');
                    document.querySelectorAll('.tab-btn')[0].classList.add('active');
                    document.querySelectorAll('.tab-btn')[1].classList.remove('active');
                    loadMediaLibrary();
                } else {
                    showToast(response.message || 'Upload failed', 'error');
                    progressDiv.style.display = 'none';
                }
            });
            
            xhr.addEventListener('error', () => {
                showToast('Upload failed. Please try again.', 'error');
                progressDiv.style.display = 'none';
            });
            
            xhr.open('POST', '{{ route("admin.media.upload") }}');
            xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
            xhr.send(formData);
        };
        
        // Drag and drop for upload area
        const uploadArea = document.getElementById('upload-area');
        if (uploadArea) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });
            
            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }
            
            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => {
                    uploadArea.classList.add('drag-over');
                }, false);
            });
            
            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, () => {
                    uploadArea.classList.remove('drag-over');
                }, false);
            });
            
            uploadArea.addEventListener('drop', (e) => {
                const files = e.dataTransfer.files;
                handleMediaUpload(files);
            }, false);
        }
    </script>
    
    @stack('scripts')
</body>
</html>
