<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class InstallController extends Controller
{
    public function index()
    {
        if (File::exists(base_path('.env')) && env('APP_INSTALLED', false)) {
            return redirect('/');
        }

        $requirements = $this->checkRequirements();
        $allMet = collect($requirements)->every(fn($r) => $r['met']);
        $license = File::exists(base_path('LICENSE'))
            ? File::get(base_path('LICENSE'))
            : 'License file not found.';

        return view('install.index', compact('requirements', 'allMet', 'license'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'license_accepted' => 'required|accepted',
            'db_host' => 'required|string',
            'db_port' => 'required|integer',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255',
            'admin_password' => 'required|string|min:8|confirmed',
            'azuracast_url' => 'nullable|url',
            'azuracast_api_key' => 'nullable|string',
            'stream_url' => 'nullable|url',
        ]);

        try {
            // Configure database connection at runtime
            config([
                'database.connections.mysql.host' => $validated['db_host'],
                'database.connections.mysql.port' => $validated['db_port'],
                'database.connections.mysql.database' => $validated['db_database'],
                'database.connections.mysql.username' => $validated['db_username'],
                'database.connections.mysql.password' => $validated['db_password'] ?? '',
            ]);

            // Purge and test connection
            DB::purge('mysql');
            DB::reconnect('mysql');
            DB::connection('mysql')->getPdo();

            // Create .env file
            $envContent = $this->generateEnvFile($validated);
            File::put(base_path('.env'), $envContent);

            // Clear cached config so artisan commands use the new .env values
            if (function_exists('opcache_reset')) {
                opcache_reset();
            }
            Artisan::call('config:clear');

            // Re-apply runtime config after clearing (artisan may have reloaded)
            config([
                'database.connections.mysql.host' => $validated['db_host'],
                'database.connections.mysql.port' => $validated['db_port'],
                'database.connections.mysql.database' => $validated['db_database'],
                'database.connections.mysql.username' => $validated['db_username'],
                'database.connections.mysql.password' => $validated['db_password'] ?? '',
            ]);
            DB::purge('mysql');
            DB::reconnect('mysql');

            // Run migrations
            Artisan::call('migrate', [
                '--force' => true,
                '--path' => 'database/migrations',
            ]);

            // Verify critical tables exist
            $requiredTables = [
                'users', 'sessions', 'cache', 'cache_locks',
                'pages', 'team_members', 'settings', 'updates',
                'schedule_shows', 'categories', 'tags', 'articles',
                'article_tag', 'dj_profiles', 'events', 'event_dj', 'media',
            ];
            $missingTables = [];
            foreach ($requiredTables as $table) {
                if (!Schema::hasTable($table)) {
                    $missingTables[] = $table;
                }
            }
            if (!empty($missingTables)) {
                throw new \Exception('Migration incomplete. Missing tables: ' . implode(', ', $missingTables));
            }

            // Run seeders
            Artisan::call('db:seed', ['--force' => true]);

            // Update admin user
            DB::table('users')->where('id', 1)->update([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => bcrypt($validated['admin_password']),
            ]);

            // Save stream_url setting if provided
            if (!empty($validated['stream_url'])) {
                DB::table('settings')->updateOrInsert(
                    ['key' => 'stream_url'],
                    [
                        'value' => $validated['stream_url'],
                        'type' => 'string',
                        'group' => 'streaming',
                        'label' => 'Direct Stream URL',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }

            // Create storage symlink
            Artisan::call('storage:link', ['--force' => true]);

            // Final cache clear
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return redirect('/admin/login')->with('success', 'Installation complete! Please login with your admin credentials.');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Installation failed: ' . $e->getMessage());
        }
    }

    private function checkRequirements(): array
    {
        $writableDirs = [
            storage_path(),
            storage_path('framework'),
            storage_path('logs'),
            base_path('bootstrap/cache'),
            public_path(),
        ];
        $allWritable = true;
        foreach ($writableDirs as $dir) {
            if (!is_writable($dir)) {
                $allWritable = false;
                break;
            }
        }

        return [
            'php' => [
                'name' => 'PHP Version >= 8.2',
                'met' => version_compare(PHP_VERSION, '8.2.0', '>='),
                'current' => PHP_VERSION,
            ],
            'pdo' => [
                'name' => 'PDO Extension',
                'met' => extension_loaded('pdo'),
                'current' => extension_loaded('pdo') ? 'Installed' : 'Missing',
            ],
            'pdo_mysql' => [
                'name' => 'PDO MySQL Driver',
                'met' => extension_loaded('pdo_mysql'),
                'current' => extension_loaded('pdo_mysql') ? 'Installed' : 'Missing',
            ],
            'mbstring' => [
                'name' => 'Mbstring Extension',
                'met' => extension_loaded('mbstring'),
                'current' => extension_loaded('mbstring') ? 'Installed' : 'Missing',
            ],
            'openssl' => [
                'name' => 'OpenSSL Extension',
                'met' => extension_loaded('openssl'),
                'current' => extension_loaded('openssl') ? 'Installed' : 'Missing',
            ],
            'tokenizer' => [
                'name' => 'Tokenizer Extension',
                'met' => extension_loaded('tokenizer'),
                'current' => extension_loaded('tokenizer') ? 'Installed' : 'Missing',
            ],
            'xml' => [
                'name' => 'XML Extension',
                'met' => extension_loaded('xml'),
                'current' => extension_loaded('xml') ? 'Installed' : 'Missing',
            ],
            'curl' => [
                'name' => 'cURL Extension',
                'met' => extension_loaded('curl'),
                'current' => extension_loaded('curl') ? 'Installed' : 'Missing',
            ],
            'zip' => [
                'name' => 'Zip Extension',
                'met' => extension_loaded('zip'),
                'current' => extension_loaded('zip') ? 'Installed' : 'Missing',
            ],
            'writable' => [
                'name' => 'Storage/Cache Writable',
                'met' => $allWritable,
                'current' => $allWritable ? 'Writable' : 'Not writable',
            ],
        ];
    }

    private function generateEnvFile(array $data): string
    {
        $appKey = 'base64:' . base64_encode(random_bytes(32));
        $appUrl = url('/');
        $dbPassword = $data['db_password'] ?? '';
        $azuracastUrl = $data['azuracast_url'] ?? '';
        $azuracastKey = $data['azuracast_api_key'] ?? '';

        return <<<ENV
APP_NAME=NovaRadio
APP_ENV=production
APP_KEY={$appKey}
APP_DEBUG=false
APP_URL={$appUrl}
APP_INSTALLED=true

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST={$data['db_host']}
DB_PORT={$data['db_port']}
DB_DATABASE={$data['db_database']}
DB_USERNAME={$data['db_username']}
DB_PASSWORD={$dbPassword}

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync

CACHE_STORE=file

AZURACAST_BASE_URL={$azuracastUrl}
AZURACAST_API_KEY={$azuracastKey}
AZURACAST_STATION_ID=1

GITHUB_REPO=novik133/NovaRadio

VITE_APP_NAME=NovaRadio
ENV;
    }
}
