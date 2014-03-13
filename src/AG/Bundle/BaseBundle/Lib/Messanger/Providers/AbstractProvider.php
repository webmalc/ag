<?php

namespace AG\Bundle\BaseBundle\Lib\Messanger\Providers;

abstract class AbstractProvider implements ProviderInterface
{
    /**
     * Name of provider
     */
    const NAME = 'provider';
    
    /**
     * Recipient address
     * @var string 
     */
    private $recipient = null;
    
    /**
     * Message subject
     * @var string 
     */
    private $subject = 'Сообщение от AutoGRU';
    
    /**
     * Message data
     * @var string 
     */
    private $data = [];
    
    /**
     * {@inheridoc}
     */
    public function getName()
    {
        return static::NAME;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getRecipient()
    {
        return $this->recipient;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setSubject($subject)
    {
        if (!empty($subject)) {
            $this->subject = $subject;
        }
        
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->subject;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        return $this->data;
    }
    
    /**
     * {@inheritdoc}
     */
    public function send()
    {
        if (!$this->getRecipient()) {
            throw new \Exception('Не указан адрес получателя');
        }
    }
}
