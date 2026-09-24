<?php

namespace App\Services\AdminSeller;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountService
{
    /**
     * Update account details for a user.
     */
    public function updateAccount(User $user, array $data, ?\Illuminate\Http\UploadedFile $avatar = null, ?string $password = null): void
    {
        DB::transaction(function () use ($user, $data, $avatar, $password) {
            $user->username = $data['username'];
            $user->name = $data['name'];
            if (isset($data['bio'])) {
                $user->bio = $data['bio'];
            }

            if (isset($data['remove_avatar']) && $data['remove_avatar']) {
                if ($user->avatar) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = null;
            } elseif ($avatar) {
                if ($user->avatar) {
                    \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
                }
                $path = $avatar->store('avatars', 'public');
                $user->avatar = $path;
            }

            if ($password) {
                $user->password = Hash::make($password);
            }

            $user->save();

            ActivityLogger::log(
                'update_account',
                "User {$user->name} memperbarui informasi akun" . ($password ? " dan kata sandi" : "") . ".",
                ['username' => $user->username, 'password_changed' => (bool)$password],
                $user->id
            );
        });
    }

    /**
     * Change user password securely.
     */
    public function changePassword(User $user, string $newPassword): void
    {
        DB::transaction(function () use ($user, $newPassword) {
            $user->password = Hash::make($newPassword);
            $user->save();

            ActivityLogger::log(
                'change_password',
                "User {$user->name} berhasil mengubah kata sandi akun.",
                ['user_id' => $user->id],
                $user->id
            );
        });
    }

    /**
     * Update notification preferences for a user.
     */
    public function updateNotificationPreferences(User $user, array $preferences): void
    {
        DB::transaction(function () use ($user, $preferences) {
            $user->notification_preferences = $preferences;
            $user->save();

            ActivityLogger::log(
                'update_notification_preferences',
                "User {$user->name} memperbarui preferensi notifikasi email.",
                ['preferences' => $preferences],
                $user->id
            );
        });
    }

    /**
     * Soft delete user account.
     */
    public function deleteAccount(User $user): void
    {
        \App\Models\Appearance::where('user_id', $user->id)->delete();
        \App\Models\DigitalProduct::where('user_id', $user->id)->delete();
        
        $user->delete();
    }
}
