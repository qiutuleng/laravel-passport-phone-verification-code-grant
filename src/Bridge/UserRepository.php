<?php

namespace QiuTuleng\PhoneVerificationCodeGrant\Bridge;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\Bridge\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use QiuTuleng\PhoneVerificationCodeGrant\Interfaces\PhoneVerificationCodeGrantUserInterface;
use RuntimeException;

class UserRepository implements UserRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function getUserEntityByUserCredentials($phoneNumber, $verificationCode, $grantType, ClientEntityInterface $clientEntity)
    {
        $provider = config('auth.guards.api.provider');

        if (is_null($model = config('auth.providers.' . $provider . '.model'))) {
            throw new RuntimeException('Unable to determine authentication model from configuration.');
        }

        /** @var Model $userInstance */
        $userInstance = new $model;
        if (!$userInstance instanceof PhoneVerificationCodeGrantUserInterface) {
            $interfaceName = PhoneVerificationCodeGrantUserInterface::class;
            throw OAuthServerException::serverError("Must needs implement `{$interfaceName}` interface in your `{$model}` model.");
        }

        /** @var PhoneVerificationCodeGrantUserInterface $user */
        $user = $userInstance->findOrCreateForPassportVerifyCodeGrant($phoneNumber);

        if (!$user || !$user->validateForPassportVerifyCodeGrant($verificationCode)) {
            return;
        }

        return new User($user->getAuthIdentifier());
    }
}
