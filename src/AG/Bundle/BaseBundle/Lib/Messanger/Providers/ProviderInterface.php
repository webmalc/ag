<?php

namespace AG\Bundle\BaseBundle\Lib\Messanger\Providers;

/**
 * ProviderInterface
 */
interface ProviderInterface
{
    /**
     * Send message
     * @return boolean
     */
    public function send();
    
    /**
     * Get provider name
     * @return string
     */
    public function getName();
    
    /**
     * Set recipient address
     * @param string $recipient
     * @return ProviderInterface
     */
    public function setRecipient($recipient);
    
    /**
     * Get recipient address
     * @return string
     */
    public function getRecipient();
    
    /**
     * Set message subject
     * @param string $subject
     * @return ProviderInterface
     */
    public function setSubject($subject);
    
    /**
     * Get message subject
     * @return string
     */
    public function getSubject();
    
    /**
     * Set message data
     * @param [] $data
     * @return ProviderInterface
     */
    public function setData(array $data);
    
    /**
     * Get message subject
     * @return []
     */
    public function getData();
    
    /**
     * Get message text
     * @return string
     */
    public function getText();
}
