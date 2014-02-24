<?php

namespace AG\Bundle\UserBundle;

use FOS\UserBundle\FOSUserBundle;

class AGUserBundle extends FOSUserBundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
