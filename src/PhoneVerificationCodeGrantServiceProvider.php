<?php

namespace QiuTuleng\PhoneVerificationCodeGrant;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;
use QiuTuleng\PhoneVerificationCodeGrant\Bridge\UserRepository;

class PhoneVerificationCodeGrantServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->resolving(AuthorizationServer::class, function (AuthorizationServer $server) {
            $server->enableGrantType($this->makeVerificationCodeGrant(), Passport::tokensExpireIn());
        });
    }

    protected function makeVerificationCodeGrant()
    {
        $grant = new PhoneVerificationCodeGrant(
            $this->app->make(UserRepository::class),
            $this->app->make(RefreshTokenRepository::class)
        );

        $grant->setRefreshTokenTTL(Passport::refreshTokensExpireIn());

        return $grant;
    }
}
