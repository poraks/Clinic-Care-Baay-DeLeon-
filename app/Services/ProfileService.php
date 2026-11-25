<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    /**
     * Update the user's profile information.
     */
    public function updateProfile(User $user, array $data): User
    {
        $user->fill($data);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        return $user;
    }

    /**
     * Delete the user's account.
     */
    public function deleteAccount(User $user): void
    {
        Auth::logout();

        $user->delete();
    }
}