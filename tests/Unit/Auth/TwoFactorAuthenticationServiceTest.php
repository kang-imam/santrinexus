<?php

use App\DataTransferObjects\Auth\TwoFactor\TwoFactorRecoveryCodesData;
use App\Models\User;
use App\Services\Auth\TwoFactorAuthenticationService;
use Illuminate\Support\Facades\Hash;
use LogicException;
use PragmaRX\Google2FA\Google2FA;

beforeEach(function () {
    $this->service = app(TwoFactorAuthenticationService::class);
    $this->google2fa = app(Google2FA::class);
    $this->user = User::query()->create([
        'name' => 'Test User',
        'username' => 'testuser',
        'email' => 'test@example.com',
        'password' => 'password123',
    ]);
    $this->enableTwoFactor = function (): array {
        $setup = $this->service->beginSetup($this->user);
        $code = $this->google2fa->getCurrentOtp($setup->secret);
        $recoveryCodes = $this->service->confirmSetup($this->user, $code);
        $this->user->refresh();

        return $recoveryCodes->codes;
    };
});
it('generates a secret and QR code when beginning setup', function () {
    $result = $this->service->beginSetup($this->user);
    expect($result->secret)->toBeString()->not->toBeEmpty()
        ->and($result->provisioningUri)->toContain('otpauth://totp/')
        ->and($result->qrCode)->toStartWith('data:image/svg+xml;base64,');
    $this->user->refresh();
    expect($this->user->two_factor_secret)->toBe($result->secret)
        ->and($this->user->two_factor_enabled)->toBeFalse()
        ->and($this->user->two_factor_confirmed_at)->toBeNull()
        ->and($this->user->two_factor_recovery_codes)->toBeNull();
});
it('generates a provisioning URI for an existing setup', function () {
    $setup = $this->service->beginSetup($this->user);
    $this->user->refresh();
    expect($this->service->getProvisioningUri($this->user))
        ->toBe($setup->provisioningUri);
});
it('generates a QR code for an existing setup', function () {
    $this->service->beginSetup($this->user);
    $this->user->refresh();
    expect($this->service->generateQrCode($this->user))
        ->toStartWith('data:image/svg+xml;base64,');
});
it('rejects provisioning URI generation without a secret', function () {
    expect(fn () => $this->service->getProvisioningUri($this->user))
        ->toThrow(LogicException::class);
});
it('rejects setup when two factor authentication is already enabled', function () {
    ($this->enableTwoFactor)();
    expect(fn () => $this->service->beginSetup($this->user))
        ->toThrow(LogicException::class);
});
it('enables two factor authentication with a valid OTP and generates ten recovery codes', function () {
    $codes = ($this->enableTwoFactor)();
    expect($codes)->toBeArray()->toHaveCount(10);
    $this->user->refresh();
    expect($this->service->isEnabled($this->user))->toBeTrue()
        ->and($this->service->isConfirmed($this->user))->toBeTrue()
        ->and($this->user->two_factor_confirmed_at)->not->toBeNull()
        ->and($this->user->two_factor_recovery_codes)->toHaveCount(10)
        ->and($this->user->two_factor_last_used_counter)->toBeInt();
});
it('rejects an invalid OTP during setup', function () {
    $this->service->beginSetup($this->user);
    expect($this->service->confirmSetup($this->user, '123456'))->toBeNull();
    $this->user->refresh();
    expect($this->service->isEnabled($this->user))->toBeFalse()
        ->and($this->user->two_factor_confirmed_at)->toBeNull()
        ->and($this->user->two_factor_recovery_codes)->toBeNull();
});
it('rejects an OTP with an invalid format during setup', function () {
    $this->service->beginSetup($this->user);
    foreach (['', '12345', '1234567', 'abcdef', '12 3456'] as $code) {
        expect($this->service->confirmSetup($this->user, $code))->toBeNull();
    }
});
it('rejects confirming an already enabled setup', function () {
    ($this->enableTwoFactor)();
    $this->user->refresh();
    $code = $this->google2fa->getCurrentOtp($this->user->two_factor_secret);
    expect($this->service->confirmSetup($this->user, $code))->toBeNull();
});
it('rejects an OTP that has already been used during setup', function () {
    $setup = $this->service->beginSetup($this->user);
    $code = $this->google2fa->getCurrentOtp($setup->secret);
    expect($this->service->confirmSetup($this->user, $code))
        ->toBeInstanceOf(TwoFactorRecoveryCodesData::class)
        ->and($this->service->verifyCode($this->user, $code))->toBeFalse();
});
it('accepts a valid OTP', function () {
    ($this->enableTwoFactor)();
    $this->user->forceFill(['two_factor_last_used_counter' => null])->save();
    $code = $this->google2fa->getCurrentOtp($this->user->two_factor_secret);
    expect($this->service->verifyCode($this->user, $code))->toBeTrue();
});
it('rejects an invalid OTP', function () {
    ($this->enableTwoFactor)();
    expect($this->service->verifyCode($this->user, '123456'))->toBeFalse();
});
it('rejects an OTP with an invalid format', function () {
    ($this->enableTwoFactor)();
    foreach (['', '12345', '1234567', 'abcdef', '12 3456'] as $code) {
        expect($this->service->verifyCode($this->user, $code))->toBeFalse();
    }
});
it('rejects OTP verification when two factor authentication is not enabled', function () {
    expect($this->service->verifyCode($this->user, '123456'))->toBeFalse();
});
it('accepts a recovery code and consumes it after use', function () {
    $codes = ($this->enableTwoFactor)();
    $code = $codes[0];
    expect($this->service->verifyRecoveryCode($this->user, $code))->toBeTrue()
        ->and($this->service->verifyRecoveryCode($this->user, $code))->toBeFalse();
    $this->user->refresh();
    expect($this->user->two_factor_recovery_codes)->toHaveCount(9);
});
it('accepts a recovery code without its separator and regardless of letter case', function () {
    $codes = ($this->enableTwoFactor)();
    $code = strtolower(str_replace('-', '', $codes[0]));
    expect($this->service->verifyRecoveryCode($this->user, $code))->toBeTrue();
});
it('rejects an invalid recovery code', function () {
    ($this->enableTwoFactor)();
    expect($this->service->verifyRecoveryCode($this->user, 'INVALID-CODE'))->toBeFalse();
});
it('rejects recovery codes with an invalid format', function () {
    ($this->enableTwoFactor)();
    foreach (['', '12345', '123456789', '12345678901', '!!!'] as $code) {
        expect($this->service->verifyRecoveryCode($this->user, $code))->toBeFalse();
    }
});
it('rejects recovery code verification when two factor authentication is not enabled', function () {
    expect($this->service->verifyRecoveryCode($this->user, 'ABCDE-FGHIJ'))->toBeFalse();
});
it('stores recovery codes as hashes instead of plaintext', function () {
    $codes = ($this->enableTwoFactor)();
    $this->user->refresh();
    foreach ($this->user->two_factor_recovery_codes as $index => $hash) {
        expect($hash)->not->toBe($codes[$index])
            ->and(Hash::check(str_replace('-', '', $codes[$index]), $hash))->toBeTrue();
    }
});
it('regenerates recovery codes and invalidates the previous codes', function () {
    $oldCodes = ($this->enableTwoFactor)();
    $this->user->forceFill(['two_factor_last_used_counter' => null])->save();
    $code = $this->google2fa->getCurrentOtp($this->user->two_factor_secret);
    $newCodes = $this->service->regenerateRecoveryCodes($this->user, $code);
    expect($newCodes)->toBeInstanceOf(TwoFactorRecoveryCodesData::class)
        ->and($newCodes->codes)->toHaveCount(10);
    foreach ($oldCodes as $oldCode) {
        expect($this->service->verifyRecoveryCode($this->user, $oldCode))->toBeFalse();
    }
    expect($this->service->verifyRecoveryCode($this->user, $newCodes->codes[0]))->toBeTrue();
    $this->user->refresh();
    expect($this->user->two_factor_recovery_codes)->toHaveCount(9);
});
it('rejects recovery code regeneration with an invalid OTP', function () {
    $oldCodes = ($this->enableTwoFactor)();
    expect($this->service->regenerateRecoveryCodes($this->user, '123456'))->toBeNull()
        ->and($this->service->verifyRecoveryCode($this->user, $oldCodes[0]))->toBeTrue();
});
it('rejects recovery code regeneration when two factor authentication is not enabled', function () {
    expect($this->service->regenerateRecoveryCodes($this->user, '123456'))->toBeNull();
});
it('rejects recovery code regeneration with an invalid OTP format', function () {
    ($this->enableTwoFactor)();
    foreach (['', '12345', '1234567', 'abcdef'] as $code) {
        expect($this->service->regenerateRecoveryCodes($this->user, $code))->toBeNull();
    }
});
it('disables two factor authentication with a valid OTP', function () {
    ($this->enableTwoFactor)();
    $this->user->forceFill(['two_factor_last_used_counter' => null])->save();
    $code = $this->google2fa->getCurrentOtp($this->user->two_factor_secret);
    expect($this->service->disable($this->user, $code))->toBeTrue();
    $this->user->refresh();
    expect($this->service->isEnabled($this->user))->toBeFalse()
        ->and($this->service->isConfirmed($this->user))->toBeFalse()
        ->and($this->user->two_factor_enabled)->toBeFalse()
        ->and($this->user->two_factor_secret)->toBeNull()
        ->and($this->user->two_factor_recovery_codes)->toBeNull()
        ->and($this->user->two_factor_confirmed_at)->toBeNull()
        ->and($this->user->two_factor_last_used_counter)->toBeNull();
});
it('disables two factor authentication with a valid recovery code', function () {
    $codes = ($this->enableTwoFactor)();
    expect($this->service->disable($this->user, $codes[0], true))->toBeTrue();
    $this->user->refresh();
    expect($this->service->isEnabled($this->user))->toBeFalse()
        ->and($this->user->two_factor_secret)->toBeNull()
        ->and($this->user->two_factor_recovery_codes)->toBeNull()
        ->and($this->user->two_factor_confirmed_at)->toBeNull()
        ->and($this->user->two_factor_last_used_counter)->toBeNull();
});
it('rejects disabling two factor authentication with an invalid OTP', function () {
    ($this->enableTwoFactor)();
    expect($this->service->disable($this->user, '123456'))->toBeFalse();
    $this->user->refresh();
    expect($this->service->isEnabled($this->user))->toBeTrue();
});
it('rejects disabling two factor authentication with an invalid recovery code', function () {
    ($this->enableTwoFactor)();
    expect($this->service->disable($this->user, 'INVALID-CODE', true))->toBeFalse();
    $this->user->refresh();
    expect($this->service->isEnabled($this->user))->toBeTrue();
});
it('rejects disabling two factor authentication when it is not enabled', function () {
    expect($this->service->disable($this->user, '123456'))->toBeFalse()
        ->and($this->service->disable($this->user, 'ABCDE-FGHIJ', true))->toBeFalse();
});
it('cancels an unconfirmed setup', function () {
    $this->service->beginSetup($this->user);
    expect($this->service->cancelSetup($this->user))->toBeTrue();
    $this->user->refresh();
    expect($this->user->two_factor_secret)->toBeNull()
        ->and($this->user->two_factor_recovery_codes)->toBeNull()
        ->and($this->user->two_factor_confirmed_at)->toBeNull()
        ->and($this->user->two_factor_last_used_counter)->toBeNull()
        ->and($this->service->isEnabled($this->user))->toBeFalse();
});
it('does not allow cancelling an enabled setup', function () {
    ($this->enableTwoFactor)();
    expect($this->service->cancelSetup($this->user))->toBeFalse();
    $this->user->refresh();
    expect($this->service->isEnabled($this->user))->toBeTrue()
        ->and($this->user->two_factor_secret)->not->toBeNull();
});
it('allows restarting an unconfirmed setup', function () {
    $firstSetup = $this->service->beginSetup($this->user);
    $secondSetup = $this->service->beginSetup($this->user);
    expect($secondSetup->secret)->not->toBe($firstSetup->secret);
    $this->user->refresh();
    expect($this->user->two_factor_secret)->toBe($secondSetup->secret);
});
