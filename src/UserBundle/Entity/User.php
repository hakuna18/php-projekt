<?php
/**
 * User entity.
 */
namespace UserBundle\Entity;
use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(
 *     name="fos_user"
 * )
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     nullable=false,
     *     options={"unsigned"=true},
     * )
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->loans = new ArrayCollection();

        $this->name = "";
        $this->surname = "";

        parent::__construct();
    }

    /**
     * Name.
     *
     * @var string $name
     *
     * @ORM\Column(
     *     name="name",
     *     type="string",
     *     length=128,
     *     nullable=false,
     * )
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="128",
     * )
     */
    protected $name;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Name
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


     /**
     * Surname.
     *
     * @var string $surname
     *
     * @ORM\Column(
     *     name="surname",
     *     type="string",
     *     length=128,
     *     nullable=false,
     * )
     */
    protected $surname;

    /**
     * Set surname
     *
     * @param string $surname
     *
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string
     */
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * Reservations.
     *
     * @ORM\OneToMany(
     * targetEntity="AppBundle\Entity\Reservation", 
     * mappedBy="user"
     * )
     */
    private $reservations;

     /**
     * Get reservations
     *
     * @return ArrayCollection
     */
    public function getReservations()
    {
        return $this->reservations;
    }

    /**
     * Loans.
     *
     * @ORM\OneToMany(
     * targetEntity="AppBundle\Entity\Loan", 
     * mappedBy="user"
     * )
     */
    private $loans;

     /**
     * Get reservations
     *
     * @return ArrayCollection
     */
    public function getLoans()
    {
        return $this->loans;
    }  
}