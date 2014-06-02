<?php

namespace AG\Bundle\CarBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AG\Bundle\UserBundle\Document\User;

/**
 * Car search controller
 * @Route("/")
 */
class SearchController extends Controller
{
    /**
     * Car search layout
     * @Route("/", name="car_search_layout")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        /*$car = new \AG\Bundle\CarBundle\Document\Car();
        $car->setNumber('Й111ЫУ111')
            ->setUser($this->getUser())
        ;

            $dm = $this->get('doctrine_mongodb')->getManager();
            $dm->persist($car);
            $dm->flush();
        */    
        
        return [];
    }
    
}
