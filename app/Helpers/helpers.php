<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

function superAdmin(): User
{
    return User::find(1);
}

function user(): User
{
    return Auth::user();
}
