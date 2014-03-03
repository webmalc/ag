<?php

namespace AG\Bundle\UserBundle\Security;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\DefaultAuthenticationFailureHandler;
use Symfony\Component\Security\Http\HttpUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\Translator;

class AuthenticationFailureHandler extends DefaultAuthenticationFailureHandler
{
    private $translator = null;
    
    /**
     * {@inheritdoc}
     */
    public function __construct(
        HttpKernelInterface $httpKernel,
        HttpUtils $httpUtils,
        array $options,
        LoggerInterface $logger = null,
        Translator $translator = null
    ) {
        $this->translator = $translator;
        parent::__construct($httpKernel, $httpUtils, $options, $logger);
    }

    /**
     * {@inheritdoc}
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        if ($request->isXmlHttpRequest()) {
            
            $message = $exception->getMessage();
            
            if($this->translator) {
                $message = $this->translator->trans($message, [], 'FOSUserBundle');
            }
            
            $response = new JsonResponse(
                [
                    'success' => false,
                    'message' => $message
                ]
            );
        } else {
            $response = parent::onAuthenticationFailure($request, $exception);
        }
        return $response;
    }
}
