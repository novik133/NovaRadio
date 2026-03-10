<?php

return [
    'title' => 'NovaRadio Installer',
    'steps' => [
        'license' => 'License',
        'requirements' => 'Requirements',
        'database' => 'Database',
        'admin' => 'Admin',
        'streaming' => 'Streaming',
    ],

    // License step
    'license_title' => 'License Agreement',
    'license_subtitle' => 'Please read and accept the license agreement to continue.',
    'accept_license' => 'I accept the license agreement',
    'must_accept' => 'You must accept the license agreement to continue.',

    // Requirements step
    'requirements_title' => 'System Requirements',
    'requirements_subtitle' => 'Ensure your server meets all requirements.',
    'requirements_met' => 'All requirements met!',
    'requirements_not_met' => 'Some requirements are not met. Please fix them before continuing.',
    'installed' => 'Installed',
    'missing' => 'Missing',

    // Database step
    'database_title' => 'Database Configuration',
    'database_subtitle' => 'Enter your database connection details.',
    'db_host' => 'Database Host',
    'db_port' => 'Port',
    'db_name' => 'Database Name',
    'db_username' => 'Username',
    'db_password' => 'Password',

    // Admin step
    'admin_title' => 'Administrator Account',
    'admin_subtitle' => 'Create the initial administrator account.',
    'admin_name' => 'Full Name',
    'admin_email' => 'Email Address',
    'admin_password' => 'Password',
    'admin_password_confirm' => 'Confirm Password',
    'password_min' => 'Minimum 8 characters',
    'passwords_must_match' => 'Passwords must match',

    // Streaming step
    'streaming_title' => 'Streaming Configuration',
    'streaming_subtitle' => 'Configure your AzuraCast integration (optional).',
    'azuracast_url' => 'AzuraCast URL',
    'azuracast_url_placeholder' => 'https://your-azuracast-instance.com',
    'azuracast_api_key' => 'AzuraCast API Key',
    'stream_url' => 'Direct Stream URL (fallback)',
    'stream_url_placeholder' => 'https://your-stream.com/radio.mp3',
    'stream_url_hint' => 'Used if AzuraCast is not available',
    'skip_streaming' => 'You can configure streaming later in admin settings.',

    // Buttons
    'next' => 'Next',
    'previous' => 'Previous',
    'install' => 'Install NovaRadio',
    'installing' => 'Installing...',

    // Messages
    'install_success' => 'Installation complete! Please login with your admin credentials.',
    'install_failed' => 'Installation failed: :error',
];
