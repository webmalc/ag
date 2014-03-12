<?php

namespace AG\Bundle\UserBundle\Security;

use FOS\UserBundle\Security\EmailUserProvider as Base;
use AG\Bundle\UserBundle\Document\User;

class EmailUserProvider extends Base
{
    /**
     * {@inheritDoc}
     */
    protected function findUser($username)
    {
        $user = parent::findUser($username);
        if ($user == null) {
            return $this->userManager->findUserBy(['phone' => User::cleanPhone($username)]);
        }
        return $user;
    }
}
