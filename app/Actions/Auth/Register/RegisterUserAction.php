<?php

namespace App\Actions\Auth\Register;

use App\DataTransferObjects\Auth\RegisterData;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class RegisterUserAction
{
    public function handle(RegisterData $data): User
    {
        return DB::transaction(function () use ($data): User {
            return User::create([
                'name' => $data->name,
                'username' => $data->username,
                'email' => $data->email,
                'phone' => $data->phone,
                'password' => $data->password,
            ]);
        });
    }
}
