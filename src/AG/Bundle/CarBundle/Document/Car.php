<?php

namespace AG\Bundle\CarBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Bundle\MongoDBBundle\Validator\Constraints\Unique as MongoDBUnique;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableDocument;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableDocument;

/**
 * @ODM\Document(collection="Cars")
 * @Gedmo\Loggable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt", timeAware=false)
 * @MongoDBUnique(fields="number", message="Такой номер уже зарегистрирован")
 */
class Car implements \JsonSerializable
{

    /**
     * @var string
     * @ODM\Id
     */
    protected $id;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ODM\String(name="number", type="string")
     * @Assert\NotNull()
     * @Assert\Length(
     *      min=6,
     *      minMessage="Слишком короткий номер",
     *      max=9,
     *      maxMessage="Слишком длинный номер"
     * )
     */
    protected $number;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ODM\String(name="mark", type="string")
     * @Assert\Length(
     *      min=1,
     *      minMessage="Слишком короткая марка",
     *      max=100,
     *      maxMessage="Слишком длинная марка"
     * )
     */
    protected $mark;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ODM\String(name="model", type="string")
     * @Assert\Length(
     *      min=1,
     *      minMessage="Слишком короткая модель",
     *      max=100,
     *      maxMessage="Слишком длинная модель"
     * )
     */
    protected $model;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ODM\String(name="horsepower", type="int")
     * @Assert\Type("numeric")
     */
    protected $horsepower;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ODM\String(name="year", type="int")
     * @Assert\Type("numeric")
     */
    protected $year;

    /** @ODM\ReferenceOne(targetDocument="AG\Bundle\UserBundle\Document\User", inversedBy="cars") */
    protected $user;

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
     * Set number
     *
     * @param string $number
     * @return self
     */
    public function setNumber($number)
    {
        $this->number = self::cleanNumber($number);
        return $this;
    }

    /**
     * Cleans number
     * @param string $number
     * @return string
     */
    public static function cleanNumber($number)
    {
        $number = preg_replace('/[^А-Яа-я0-9]/u', '', $number);

        if (empty($number)) {
            return null;
        }
        return mb_strtoupper($number);
    }

    /**
     * Get number
     *
     * @return string $number
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set mark
     *
     * @param string $mark
     * @return self
     */
    public function setMark($mark)
    {
        $this->mark = $mark;
        return $this;
    }

    /**
     * Get mark
     *
     * @return string $mark
     */
    public function getMark()
    {
        return $this->mark;
    }

    /**
     * Set model
     *
     * @param string $model
     * @return self
     */
    public function setModel($model)
    {
        $this->model = $model;
        return $this;
    }

    /**
     * Get model
     *
     * @return string $model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set horsepower
     *
     * @param int $horsepower
     * @return self
     */
    public function setHorsepower($horsepower)
    {
        $this->horsepower = $horsepower;
        return $this;
    }

    /**
     * Get horsepower
     *
     * @return int $horsepower
     */
    public function getHorsepower()
    {
        return $this->horsepower;
    }

    /**
     * Set year
     *
     * @param int $year
     * @return self
     */
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    /**
     * Get year
     *
     * @return int $year
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set user
     *
     * @param AG\Bundle\UserBundle\Document\User $user
     * @return self
     */
    public function setUser(\AG\Bundle\UserBundle\Document\User $user)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return AG\Bundle\UserBundle\Document\User $user
     */
    public function getUser()
    {
        return $this->user;
    }

    public function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'number' => $this->getNumber(),
            'mark' => $this->getMark(),
            'model' => $this->getModel(),
            'horsepower' => $this->getHorsepower(),
            'year' => $this->getYear(),
        ];
    }
    
    /**
     * Set data from array 
     * @param string[] $data
     * @return \AG\Bundle\CarBundle\Document\Car
     */
    public function setArrayData($data)
    {
        $allowed = ['number', 'mark', 'model', 'horsepower', 'year'];
        
        foreach ($data as $key => $value) {
            if(!in_array($key, $allowed)) {
                continue;
            }
            $method = 'set' . ucfirst($key);
            $this->$method($value);
        }
        
        return $this;
    }

}
