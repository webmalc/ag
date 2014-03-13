<?php

namespace AG\Bundle\BaseBundle\Lib\Messanger\Providers;

/**
 * Send message by email
 */
class EmailProvider extends AbstractProvider
{
    /**
     * Name of provider
     */
    const NAME = 'email';
    
    /**
     * From email
     */
    const FROM = 'robot@autog.ru';
    
    /**
     * @var \Swift_Mailer 
     */
    private $mailer;
    
    /**
     * @var Twig_Environment 
     */
    private $twig;
    
    /**
     * Twig template name
     * @var string 
     */
    private $template = 'AGBaseBundle:Messanger:baseEmail.html.twig';

    /**
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
    }
    
    /**
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
    
    /**
     * @param string $template
     * @return EmailProvider
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getText()
    {
        $data = array_merge($this->getData(), ['subject' => $this->getSubject()]);
        return $this->twig->render($this->getTemplate(), $data);
    }
    
    /**
     * {@inheridoc}
     */
    public function send()
    {
        parent::send();
        
        $message = \Swift_Message::newInstance();
        $message->setSubject($this->getSubject())
                ->setFrom(self::FROM)
                ->setTo($this->getRecipient())
                ->setBody($this->getText(), 'text/html')
        ;
        $this->mailer->send($message);
        
        return true;
    }
}
