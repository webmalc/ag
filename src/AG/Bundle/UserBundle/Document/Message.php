<?php

namespace AG\Bundle\UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="Messages")
 */
class Message
{

    /**
     * @var string
     * @ODM\Id
     */
    private $id;

    /**
     * @var string
     * @ODM\String(name="ip", type="string")
     */
    private $ip;

    /**
     * @var string
     * @ODM\String(name="subject", type="string")
     */
    private $subject;
    
    /**
     * @var string
     * @ODM\String(name="message", type="string")
     */
    private $message;

    /**
     * @var \DateTime
     * @ODM\Date
     */
    private $date;
    
    /** @ODM\ReferenceOne(targetDocument="AG\Bundle\UserBundle\Document\User", inversedBy="sendMessages") */
    private $sender;
    
    /** @ODM\ReferenceOne(targetDocument="AG\Bundle\UserBundle\Document\User", inversedBy="getMessages") */
    private $recipient;

    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ip
     *
     * @param string $ip
     * @return self
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        return $this;
    }

    /**
     * Get ip
     *
     * @return string $ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set subject
     *
     * @param string $subject
     * @return self
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * Get subject
     *
     * @return string $subject
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return self
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Get message
     *
     * @return string $message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set date
     *
     * @param date $date
     * @return self
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * Get date
     *
     * @return date $date
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set sender
     *
     * @param AG\Bundle\UserBundle\Document\User $sender
     * @return self
     */
    public function setSender(\AG\Bundle\UserBundle\Document\User $sender)
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * Get sender
     *
     * @return AG\Bundle\UserBundle\Document\User $sender
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * Set recipient
     *
     * @param AG\Bundle\UserBundle\Document\User $recipient
     * @return self
     */
    public function setRecipient(\AG\Bundle\UserBundle\Document\User $recipient)
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * Get recipient
     *
     * @return AG\Bundle\UserBundle\Document\User $recipient
     */
    public function getRecipient()
    {
        return $this->recipient;
    }
}
