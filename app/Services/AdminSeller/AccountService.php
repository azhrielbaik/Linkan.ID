<?php

namespace App\Services\AdminSeller;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\ActivityLogger;
use Illuminate\Support\Facades\Auth;

class AccountService
{
    /**
     * Update account details for a user.
     */
    public function updateAccount(User $user, array $data, ?string $password = null): void
    {
        $user->username = $data['username'];
        $user->name = $data['name'];

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
