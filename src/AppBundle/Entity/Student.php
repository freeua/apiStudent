<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Student
 *
 * @ORM\Table(name="student")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StudentRepository")
 * @UniqueEntity(
 *     fields="email",
 *     message="Email is already used.",
 *     groups={"student"}
 * )
 */
class Student
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank(
     *     message="name is required",
     *     groups={"student"}
     * )
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @Assert\NotBlank(
     *     message="email is required",
     *     groups={"student"}
     * )
     * @Assert\Email(
     *     message = "email is invalid.",
     *     groups={"student"}
     * )
     *
     * @ORM\Column(type="string", unique=true)
     */
    private $email;

    /**
     * @Assert\NotBlank(
     *     message="phone is required",
     *     groups={"student"}
     * )
     *
     * @Assert\Regex(
     *     pattern="/^\+?\d{7,13}$/",
     *     message="phone is invalid",
     *     groups={"student"}
     * )
     *
     * @ORM\Column(type="string")
     */
    private $phone;

    /**
     * Student constructor.
     * @param array $parameters
     */
    public function __construct($parameters = array())
    {
        foreach($parameters as $key => $value) {
            if(property_exists($this,$key) && $key!='id'){
                $this->$key = $value;
            }
        }
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param mixed $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
}

