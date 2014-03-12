<?php

namespace AG\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * User controller
 * @Route("/")
 */
class UserController extends Controller
{
    /**
     * @Route("/hello/{name}")
     * @Template()
     */
    public function indexAction($name)
    {
        $manager = $this->get('doctrine_mongodb')->getManager();
        $user = $this->getUser();
        $user->setPhone('8 925 317-28-78');
        //$manager->persist($user);
        //$manager->flush();
        
        return array('name' => $name);
    }
}
