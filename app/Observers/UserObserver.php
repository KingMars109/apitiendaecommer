<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Cliente;

class UserObserver
{
    public function created(User $user)
    {
        Cliente::create([
            'nombre' => $user->name,
            'email' => $user->email,
            'user_id' => $user->id
        ]);
    }
}
