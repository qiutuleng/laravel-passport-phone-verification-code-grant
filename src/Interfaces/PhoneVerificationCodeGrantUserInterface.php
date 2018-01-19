<?php

namespace QiuTuleng\PhoneVerificationCodeGrant\Interfaces;

interface PhoneVerificationCodeGrantUserInterface
{
    public function findOrNewForPassportVerifyCodeGrant($phoneNumber);
    public function validateForPassportVerifyCodeGrant($verificationCode);
}
