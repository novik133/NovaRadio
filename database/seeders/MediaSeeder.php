<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;
use Illuminate\Support\Facades\File;

class MediaSeeder extends Seeder
{
    public function run(): void
    {
        // Register existing images in media library
        $imagesToRegister = [
            // Hero images
            ['path' => 'images/hero/hero-main.jpg', 'folder' => 'hero', 'alt' => 'Hero Main Image'],
            
            // Team photos
            ['path' => 'images/team/alex-chen.jpg', 'folder' => 'team', 'alt' => 'Alex Chen'],
            ['path' => 'images/team/sarah-mitchell.jpg', 'folder' => 'team', 'alt' => 'Sarah Mitchell'],
            ['path' => 'images/team/marcus-johnson.jpg', 'folder' => 'team', 'alt' => 'Marcus Johnson'],
            ['path' => 'images/team/emma-rodriguez.jpg', 'folder' => 'team', 'alt' => 'Emma Rodriguez'],
            
            // Article images
            ['path' => 'images/articles/welcome.jpg', 'folder' => 'articles', 'alt' => 'Welcome to NovaRadio'],
            ['path' => 'images/articles/electronic-music.jpg', 'folder' => 'articles', 'alt' => 'Electronic Music'],
            
            // Event images
            ['path' => 'images/events/jazz-night.jpg', 'folder' => 'events', 'alt' => 'Jazz Night Live'],
            ['path' => 'images/events/summer-festival.jpg', 'folder' => 'events', 'alt' => 'Summer Music Festival'],
            
            // Page images
            ['path' => 'images/pages/about-studio.jpg', 'folder' => 'pages', 'alt' => 'Radio Studio'],
            ['path' => 'images/pages/about-equipment.jpg', 'folder' => 'pages', 'alt' => 'Studio Equipment'],
            ['path' => 'images/pages/about-dj.jpg', 'folder' => 'pages', 'alt' => 'DJ at Work'],
            ['path' => 'images/pages/contact.jpg', 'folder' => 'pages', 'alt' => 'Contact Us'],
            ['path' => 'images/pages/privacy.jpg', 'folder' => 'pages', 'alt' => 'Privacy Policy'],
            ['path' => 'images/pages/terms.jpg', 'folder' => 'pages', 'alt' => 'Terms of Service'],
            ['path' => 'images/pages/cookies.jpg', 'folder' => 'pages', 'alt' => 'Cookie Policy'],
        ];

        foreach ($imagesToRegister as $imageData) {
            $this->registerImage($imageData['path'], $imageData['folder'], $imageData['alt']);
        }
    }

    private function registerImage(string $path, string $folder, string $altText): ?Media
    {
        try {
            $fullPath = public_path($path);
            
            // Check if file exists
            if (!File::exists($fullPath)) {
                \Log::warning("Image not found: {$fullPath}");
                return null;
            }

            // Get filename from path
            $filename = basename($path);
            
            // Check if already registered
            $existing = Media::where('path', $path)->first();
            if ($existing) {
                return $existing;
            }

            // Get file info
            $fileSize = File::size($fullPath);
            $mimeType = File::mimeType($fullPath);

            // Create media record
            $media = Media::create([
                'filename' => $filename,
                'original_filename' => $filename,
                'path' => $path,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'folder' => $folder,
                'alt_text' => $altText,
            ]);

            return $media;
        } catch (\Exception $e) {
            \Log::error('Failed to register image: ' . $path . ' - ' . $e->getMessage());
            return null;
        }
    }
}
