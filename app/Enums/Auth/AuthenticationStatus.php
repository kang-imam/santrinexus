<?php

namespace App\Enums\Auth;

enum AuthenticationStatus: string
{
    case AUTHENTICATED = 'authenticated';
    case TWO_FACTOR_REQUIRED = 'two_factor_required';
}
