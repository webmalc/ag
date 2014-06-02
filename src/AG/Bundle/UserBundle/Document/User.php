<?php

namespace AG\Bundle\UserBundle\Document;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableDocument;

/**
 * @ODM\Document(collection="Users")
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @MongoDBUnique(fields="phone", message="Такой телефон уже зарегистрирован")
 * @MongoDBUnique(fields="email", message="Такой e-mail уже зарегистрирован")
 */
class User extends BaseUser
{
    /**
     * @var string
     * @ODM\Id
     */
    protected $id;
    
    /**
     * @var string
     * @Gedmo\Versioned
     * @ODM\String(name="firstName", type="string")
     * @Assert\Length(
     *      min=2,
     *      minMessage="Слишком короткое имя",
     *      max=100,
     *      maxMessage="Слишком длинное имя"
     * )
     */
    protected $firstName;
    
    /**
     * @var string
     * @Gedmo\Versioned
     * @ODM\String(name="lastName", type="string")
     * @Assert\Length(
     *      min=2,
     *      minMessage="Слишком короткая фамилия",
     *      max=100,
     *      maxMessage="Слишком длинная фамилия"
     * )
     */
    protected $lastName;
    
    /**
     * @var string
     * @Gedmo\Versioned
     * @ODM\String(name="birthday", type="date")
     * @Assert\DateTime()
     */
    protected $birthday;
    
    /**
     * @var string
     * @Gedmo\Versioned
     * @ODM\String(name="sex", type="string")
     * @Assert\Choice(choices = {"male", "female", "undefined"}, message = "Некорректный пол.")
     */
    protected $sex;
    
    /**
     * @var string
     * @Gedmo\Versioned
     * @ODM\String(name="city", type="string")
     * @Assert\Length(
     *      min=2,
     *      minMessage="Слишком короткое название города",
     *      max=100,
     *      maxMessage="Слишком длинное  название города"
     * )
     */
    protected $city;
    
    /**
     * @var string
     * @Gedmo\Versioned
     * @ODM\String(name="country", type="string")
     * @Assert\Length(
     *      min=2,
     *      minMessage="Слишком короткое название страны",
     *      max=100,
     *      maxMessage="Слишком длинное название страны"
     * )
     */
    protected $country;

    /**
     * Phone number
     * @var string
     * @Gedmo\Versioned
     * @ODM\String(name="phone", type="string")
     * @Assert\Length(
     *      min=11,
     *      minMessage="Некорректный мобильный номер. Например +7(925)123-45-67"
     * )
     */
    protected $phone;
    
    /** 
     * Social networks
     * @var Social[]
     * @ODM\EmbedMany(targetDocument="Social")
     */
    protected $socials = array();

    /** @ODM\ReferenceMany(targetDocument="AG\Bundle\CarBundle\Document\Car", mappedBy="user") */
    protected $cars;
    
    /** @ODM\ReferenceMany(targetDocument="Message", mappedBy="sender") */
    protected $sendMessages;
    
    /** @ODM\ReferenceMany(targetDocument="Message", mappedBy="recipient") */
    protected $getMessages;
    
    /**
     * Hook timestampable behavior
     * updates createdAt, updatedAt fields
     */
    use TimestampableDocument;
    
    /**
     * Hook softdeleteable behavior
     * deletedAt field
     */
    use SoftDeleteableDocument;
    
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
        
        return $this;
    }

    /**
     * Set phone
     * @param string $phone
     * @return \AG\Bundle\UserBundle\Entity\User
     */
    public function setPhone($phone)
    {
        $this->phone = self::cleanPhone($phone);
        if (empty($this->phone)) {
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
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        if(empty($phone)) return null;
        
        if(mb_strlen($phone) == 10) {
            $phone = '7' . $phone;
        }
        if($phone[0] == 8) {
            $phone[0] = '7';
        }
        
        return $phone;
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
     * Set firstName
     *
     * @param string $firstName
     * @return self
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Get firstName
     *
     * @return string $firstName
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     * @return self
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * Get lastName
     *
     * @return string $lastName
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set birthday
     *
     * @param date $birthday
     * @return self
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * Get birthday
     *
     * @return date $birthday
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * Set sex
     *
     * @param string $sex
     * @return self
     */
    public function setSex($sex)
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * Get sex
     *
     * @return string $sex
     */
    public function getSex()
    {
        return $this->sex;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return self
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param string $country
     * @return self
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return string $country
     */
    public function getCountry()
    {
        return $this->country;
    }
    
    /**
     * Add social
     *
     * @param AG\Bundle\UserBundle\Document\Social $social
     */
    public function addSocial(\AG\Bundle\UserBundle\Document\Social $social)
    {
        $this->socials[] = $social;
    }

    /**
     * Remove social
     *
     * @param AG\Bundle\UserBundle\Document\Social $social
     */
    public function removeSocial(\AG\Bundle\UserBundle\Document\Social $social)
    {
        $this->socials->removeElement($social);
    }

    /**
     * Get socials
     *
     * @return Doctrine\Common\Collections\Collection $socials
     */
    public function getSocials()
    {
        return $this->socials;
    }
    
    /**
     * Return user full name
     * @param boolean $full
     * @return string
     */
    public function getFullName($full = false)
    {
        $username = $this->email;
        
        if (!empty($this->firstName)) {
            
            $username = $this->firstName;
            
            if (!empty($this->lastName) && $full) {
                $username = $this->lastName . ' ' . $this->firstName;
            }
        }
        
        return $username;
    }

    /**
     * Add car
     *
     * @param AG\Bundle\CarBundle\Document\Car $car
     */
    public function addCar(\AG\Bundle\CarBundle\Document\Car $car)
    {
        $this->cars[] = $car;
    }

    /**
     * Remove car
     *
     * @param AG\Bundle\CarBundle\Document\Car $car
     */
    public function removeCar(\AG\Bundle\CarBundle\Document\Car $car)
    {
        $this->cars->removeElement($car);
    }

    /**
     * Get cars
     *
     * @return Doctrine\Common\Collections\Collection $cars
     */
    public function getCars()
    {
        return $this->cars;
    }

    /**
     * Add sendMessage
     *
     * @param AG\Bundle\UserBundle\Document\Message $sendMessage
     */
    public function addSendMessage(\AG\Bundle\UserBundle\Document\Message $sendMessage)
    {
        $this->sendMessages[] = $sendMessage;
    }

    /**
     * Remove sendMessage
     *
     * @param AG\Bundle\UserBundle\Document\Message $sendMessage
     */
    public function removeSendMessage(\AG\Bundle\UserBundle\Document\Message $sendMessage)
    {
        $this->sendMessages->removeElement($sendMessage);
    }

    /**
     * Get sendMessages
     *
     * @return Doctrine\Common\Collections\Collection $sendMessages
     */
    public function getSendMessages()
    {
        return $this->sendMessages;
    }

    /**
     * Add getMessage
     *
     * @param AG\Bundle\UserBundle\Document\Message $getMessage
     */
    public function addGetMessage(\AG\Bundle\UserBundle\Document\Message $getMessage)
    {
        $this->getMessages[] = $getMessage;
    }

    /**
     * Remove getMessage
     *
     * @param AG\Bundle\UserBundle\Document\Message $getMessage
     */
    public function removeGetMessage(\AG\Bundle\UserBundle\Document\Message $getMessage)
    {
        $this->getMessages->removeElement($getMessage);
    }

    /**
     * Get getMessages
     *
     * @return Doctrine\Common\Collections\Collection $getMessages
     */
    public function getGetMessages()
    {
        return $this->getMessages;
    }
}
