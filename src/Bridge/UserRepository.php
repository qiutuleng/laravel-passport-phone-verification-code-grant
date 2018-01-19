<?php

namespace QiuTuleng\PhoneVerificationCodeGrant\Bridge;

use Laravel\Passport\Bridge\User;
use League\OAuth2\Server\Exception\OAuthServerException;
use RuntimeException;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials($phoneNumber, $code, $grantType, ClientEntityInterface $clientEntity)
    {
        $provider = config('auth.guards.api.provider');

        if (is_null($model = config('auth.providers.'.$provider.'.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }

        if (method_exists($model, 'findForPassport')) {
            $user = (new $model)->findForPassport($phoneNumber);
        } else {
            $user = (new $model)->where('email', $phoneNumber)->first();
        }

        if (! $user) {
            return;
        } elseif (method_exists($user, 'validateForPassportVerifyCodeGrant')) {
            if (! $user->validateForPassportVerifyCodeGrant($code)) {
                return;
            }
        } else {
            throw OAuthServerException::serverError("Method [validateForPassportVerifyCodeGrant] does not exist on {$model} class");
        }

        return new User($user->getAuthIdentifier());
    }
}
