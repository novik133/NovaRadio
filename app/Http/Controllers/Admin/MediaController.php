<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    private $mediaPath;
    
    public function __construct()
    {
        $this->mediaPath = public_path('images');
    }
    
    public function index(Request $request)
    {
        $folder = $request->get('folder', '');
        
        // Sanitize folder path
        $folder = $this->sanitizePath($folder);
        $currentPath = $this->mediaPath . ($folder ? '/' . $folder : '');
        
        // Ensure directory exists
        if (!File::exists($currentPath)) {
            File::makeDirectory($currentPath, 0755, true);
        }
        
        // Get folders
        $folders = [];
        $directories = File::directories($currentPath);
        foreach ($directories as $dir) {
            $folderName = basename($dir);
            $folders[] = [
                'name' => $folderName,
                'path' => $folder ? $folder . '/' . $folderName : $folderName,
                'items' => count(File::allFiles($dir, false))
            ];
        }
        
        // Get files from database
        $files = [];
        $mediaQuery = Media::query();
        
        if ($folder) {
            $mediaQuery->where('folder', $folder);
        } else {
            $mediaQuery->whereNull('folder');
        }
        
        $mediaFiles = $mediaQuery->orderBy('filename')->get();
        
        foreach ($mediaFiles as $media) {
            $files[] = [
                'id' => $media->id,
                'name' => $media->filename,
                'path' => $media->path,
                'url' => asset($media->path),
                'size' => $this->formatSize($media->file_size),
                'extension' => $media->mime_type,
                'is_image' => str_starts_with($media->mime_type, 'image/'),
                'modified' => $media->updated_at->format('Y-m-d H:i'),
                'alt_text' => $media->alt_text,
                'title' => $media->title
            ];
        }
        
        // Breadcrumb
        $breadcrumb = [['name' => 'Media', 'path' => '']];
        if ($folder) {
            $parts = explode('/', $folder);
            $currentBreadcrumb = '';
            foreach ($parts as $part) {
                $currentBreadcrumb .= ($currentBreadcrumb ? '/' : '') . $part;
                $breadcrumb[] = ['name' => $part, 'path' => $currentBreadcrumb];
            }
        }
        
        return view('admin.media.index', compact('folders', 'files', 'folder', 'breadcrumb'));
    }
    
    public function upload(Request $request)
    {
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|mimes:jpg,jpeg,png,gif,svg,webp,pdf,doc,docx|max:10240' // 10MB max
        ]);
        
        $folder = $this->sanitizePath($request->get('folder', ''));
        $uploadPath = $this->mediaPath . ($folder ? '/' . $folder : '');
        
        if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0755, true);
        }
        
        $uploadedFiles = [];
        
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $mimeType = $file->getMimeType();
                $fileSize = $file->getSize();
                
                // Generate unique filename
                $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
                
                // Move file
                $file->move($uploadPath, $filename);
                
                $relativePath = 'images/' . ($folder ? $folder . '/' : '') . $filename;
                
                // Save to database
                $media = Media::create([
                    'filename' => $filename,
                    'original_filename' => $originalName,
                    'path' => $relativePath,
                    'folder' => $folder ?: null,
                    'mime_type' => $mimeType,
                    'file_size' => $fileSize,
                    'alt_text' => pathinfo($originalName, PATHINFO_FILENAME),
                ]);
                
                $uploadedFiles[] = [
                    'id' => $media->id,
                    'name' => $filename,
                    'url' => asset($relativePath),
                    'path' => $relativePath
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'files' => $uploadedFiles,
            'message' => count($uploadedFiles) . ' file(s) uploaded successfully'
        ]);
    }
    
    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|regex:/^[a-zA-Z0-9\-_]+$/'
        ]);
        
        $folder = $this->sanitizePath($request->get('current_folder', ''));
        $newFolder = $request->get('name');
        
        $fullPath = $this->mediaPath . ($folder ? '/' . $folder : '') . '/' . $newFolder;
        
        if (File::exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'Folder already exists'
            ], 422);
        }
        
        File::makeDirectory($fullPath, 0755, true);
        
        return response()->json([
            'success' => true,
            'message' => 'Folder created successfully'
        ]);
    }
    
    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|integer'
        ]);
        
        $media = Media::findOrFail($request->get('id'));
        $fullPath = public_path($media->path);
        
        // Delete file
        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
        
        // Delete from database
        $media->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'alt_text' => 'nullable|string|max:255',
            'title' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500'
        ]);
        
        $media = Media::findOrFail($id);
        $media->update($request->only(['alt_text', 'title', 'caption']));
        
        return response()->json([
            'success' => true,
            'message' => 'Media updated successfully',
            'media' => $media
        ]);
    }
    
    public function browse(Request $request)
    {
        $folder = $request->get('folder', '');
        $type = $request->get('type', 'all'); // all, image
        
        $folder = $this->sanitizePath($folder);
        
        // Get folders
        $currentPath = $this->mediaPath . ($folder ? '/' . $folder : '');
        $folders = [];
        
        if (File::exists($currentPath)) {
            $directories = File::directories($currentPath);
            foreach ($directories as $dir) {
                $folders[] = [
                    'name' => basename($dir),
                    'path' => $folder ? $folder . '/' . basename($dir) : basename($dir)
                ];
            }
        }
        
        // Get files from database
        $mediaQuery = Media::query();
        
        if ($folder) {
            $mediaQuery->where('folder', $folder);
        } else {
            $mediaQuery->whereNull('folder');
        }
        
        if ($type === 'image') {
            $mediaQuery->where('mime_type', 'like', 'image/%');
        }
        
        $mediaFiles = $mediaQuery->orderBy('filename')->get();
        
        $files = [];
        foreach ($mediaFiles as $media) {
            $files[] = [
                'id' => $media->id,
                'name' => $media->filename,
                'path' => $media->path,
                'url' => asset($media->path),
                'thumbnail' => str_starts_with($media->mime_type, 'image/') ? asset($media->path) : null,
                'size' => $this->formatSize($media->file_size),
                'is_image' => str_starts_with($media->mime_type, 'image/'),
                'alt_text' => $media->alt_text,
                'title' => $media->title
            ];
        }
        
        return response()->json([
            'success' => true,
            'files' => $files,
            'folders' => $folders,
            'current_folder' => $folder
        ]);
    }
    
    private function sanitizePath($path)
    {
        // Remove any .. or . from path
        $path = str_replace(['..', './', '.\\'], '', $path);
        $path = trim($path, '/\\');
        return $path;
    }
    
    private function formatSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;
        
        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }
        
        return round($size, 2) . ' ' . $units[$unitIndex];
    }
}
