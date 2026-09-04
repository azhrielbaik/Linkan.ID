<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('admin-notifications', function ($user) {
    // Only allow users with role platform_admin
    return $user->role === 'platform_admin';
});

Broadcast::channel('seller-notifications.{id}', function ($user, $id) {
    // Only allow the specific user
    return (int) $user->id === (int) $id;
});
