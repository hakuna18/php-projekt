<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;

/**
 * Loan
 *
 * @ORM\Table(name="loan")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LoanRepository")
 */
class Loan
{
    const NUM_ITEMS = 5;

    private static $LOAN_DAYS_LIMIT = 30;
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="loanDate", type="datetime")
     */
    private $loanDate;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User", inversedBy="loans")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Book", inversedBy="loans")
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     */
    private $book;

    public function __construct()
    {
        $this->loanDate = new DateTime('now');
    }

    public function isExpired()
    {
        return time() - $this->loanDate->getTimestamp() > 3600 * 24 * LOAN::$LOAN_DAYS_LIMIT;
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
     * Get loanDate
     *
     * @return \DateTime
     */
    public function getLoanDate()
    {
        return $this->loanDate;
    }

    /**
     * Set user
     *
     * @param \stdClass $user
     *
     * @return Loan
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \stdClass
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set book
     *
     * @param \stdClass $book
     *
     * @return Loan
     */
    public function setBook($book)
    {
        $this->book = $book;

        return $this;
    }

    /**
     * Get book
     *
     * @return \stdClass
     */
    public function getBook()
    {
        return $this->book;
    }
}
