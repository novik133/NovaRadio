<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;
use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@novikradio.com')->first();
        if (!$admin) {
            return;
        }

        // Register existing images in media library
        $imagesToRegister = [
            // Hero images
            ['path' => 'public/images/hero/hero-main.jpg', 'folder' => 'hero', 'alt' => 'Hero Main Image'],
            
            // Team photos
            ['path' => 'public/images/team/alex-chen.jpg', 'folder' => 'team', 'alt' => 'Alex Chen'],
            ['path' => 'public/images/team/sarah-mitchell.jpg', 'folder' => 'team', 'alt' => 'Sarah Mitchell'],
            ['path' => 'public/images/team/marcus-johnson.jpg', 'folder' => 'team', 'alt' => 'Marcus Johnson'],
            ['path' => 'public/images/team/emma-rodriguez.jpg', 'folder' => 'team', 'alt' => 'Emma Rodriguez'],
            
            // Article images
            ['path' => 'public/images/articles/electronic-music.jpg', 'folder' => 'articles', 'alt' => 'Electronic Music'],
            
            // Event images
            ['path' => 'public/images/events/jazz-night.jpg', 'folder' => 'events', 'alt' => 'Jazz Night Live'],
            ['path' => 'public/images/events/summer-festival.jpg', 'folder' => 'events', 'alt' => 'Summer Music Festival'],
        ];

        foreach ($imagesToRegister as $imageData) {
            $this->registerImage($imageData['path'], $imageData['folder'], $imageData['alt'], $admin->id);
        }
    }

    private function registerImage(string $storagePath, string $folder, string $altText, int $uploadedBy): ?Media
    {
        try {
            // Check if file exists
            if (!Storage::exists($storagePath)) {
                return null;
            }

            // Get filename from path
            $filename = basename($storagePath);
            
            // Check if already registered
            $existing = Media::where('filename', $filename)->where('folder', $folder)->first();
            if ($existing) {
                return $existing;
            }

            // Get file info
            $fileSize = Storage::size($storagePath);
            $mimeType = Storage::mimeType($storagePath);
            
            // Remove 'public/' prefix for path storage
            $relativePath = str_replace('public/', '', $storagePath);

            // Create media record
            $media = Media::create([
                'filename' => $filename,
                'original_filename' => $filename,
                'path' => $relativePath,
                'mime_type' => $mimeType,
                'size' => $fileSize,
                'folder' => $folder,
                'alt_text' => $altText,
                'uploaded_by' => $uploadedBy,
            ]);

            return $media;
        } catch (\Exception $e) {
            \Log::error('Failed to register image: ' . $e->getMessage());
            return null;
        }
    }
}
