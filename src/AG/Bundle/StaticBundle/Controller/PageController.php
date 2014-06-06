<?php

namespace AG\Bundle\StaticBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Page controller
 * @Route("/page")
 */
class PageController extends Controller
{
    /**
     * @Route("/{name}", name="page")
     * @Method("GET")
     */
    public function pagesAction($name)
    {
        try {
            return $this->render(
                'AGStaticBundle:Page:' . $name . '.html.twig'
            );
        } catch (\Exception $e) {
            throw $this->createNotFoundException();
        }
    }
}
