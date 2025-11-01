<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Admin dashboard channel
Broadcast::channel('admin.dashboard', function ($user) {
    return in_array($user->role, ['admin', 'department_head']);
});

// Department channel
Broadcast::channel('department.{id}', function ($user, $id) {
    return $user->department_id == $id || $user->role === 'admin';
});

// User-specific channel
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});
