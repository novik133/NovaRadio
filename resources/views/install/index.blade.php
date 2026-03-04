<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install NovaRadio</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .install-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .install-header {
            text-align: center;
            margin-bottom: 40px;
            color: white;
        }
        .install-header h1 {
            font-size: 36px;
            margin-bottom: 12px;
        }
        .install-header p {
            opacity: 0.9;
        }
        .install-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.2);
        }
        .install-card h2 {
            font-size: 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .requirements-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        .requirement-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: #f8fafc;
            border-radius: 8px;
        }
        .requirement-item.met {
            background: #dcfce7;
        }
        .requirement-item.not-met {
            background: #fee2e2;
        }
        .requirement-icon {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }
        .met .requirement-icon {
            background: #10b981;
            color: white;
        }
        .not-met .requirement-icon {
            background: #ef4444;
            color: white;
        }
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
        }
        .form-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .form-group.full-width {
            grid-column: 1 / -1;
        }
        .form-group label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
        }
        .form-group input, .form-group textarea {
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 14px;
        }
        .form-group input:focus, .form-group textarea:focus {
            outline: none;
            border-color: #6366f1;
        }
        .btn-install {
            width: 100%;
            padding: 16px;
            background: #6366f1;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }
        .btn-install:hover {
            background: #4f46e5;
        }
        .alert {
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 24px;
        }
        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }
        .alert-success {
            background: #dcfce7;
            color: #166534;
        }
        @media (max-width: 768px) {
            .requirements-grid, .form-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="install-container">
        <div class="install-header">
            <h1><i class="fas fa-broadcast-tower"></i> NovaRadio</h1>
            <p>Installation Wizard v2.0</p>
        </div>

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <div class="install-card">
            <h2><i class="fas fa-check-circle" style="color: #10b981;"></i> System Requirements</h2>
            <div class="requirements-grid">
                @foreach($requirements as $req)
                    <div class="requirement-item {{ $req['met'] ? 'met' : 'not-met' }}">
                        <div class="requirement-icon">
                            <i class="fas {{ $req['met'] ? 'fa-check' : 'fa-times' }}"></i>
                        </div>
                        <div>
                            <div style="font-weight: 500;">{{ $req['name'] }}</div>
                            <div style="font-size: 12px; color: #64748b;">{{ $req['current'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="install-card">
            <h2><i class="fas fa-database" style="color: #6366f1;"></i> Database Configuration</h2>
            <form method="POST" action="{{ url('/install') }}">
                @csrf
                <div class="form-grid">
                    <div class="form-group">
                        <label>Database Host</label>
                        <input type="text" name="db_host" value="127.0.0.1" required>
                    </div>
                    <div class="form-group">
                        <label>Database Port</label>
                        <input type="number" name="db_port" value="3306" required>
                    </div>
                    <div class="form-group">
                        <label>Database Name</label>
                        <input type="text" name="db_database" placeholder="novaradio" required>
                    </div>
                    <div class="form-group">
                        <label>Database Username</label>
                        <input type="text" name="db_username" placeholder="root" required>
                    </div>
                    <div class="form-group full-width">
                        <label>Database Password</label>
                        <input type="password" name="db_password" placeholder="Leave empty if no password">
                    </div>
                </div>

                <h2 style="margin-top: 32px; margin-bottom: 24px;">
                    <i class="fas fa-user-shield" style="color: #6366f1;"></i> Admin Account
                </h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label>Admin Name</label>
                        <input type="text" name="admin_name" value="Administrator" required>
                    </div>
                    <div class="form-group">
                        <label>Admin Email</label>
                        <input type="email" name="admin_email" placeholder="admin@example.com" required>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="admin_password" required>
                    </div>
                    <div class="form-group">
                        <label>Confirm Password</label>
                        <input type="password" name="admin_password_confirmation" required>
                    </div>
                </div>

                <h2 style="margin-top: 32px; margin-bottom: 24px;">
                    <i class="fas fa-broadcast-tower" style="color: #6366f1;"></i> AzuraCast (Optional)
                </h2>
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>AzuraCast URL</label>
                        <input type="url" name="azuracast_url" placeholder="https://your-azuracast.com">
                    </div>
                    <div class="form-group full-width">
                        <label>AzuraCast API Key</label>
                        <input type="text" name="azuracast_api_key" placeholder="Your API key">
                    </div>
                </div>

                <button type="submit" class="btn-install" style="margin-top: 32px;">
                    <i class="fas fa-rocket"></i> Install NovaRadio
                </button>
            </form>
        </div>
    </div>
</body>
</html>
