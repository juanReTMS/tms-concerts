<?php

namespace App\Security;

use App\Entity\Person as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class PersonConfirmedChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
        if (!$user->getConfirmed()) {
            throw new CustomUserMessageAuthenticationException("Please confirm your account ...");
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
    }
}