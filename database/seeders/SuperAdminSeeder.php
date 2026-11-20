<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $email = config('superadmin.email');
        $password = config('superadmin.password');
        if (! is_string($email) || trim($email) === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RuntimeException('SUPER_ADMIN_EMAIL wajib diisi dengan alamat email yang valid.');
        }
        if (! is_string($password) || strlen($password) < 12) {
            throw new RuntimeException('SUPER_ADMIN_PASSWORD wajib diisi dengan minimal 12 karakter.');
        }
        $name = config('superadmin.name');
        $username = config('superadmin.username');
        $phone = config('superadmin.phone');
        if (! is_string($name) || trim($name) === '') {
            throw new RuntimeException('SUPER_ADMIN_NAME wajib diisi.');
        }
        if ($username !== null && (! is_string($username) || trim($username) === '')) {
            throw new RuntimeException('SUPER_ADMIN_USERNAME tidak valid.');
        }
        if ($phone !== null && ! is_string($phone)) {
            throw new RuntimeException('SUPER_ADMIN_PHONE tidak valid.');
        }
        DB::transaction(function () use ($email, $password, $name, $username, $phone): void {
            $role = Role::firstOrCreate([
                'name' => 'Super Admin',
                'guard_name' => 'web',
            ]);
            $user = User::withTrashed()->where('email', $email)->lockForUpdate()->first();
            if ($user?->trashed()) {
                throw new RuntimeException('Email Super Admin digunakan akun yang sudah dihapus. Pulihkan akun secara manual.');
            }
            if (! $user) {
                $user = new User;
                $user->name = $name;
                $user->username = $username;
                $user->email = $email;
                $user->phone = $phone;
                $user->password = $password;
                $user->status = 'active';
                $user->email_verified_at = now();
                $user->save();
            }
            $user->assignRole($role);
            $role->syncPermissions(
                Permission::where('guard_name', 'web')->get()
            );
        });
        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
