<?php

namespace AG\Bundle\UserBundle\Security;

use FOS\UserBundle\Security\EmailUserProvider as Base;
use AG\Bundle\UserBundle\Document\User;

class EmailUserProvider extends Base
{
    /**
     * {@inheritDoc}
     */
    public function findUser($username)
    {
        $user = parent::findUser($username);
        if ($user == null) {
            
            $phone =  User::cleanPhone($username);
            
            if(!empty($phone)) {
                return $this->userManager->findUserBy(['phone' => User::cleanPhone($phone)]);
            } 
            return null;
        }
        return $user;
    }
}
