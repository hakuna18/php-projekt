<?php
/**
 * User.
 */
namespace UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User Entity
 *
 * @ORM\Entity
 * @ORM\Table(
 *     name="fos_user"
 * )
 */
class User extends BaseUser
{
    const NUM_ITEMS = 5;

    /**
     * Entity's ID
     *
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
     * Reservations.
     *
     * @ORM\OneToMany(
     * targetEntity="AppBundle\Entity\Reservation",
     * mappedBy="user"
     * )
     */
    private $reservations;

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
     * User entity constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->setRole("ROLE_READER");

        $this->reservations = new ArrayCollection();
        $this->loans = new ArrayCollection();

        // This is a work-around for a case when the user is created from the FOS user create CLI.
        $this->name = "empty";
        $this->surname = "empty";
    }

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
     * Get reservations
     *
     * @return ArrayCollection
     */
    public function getReservations()
    {
        return $this->reservations;
    }

     /**
     * Get reservations
     *
     * @return ArrayCollection
     */
    public function getLoans()
    {
        return $this->loans;
    }

    /**
     * Get this user's role that is not the default "ROLE_USER".
     *
     * @return string
     */
    public function getRole()
    {
        foreach (parent::getRoles() as $role) {
            if ("ROLE_USER" !== $role) {
                return $role;
            }
        }

        return null;
    }

    /**
     * Set role
     *
     * @param string $role
     *
     * @return User
     */
    public function setRole($role)
    {
        parent::setRoles([$role]);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSuperAdmin($boolean)
    {
        // super admin cant be a reader
        parent::removeRole("ROLE_READER");

        parent::setSuperAdmin($boolean);
    }
}
