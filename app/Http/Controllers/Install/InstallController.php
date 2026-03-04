<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InstallController extends Controller
{
    public function index()
    {
        if (File::exists(base_path('.env')) && env('APP_INSTALLED', false)) {
            return redirect('/');
        }

        $requirements = $this->checkRequirements();

        return view('install.index', compact('requirements'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
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
        ]);

        try {
            // Test database connection
            config(['database.connections.mysql.host' => $validated['db_host']]);
            config(['database.connections.mysql.port' => $validated['db_port']]);
            config(['database.connections.mysql.database' => $validated['db_database']]);
            config(['database.connections.mysql.username' => $validated['db_username']]);
            config(['database.connections.mysql.password' => $validated['db_password']]);

            DB::purge('mysql');
            DB::connection('mysql')->getPdo();

            // Create .env file
            $envContent = $this->generateEnvFile($validated);
            File::put(base_path('.env'), $envContent);

            // Run migrations and seeders
            Artisan::call('migrate', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);

            // Update admin user
            DB::table('users')->where('id', 1)->update([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => bcrypt($validated['admin_password']),
            ]);

            // Clear cache
            Artisan::call('config:clear');
            Artisan::call('cache:clear');

            return redirect('/admin/login')->with('success', 'Installation complete! Please login with your admin credentials.');
        } catch (\Exception $e) {
            return back()->with('error', 'Installation failed: ' . $e->getMessage());
        }
    }

    private function checkRequirements(): array
    {
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
        ];
    }

    private function generateEnvFile(array $data): string
    {
        $appKey = 'base64:' . base64_encode(random_bytes(32));

        return <<<ENV
APP_NAME=NovaRadio
APP_ENV=production
APP_KEY={$appKey}
APP_DEBUG=false
APP_URL=http://localhost
APP_INSTALLED=true

LOG_CHANNEL=stack
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST={$data['db_host']}
DB_PORT={$data['db_port']}
DB_DATABASE={$data['db_database']}
DB_USERNAME={$data['db_username']}
DB_PASSWORD={$data['db_password']}

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

AZURACAST_BASE_URL={$data['azuracast_url']}
AZURACAST_API_KEY={$data['azuracast_api_key']}
AZURACAST_STATION_ID=1

GITHUB_REPO=novik133/NovaRadia

VITE_APP_NAME=NovaRadio
ENV;
    }
}
