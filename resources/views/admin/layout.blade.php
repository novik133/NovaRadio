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
                        // Open media manager popup
                        window.openMediaPicker(callback);
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
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif
            @if(session('warning'))
                <div class="alert alert-warning">{{ session('warning') }}</div>
            @endif

            @yield('content')
        </main>
    </div>
    
    @stack('scripts')
</body>
</html>
