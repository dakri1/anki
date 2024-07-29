<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

class AccessTokenHandler implements AccessTokenHandlerInterface
{

    public function __construct(private readonly UserRepository $userRepository)
    {
    }


    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {

        $user = $this->userRepository->findOneBy(['token' => $accessToken]);

        if (!$user instanceof User) {
            throw new BadCredentialsException('Invalid creds');
        }

        return new UserBadge($user->getUserIdentifier());
    }

}