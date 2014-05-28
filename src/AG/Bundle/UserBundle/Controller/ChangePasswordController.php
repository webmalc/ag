<?php

namespace AG\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Change password controller
 * @Route("/password")
 */
class ChangePasswordController extends Controller
{
    /**
     * New password action
     * @Route("/change", name="rest_password_change")
     * @Method("POST")
     */
    public function newAction(Request $request)
    {
        if (!$this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createNotFoundException();
        }
        $translator = $this->container->get('translator');
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (empty($data['password']) || empty($user) || mb_strlen($data['password'], 'utf-8') < 6) {
            return new JsonResponse([
                'success' => false,
                'message' => $translator->trans('new.password.error', [], 'AGUserBundle')
            ]);
        }
        
        $user->setPlainPassword($data['password']);

        /* Validate user */
        $errors = $this->get('validator')->validate($user);

        if (count($errors) > 0) {

            return new JsonResponse([
                'success' => false,
                'message' => (string) $errors
            ]);
        }

        $userManager = $this->container->get('fos_user.user_manager');
        $userManager->updateUser($user);

        return new JsonResponse([
                'success' => true,
                'message' => 'Ура! Пароль успешно изменен',
            ]);
    }
    
    /**
     * Reset password action
     * @Route("/reset", name="password_resseting_request", options={"expose"=true})
     * @Method("POST")
     */
    public function requestAction(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $response = new JsonResponse();
        $translator = $this->container->get('translator');
        
        $response->setData([
            'success' => false,
            'message' => $translator->trans('remind_password.error', [], 'AGUserBundle')
        ]);
        
        /* Get request username */
        if (empty($data['_username'])) {
            return $response;
        }
        
        /* Get user form database */
        /* @var $user \AG\Bundle\UserBundle\Document\User */
        $user = $this->container
                     ->get('ag.user_provider.username_email')
                     ->findUser($data['_username']);
        if (!$user) {
            return $response;
        }

        /* Save user */
        $password = $this->get('ag.helper')->getToken(6, true, 'lud');
        $user->setPlainPassword($password);
        $this->container->get('fos_user.user_manager')->updateUser($user);
        
        /* Send messages */
        $message = ['content' => $translator->trans('remind_password.message', ['%password%' => $password], 'AGUserBundle')];
        $messanger = $this->container->get('ag.service.messanger');
        $result = $messanger->send($user, $message, $translator->trans('remind_password.message_subject', [], 'AGUserBundle'), true);
        
        foreach ($result as $entry) {
            if ($entry['success']) {
                $response->setData([
                    'success' => true,
                    'message' => $translator->trans('remind_password.success', [], 'AGUserBundle')
                ]);
                break;
            }
        }
        
        return $response;
    }
}
