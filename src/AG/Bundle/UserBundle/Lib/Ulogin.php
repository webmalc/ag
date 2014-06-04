<?php

namespace AG\Bundle\UserBundle\Lib;

use AG\Bundle\UserBundle\Document\User;
use AG\Bundle\UserBundle\Document\Social;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

/**
 * Ulogin helper
 */
class Ulogin
{
    const URL = 'http://ulogin.ru/token.php?host=autog.ru&token=';
    
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager 
     */
    private $dm;
    
    /** 
     * @var \AG\Bundle\UserBundle\Security\EmailUserProvider 
     */
    private $userProvider;
    
    /**
     * @var \AG\Bundle\BaseBundle\Lib\Helper 
     */
    private $helper;
    
    /**
     * @var \Symfony\Component\Validator\Validator 
     */
    private $validator;
    
    /**
     * @var \Symfony\Bundle\FrameworkBundle\Translation\Translator 
     */
    private $translator;
    
    /**
     *
     * @var \AG\Bundle\BaseBundle\Lib\Messanger\MessangerService 
     */
    private $messanger;
    
    /**
     *
     * @var \Symfony\Component\HttpFoundation\Session\Session 
     */
    private $session;
    
    /**
     * @var \Symfony\Component\Security\Core\SecurityContext 
     */
    private $security;

    /**
     * Constructor
     * @param \Doctrine\ODM\MongoDB\DocumentManager $dm
     */
    public function __construct(ContainerInterface $container)
    {
        $this->dm = $container->get('doctrine_mongodb')->getManager();
        $this->userProvider = $container->get('ag.user_provider.username_email');
        $this->helper = $container->get('ag.helper');
        $this->validator = $container->get('validator');
        $this->translator = $container->get('translator');
        $this->messanger = $container->get('ag.service.messanger');
        $this->security = $container->get('security.context');
        $this->session = $container->get('session');
    }
    
    /**
     * Login user by ulogin
     * @param string $token
     * @param string $host
     * @throws \Exception
     * @return string 
     */
    public function auth($token, $host)
    {
        $request = curl_init(self::URL . $token . '&host=' . $host);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($request);

        $data = json_decode($result, true);
        
        if (empty($data) || !empty($data['error']) || empty($data['email']) || !$data['verified_email']) {
            throw new \Exception('Unable get data from ulogin.');
        }
        
        $user = $this->userProvider->findUser($data['email']);
        
        if (!$user) {
            $this->addUser($data);
            
            return 'new';
        } else {
            $this->updateUser($user, $data);
            
            return 'old';
        }
    }
    
    /**
     * Set data to user from ulogin
     * @param \AG\Bundle\UserBundle\Document\User $user
     * @param array $data
     * @return \AG\Bundle\UserBundle\Document\User
     */
    private function setData(User $user, $data)
    {
        if (!empty($data['first_name']) && !$user->getFirstName()) {
            $user->setFirstName(filter_var($data['first_name'], FILTER_SANITIZE_STRING));
        }
        
        if (!empty($data['last_name']) && !$user->getLastName()) {
            $user->setLastName(filter_var($data['last_name'], FILTER_SANITIZE_STRING));
        }
        
        if (!empty($data['bdate']) && !$user->getBirthday()) {
            $birthday = \DateTime::createFromFormat('d.m.Y', $data['bdate']);
            
            if ($birthday) {
                $user->setBirthday($birthday);
            }
        }
        
        if (!empty($data['sex']) && !$user->getSex()) {
            switch ($data['sex']) {
                case 1:
                    $user->setSex('female');
                    break;
                case 2:
                    $user->setSex('male');
                    break;
                default:
                    $user->setSex('undefined');
            }
        }
        
        if (!empty($data['city']) && !$user->getCity()) {
            $user->setCity(filter_var($data['city'], FILTER_SANITIZE_STRING));
        }
        
        if (!empty($data['country']) && !$user->getCountry()) {
            $user->setCountry(filter_var($data['country'], FILTER_SANITIZE_STRING));
        }
        
        $social = new Social();
        
        if (!empty($data['network'])) {
            $social->setNetwork(filter_var($data['network'], FILTER_SANITIZE_STRING));
        }
        
        if (!empty($data['profile'])) {
            $social->setProfile(filter_var($data['profile'], FILTER_SANITIZE_STRING));
        }
        
        if (!empty($data['uid'])) {
            $social->setUid(filter_var($data['uid'], FILTER_SANITIZE_STRING));
        }
        
        if (!empty($data['identity'])) {
            $social->setIdentity(filter_var($data['identity'], FILTER_SANITIZE_STRING));
        }
        
        $user->setLastLogin(new \DateTime());
        
        foreach ($user->getSocials() as $userSocial) {
            if($userSocial->getNetwork() == $social->getNetwork()) {
                return $user;
            }
        }
        $user->addSocial($social);
        
        return $user;
    }
    
    /**
     * Update user & login
     * @param \AG\Bundle\UserBundle\Document\User $user
     * @param array $data
     */
    private function updateUser(User $user, array $data)
    {
        $this->setData($user, $data);
        $this->dm->persist($user);
        $this->dm->flush();
        
        $this->setSession($user);
    }

    /**
     * Create new user & login
     * @param array $data
     * @throws \Exception
     */
    private function addUser(array $data)
    {
        $password = $this->helper->getToken(6, true, 'lud');
        $user = new User();
        
        $user->setEmail(filter_var($data['email'], FILTER_SANITIZE_EMAIL))
             ->setPlainPassword($password)
             ->setEnabled(true)
        ;
        $this->setData($user, $data);
        
        /* Validate user */
        $errors = $this->validator->validate($user);
        
        if (count($errors) > 0) {

            throw new \Exception('Unable to save user. Errors: ' . (string) $errors);
        }
        
        $this->dm->persist($user);
        $this->dm->flush();
        
        /* Send messages */
        $message = [
            'content' => $this->translator->trans('register.message', ['%password%' => $password], 'AGUserBundle')
        ];
        $this->messanger->send(
            $user,
            $message,
            $this->translator->trans('register.message_subject', [], 'AGUserBundle'),
            false
        );
        
        $this->setSession($user);
    }
    
    /**
     * Set user session
     * @param \AG\Bundle\UserBundle\Document\User $user
     */
    private function setSession(User $user)
    {
        $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
        $this->security->setToken($token);
        $this->session->set('_security_main', serialize($token));
    }
}
