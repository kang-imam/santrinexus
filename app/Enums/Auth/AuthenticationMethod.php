<?php

namespace App\Enums\Auth;

enum AuthenticationMethod: string
{
    case PASSWORD = 'password';
    case GOOGLE = 'google';
    case MAGIC_LINK = 'magic_link';
}
