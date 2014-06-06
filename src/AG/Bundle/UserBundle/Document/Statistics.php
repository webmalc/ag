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
     * @ODM\Int(name="horsepower", type="int")
     */
    protected $carsTotal;
    
    /**
     * @var int
     * @ODM\Int(name="horsepower", type="int")
     */
    protected $carsToday;
    
    /**
     * @var int
     * @ODM\Int(name="horsepower", type="int")
     */
    protected $messagesTotal;
    
    /**
     * @var int
     * @ODM\Int(name="horsepower", type="int")
     */
    protected $messagesToday;
}
