<?php

namespace App\Services\Auth;

use App\Contracts\Auth\TwoFactorAuthenticationServiceInterface;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorRecoveryCodesData;
use App\DataTransferObjects\Auth\TwoFactor\TwoFactorSetupData;
use App\Models\User;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use LogicException;
use PragmaRX\Google2FA\Google2FA;

final class TwoFactorAuthenticationService implements TwoFactorAuthenticationServiceInterface
{
    private const OTP_WINDOW = 1;

    private const RECOVERY_CODE_COUNT = 10;

    private const QR_CODE_SIZE = 320;

    public function __construct(private readonly Google2FA $google2fa) {}

    public function beginSetup(User $user): TwoFactorSetupData
    {
        if ($this->isEnabled($user)) {
            throw new LogicException('Autentikasi dua faktor sudah aktif.');
        }
        $secret = DB::transaction(function () use ($user): string {
            $account = User::query()->lockForUpdate()->findOrFail($user->getKey());
            if ($this->isEnabled($account)) {
                throw new LogicException('Autentikasi dua faktor sudah aktif.');
            }
            $secret = $this->google2fa->generateSecretKey(32);
            $account->forceFill([
                'two_factor_enabled' => false,
                'two_factor_secret' => $secret,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'two_factor_last_used_counter' => null,
            ])->save();

            return $secret;
        });
        $account = User::query()->findOrFail($user->getKey());
        $provisioningUri = $this->getProvisioningUri($account);

        return new TwoFactorSetupData(
            secret: $secret,
            provisioningUri: $provisioningUri,
            qrCode: $this->generateQrCodeFromUri($provisioningUri),
        );
    }

    public function getProvisioningUri(User $user): string
    {
        $secret = $user->two_factor_secret;
        if (! is_string($secret) || $secret === '') {
            throw new LogicException('Secret autentikasi dua faktor belum tersedia.');
        }

        return $this->google2fa->getQRCodeUrl(
            (string) config('app.name', 'SantriNexus'),
            $user->email,
            $secret
        );
    }

    public function generateQrCode(User $user): string
    {
        return $this->generateQrCodeFromUri($this->getProvisioningUri($user));
    }

