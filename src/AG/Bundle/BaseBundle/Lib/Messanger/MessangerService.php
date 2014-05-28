<?php

namespace AG\Bundle\BaseBundle\Lib\Messanger;

use AG\Bundle\UserBundle\Document\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * MessangerService
 */
class MessangerService
{

    /**
     * @var ContainerInterface 
     */
    private $container;

    /**
     * @var Messanger 
     */
    private $messanger;

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->messanger = new Messanger();
    }

    /**
     * Send messages to user 
     * @param \AG\Bundle\UserBundle\Document\User $user
     * @param string[] $data
     * @param string $subject message subject
     * @param string $sms send sms on/off
     * @return string[]
     */
    public function send(User $user, $data = null, $subject = null, $sms = false)
    {
        /* Email send */
        $emailProvider = $this->container->get('ag.messanger.providers.email');
        $emailProvider->setRecipient($user->getEmail())
                ->setSubject($subject)
                ->setData($data)
        ;

        $this->messanger->addProvider($emailProvider);

        /* SMS send */
        if ($sms && $user->getPhone()) {
            $smsProvider = $this->container->get('ag.messanger.providers.sms');
            $smsProvider->setRecipient($user->getPhone())
                    ->setSubject($subject)
                    ->setData($data)
            ;
            $this->messanger->addProvider($smsProvider);
        }

        $result = $this->messanger->send();

        return $result;
    }

    /**
     * Send sms to phone 
     * @param string[] $phone 79244538712
     * @param string[] $data
     * @param string $subject message subject
     * @return string[]
     */
    public function sendSms($phone, $data, $subject)
    {
        $smsProvider = $this->container->get('ag.messanger.providers.sms');
        $smsProvider->setRecipient($phone)
                ->setSubject($subject)
                ->setData($data)
        ;
        $this->messanger->addProvider($smsProvider);

        $result = $this->messanger->send();

        return $result;
    }

}
