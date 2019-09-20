# Laravel Passport Phone Verification Code Grant

## 介绍

基于Laravel Passport的手机验证码授权

## 安装

进入到你的项目目录，然后运行以下命令

```bash
composer require qiutuleng/laravel-passport-phone-verification-code-grant
```

## 设置

### Laravel

如果你的Laravel版本大于等于`5.5`，服务提供者将会自动注册到程序中。

其他版本你需要把`\QiuTuleng\PhoneVerificationCodeGrant\PhoneVerificationCodeGrantServiceProvider::class`添加到`config/app.php`中的`providers`属性中。

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

你可以在`app/Providers/AppServiceProvider.php`的`register`函数中添加以下代码注册服务提供者。

```php
$app->register(\QiuTuleng\PhoneVerificationCodeGrant\PhoneVerificationCodeGrantServiceProvider::class);
```

## 如何使用？

### 配置

1. 你必须在 `User` Model中实现 `\QiuTuleng\PhoneVerificationCodeGrant\Interfaces\PhoneVerificationCodeGrantUserInterface` 接口。

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

2. 在 `User` Model中实现接口定义的 `findOrNewForPassportVerifyCodeGrant` 方法和 `validateForPassportVerifyCodeGrant` 方法。

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

3. (可选) 你还可以在配置文件中重命名 `phone_number` 和 `verification_code` 字段:

   为此，请在“config/passport.php”中添加字段，例如：

   ```php
   //...
       'phone_verification' => [
           'phone_number_request_key' => 'phone',
           'verification_code_request_key' => 'verification_code',
       ],
   //...
   ```


### 请求Token

你可以使用`POST`方式访问`/oautn/token`接口来获取Token，具体请求参数参照以下代码。

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

## 更多

你可以访问 [Laravel/Passport](https://laravel.com/docs/master/passport) 官方文档来了解更多信息。

## 贡献

你可以在此仓库中创建一个 [pull requests](https://github.com/qiutuleng/vue-router-modern/pulls) 。

期待你的想法或代码。

## Issues

如果你有任何问题，请在 [Issues](https://github.com/qiutuleng/vue-router-modern/issues) 中提出，我将会尽力为你解决。
