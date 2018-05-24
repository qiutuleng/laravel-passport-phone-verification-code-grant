# Introduction

Resource owner phone verification code credentials grant for Laravel Passport

# Install

**You must installed [laravel/passport](http://laravel.com/docs/master/passport) and configrue before installing this package.**

Under your working folder and run the command in terminal:

```
composer require qiutuleng/laravel-passport-phone-verification-code-grant
```

# Setup

## Laravel 

Other versions, you must needs add `\QiuTuleng\PhoneVerificationCodeGrant\PhoneVerificationCodeGrantServiceProvider::class` to the `providers` array in `config/app.php`:

```php
'providers' => [
    /*
     * Package Service Providers...
     */
     ...
     \QiuTuleng\PhoneVerificationCodeGrant\PhoneVerificationCodeGrantServiceProvider::class,
]
```

## Lumen

```php
$app->register(\QiuTuleng\PhoneVerificationCodeGrant\PhoneVerificationCodeGrantServiceProvider::class);
```

# How to use?

## Configure

1. You must needs implement `\QiuTuleng\PhoneVerificationCodeGrant\Interfaces\PhoneVerificationCodeGrantUserInterface` interface in your `User` model.

```php
<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use QiuTuleng\PhoneVerificationCodeGrant\Interfaces\PhoneVerificationCodeGrantUserInterface;

class User extends Authenticatable implement PhoneVerificationCodeGrantUserInterface
{
    use HasApiTokens, Notifiable;
}
```

2. Add `findOrNewForPassportVerifyCodeGrant` and `validateForPassportVerifyCodeGrant` methods to your `User` model.

```php
/**
 * Find or create a user by phone number
 *
 * @param $phoneNumber
 * @return \Illuminate\Database\Eloquent\Model|null
 */
public function findOrCreateForPassportVerifyCodeGrant($phoneNumber)
{
    // If you need to automatically register the user.
    return static::firstOrCreate(['mobile' => $phoneNumber]);

    // If the phone number is not exists in users table, will be fail to authenticate.
    // return static::where('mobile', '=', $phoneNumber)->first();
}

/**
 * Check the verification code is valid.
 *
 * @param $verificationCode
 * @return boolean
 */
public function validateForPassportVerifyCodeGrant($verificationCode)
{
    // Check verification code is valid.
    // return \App\Code::where('mobile', $this->mobile)->where('code', '=', $verificationCode)->where('expired_at', '>', now()->toDatetimeString())->exists();
    return true;
}
```


## Request Tokens

you may request an access token by issuing a `POST` request to the `/oauth/token` route with the user's phone number and verification code.

```php
$http = new GuzzleHttp\Client;

$response = $http->post('http://your-app.com/oauth/token', [
    'form_params' => [
        'grant_type' => 'phone_verification_code',
        'client_id' => 'client-id',
        'client_secret' => 'client-secret',
        'phone_number' => '+8613416292625',
        'verification_code' => 927068,
        'scope' => '',
    ],
]);

return json_decode((string) $response->getBody(), true);
```

## More

You can check out the [Laravel/Passport](https://laravel.com/docs/master/passport) official documentation to learn more
