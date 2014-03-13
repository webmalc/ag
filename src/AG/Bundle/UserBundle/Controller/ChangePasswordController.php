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
        $response->setData([
            'success' => false,
            'message' => 'Что-то неправильно'
        ]);
        
        if (empty($data['_username'])) {
            return $response;
        }
        
        $user = $this->container
                     ->get('ag.user_provider.username_email')
                     ->findUser($data['_username']);
        
        if (!$user) {
            return $response;
        }
        
        $message = ['content' => 'Ваш новый пароль: 123456'];
        $messanger = $this->container->get('ag.service.messanger');
        $result = $messanger->send($user, $message, 'Смена пароля', true);

        $response->setData([
            'success' => true,
            'message' => 'Новый пароль выслан вам'
        ]);
        
        return $response;
    }
}
