<?php

namespace AG\Bundle\UserBundle\Lib;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use AG\Bundle\CarBundle\Document\Car;

/**
 * Payy helper
 */
class Payy
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface 
     */
    private $container;
    
    /**
     *
     * @var string 
     */
    private $key;
    
    /**
     *
     * @var string 
     */
    private $shortPhone;
    
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->key = $container->getParameter('ag.payy.key');
        $this->shortPhone = $container->getParameter('ag.payy.short_phone');
    }
    
    public function send(Request $request)
    {
        $required = ['unique', 'country', 'operator', 'number', 'phone', 'message', 'hash'];
        
        $params = [];
        
        foreach ($required as $field) {
            $params[$field] = $request->get($field);
            
            if(empty($params[$field])) {
                throw new \Exception('Unable get POST param: ' . $field);
            }
        }
        if ($this->shortPhone != $params['number']) {
            throw new \Exception('Numbers not equals');
        }
        
        $hash = md5($params['unique'].$params['country'].$params['operator'].$params['number'].$params['phone'].$params['message'].$this->key);
        
        //var_dump($hash);
        
        if ($hash != $params['hash']) {
            throw new \Exception('Hashs not equals');
        }
        
        $carNumber = explode(' ', $params['message'])[0];
        $text = str_replace($carNumber. ' ', '', $params['message']);
        $translator = $this->container->get('translator');
        
        $car = $this->container
                    ->get('doctrine_mongodb')
                    ->getRepository('AGCarBundle:Car')
                    ->findOneBy(['number' => Car::cleanNumber($carNumber)]);
        
        if (empty($car)) {
            throw new \Exception('Car not found');
        }
        
        /* Send messages */
        $message = [
            'content' => $text
        ];
        $messanger = $this->container->get('ag.service.messanger');
        $result = $messanger->send(
                $car->getUser(), $message, $translator->trans('send_message.subject', [], 'AGCarBundle'), true, false, false
        );
        return $result; 
    }
}
