<?php

namespace AG\Bundle\UserBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;

/**
 * @ODM\EmbeddedDocument
 */
class Social
{
    /**
     * @var string
     * @ODM\String(name="network", type="string")
     */
    protected $network;
    
    /**
     * @var string
     * @ODM\String(name="profile", type="string")
     */
    protected $profile;
    
    /**
     * @var string
     * @ODM\String(name="uid", type="string")
     */
    protected $uid;
    
    /**
     * @var string
     * @ODM\String(name="identity", type="string")
     */
    protected $identity;
    
    

    /**
     * Set network
     *
     * @param string $network
     * @return self
     */
    public function setNetwork($network)
    {
        $this->network = $network;
        return $this;
    }

    /**
     * Get network
     *
     * @return string $network
     */
    public function getNetwork()
    {
        return $this->network;
    }

    /**
     * Set profile
     *
     * @param string $profile
     * @return self
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;
        return $this;
    }

    /**
     * Get profile
     *
     * @return string $profile
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set uid
     *
     * @param string $uid
     * @return self
     */
    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * Get uid
     *
     * @return string $uid
     */
    public function getUid()
    {
        return $this->uid;
    }

    /**
     * Set identity
     *
     * @param string $identity
     * @return self
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * Get identity
     *
     * @return string $identity
     */
    public function getIdentity()
    {
        return $this->identity;
    }
}