    private function generateQrCodeFromUri(string $provisioningUri): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle(self::QR_CODE_SIZE),
            new SvgImageBackEnd
        );
        $writer = new Writer($renderer);
        $svg = $writer->writeString($provisioningUri);

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    public function confirmSetup(User $user, string $code): ?TwoFactorRecoveryCodesData
    {
        $code = trim($code);
        if (! preg_match('/\A\d{6}\z/', $code)) {
            return null;
        }

        return DB::transaction(function () use ($user, $code): ?TwoFactorRecoveryCodesData {
            $account = User::query()->lockForUpdate()->findOrFail($user->getKey());
            if ($this->isEnabled($account) || ! is_string($account->two_factor_secret) || $account->two_factor_secret === '') {
                return null;
            }
            $counter = $this->google2fa->verifyKeyNewer(
                $account->two_factor_secret,
                $code,
                -1,
                self::OTP_WINDOW
            );
            if ($counter === false) {
                return null;
            }
            [$codes, $hashes] = $this->createRecoveryCodes();
            $account->forceFill([
                'two_factor_enabled' => true,
                'two_factor_confirmed_at' => now(),
                'two_factor_recovery_codes' => $hashes,
                'two_factor_last_used_counter' => $counter,
            ])->save();

            return new TwoFactorRecoveryCodesData($codes);
        });
    }

    public function verifyCode(User $user, string $code): bool
    {
        $code = trim($code);
        if (! preg_match('/\A\d{6}\z/', $code)) {
            return false;
        }

        return DB::transaction(function () use ($user, $code): bool {
            $account = User::query()->lockForUpdate()->findOrFail($user->getKey());
            if (! $this->isEnabled($account)) {
                return false;
            }

            return $this->verifyTotpForLockedUser($account, $code);
        });
    }

    public function verifyRecoveryCode(User $user, string $code): bool
    {
        $normalizedCode = $this->normalizeRecoveryCode($code);
        if (! preg_match('/\A[A-Z0-9]{10}\z/', $normalizedCode)) {
            return false;
        }

        return DB::transaction(function () use ($user, $normalizedCode): bool {
            $account = User::query()->lockForUpdate()->findOrFail($user->getKey());
            if (! $this->isEnabled($account)) {
                return false;
            }

            return $this->consumeRecoveryCodeForLockedUser($account, $normalizedCode);
        });
    }

    public function regenerateRecoveryCodes(User $user, string $code): ?TwoFactorRecoveryCodesData
    {
        $code = trim($code);
        if (! preg_match('/\A\d{6}\z/', $code)) {
            return null;
        }

        return DB::transaction(function () use ($user, $code): ?TwoFactorRecoveryCodesData {
            $account = User::query()->lockForUpdate()->findOrFail($user->getKey());
            if (! $this->isEnabled($account) || ! $this->verifyTotpForLockedUser($account, $code)) {
                return null;
            }
            [$codes, $hashes] = $this->createRecoveryCodes();
            $account->forceFill([
                'two_factor_recovery_codes' => $hashes,
            ])->save();

            return new TwoFactorRecoveryCodesData($codes);
        });
    }

    public function disable(User $user, string $code, bool $useRecoveryCode = false): bool
    {
        return DB::transaction(function () use ($user, $code, $useRecoveryCode): bool {
            $account = User::query()->lockForUpdate()->findOrFail($user->getKey());
            if (! $this->isEnabled($account)) {
                return false;
            }
            $verified = $useRecoveryCode
                ? $this->consumeRecoveryCodeForLockedUser($account, $this->normalizeRecoveryCode($code))
                : preg_match('/\A\d{6}\z/', trim($code)) === 1 && $this->verifyTotpForLockedUser($account, trim($code));
            if (! $verified) {
                return false;
            }
            $account->forceFill([
                'two_factor_enabled' => false,
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'two_factor_last_used_counter' => null,
            ])->save();

            return true;
        });
    }

    public function cancelSetup(User $user): bool
    {
        return DB::transaction(function () use ($user): bool {
            $account = User::query()->lockForUpdate()->findOrFail($user->getKey());
            if ($this->isEnabled($account)) {
                return false;
            }
            $account->forceFill([
                'two_factor_secret' => null,
                'two_factor_recovery_codes' => null,
                'two_factor_confirmed_at' => null,
                'two_factor_last_used_counter' => null,
            ])->save();

            return true;
        });
    }

    public function isEnabled(User $user): bool
    {
        return (bool) $user->two_factor_enabled && $this->isConfirmed($user);
    }

    public function isConfirmed(User $user): bool
    {
        return $user->two_factor_confirmed_at !== null
            && is_string($user->two_factor_secret)
            && $user->two_factor_secret !== '';
    }

    private function verifyTotpForLockedUser(User $user, string $code): bool
    {
        if (! is_string($user->two_factor_secret) || $user->two_factor_secret === '') {
            return false;
        }
        $lastCounter = $user->two_factor_last_used_counter ?? -1;
        $counter = $this->google2fa->verifyKeyNewer(
            $user->two_factor_secret,
            $code,
            $lastCounter,
            self::OTP_WINDOW
        );
        if ($counter === false) {
            return false;
        }
        $user->forceFill([
            'two_factor_last_used_counter' => $counter,
        ])->save();

        return true;
    }

    private function consumeRecoveryCodeForLockedUser(User $user, string $code): bool
    {
        if (! preg_match('/\A[A-Z0-9]{10}\z/', $code)) {
            return false;
        }
        $hashes = $user->two_factor_recovery_codes ?? [];
        if (! is_array($hashes)) {
            return false;
        }
        foreach ($hashes as $index => $hash) {
            if (is_string($hash) && Hash::check($code, $hash)) {
                unset($hashes[$index]);
                $user->forceFill([
                    'two_factor_recovery_codes' => array_values($hashes),
                ])->save();

                return true;
            }
        }

        return false;
    }

    private function createRecoveryCodes(): array
    {
        $codes = [];
        $hashes = [];
        for ($index = 0; $index < self::RECOVERY_CODE_COUNT; $index++) {
            $code = Str::upper(Str::random(5)).'-'.Str::upper(Str::random(5));
            $codes[] = $code;
            $hashes[] = Hash::make(str_replace('-', '', $code));
        }

        return [$codes, $hashes];
    }

    private function normalizeRecoveryCode(string $code): string
    {
        return preg_replace('/[^A-Z0-9]/', '', Str::upper(trim($code))) ?? '';
    }
}
