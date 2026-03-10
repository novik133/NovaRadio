<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('installer.title') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --color-primary: hsl(14, 100%, 50%);
            --color-primary-dark: hsl(14, 100%, 45%);
            --color-secondary: hsl(220, 14%, 96%);
            --color-bg-dark: hsl(0, 0%, 99%);
            --color-bg-card: hsl(0, 0%, 100%);
            --color-bg-input: hsl(220, 14%, 96%);
            --color-border: hsl(220, 13%, 91%);
            --color-text: hsl(220, 20%, 14%);
            --color-text-muted: hsl(220, 10%, 46%);
            --color-success: #22c55e;
            --color-error: #ef4444;
            --radius: 8px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background: var(--color-bg-dark);
            min-height: 100vh;
            color: var(--color-text);
            -webkit-font-smoothing: antialiased;
        }
        
        h1, h2, h3, h4, h5, h6 {
            font-family: 'Roboto Mono', monospace;
        }

        .install-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
        }

        .install-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .install-logo {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 12px;
        }

        .install-logo-icon {
            width: 48px;
            height: 48px;
            background: hsl(14, 100%, 97%);
            border-radius: var(--radius);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--color-primary);
            font-size: 22px;
            border: 1px solid hsl(14, 100%, 92%);
        }

        .install-logo span {
            font-size: 32px;
            font-weight: 700;
            color: var(--color-text);
            font-family: 'Roboto Mono', monospace;
        }
        
        .install-logo span .highlight {
            color: var(--color-primary);
        }

        .install-header p {
            color: var(--color-text-muted);
            font-size: 15px;
        }

        /* Step Indicator */
        .step-indicator {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 32px;
            max-width: 700px;
            width: 100%;
        }

        .step-dot {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .step-num {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Roboto Mono', monospace;
            background: var(--color-bg-input);
            color: var(--color-text-muted);
            border: 2px solid var(--color-border);
            transition: all 0.3s;
        }

        .step-dot.active .step-num {
            background: var(--color-primary);
            border-color: var(--color-primary);
            color: white;
            box-shadow: 0 0 20px hsla(14, 100%, 50%, 0.3);
        }

        .step-dot.completed .step-num {
            background: var(--color-success);
            border-color: var(--color-success);
            color: white;
        }

        .step-label {
            font-size: 11px;
            font-weight: 600;
            font-family: 'Roboto Mono', monospace;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--color-text-muted);
            display: none;
        }

        .step-dot.active .step-label {
            color: var(--color-text);
            display: inline;
        }

        .step-line {
            width: 40px;
            height: 2px;
            background: var(--color-border);
            transition: background 0.3s;
        }

        .step-line.completed {
            background: var(--color-success);
        }

        /* Card */
        .install-card {
            max-width: 700px;
            width: 100%;
            background: var(--color-bg-card);
            border-radius: 12px;
            border: 1px solid var(--color-border);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card-body {
            padding: 32px;
        }

        .card-title {
            font-size: 20px;
            font-weight: 600;
            font-family: 'Roboto Mono', monospace;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--color-text);
        }

        .card-title i {
            color: var(--color-primary);
            font-size: 18px;
        }

        .card-subtitle {
            color: var(--color-text-muted);
            font-size: 14px;
            margin-bottom: 24px;
        }

        /* Step panels */
        .step-panel {
            display: none;
        }

        .step-panel.active {
            display: block;
        }

        /* License */
        .license-box {
            background: var(--color-bg-input);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            padding: 24px;
            max-height: 320px;
            overflow-y: auto;
            margin-bottom: 24px;
            font-size: 13px;
            line-height: 1.8;
            color: var(--color-text-muted);
            white-space: pre-wrap;
            font-family: 'Roboto Mono', monospace;
        }

        .license-box::-webkit-scrollbar {
            width: 6px;
        }

        .license-box::-webkit-scrollbar-track {
            background: transparent;
        }

        .license-box::-webkit-scrollbar-thumb {
            background: var(--color-border);
            border-radius: 3px;
        }

        .license-accept {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--color-bg-input);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            padding: 16px 20px;
        }

        .license-accept-text {
            font-weight: 500;
            font-size: 14px;
            font-family: 'Roboto Mono', monospace;
        }

        .license-accept-text small {
            display: block;
            color: var(--color-text-muted);
            font-weight: 400;
            font-size: 12px;
            margin-top: 2px;
            font-family: 'Inter', sans-serif;
        }

        /* Switch Toggle */
        .switch-toggle {
            position: relative;
            display: inline-block;
        }

        .switch-toggle input {
            opacity: 0;
            width: 0;
            height: 0;
            position: absolute;
        }

        .switch-toggle label {
            display: block;
            width: 52px;
            height: 28px;
            background: var(--color-bg-input);
            border-radius: 28px;
            cursor: pointer;
            position: relative;
            transition: background 0.3s;
            border: 2px solid var(--color-border);
        }

        .switch-toggle label::after {
            content: '';
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            background: white;
            border-radius: 50%;
            transition: transform 0.3s;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        }

        .switch-toggle input:checked + label {
            background: var(--color-primary);
            border-color: var(--color-primary);
        }

        .switch-toggle input:checked + label::after {
            transform: translateX(24px);
        }

        /* Requirements */
        .requirements-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .requirement-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            background: var(--color-bg-card);
            border-radius: var(--radius);
            border: 1px solid var(--color-border);
        }

        .requirement-icon {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            flex-shrink: 0;
        }

        .requirement-item.met .requirement-icon {
            background: rgba(34, 197, 94, 0.2);
            color: var(--color-success);
        }

        .requirement-item.not-met .requirement-icon {
            background: rgba(239, 68, 68, 0.2);
            color: var(--color-error);
        }

        .requirement-name {
            font-weight: 500;
            font-size: 14px;
            font-family: 'Roboto Mono', monospace;
        }

        .requirement-status {
            font-size: 12px;
            color: var(--color-text-muted);
            font-family: 'Inter', sans-serif;
        }

        /* Form */
        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
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
            font-size: 13px;
            font-weight: 500;
            color: var(--color-text);
            font-family: 'Roboto Mono', monospace;
        }

        .form-group input,
        .form-group select {
            padding: 10px 14px;
            background: var(--color-bg-card);
            border: 1px solid var(--color-border);
            border-radius: var(--radius);
            font-size: 14px;
            color: var(--color-text);
            font-family: 'Inter', sans-serif;
            transition: all 0.2s;
        }

        .form-group input::placeholder {
            color: var(--color-text-muted);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px hsla(14, 100%, 50%, 0.1);
        }

        .form-group small {
            font-size: 12px;
            color: var(--color-text-muted);
        }

        /* Buttons */
        .card-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 32px;
            border-top: 1px solid var(--color-border);
            background: var(--color-secondary);
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border-radius: var(--radius);
            font-weight: 500;
            font-size: 14px;
            font-family: 'Roboto Mono', monospace;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
        }

        .btn-primary {
            background: var(--color-primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--color-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px hsla(14, 100%, 50%, 0.3);
        }

        .btn-primary:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .btn-secondary {
            background: var(--color-bg-input);
            color: var(--color-text);
            border: 1px solid var(--color-border);
        }

        .btn-secondary:hover {
            background: var(--color-border);
        }

        .btn-install {
            background: var(--color-primary);
            color: white;
            padding: 12px 28px;
            font-size: 14px;
        }

        .btn-install:hover {
            background: var(--color-primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px hsla(14, 100%, 50%, 0.3);
        }

        .btn-install:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        /* Alerts */
        .alert {
            padding: 14px 20px;
            border-radius: var(--radius);
            margin-bottom: 20px;
            max-width: 700px;
            width: 100%;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 14px;
        }

        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #fca5a5;
        }

        .alert-success {
            background: rgba(34, 197, 94, 0.15);
            border: 1px solid rgba(34, 197, 94, 0.3);
            color: #86efac;
        }

        /* Section divider */
        .section-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 24px 0 20px;
        }

        .section-divider span {
            font-size: 11px;
            font-weight: 600;
            font-family: 'Roboto Mono', monospace;
            color: var(--color-text-muted);
            text-transform: uppercase;
            letter-spacing: 0.05em;
            white-space: nowrap;
        }

        .section-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--color-border);
        }

        /* Loading spinner */
        .spinner {
            display: inline-block;
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .requirements-grid, .form-grid {
                grid-template-columns: 1fr;
            }
            .step-label {
                display: none !important;
            }
            .card-body {
                padding: 24px 20px;
            }
            .card-footer {
                padding: 16px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="install-wrapper">
        <div class="install-header">
            <div class="install-logo">
                <div class="install-logo-icon">
                    <i class="fas fa-broadcast-tower"></i>
                </div>
                <span>NovaRadio<span class="highlight">CMS</span></span>
            </div>
            <p>{{ __('installer.title') }}</p>
        </div>

        <!-- Step Indicator -->
        <div class="step-indicator">
            <div class="step-dot active" data-step="1">
                <span class="step-num">1</span>
                <span class="step-label">{{ __('installer.steps.license') }}</span>
            </div>
            <div class="step-line"></div>
            <div class="step-dot" data-step="2">
                <span class="step-num">2</span>
                <span class="step-label">{{ __('installer.steps.requirements') }}</span>
            </div>
            <div class="step-line"></div>
            <div class="step-dot" data-step="3">
                <span class="step-num">3</span>
                <span class="step-label">{{ __('installer.steps.database') }}</span>
            </div>
            <div class="step-line"></div>
            <div class="step-dot" data-step="4">
                <span class="step-num">4</span>
                <span class="step-label">{{ __('installer.steps.admin') }}</span>
            </div>
            <div class="step-line"></div>
            <div class="step-dot" data-step="5">
                <span class="step-num">5</span>
                <span class="step-label">{{ __('installer.steps.streaming') }}</span>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        <form method="POST" action="{{ url('/install') }}" id="install-form">
            @csrf

            <div class="install-card">
                <!-- Step 1: License -->
                <div class="step-panel active" id="step-1">
                    <div class="card-body">
                        <div class="card-title"><i class="fas fa-file-contract"></i> {{ __('installer.license_title') }}</div>
                        <div class="card-subtitle">{{ __('installer.license_subtitle') }}</div>

                        <div class="license-box">{{ $license }}</div>

                        <div class="license-accept">
                            <div class="license-accept-text">
                                {{ __('installer.accept_license') }}
                                <small>{{ __('installer.must_accept') }}</small>
                            </div>
                            <div class="switch-toggle">
                                <input type="checkbox" id="license_accepted" name="license_accepted">
                                <label for="license_accepted"></label>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div></div>
                        <button type="button" class="btn btn-primary" id="btn-next-1" disabled onclick="goToStep(2)">
                            {{ __('installer.next') }} <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 2: Requirements -->
                <div class="step-panel" id="step-2">
                    <div class="card-body">
                        <div class="card-title"><i class="fas fa-clipboard-check"></i> {{ __('installer.requirements_title') }}</div>
                        <div class="card-subtitle">{{ __('installer.requirements_subtitle') }}</div>

                        <div class="requirements-grid">
                            @foreach($requirements as $req)
                                <div class="requirement-item {{ $req['met'] ? 'met' : 'not-met' }}">
                                    <div class="requirement-icon">
                                        <i class="fas {{ $req['met'] ? 'fa-check' : 'fa-times' }}"></i>
                                    </div>
                                    <div>
                                        <div class="requirement-name">{{ $req['name'] }}</div>
                                        <div class="requirement-status">{{ $req['current'] }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-secondary" onclick="goToStep(1)">
                            <i class="fas fa-arrow-left"></i> {{ __('installer.previous') }}
                        </button>
                        <button type="button" class="btn btn-primary" onclick="goToStep(3)" {{ $allMet ? '' : 'disabled' }}>
                            {{ __('installer.next') }} <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 3: Database -->
                <div class="step-panel" id="step-3">
                    <div class="card-body">
                        <div class="card-title"><i class="fas fa-database"></i> {{ __('installer.database_title') }}</div>
                        <div class="card-subtitle">{{ __('installer.database_subtitle') }}</div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="db_host">{{ __('installer.db_host') }}</label>
                                <input type="text" id="db_host" name="db_host" value="{{ old('db_host', '127.0.0.1') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="db_port">{{ __('installer.db_port') }}</label>
                                <input type="number" id="db_port" name="db_port" value="{{ old('db_port', '3306') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="db_database">{{ __('installer.db_name') }}</label>
                                <input type="text" id="db_database" name="db_database" value="{{ old('db_database') }}" placeholder="novaradio" required>
                            </div>
                            <div class="form-group">
                                <label for="db_username">{{ __('installer.db_username') }}</label>
                                <input type="text" id="db_username" name="db_username" value="{{ old('db_username') }}" placeholder="root" required>
                            </div>
                            <div class="form-group full-width">
                                <label for="db_password">{{ __('installer.db_password') }}</label>
                                <input type="password" id="db_password" name="db_password" placeholder="Leave empty if no password">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-secondary" onclick="goToStep(2)">
                            <i class="fas fa-arrow-left"></i> {{ __('installer.previous') }}
                        </button>
                        <button type="button" class="btn btn-primary" onclick="goToStep(4)">
                            {{ __('installer.next') }} <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 4: Admin Account -->
                <div class="step-panel" id="step-4">
                    <div class="card-body">
                        <div class="card-title"><i class="fas fa-user-shield"></i> {{ __('installer.admin_title') }}</div>
                        <div class="card-subtitle">{{ __('installer.admin_subtitle') }}</div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="admin_name">{{ __('installer.admin_name') }}</label>
                                <input type="text" id="admin_name" name="admin_name" value="{{ old('admin_name', 'Administrator') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="admin_email">{{ __('installer.admin_email') }}</label>
                                <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email') }}" placeholder="admin@example.com" required>
                            </div>
                            <div class="form-group">
                                <label for="admin_password">{{ __('installer.admin_password') }}</label>
                                <input type="password" id="admin_password" name="admin_password" required>
                                <small>{{ __('installer.password_min') }}</small>
                            </div>
                            <div class="form-group">
                                <label for="admin_password_confirmation">{{ __('installer.admin_password_confirm') }}</label>
                                <input type="password" id="admin_password_confirmation" name="admin_password_confirmation" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-secondary" onclick="goToStep(3)">
                            <i class="fas fa-arrow-left"></i> {{ __('installer.previous') }}
                        </button>
                        <button type="button" class="btn btn-primary" onclick="goToStep(5)">
                            {{ __('installer.next') }} <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                </div>

                <!-- Step 5: Streaming (AzuraCast) -->
                <div class="step-panel" id="step-5">
                    <div class="card-body">
                        <div class="card-title"><i class="fas fa-broadcast-tower"></i> {{ __('installer.streaming_title') }}</div>
                        <div class="card-subtitle">{{ __('installer.streaming_subtitle') }}</div>

                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="azuracast_url">{{ __('installer.azuracast_url') }}</label>
                                <input type="url" id="azuracast_url" name="azuracast_url" value="{{ old('azuracast_url') }}" placeholder="https://your-azuracast.com">
                            </div>
                            <div class="form-group full-width">
                                <label for="azuracast_api_key">{{ __('installer.azuracast_api_key') }}</label>
                                <input type="text" id="azuracast_api_key" name="azuracast_api_key" value="{{ old('azuracast_api_key') }}" placeholder="Your API key">
                            </div>
                        </div>

                        <div class="section-divider">
                            <span>Or use a direct stream</span>
                        </div>

                        <div class="form-grid">
                            <div class="form-group full-width">
                                <label for="stream_url">{{ __('installer.stream_url') }}</label>
                                <input type="url" id="stream_url" name="stream_url" value="{{ old('stream_url') }}" placeholder="{{ __('installer.stream_url_placeholder') }}">
                                <small>{{ __('installer.stream_url_hint') }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="button" class="btn btn-secondary" onclick="goToStep(4)">
                            <i class="fas fa-arrow-left"></i> {{ __('installer.previous') }}
                        </button>
                        <button type="submit" class="btn btn-install" id="btn-submit">
                            <i class="fas fa-rocket"></i> {{ __('installer.install') }}
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 5;

        function goToStep(step) {
            // Validate current step before moving forward
            if (step > currentStep && !validateStep(currentStep)) return;

            // Hide current
            document.getElementById('step-' + currentStep).classList.remove('active');
            // Show target
            document.getElementById('step-' + step).classList.add('active');

            // Update indicator
            document.querySelectorAll('.step-dot').forEach(dot => {
                const s = parseInt(dot.dataset.step);
                dot.classList.remove('active', 'completed');
                if (s < step) dot.classList.add('completed');
                else if (s === step) dot.classList.add('active');
            });

            document.querySelectorAll('.step-line').forEach((line, idx) => {
                line.classList.toggle('completed', idx < step - 1);
            });

            currentStep = step;
        }

        function validateStep(step) {
            if (step === 1) {
                if (!document.getElementById('license_accepted').checked) {
                    return false;
                }
            }
            if (step === 3) {
                const fields = ['db_host', 'db_port', 'db_database', 'db_username'];
                for (const f of fields) {
                    if (!document.getElementById(f).value.trim()) {
                        document.getElementById(f).focus();
                        return false;
                    }
                }
            }
            if (step === 4) {
                const name = document.getElementById('admin_name').value.trim();
                const email = document.getElementById('admin_email').value.trim();
                const pass = document.getElementById('admin_password').value;
                const passConf = document.getElementById('admin_password_confirmation').value;

                if (!name || !email) {
                    document.getElementById(name ? 'admin_email' : 'admin_name').focus();
                    return false;
                }
                if (pass.length < 8) {
                    document.getElementById('admin_password').focus();
                    return false;
                }
                if (pass !== passConf) {
                    document.getElementById('admin_password_confirmation').focus();
                    return false;
                }
            }
            return true;
        }

        // License toggle enables/disables next button
        document.getElementById('license_accepted').addEventListener('change', function() {
            document.getElementById('btn-next-1').disabled = !this.checked;
        });

        // Form submit loading state
        document.getElementById('install-form').addEventListener('submit', function(e) {
            if (!validateStep(currentStep)) {
                e.preventDefault();
                return;
            }
            const btn = document.getElementById('btn-submit');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> {{ __('installer.installing') }}';
        });
    </script>
</body>
</html>
