<?php

namespace AG\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AG\Bundle\UserBundle\Document\User;

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
        return array('name' => $name);
    }
    
    /**
     * @Route("/registration", name="user_registration")
     * @Method("GET")
     * @Template()
     */
    public function registrationAction()
    {
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->createNotFoundException();
        }
        
        return array();
    }


    /**
     * @Route("/new", name="rest_user_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        if( $this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY') ){
            return $this->createNotFoundException();
        }
        
        $data = json_decode($request->getContent(), true);
        $translator = $this->container->get('translator');
        
        $response = new JsonResponse([
            'success' => false,
            'message'   => $translator->trans('register.error', [], 'AGUserBundle')
        ]);
        
        /* Get request username */
        if (empty($data['email'])) {
            
            return $response;
        }
        
        $user = new User();
        $password = $this->get('ag.helper')->getToken(6, true, 'lud');
        
        $user->setEmail(filter_var($data['email'], FILTER_SANITIZE_EMAIL))
             ->setPlainPassword($password)
             ->setEnabled(true)
        ;
        
        /* Validate user */
        $errors = $this->get('validator')->validate($user);
        
        if (count($errors) > 0) {

            foreach ($errors as $error) {
                if ($error->getPropertyPath()) {
                    $response->setData([
                        'success'   => false,
                        'message'   => $error->getMessage()
                    ]);
                } 
            }
            
            return $response;
        }
        
        $dm = $this->get('doctrine_mongodb')->getManager();
        $dm->persist($user);
        $dm->flush();
        
        /* Send messages */
        $message = ['content' => $translator->trans('register.message', ['%password%' => $password], 'AGUserBundle')];
        $messanger = $this->container->get('ag.service.messanger');
        $messanger->send($user, $message, $translator->trans('register.message_subject', [], 'AGUserBundle'), false);
        
        $response->setData([
            'success' => true,
            'message' => $user->getEmail()
        ]);
        
        $this->get('session')->getFlashBag()->add(
            'success',
            $translator->trans('register.success', [], 'AGUserBundle')
        );

        return $response;
    }
    
    /**
     * @Route("/", name="rest_user_list", options={"expose"=true})
     * @Method("GET")
     */
    public function listAction()
    {
        return new JsonResponse();
    }
}
