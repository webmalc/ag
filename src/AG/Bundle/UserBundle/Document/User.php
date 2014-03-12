<?php

namespace AG\Bundle\UserBundle\Document;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @MongoDB\Document(collection="Users")
 * @Gedmo\Loggable
 * @UniqueEntity(fields="phone", message="Такой телефон уже зарегистрирован")
 */
class User extends BaseUser {

    /**
     * @var string
     * @MongoDB\Id
     */
    protected $id;

    /**
     * Phone number
     * @var string
     * @Gedmo\Versioned
     * @MongoDB\String(name="phone", type="string") @MongoDB\UniqueIndex(order="asc", sparse=true)
     * @Assert\NotNull()
     * @Assert\Length(
     *      min=11,
     *      minMessage="Некорректный мобильный номер. Например +7(925)123-45-67"
     * )
     */
    private $phone;

    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Set email & username
     * {@inheritdoc}
     */
    public function setEmail($email)
    {
        parent::setEmail($email);
        parent::setUsername($email);
    }

    /**
     * Set phone
     * @param string $phone
     * @return \AG\Bundle\UserBundle\Entity\User
     */
    public function setPhone($phone)
    {
        $this->phone = self::cleanPhone($phone);
        if(empty($this->phone)) {
            $this->phone = null;
        }
        return $this;
    }

    /**
     * Get phone
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Cleans phone number
     * @param string $phone
     * @return string
     */
    public static function cleanPhone($phone)
    {
        return preg_replace('/[^0-9]/', '', $phone);
    }

}
