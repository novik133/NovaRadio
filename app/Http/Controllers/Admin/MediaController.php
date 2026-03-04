<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MediaController extends Controller
{
    private $mediaPath;
    private $publicPath;
    
    public function __construct()
    {
        $this->mediaPath = storage_path('app/public/media');
        $this->publicPath = public_path('storage/media');
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
        
        // Get files
        $files = [];
        $allFiles = File::files($currentPath);
        foreach ($allFiles as $file) {
            $extension = strtolower($file->getExtension());
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
            
            $filePath = $folder ? $folder . '/' . $file->getFilename() : $file->getFilename();
            
            $files[] = [
                'name' => $file->getFilename(),
                'path' => $filePath,
                'url' => asset('storage/media/' . $filePath),
                'size' => $this->formatSize($file->getSize()),
                'extension' => $extension,
                'is_image' => $isImage,
                'modified' => date('Y-m-d H:i', $file->getMTime())
            ];
        }
        
        // Sort: folders first, then files
        usort($files, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
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
            'files.*' => 'file|max:10240' // 10MB max per file
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
                
                // Generate unique filename
                $filename = Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '_' . time() . '.' . $extension;
                
                // Move file
                $file->move($uploadPath, $filename);
                
                $filePath = $folder ? $folder . '/' . $filename : $filename;
                
                $uploadedFiles[] = [
                    'name' => $filename,
                    'url' => asset('storage/media/' . $filePath),
                    'path' => $filePath
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
            'path' => 'required|string'
        ]);
        
        $path = $this->sanitizePath($request->get('path'));
        $fullPath = $this->mediaPath . '/' . $path;
        
        if (!File::exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File or folder not found'
            ], 404);
        }
        
        // Check if trying to delete root
        if (dirname($path) === '.') {
            // It's in root, check if it's a folder or file
            if (is_dir($fullPath)) {
                // Count items
                $items = count(File::allFiles($fullPath, false));
                if ($items > 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Folder is not empty. Delete all contents first.'
                    ], 422);
                }
            }
        }
        
        if (is_dir($fullPath)) {
            File::deleteDirectory($fullPath);
        } else {
            File::delete($fullPath);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Deleted successfully'
        ]);
    }
    
    public function rename(Request $request)
    {
        $request->validate([
            'path' => 'required|string',
            'new_name' => 'required|string|max:50|regex:/^[a-zA-Z0-9\-_\.]+$/'
        ]);
        
        $path = $this->sanitizePath($request->get('path'));
        $newName = $request->get('new_name');
        
        $fullPath = $this->mediaPath . '/' . $path;
        $newPath = dirname($fullPath) . '/' . $newName;
        
        if (!File::exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File or folder not found'
            ], 404);
        }
        
        if (File::exists($newPath)) {
            return response()->json([
                'success' => false,
                'message' => 'A file or folder with that name already exists'
            ], 422);
        }
        
        File::move($fullPath, $newPath);
        
        return response()->json([
            'success' => true,
            'message' => 'Renamed successfully'
        ]);
    }
    
    public function browse(Request $request)
    {
        $folder = $request->get('folder', '');
        $type = $request->get('type', 'all'); // all, image
        
        $folder = $this->sanitizePath($folder);
        $currentPath = $this->mediaPath . ($folder ? '/' . $folder : '');
        
        if (!File::exists($currentPath)) {
            return response()->json([
                'success' => true,
                'files' => [],
                'folders' => []
            ]);
        }
        
        // Get folders
        $folders = [];
        $directories = File::directories($currentPath);
        foreach ($directories as $dir) {
            $folders[] = [
                'name' => basename($dir),
                'path' => $folder ? $folder . '/' . basename($dir) : basename($dir)
            ];
        }
        
        // Get files
        $files = [];
        $allFiles = File::files($currentPath);
        foreach ($allFiles as $file) {
            $extension = strtolower($file->getExtension());
            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp']);
            
            if ($type === 'image' && !$isImage) {
                continue;
            }
            
            $filePath = $folder ? $folder . '/' . $file->getFilename() : $file->getFilename();
            
            $files[] = [
                'name' => $file->getFilename(),
                'path' => $filePath,
                'url' => asset('storage/media/' . $filePath),
                'thumbnail' => $isImage ? asset('storage/media/' . $filePath) : null,
                'size' => $this->formatSize($file->getSize()),
                'is_image' => $isImage
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
