<?php

declare(strict_types=1);

namespace App\Enums;

enum RouteNameEnum: string
{
    case APPEARANCE_EDIT    = 'appearance.edit';
    case HOME               = 'home';
    case PROFILE_EDIT       = 'profile.edit';
    case TWO_FACTOR_SHOW    = 'two-factor.show';
    case USER_PASSWORD_EDIT = 'user-password.edit';

    // Fortify
    case LOGIN                                = 'login';
    case LOGIN_STORE                          = 'login.store';
    case LOGOUT                               = 'logout';
    case PASSWORD_CONFIRM                     = 'password.confirm';
    case PASSWORD_CONFIRMATION                = 'password.confirmation';
    case PASSWORD_CONFIRM_STORE               = 'password.confirm.store';
    case PASSWORD_EMAIL                       = 'password.email';
    case PASSWORD_REQUEST                     = 'password.request';
    case PASSWORD_RESET                       = 'password.reset';
    case PASSWORD_UPDATE                      = 'password.update';
    case REGISTER                             = 'register';
    case REGISTER_STORE                       = 'register.store';
    case TWO_FACTOR_CONFIRM                   = 'two-factor.confirm';
    case TWO_FACTOR_DISABLE                   = 'two-factor.disable';
    case TWO_FACTOR_ENABLE                    = 'two-factor.enable';
    case TWO_FACTOR_LOGIN                     = 'two-factor.login';
    case TWO_FACTOR_LOGIN_STORE               = 'two-factor.login.store';
    case TWO_FACTOR_QR_CODE                   = 'two-factor.qr-code';
    case TWO_FACTOR_RECOVERY_CODES            = 'two-factor.recovery-codes';
    case TWO_FACTOR_REGENERATE_RECOVERY_CODES = 'two-factor.regenerate-recovery-codes';
    case TWO_FACTOR_SECRET_KEY                = 'two-factor.secret-key';
    case USER_PASSWORD_UPDATE                 = 'user-password.update';
    case USER_PROFILE_INFORMATION_UPDATE      = 'user-profile-information.update';
    case VERIFICATION_NOTICE                  = 'verification.notice';
    case VERIFICATION_SEND                    = 'verification.send';
    case VERIFICATION_VERIFY                  = 'verification.verify';
}
