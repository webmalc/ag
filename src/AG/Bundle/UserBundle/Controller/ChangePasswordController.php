<?php

namespace AG\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Change password controller
 * @Route("/resetting")
 */
class ChangePasswordController extends Controller
{
    /**
     * Show reset form
     * @Route("/request", name="password_resseting_request")
     * @Method("GET")
     * @Template()
     */
    public function requestAction()
    {
        return array();
    }
}
