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
    private string $currentVersion = '2.0.3';
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
                'download_url' => $release['tarball_url'] ?? '',
                'html_url' => $release['html_url'] ?? '',
            ]);

            return [
                'success' => true,
                'has_update' => $hasUpdate,
                'current_version' => $this->currentVersion,
                'latest_version' => $latestVersion,
                'changelog' => $release['body'] ?? 'No changelog available',
                'download_url' => $release['tarball_url'] ?? '',
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
        $backupPath = null;
        $this->logUpdate('install', UpdateLog::STATUS_PENDING, $version, 'Starting installation...');

        try {
            $tempDir = storage_path('app/updates');
            File::ensureDirectoryExists($tempDir);

            // Download update
            $this->logUpdate('download', UpdateLog::STATUS_PENDING, $version, 'Downloading update...');
            $archiveFile = $tempDir . '/update.tar.gz';

            $response = Http::timeout(120)->get($downloadUrl);
            if (!$response->successful()) {
                throw new \Exception('Failed to download update package');
            }

            File::put($archiveFile, $response->body());
            $this->logUpdate('download', UpdateLog::STATUS_SUCCESS, $version, 'Download complete');

            // Extract
            $extractPath = $tempDir . '/extracted';
            File::ensureDirectoryExists($extractPath);

            // Extract tar.gz
            $command = "tar -xzf " . escapeshellarg($archiveFile) . " -C " . escapeshellarg($extractPath);
            exec($command, $output, $returnCode);
            
            if ($returnCode !== 0) {
                throw new \Exception('Failed to extract update package');
            }

            // Find actual source directory (GitHub extracts to subdirectory)
            $sourcePath = $extractPath;
            $dirs = File::directories($extractPath);
            if (count($dirs) === 1 && is_dir($dirs[0])) {
                $sourcePath = $dirs[0];
            }

            // Validate PHP syntax in extracted files
            $this->logUpdate('validate', UpdateLog::STATUS_PENDING, $version, 'Validating PHP syntax...');
            $validationErrors = $this->validatePhpSyntax($sourcePath);
            
            if (!empty($validationErrors)) {
                throw new \Exception('PHP syntax validation failed: ' . implode(', ', $validationErrors));
            }
            
            $this->logUpdate('validate', UpdateLog::STATUS_SUCCESS, $version, 'Syntax validation passed');

            // Backup current files
            $backupPath = storage_path('app/backups/' . date('Y-m-d-His'));
            File::ensureDirectoryExists($backupPath);
            $this->logUpdate('backup', UpdateLog::STATUS_PENDING, $version, 'Creating backup...');
            $this->backupFiles($backupPath);
            $this->logUpdate('backup', UpdateLog::STATUS_SUCCESS, $version, 'Backup created');

            // Install new files
            $this->logUpdate('install', UpdateLog::STATUS_PENDING, $version, 'Installing files...');
            $this->installFiles($sourcePath);

            // Run migrations
            $this->logUpdate('migrate', UpdateLog::STATUS_PENDING, $version, 'Running migrations...');
            Artisan::call('migrate', ['--force' => true]);
            $this->logUpdate('migrate', UpdateLog::STATUS_SUCCESS, $version, 'Migrations complete');

            // Clear caches
            $this->logUpdate('cache', UpdateLog::STATUS_PENDING, $version, 'Clearing caches...');
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            $this->logUpdate('cache', UpdateLog::STATUS_SUCCESS, $version, 'Caches cleared');

            // Cleanup
            File::deleteDirectory($extractPath);
            File::delete($archiveFile);

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

            // Automatic rollback if backup exists
            if ($backupPath && File::exists($backupPath)) {
                try {
                    $this->logUpdate('rollback', UpdateLog::STATUS_PENDING, $version, 'Rolling back to previous version...');
                    $this->restoreBackup($backupPath);
                    
                    // Clear caches after rollback
                    Artisan::call('cache:clear');
                    Artisan::call('config:clear');
                    Artisan::call('view:clear');
                    Artisan::call('route:clear');
                    
                    $this->logUpdate('rollback', UpdateLog::STATUS_SUCCESS, $version, 'Rollback successful');
                    
                    return [
                        'success' => false,
                        'message' => 'Update failed and was rolled back: ' . $e->getMessage(),
                        'rolled_back' => true,
                    ];
                } catch (\Exception $rollbackError) {
                    Log::error('Rollback failed', ['error' => $rollbackError->getMessage()]);
                    $this->logUpdate('rollback', UpdateLog::STATUS_ERROR, $version, 'Rollback failed: ' . $rollbackError->getMessage());
                }
            }

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'rolled_back' => false,
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

    private function validatePhpSyntax(string $path): array
    {
        $errors = [];
        $phpFiles = File::allFiles($path);

        foreach ($phpFiles as $file) {
            if ($file->getExtension() === 'php') {
                $output = [];
                $returnCode = 0;
                exec('php -l ' . escapeshellarg($file->getPathname()) . ' 2>&1', $output, $returnCode);
                
                if ($returnCode !== 0) {
                    $errors[] = $file->getRelativePath() . '/' . $file->getFilename() . ': ' . implode(' ', $output);
                }
            }
        }

        return $errors;
    }

    private function restoreBackup(string $backupPath): void
    {
        $dirsToRestore = ['app', 'config', 'database', 'resources', 'routes'];
        
        foreach ($dirsToRestore as $dir) {
            $source = $backupPath . '/' . $dir;
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
