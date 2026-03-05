<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        
        return view('admin.profile.edit', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:50',
            'bio' => 'nullable|string|max:1000',
            'timezone' => 'nullable|string|max:50',
            'avatar' => 'nullable|url|max:500',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }
    
    public function updatePassword(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);
        
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }
        
        $user->update(['password' => Hash::make($validated['password'])]);
        
        return redirect()->route('admin.profile.edit')
            ->with('success', 'Password updated successfully!');
    }
    
    public function uploadAvatar(Request $request)
    {
        try {
            $user = Auth::user();
            
            $request->validate([
                'avatar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);
            
            $file = $request->file('avatar');
            $filename = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Get file info before moving
            $originalName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $fileSize = $file->getSize();
            
            // Save to public/images/avatars
            $destinationPath = public_path('images/avatars');
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }
            
            $file->move($destinationPath, $filename);
            
            $avatarPath = 'images/avatars/' . $filename;
            
            // Register in media library
            \App\Models\Media::create([
                'filename' => $filename,
                'original_filename' => $originalName,
                'path' => $avatarPath,
                'mime_type' => $mimeType,
                'file_size' => $fileSize,
                'folder' => 'avatars',
                'alt_text' => $user->name . ' avatar',
            ]);
            
            $user->update(['avatar' => $avatarPath]);
            
            return response()->json([
                'success' => true,
                'url' => asset($avatarPath)
            ]);
        } catch (\Exception $e) {
            \Log::error('Avatar upload failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error uploading avatar: ' . $e->getMessage()
            ], 500);
        }
    }
}
