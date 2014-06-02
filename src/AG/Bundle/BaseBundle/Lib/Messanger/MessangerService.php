<?php

namespace AG\Bundle\BaseBundle\Lib\Messanger;

use AG\Bundle\UserBundle\Document\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AG\Bundle\UserBundle\Document\Message;

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
        if ($sms && $user->getPhone() && $this->checkIp()) {
            $smsProvider = $this->container->get('ag.messanger.providers.sms');
            $smsProvider->setRecipient($user->getPhone())
                    ->setSubject($subject)
                    ->setData($data)
            ;
            $this->messanger->addProvider($smsProvider);
            
            $this->addMessage($user, $data, $subject);
        }

        $result = $this->messanger->send();

        return $result;
    }
    
    public function checkIp()
    {
        $dm = $this->container->get('doctrine_mongodb')->getManager();
        $message = $dm->createQueryBuilder('AGUserBundle:Message')
            ->field('ip')->equals($this->container->get('request')->getClientIp())
            ->sort('date', 'desc')
            ->limit(1)
            ->getQuery()
            ->getSingleResult()
        ;
        if (!$message) {
            return true;
        }
        
        $interval = $message->getDate()->diff(new \DateTime());
        
        if ($interval->i < $this->container->getParameter('max.sms.interval')) {
            return false;
        }
        
        return true;
    }


    /**
     * Log message sending
     * @param \AG\Bundle\UserBundle\Document\User $user
     * @param string[] $data
     * @param string $subject
     */
    public function addMessage(User $user, $data = null, $subject = null)
    {
        $security = $this->container->get('security.context');
        $dm = $this->container->get('doctrine_mongodb')->getManager();
        
        $message = new Message();
        
        $message->setDate(new \DateTime())
                ->setMessage((empty($data['content'])) ? null : $data['content'])
                ->setSubject($subject)
                ->setRecipient($user)
                ->setIp($this->container->get('request')->getClientIp())
        ;
        
        if ($security->isGranted('IS_AUTHENTICATED_FULLY')) {
            $message->setSender($security->getToken()->getUser());
        }
        
        $dm->persist($message);
        $dm->flush();
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
