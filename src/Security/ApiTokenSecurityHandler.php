<?php

namespace App\Security;

use App\Repository\ApiTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class ApiTokenSecurityHandler implements AccessTokenHandlerInterface
{
    public function __construct(private ApiTokenRepository $tokenRepository){}


    /**
     * @inheritDoc
     */
    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        $token = $this->tokenRepository->findOneByToken($accessToken);

        if(! $token){
            throw new BadCredentialsException();
        }

        return new UserBadge($token->getUser()->getUserIdentifier());

    }
}