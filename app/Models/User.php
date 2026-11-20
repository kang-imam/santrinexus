<?php

namespace App\Models;

use App\Notifications\Auth\ResetPasswordNotification;
use Filament\Panel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use InvalidArgumentException;
use libphonenumber\NumberParseException;
use libphonenumber\PhoneNumberFormat;
use libphonenumber\PhoneNumberUtil;
use Spatie\Permission\Traits\HasRoles;

#[Fillable([
    'name',
    'username',
    'email',
    'phone',
    'password',
    'avatar',
])]
#[Hidden([
    'password',
    'remember_token',
    'two_factor_secret',
    'two_factor_recovery_codes',
    'two_factor_last_used_counter',
])]
final class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, HasRoles, Notifiable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_enabled' => 'boolean',
            'two_factor_secret' => 'encrypted',
            'two_factor_recovery_codes' => 'array',
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_last_used_counter' => 'integer',
        ];
    }

    protected function phone(): Attribute
    {
        return Attribute::make(
            set: fn (?string $value) => self::normalizePhone($value),
        );
    }

    public static function normalizePhone(?string $value): ?string
    {
        if ($value === null || trim($value) === '') {
            return null;
        }
        $value = trim($value);
        $phoneUtil = PhoneNumberUtil::getInstance();
        try {
            $phoneNumber = $phoneUtil->parse($value, 'ID');
            if (! $phoneUtil->isValidNumber($phoneNumber)) {
                throw new InvalidArgumentException('Nomor telepon tidak valid.');
            }

            return $phoneUtil->format($phoneNumber, PhoneNumberFormat::E164);
        } catch (NumberParseException $exception) {
            throw new InvalidArgumentException('Format nomor telepon tidak valid.', 0, $exception);
        }
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->status === 'active' && ($this->hasRole('Super Admin', 'web') || $this->can('panel.access'));
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
