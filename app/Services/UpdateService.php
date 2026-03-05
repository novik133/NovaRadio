<?php

namespace App\Services;

use App\Models\UpdateLog;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class UpdateService
{
    private string $githubRepo;
    private string $currentVersion = '2.0.1';
    private ?string $githubToken;

    public function __construct()
    {
        $this->githubRepo = config('services.github.repo', env('GITHUB_REPO', 'novik133/NovaRadio'));
        $this->githubToken = config('services.github.token', env('GITHUB_TOKEN', ''));
    }

    public function getCurrentVersion(): string
    {
        return $this->currentVersion;
    }

    public function checkForUpdates(): array
    {
        try {
            $headers = [
                'Accept' => 'application/vnd.github.v3+json',
            ];

            if ($this->githubToken) {
                $headers['Authorization'] = "token {$this->githubToken}";
            }

            $response = Http::withHeaders($headers)
                ->timeout(30)
                ->get("https://api.github.com/repos/{$this->githubRepo}/releases/latest");

            if (!$response->successful()) {
                $this->logUpdate('check', UpdateLog::STATUS_ERROR, $this->currentVersion, 'Failed to fetch release from GitHub');
                return [
                    'success' => false,
                    'message' => 'Failed to check for updates',
                ];
            }

            $release = $response->json();
            $latestVersion = ltrim($release['tag_name'] ?? '0.0.0', 'v');
            $hasUpdate = version_compare($latestVersion, $this->currentVersion, '>');

            $status = $hasUpdate ? UpdateLog::STATUS_AVAILABLE : UpdateLog::STATUS_SUCCESS;
            $message = $hasUpdate ? "Update available: v{$latestVersion}" : 'System is up to date';

            $this->logUpdate('check', $status, $latestVersion, $message, [
                'current_version' => $this->currentVersion,
                'latest_version' => $latestVersion,
                'has_update' => $hasUpdate,
                'changelog' => $release['body'] ?? '',
                'download_url' => $release['zipball_url'] ?? '',
                'html_url' => $release['html_url'] ?? '',
            ]);

            return [
                'success' => true,
                'has_update' => $hasUpdate,
                'current_version' => $this->currentVersion,
                'latest_version' => $latestVersion,
                'changelog' => $release['body'] ?? 'No changelog available',
                'download_url' => $release['zipball_url'] ?? '',
                'html_url' => $release['html_url'] ?? '',
                'published_at' => $release['published_at'] ?? null,
            ];
        } catch (\Exception $e) {
            Log::error('Update check failed', ['error' => $e->getMessage()]);
            $this->logUpdate('check', UpdateLog::STATUS_ERROR, $this->currentVersion, $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function installUpdate(string $version, string $downloadUrl): array
    {
        $this->logUpdate('install', UpdateLog::STATUS_PENDING, $version, 'Starting installation...');

        try {
            $tempDir = storage_path('app/updates');
            File::ensureDirectoryExists($tempDir);

            // Download update
            $this->logUpdate('download', UpdateLog::STATUS_PENDING, $version, 'Downloading update...');
            $zipFile = $tempDir . '/update.zip';

            $response = Http::timeout(120)->get($downloadUrl);
            if (!$response->successful()) {
                throw new \Exception('Failed to download update package');
            }

            File::put($zipFile, $response->body());
            $this->logUpdate('download', UpdateLog::STATUS_SUCCESS, $version, 'Download complete');

            // Extract
            $extractPath = $tempDir . '/extracted';
            File::ensureDirectoryExists($extractPath);

            $zip = new \ZipArchive();
            if ($zip->open($zipFile) !== true) {
                throw new \Exception('Failed to open update package');
            }
            $zip->extractTo($extractPath);
            $zip->close();

            // Find actual source directory (GitHub extracts to subdirectory)
            $sourcePath = $extractPath;
            $dirs = File::directories($extractPath);
            if (count($dirs) === 1 && is_dir($dirs[0])) {
                $sourcePath = $dirs[0];
            }

            // Backup current files
            $backupPath = storage_path('app/backups/' . date('Y-m-d-His'));
            File::ensureDirectoryExists($backupPath);
            $this->backupFiles($backupPath);

            // Install new files
            $this->installFiles($sourcePath);

            // Run migrations
            Artisan::call('migrate', ['--force' => true]);

            // Clear caches
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            // Cleanup
            File::deleteDirectory($extractPath);
            File::delete($zipFile);

            $this->logUpdate('install', UpdateLog::STATUS_SUCCESS, $version, 'Installation complete', [
                'backup_path' => $backupPath,
            ]);

            return [
                'success' => true,
                'message' => "Successfully updated to version {$version}",
                'backup_path' => $backupPath,
            ];
        } catch (\Exception $e) {
            Log::error('Update installation failed', ['error' => $e->getMessage()]);
            $this->logUpdate('install', UpdateLog::STATUS_ERROR, $version, $e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    private function backupFiles(string $backupPath): void
    {
        $dirsToBackup = ['app', 'config', 'database', 'resources', 'routes'];
        foreach ($dirsToBackup as $dir) {
            $source = base_path($dir);
            if (File::exists($source)) {
                File::copyDirectory($source, $backupPath . '/' . $dir);
            }
        }
    }

    private function installFiles(string $sourcePath): void
    {
        $updateDirs = ['app', 'config', 'database', 'resources', 'routes'];

        foreach ($updateDirs as $dir) {
            $source = $sourcePath . '/' . $dir;
            $dest = base_path($dir);

            if (File::exists($source)) {
                if (File::exists($dest)) {
                    File::deleteDirectory($dest);
                }
                File::copyDirectory($source, $dest);
            }
        }
    }

    private function logUpdate(string $type, string $status, ?string $version, string $message, array $metadata = []): void
    {
        UpdateLog::create([
            'version' => $version,
            'type' => $type,
            'status' => $status,
            'message' => $message,
            'metadata' => $metadata,
        ]);
    }

    public function getUpdateHistory(int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return UpdateLog::latest()->limit($limit)->get();
    }
}
