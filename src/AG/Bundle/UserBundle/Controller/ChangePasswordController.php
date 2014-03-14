<?php

namespace AG\Bundle\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Change password controller
 * @Route("/resetting")
 */
class ChangePasswordController extends Controller
{
    /**
     * Reset password action
     * @Route("/request", name="password_resseting_request", options={"expose"=true})
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
