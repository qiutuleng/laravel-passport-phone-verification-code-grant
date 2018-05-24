## Introduction

Resource owner phone verification code credentials grant for Laravel Passport

## Install

Under working folder, run the command:

```
composer require qiutuleng/laravel-passport-phone-verification-code
```

## Setup

### Laravel 
If your laravel version is greater or equal to `5.5`, the service provider will be attached automatically.

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

### Lumen

```php
$app->register(\QiuTuleng\PhoneVerificationCodeGrant\PhoneVerificationCodeGrantServiceProvider::class);
```

## How to use?

### Configuring
You must needs implement `\QiuTuleng\PhoneVerificationCodeGrant\Interfaces\PhoneVerificationCodeGrantUserInterface` interface on your `User` model.
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

### Request Tokens
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

### More
You can check out the Laravel/Passport official documentation to learn more