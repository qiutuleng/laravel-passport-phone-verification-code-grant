<?php

namespace QiuTuleng\PhoneVerificationCodeGrant\Bridge;

use Laravel\Passport\Bridge\User;
use League\OAuth2\Server\Exception\OAuthServerException;
use QiuTuleng\PhoneVerificationCodeGrant\Interfaces\PhoneVerificationCodeGrantUserInterface;
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

        if (is_null($model = config('auth.providers.' . $provider . '.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }

        $userModel = new $model;
        if (!$userModel instanceof PhoneVerificationCodeGrantUserInterface) {
            $interfaceName = PhoneVerificationCodeGrantUserInterface::class;
            throw OAuthServerException::serverError("{$model} class must implement the {$interfaceName} interface");
        }

        $user = (new $model)->findOrNewForPassportVerifyCodeGrant($phoneNumber);

        if (!$user || !$user->validateForPassportVerifyCodeGrant($code)) {
            return;
        }

        return new User($user->getAuthIdentifier());
    }
}
