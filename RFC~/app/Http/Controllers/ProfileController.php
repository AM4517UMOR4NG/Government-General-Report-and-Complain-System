<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Show user profile
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update user profile
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'birth_date' => 'nullable|date|before:today',
            'gender' => 'nullable|in:male,female',
            'position' => 'nullable|string|max:100',
            'bio' => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'name', 'email', 'phone', 'address', 'birth_date', 
            'gender', 'position', 'bio'
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
                Storage::delete('public/' . $user->avatar);
            }

            // Store new avatar
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Show settings page
     */
    public function settings()
    {
        $user = Auth::user();
        $settings = $user->getSettings();
        return view('profile.settings', compact('user', 'settings'));
    }

    /**
     * Update user settings
     */
    public function updateSettings(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'dashboard_layout' => 'required|in:compact,comfortable,spacious',
            'items_per_page' => 'required|in:10,15,25,50',
            'language' => 'required|in:id,en',
            'notifications' => 'nullable|array',
            'privacy' => 'nullable|array',
        ]);

        $settings = [
            'dashboard_layout' => $validated['dashboard_layout'],
            'items_per_page' => (int) $validated['items_per_page'],
            'language' => $validated['language'],
            'notifications' => [
                'email' => $request->has('notifications.email'),
                'browser' => $request->has('notifications.browser'),
                'sms' => $request->has('notifications.sms'),
                'reports' => $request->has('notifications.reports'),
                'complaints' => $request->has('notifications.complaints'),
                'status' => $request->has('notifications.status'),
            ],
            'privacy' => [
                'show_email' => $request->has('privacy.show_email'),
                'show_phone' => $request->has('privacy.show_phone'),
                'show_address' => $request->has('privacy.show_address'),
            ],
        ];

        try {
            $user->updateSettings($settings);
            return redirect()->route('profile.settings')->with('success', 'Pengaturan berhasil disimpan!');
        } catch (\Exception $e) {
            \Log::error('Settings update failed: ' . $e->getMessage());
            return redirect()->route('profile.settings')->with('error', 'Gagal menyimpan pengaturan. Silakan coba lagi.');
        }
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini tidak benar.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.settings')->with('success', 'Password berhasil diubah!');
    }

    /**
     * Delete avatar
     */
    public function deleteAvatar()
    {
        $user = Auth::user();

        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
        }

        $user->update(['avatar' => null]);

        return redirect()->route('profile.edit')->with('success', 'Avatar berhasil dihapus!');
    }
}