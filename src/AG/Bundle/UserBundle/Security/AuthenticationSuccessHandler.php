<?php
namespace AG\Bundle\UserBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationSuccessHandler;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\Router;

/**
 * Custom AuthenticationSuccessHandler
 */
class AuthenticationSuccessHandler extends DefaultAuthenticationSuccessHandler
{
    /**
     * @var Session
     */
    private $session;
    /**
     * @var Router
     */
    private $router;

    /**
     * {@inheritdoc}
     */
    public function __construct(HttpUtils $httpUtils, array $options, Session $session, Router $router)
    {
        $this->session = $session;
        $this->router = $router;
        parent::__construct($httpUtils, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        if ($request->isXmlHttpRequest()) {
            $path = $this->session->get('_security.main.target_path');
            if (!$path) {
                $path = $this->router->generate('home');
            }
            $response = new JsonResponse(
                [
                    'success' => true,
                    'path' => $path
                ]
            );
        } else {
            $response = parent::onAuthenticationSuccess($request, $token);
        }
        return $response;
    }
}
