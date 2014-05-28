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
 * User profile controller
 * @Route("/profile")
 */
class ProfileController extends Controller
{

    /**
     * User profile layout
     * @Route("/", name="user_profile")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * User profile phone modal
     * @Route("/modal/phone", name="user_profile_phone_modal", options={"expose"=true})
     * @Method("GET")
     * @Template()
     */
    public function phoneModalAction()
    {
        return [];
    }

    /**
     * User phone check (send sms)
     * @Route("/phone/check", name="user_profile_phone_check")
     * @Method("POST")
     */
    public function phoneCheckAction(Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createNotFoundException();
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data['tmpPhone'])) {
            return new JsonResponse([
                'success' => false,
            ]);
        }

        /* Send sms */
        $session = $request->getSession();
        $session->remove('phone_confirmation');
        $phone = User::cleanPhone($data['tmpPhone']);
        
        $code = '123456789';
        
        if($this->container->getParameter("kernel.environment") == 'prod') {
            $code = rand(1000, 9999);
        }
        
        $translator = $this->container->get('translator');
        $message = [
            'content' => $translator->trans(
                    'phone_ckeck.message', ['%code%' => $code], 'AGUserBundle')
        ];
        $messanger = $this->container->get('ag.service.messanger');
        $result = $messanger->sendSms(
                $phone, $message, $translator->trans('phone_ckeck.subject', [], 'AGUserBundle')
        );
        
        if (!empty($result['sms']['success'])) {
            $session->set('phone_confirmation', [
                'phone' => $phone, 'code' => $code, 'user_id' => $this->getUser()->getId()
            ]);
        }
        
        return new JsonResponse(['success' => true]);
    }

    /**
     * User phone set
     * @Route("/phone/set", name="user_profile_phone_set")
     * @Method("POST")
     */
    public function phoneSetAction(Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createNotFoundException();
        }

        $data = json_decode($request->getContent(), true);

        $session = $request->getSession();
        $userData = $session->get('phone_confirmation');
        
        if (empty($data['code']) || empty($userData)) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Упс! Что-то не так.'
            ]);
        }
        
        $code = $data['code'];
        $user = $this->getUser();
        
        if ($code != $userData['code'] || $user->getId() != $userData['user_id'] || empty($userData['phone'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Упс! Код не совпадает.'
            ]);
        }
        
        $user->setPhone($userData['phone']);
        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($user);
        $session->remove('phone_confirmation');
        
        return new JsonResponse([
            'success' => true,
            'message' => 'Ура! Телефон сохранен успешно',
            'phone' => $user->getPhone(),
        ]);
    }
    
}
