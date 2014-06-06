<?php

namespace AG\Bundle\UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document(collection="Statistics")
 */
class Statistics
{
   /**
     * @var string
     * @ODM\Id
     */
    protected $id;
    
    /**
     * @var int
     * @ODM\Int(name="carsTotal", type="int")
     */
    protected $carsTotal;
    
    /**
     * @var int
     * @ODM\Int(name="carsToday;", type="int")
     */
    protected $carsToday;
    
    /**
     * @var int
     * @ODM\Int(name="messagesTotal", type="int")
     */
    protected $messagesTotal;
    
    /**
     * @var int
     * @ODM\Int(name="messagesToday", type="int")
     */
    protected $messagesToday;
    
    /**
     * @param int $carsTotal
     * @param int $carsToday
     * @param int $messagesTotal
     * @param int $messagesToday
     */
    public function __construct($carsTotal, $carsToday, $messagesTotal, $messagesToday)
    {
        $this->carsTotal = $carsTotal;
        $this->carsToday = $carsToday;
        $this->messagesTotal = $messagesTotal;
        $this->messagesToday = $messagesToday;
    }

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
     * Set carsTotal
     *
     * @param int $carsTotal
     * @return self
     */
    public function setCarsTotal($carsTotal)
    {
        $this->carsTotal = $carsTotal;
        return $this;
    }

    /**
     * Get carsTotal
     *
     * @return int $carsTotal
     */
    public function getCarsTotal()
    {
        return $this->carsTotal;
    }

    /**
     * Set carsToday
     *
     * @param int $carsToday
     * @return self
     */
    public function setCarsToday($carsToday)
    {
        $this->carsToday = $carsToday;
        return $this;
    }

    /**
     * Get carsToday
     *
     * @return int $carsToday
     */
    public function getCarsToday()
    {
        return $this->carsToday;
    }

    /**
     * Set messagesTotal
     *
     * @param int $messagesTotal
     * @return self
     */
    public function setMessagesTotal($messagesTotal)
    {
        $this->messagesTotal = $messagesTotal;
        return $this;
    }

    /**
     * Get messagesTotal
     *
     * @return int $messagesTotal
     */
    public function getMessagesTotal()
    {
        return $this->messagesTotal;
    }

    /**
     * Set messagesToday
     *
     * @param int $messagesToday
     * @return self
     */
    public function setMessagesToday($messagesToday)
    {
        $this->messagesToday = $messagesToday;
        return $this;
    }

    /**
     * Get messagesToday
     *
     * @return int $messagesToday
     */
    public function getMessagesToday()
    {
        return $this->messagesToday;
    }
    
    /**
     * @param int $num
     * @return self
     */
    public function addCarsToday($num)
    {
        $this->carsToday +=$num;
        $this->carsTotal +=$num;
        
        return $this;
    }
    
    /**
     * @param int $num
     * @return self
     */
    public function addMessagesToday($num)
    {
        $this->messagesToday +=$num;
        $this->messagesTotal +=$num;
        
        return $this;
    }
}
