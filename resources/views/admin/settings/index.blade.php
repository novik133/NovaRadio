@extends('admin.layout')

@section('title', __('admin.settings.title'))

@section('content')
<div class="admin-header">
    <h1><i class="fas fa-cog"></i> {{ __('admin.settings.title') }}</h1>
    <p>{{ __('admin.settings.subtitle') }}</p>
</div>

@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" class="settings-form">
    @csrf
    
    <div class="settings-tabs">
        <button type="button" class="tab-btn active" data-tab="general">
            <i class="fas fa-home"></i> {{ __('admin.settings.tab_general') }}
        </button>
        <button type="button" class="tab-btn" data-tab="contact">
            <i class="fas fa-address-card"></i> {{ __('admin.settings.tab_contact') }}
        </button>
        <button type="button" class="tab-btn" data-tab="social">
            <i class="fas fa-share-alt"></i> {{ __('admin.settings.tab_social') }}
        </button>
        <button type="button" class="tab-btn" data-tab="seo">
            <i class="fas fa-search"></i> {{ __('admin.settings.tab_seo') }}
        </button>
        <button type="button" class="tab-btn" data-tab="appearance">
            <i class="fas fa-palette"></i> {{ __('admin.settings.tab_appearance') }}
        </button>
        <button type="button" class="tab-btn" data-tab="streaming">
            <i class="fas fa-broadcast-tower"></i> {{ __('admin.settings.tab_streaming') }}
        </button>
        <button type="button" class="tab-btn" data-tab="advanced">
            <i class="fas fa-sliders-h"></i> {{ __('admin.settings.tab_advanced') }}
        </button>
    </div>
    
    <div class="settings-content">
        {{-- General Tab --}}
        <div class="tab-pane active" id="general">
            <div class="form-section">
                <h3>{{ __('admin.settings.website_info') }}</h3>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="site_name">{{ __('admin.settings.site_name') }}</label>
                        <input type="text" id="site_name" name="site_name" 
                               value="{{ ($settings["site_name"] ?? null)?->value ?? 'NovaRadio' }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="site_tagline">{{ __('admin.settings.site_tagline') }}</label>
                        <input type="text" id="site_tagline" name="site_tagline" 
                               value="{{ ($settings["site_tagline"] ?? null)?->value ?? 'Your Soundtrack to Life' }}">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="site_description">{{ __('admin.settings.site_description') }}</label>
                    <textarea id="site_description" name="site_description" rows="3">{{ ($settings["site_description"] ?? null)?->value ?? '' }}</textarea>
                    <small>{{ __('admin.settings.description_hint') }}</small>
                </div>
                
                <div class="form-row">
                    @if(auth()->user()->isAdmin())
                    <div class="form-group">
                        <label for="site_language">{{ __('admin.settings.site_language') }}</label>
                        <select id="site_language" name="site_language">
                            <option value="en" {{ (($settings["site_language"] ?? null)?->value ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="pl" {{ (($settings["site_language"] ?? null)?->value ?? '') == 'pl' ? 'selected' : '' }}>Polski</option>
                            <option value="es" {{ (($settings["site_language"] ?? null)?->value ?? '') == 'es' ? 'selected' : '' }}>Español</option>
                            <option value="fr" {{ (($settings["site_language"] ?? null)?->value ?? '') == 'fr' ? 'selected' : '' }}>Français</option>
                        </select>
                    </div>
                    @endif
                    
                    <div class="form-group">
                        <label for="timezone">{{ __('admin.settings.timezone') }}</label>
                        <select id="timezone" name="timezone">
                            @foreach(['UTC', 'Europe/Warsaw', 'Europe/London', 'America/New_York', 'America/Los_Angeles', 'Asia/Tokyo'] as $tz)
                                <option value="{{ $tz }}" {{ (($settings["timezone"] ?? null)?->value ?? 'UTC') == $tz ? 'selected' : '' }}>{{ $tz }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Contact Tab --}}
        <div class="tab-pane" id="contact">
            <div class="form-section">
                <h3>{{ __('admin.settings.contact_info') }}</h3>
                <p class="section-desc">{{ __('admin.settings.contact_desc') }}</p>
                
                <div class="form-group">
                    <label for="admin_name">{{ __('admin.settings.admin_name') }}</label>
                    <input type="text" id="admin_name" name="admin_name" 
                           value="{{ ($settings["admin_name"] ?? null)?->value ?? '' }}">
                    <small>{{ __('admin.settings.admin_name_hint') }}</small>
                </div>
                
                <div class="form-group">
                    <label for="contact_email">{{ __('admin.settings.contact_email') }}</label>
                    <input type="email" id="contact_email" name="contact_email" 
                           value="{{ ($settings["contact_email"] ?? null)?->value ?? '' }}">
                </div>
                
                <div class="form-group">
                    <label for="contact_phone">{{ __('admin.settings.contact_phone') }}</label>
                    <input type="text" id="contact_phone" name="contact_phone" 
                           value="{{ ($settings["contact_phone"] ?? null)?->value ?? '' }}">
                </div>
                
                <div class="form-group">
                    <label for="contact_address">{{ __('admin.settings.contact_address') }}</label>
                    <textarea id="contact_address" name="contact_address" rows="3">{{ ($settings["contact_address"] ?? null)?->value ?? '' }}</textarea>
                </div>
            </div>
        </div>
        
        {{-- Social Tab --}}
        <div class="tab-pane" id="social">
            <div class="form-section">
                <h3>{{ __('admin.settings.social_links') }}</h3>
                <p class="section-desc">{{ __('admin.settings.social_desc') }}</p>
                
                <div class="social-inputs">
                    <div class="form-group social-group">
                        <label for="social_facebook"><i class="fab fa-facebook"></i> {{ __('admin.settings.facebook') }}</label>
                        <input type="url" id="social_facebook" name="social_facebook" 
                               value="{{ ($settings["social_facebook"] ?? null)?->value ?? '' }}" placeholder="https://facebook.com/...">
                    </div>
                    
                    <div class="form-group social-group">
                        <label for="social_twitter"><i class="fab fa-twitter"></i> {{ __('admin.settings.twitter') }}</label>
                        <input type="url" id="social_twitter" name="social_twitter" 
                               value="{{ ($settings["social_twitter"] ?? null)?->value ?? '' }}" placeholder="https://twitter.com/...">
                    </div>
                    
                    <div class="form-group social-group">
                        <label for="social_instagram"><i class="fab fa-instagram"></i> {{ __('admin.settings.instagram') }}</label>
                        <input type="url" id="social_instagram" name="social_instagram" 
                               value="{{ ($settings["social_instagram"] ?? null)?->value ?? '' }}" placeholder="https://instagram.com/...">
                    </div>
                    
                    <div class="form-group social-group">
                        <label for="social_youtube"><i class="fab fa-youtube"></i> {{ __('admin.settings.youtube') }}</label>
                        <input type="url" id="social_youtube" name="social_youtube" 
                               value="{{ ($settings["social_youtube"] ?? null)?->value ?? '' }}" placeholder="https://youtube.com/...">
                    </div>
                    
                    <div class="form-group social-group">
                        <label for="social_discord"><i class="fab fa-discord"></i> {{ __('admin.settings.discord') }}</label>
                        <input type="url" id="social_discord" name="social_discord" 
                               value="{{ ($settings["social_discord"] ?? null)?->value ?? '' }}" placeholder="https://discord.gg/...">
                    </div>
                    
                    <div class="form-group social-group">
                        <label for="social_tiktok"><i class="fab fa-tiktok"></i> {{ __('admin.settings.tiktok') }}</label>
                        <input type="url" id="social_tiktok" name="social_tiktok" 
                               value="{{ ($settings["social_tiktok"] ?? null)?->value ?? '' }}" placeholder="https://tiktok.com/@...">
                    </div>
                </div>
            </div>
        </div>
        
        {{-- SEO Tab --}}
        <div class="tab-pane" id="seo">
            <div class="form-section">
                <h3>{{ __('admin.settings.seo_settings') }}</h3>
                
                <div class="form-group">
                    <label for="seo_meta_title">{{ __('admin.settings.meta_title') }}</label>
                    <input type="text" id="seo_meta_title" name="seo_meta_title" 
                           value="{{ ($settings["seo_meta_title"] ?? null)?->value ?? '' }}">
                    <small>{{ __('admin.settings.meta_title_hint') }}</small>
                </div>
                
                <div class="form-group">
                    <label for="seo_meta_description">{{ __('admin.settings.meta_description') }}</label>
                    <textarea id="seo_meta_description" name="seo_meta_description" rows="3">{{ ($settings["seo_meta_description"] ?? null)?->value ?? '' }}</textarea>
                    <small>{{ __('admin.settings.meta_desc_hint') }}</small>
                </div>
                
                <div class="form-group">
                    <label for="seo_keywords">{{ __('admin.settings.keywords') }}</label>
                    <input type="text" id="seo_keywords" name="seo_keywords" 
                           value="{{ ($settings["seo_keywords"] ?? null)?->value ?? '' }}">
                    <small>{{ __('admin.settings.keywords_hint') }}</small>
                </div>
                
                <div class="form-group">
                    <label for="google_analytics">{{ __('admin.settings.google_analytics') }}</label>
                    <input type="text" id="google_analytics" name="google_analytics" 
                           value="{{ ($settings["google_analytics"] ?? null)?->value ?? '' }}" placeholder="G-XXXXXXXXXX">
                </div>
            </div>
        </div>
        
        {{-- Appearance Tab --}}
        <div class="tab-pane" id="appearance">
            <div class="form-section">
                <h3>{{ __('admin.settings.appearance_settings') }}</h3>
                
                <div class="logo-upload-section">
                    <h4>{{ __('admin.settings.logo') }}</h4>
                    
                    <div class="logo-upload-row">
                        <div class="logo-upload">
                            <label>{{ __('admin.settings.logo_light') }}</label>
                            <div class="logo-preview" id="preview-light">
                                @if(($settings["logo_light"] ?? null)?->value)
                                    <img src="{{ asset($settings["logo_light"]?->value) }}" alt="Light Logo">
                                @else
                                    <div class="no-logo"><i class="fas fa-image"></i> {{ __('admin.settings.no_logo') }}</div>
                                @endif
                            </div>
                            <input type="file" id="logo_light" accept="image/*" data-type="light">
                            <button type="button" class="btn-upload" onclick="document.getElementById('logo_light').click()">
                                <i class="fas fa-upload"></i> {{ __('admin.settings.upload_btn') }}
                            </button>
                        </div>
                        
                        <div class="logo-upload">
                            <label>{{ __('admin.settings.logo_dark') }}</label>
                            <div class="logo-preview" id="preview-dark">
                                @if(($settings["logo_dark"] ?? null)?->value)
                                    <img src="{{ asset($settings["logo_dark"]?->value) }}" alt="Dark Logo">
                                @else
                                    <div class="no-logo"><i class="fas fa-image"></i> {{ __('admin.settings.no_logo') }}</div>
                                @endif
                            </div>
                            <input type="file" id="logo_dark" accept="image/*" data-type="dark">
                            <button type="button" class="btn-upload" onclick="document.getElementById('logo_dark').click()">
                                <i class="fas fa-upload"></i> {{ __('admin.settings.upload_btn') }}
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="favicon-section">
                    <h4>{{ __('admin.settings.favicon') }}</h4>
                    <div class="favicon-upload">
                        <div class="favicon-preview" id="preview-favicon">
                            @if(($settings["favicon"] ?? null)?->value)
                                <img src="{{ asset($settings["favicon"]?->value) }}" alt="Favicon">
                            @else
                                <div class="no-favicon"><i class="fas fa-globe"></i></div>
                            @endif
                        </div>
                        <input type="file" id="favicon" accept=".ico,.png,.svg">
                        <button type="button" class="btn-upload" onclick="document.getElementById('favicon').click()">
                            <i class="fas fa-upload"></i> {{ __('admin.settings.upload_favicon') }}
                        </button>
                        <small>{{ __('admin.settings.favicon_hint') }}</small>
                    </div>
                </div>
                
                <div class="hero-section" style="margin-top: 32px;">
                    <h4>{{ __('admin.settings.hero_image') }}</h4>
                    <div class="hero-upload" style="text-align: center;">
                        <div class="hero-preview" id="preview-hero" style="width: 100%; max-width: 600px; height: 200px; border: 2px dashed var(--border-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; margin: 16px auto; overflow: hidden; background: var(--bg-light);">
                            @if(($settings["hero_image"] ?? null)?->value)
                                <img src="{{ asset($settings["hero_image"]?->value) }}" alt="Hero" style="width: 100%; height: 100%; object-fit: cover;">
                            @else
                                <div class="no-hero" style="color: var(--text-muted);"><i class="fas fa-image" style="font-size: 48px;"></i></div>
                            @endif
                        </div>
                        <input type="file" id="hero_image" accept="image/*" style="display: none;">
                        <button type="button" class="btn-upload" onclick="document.getElementById('hero_image').click()">
                            <i class="fas fa-upload"></i> {{ __('admin.settings.upload_hero') }}
                        </button>
                        <small style="display: block; margin-top: 8px;">{{ __('admin.settings.hero_hint') }}</small>
                    </div>
                </div>
                
                <div class="color-section">
                    <h4>{{ __('admin.settings.theme_colors') }}</h4>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="primary_color">{{ __('admin.settings.primary_color') }}</label>
                            <div class="color-picker-wrapper">
                                <input type="color" id="primary_color" name="primary_color" 
                                       value="{{ ($settings["primary_color"] ?? null)?->value ?? '#6366f1' }}">
                                <input type="text" value="{{ ($settings["primary_color"] ?? null)?->value ?? '#6366f1' }}" class="color-input">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="secondary_color">{{ __('admin.settings.secondary_color') }}</label>
                            <div class="color-picker-wrapper">
                                <input type="color" id="secondary_color" name="secondary_color" 
                                       value="{{ ($settings["secondary_color"] ?? null)?->value ?? '#8b5cf6' }}">
                                <input type="text" value="{{ ($settings["secondary_color"] ?? null)?->value ?? '#8b5cf6' }}" class="color-input">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Streaming Tab --}}
        <div class="tab-pane" id="streaming">
            <div class="form-section">
                <h3>{{ __('admin.settings.streaming') }}</h3>
                
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="azuracast_enabled" value="1" 
                               {{ (($settings["azuracast_enabled"] ?? null)?->value ?? '1') == '1' ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        {{ __('admin.settings.azuracast_enabled') }}
                    </label>
                </div>
                
                <div class="form-group">
                    <label for="station_name">{{ __('admin.settings.station_name') }}</label>
                    <input type="text" id="station_name" name="station_name" 
                           value="{{ ($settings["station_name"] ?? null)?->value ?? 'NovaRadio' }}">
                </div>
                
                <div class="form-group">
                    <label for="stream_url">{{ __('admin.settings.stream_url') }}</label>
                    <input type="url" id="stream_url" name="stream_url" 
                           value="{{ ($settings["stream_url"] ?? null)?->value ?? '' }}" 
                           placeholder="https://your-stream.com/radio.mp3">
                    <small>{{ __('admin.settings.stream_url_hint') }}</small>
                </div>
                
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="player_autoplay" value="1" 
                               {{ (($settings["player_autoplay"] ?? null)?->value ?? '0') == '1' ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        {{ __('admin.settings.player_autoplay') }}
                    </label>
                </div>
            </div>
        </div>
        
        {{-- Advanced Tab --}}
        <div class="tab-pane" id="advanced">
            <div class="form-section">
                <h3>{{ __('admin.settings.advanced_settings') }}</h3>
                <p class="section-desc text-warning"><i class="fas fa-exclamation-triangle"></i> {{ __('admin.settings.advanced_warning') }}</p>
                
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="maintenance_mode" value="1" 
                               {{ (($settings["maintenance_mode"] ?? null)?->value ?? '0') == '1' ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        {{ __('admin.settings.maintenance_mode') }}
                    </label>
                    <small>{{ __('admin.settings.maintenance_hint') }}</small>
                </div>
                
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="cache_enabled" value="1" 
                               {{ (($settings["cache_enabled"] ?? null)?->value ?? '1') == '1' ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        {{ __('admin.settings.cache_enabled') }}
                    </label>
                </div>
                
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="debug_mode" value="1" 
                               {{ (($settings["debug_mode"] ?? null)?->value ?? '0') == '1' ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        {{ __('admin.settings.debug_mode') }}
                    </label>
                    <small>{{ __('admin.settings.debug_hint') }}</small>
                </div>
                
                <div class="form-group checkbox-group">
                    <label class="checkbox-label">
                        <input type="checkbox" name="registration_enabled" value="1" 
                               {{ (($settings["registration_enabled"] ?? null)?->value ?? '0') == '1' ? 'checked' : '' }}>
                        <span class="checkmark"></span>
                        {{ __('admin.settings.registration_enabled') }}
                    </label>
                </div>
                
                <div class="cache-actions">
                    <h4>{{ __('admin.settings.cache_management') }}</h4>
                    <div class="action-buttons">
                        <a href="{{ route('admin.settings.clear-cache', 'config') }}" class="btn-action">
                            <i class="fas fa-broom"></i> {{ __('admin.settings.clear_config_cache') }}
                        </a>
                        <a href="{{ route('admin.settings.clear-cache', 'view') }}" class="btn-action">
                            <i class="fas fa-eye-slash"></i> {{ __('admin.settings.clear_view_cache') }}
                        </a>
                        <a href="{{ route('admin.settings.clear-cache', 'all') }}" class="btn-action">
                            <i class="fas fa-trash-alt"></i> {{ __('admin.settings.clear_all_cache') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="form-actions">
        <button type="submit" class="btn-save">
            <i class="fas fa-save"></i> {{ __('admin.settings.save') }}
        </button>
    </div>
</form>

@push('styles')
<style>
.settings-tabs {
    display: flex;
    gap: 4px;
    border-bottom: 1px solid var(--border-color);
    margin-bottom: 24px;
    overflow-x: auto;
    padding-bottom: 1px;
}

.tab-btn {
    padding: 12px 20px;
    border: none;
    background: transparent;
    color: var(--text-muted);
    font-weight: 500;
    cursor: pointer;
    white-space: nowrap;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
}

.tab-btn:hover {
    color: var(--text-color);
    background: var(--bg-hover);
}

.tab-btn.active {
    color: var(--primary-color);
    border-bottom-color: var(--primary-color);
}

.tab-pane {
    display: none;
}

.tab-pane.active {
    display: block;
}

.form-section {
    background: white;
    padding: 24px;
    border-radius: 8px;
    margin-bottom: 24px;
}

.form-section h3 {
    margin-bottom: 8px;
    font-size: 18px;
}

.section-desc {
    color: var(--text-muted);
    font-size: 14px;
    margin-bottom: 20px;
}

.social-inputs {
    display: grid;
    gap: 16px;
}

.social-group label {
    display: flex;
    align-items: center;
    gap: 8px;
}

.social-group label i {
    width: 20px;
    color: var(--primary-color);
}

.logo-upload-row {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 24px;
    margin-top: 16px;
}

.logo-upload, .favicon-upload {
    text-align: center;
}

.logo-preview, .favicon-preview {
    width: 200px;
    height: 80px;
    border: 2px dashed var(--border-color);
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 16px auto;
    overflow: hidden;
    background: var(--bg-light);
}

.favicon-preview {
    width: 80px;
    height: 80px;
}

.logo-preview img, .favicon-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.no-logo, .no-favicon {
    color: var(--text-muted);
    font-size: 32px;
}

input[type="file"] {
    display: none;
}

.btn-upload {
    padding: 8px 16px;
    background: var(--bg-light);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
}

.btn-upload:hover {
    background: var(--bg-hover);
}

.color-picker-wrapper {
    display: flex;
    align-items: center;
    gap: 12px;
}

input[type="color"] {
    width: 50px;
    height: 40px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}

.color-input {
    width: 120px;
}

.checkbox-group {
    margin-bottom: 16px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 12px;
    cursor: pointer;
    font-weight: 500;
}

.checkbox-label input {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid var(--border-color);
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.checkbox-label input:checked + .checkmark {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.checkbox-label input:checked + .checkmark::after {
    content: '✓';
    color: white;
    font-size: 12px;
}

.text-warning {
    color: #f59e0b;
}

.cache-actions {
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--border-color);
}

.action-buttons {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    margin-top: 16px;
}

.btn-action {
    padding: 10px 16px;
    background: var(--bg-light);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    text-decoration: none;
    color: var(--text-color);
    font-size: 14px;
}

.btn-action:hover {
    background: var(--bg-hover);
}

.form-actions {
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid var(--border-color);
}

.btn-save {
    padding: 14px 28px;
    background: var(--primary-color);
    color: white;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 8px;
}

.btn-save:hover {
    background: var(--primary-dark);
}
</style>
@endpush

@push('scripts')
<script>
// Tab switching
document.querySelectorAll('.tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-pane').forEach(p => p.classList.remove('active'));
        
        btn.classList.add('active');
        document.getElementById(btn.dataset.tab).classList.add('active');
    });
});

// Logo upload
['light', 'dark'].forEach(type => {
    const input = document.getElementById('logo_' + type);
    if (input) {
        input.addEventListener('change', async (e) => {
            const file = e.target.files[0];
            if (!file) return;
            
            const formData = new FormData();
            formData.append('logo', file);
            formData.append('type', type);
            
            try {
                const response = await fetch('{{ route("admin.settings.upload-logo") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                });
                
                const data = await response.json();
                if (data.success) {
                    const preview = document.getElementById('preview-' + type);
                    preview.innerHTML = '<img src="' + data.url + '" alt="Logo">';
                }
            } catch (err) {
                console.error('Upload failed:', err);
            }
        });
    }
});

// Favicon upload
document.getElementById('favicon')?.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('favicon', file);
    
    try {
        const response = await fetch('{{ route("admin.settings.upload-favicon") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
        
        const data = await response.json();
        if (data.success) {
            document.getElementById('preview-favicon').innerHTML = '<img src="' + data.url + '" alt="Favicon">';
        }
    } catch (err) {
        console.error('Upload failed:', err);
    }
});

// Hero image upload
document.getElementById('hero_image')?.addEventListener('change', async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    
    const formData = new FormData();
    formData.append('hero_image', file);
    
    try {
        const response = await fetch('{{ route("admin.settings.upload-hero") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        });
        
        const data = await response.json();
        if (data.success) {
            document.getElementById('preview-hero').innerHTML = '<img src="' + data.url + '" alt="Hero" style="width: 100%; height: 100%; object-fit: cover;">';
        }
    } catch (err) {
        console.error('Upload failed:', err);
    }
});

// Color picker sync
document.querySelectorAll('input[type="color"]').forEach(picker => {
    picker.addEventListener('input', (e) => {
        e.target.nextElementSibling.value = e.target.value;
    });
});
</script>
@endpush
@endsection
