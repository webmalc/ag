<?php

namespace AG\Bundle\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 * @UniqueEntity(fields="phone", message="Такой телефон уже зарегистрирован")
 */
class User extends BaseUser {

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Phone number
     * @var string
     * @ORM\Column(name="phone", type="string", length=11)
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
     * Set phone
     * @param string $phone
     * @return \AG\Bundle\UserBundle\Entity\User
     */
    public function setPhone($phone)
    {
        $this->phone = self::cleanPhone($phone);
        $this->username = $this->phone;
        $this->usernameCanonical = $this->phone;

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
     * Set username
     * @param string $username
     * @return \AG\Bundle\UserBundle\Entity\User
     */
    public function setUsername($username)
    {
        $this->username = self::cleanPhone($username);
        $this->phone = $this->username;

        return $this;
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
