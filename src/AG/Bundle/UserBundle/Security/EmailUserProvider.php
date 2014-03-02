<?php

namespace AG\Bundle\UserBundle\Security;

use FOS\UserBundle\Security\EmailUserProvider as Base;
use AG\Bundle\UserBundle\Entity\User;

class EmailUserProvider extends Base
{
    /**
     * {@inheritDoc}
     */
    protected function findUser($username)
    {
        $user = parent::findUser($username);
        if ($user == null) {
            return parent::findUser(User::cleanPhone($username));
        }
        return $user;
    }
}
