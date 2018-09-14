<?php
// src/AppBundle/Entity/Book.php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Class Book.
 *
 * @ORM\Table(
 *     name="books"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\BooksRepository"
 * )
 */
class Book
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
    const NUM_ITEMS = 5;

    /**
     * Primary key.
     *
     * @var int $id
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
    private $id;

    /**
     * ISBN.
     *
     * @var string $isbn
     *
     * @ORM\Column(
     *     name="isbn",
     *     type="string",
     *     length=13,
     *     nullable=false,
     * )
     *
     * @Assert\NotBlank
     * @Assert\Type(type="digit",message="isbn.only_digits")
     * @Assert\Length(min="13",max="13",exactMessage="isbn.13_digits")
     *
     */
    private $isbn;

     /**
     * Title.
     *
     * @var string $title
     *
     * @ORM\Column(
     *     name="title",
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
    private $title;

     /**
     * Author.
     *
     * @var string $author
     *
     * @ORM\Column(
     *     name="author",
     *     type="string",
     *     length=128,
     *     nullable=false,
     * )
     *
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="5",
     *     max="128",
     * )
     * @Assert\Regex(
     *      pattern= "/[0-9]+/",
     *      match=false,
     *      message= "author.no_digits"
     * )
     */
    private $author;

     /**
     * Genre.
     *
     * @var string $genre
     *
     * @ORM\Column(
     *     name="genre",
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
     * @Assert\Regex(
     *      pattern= "/[0-9]+/",
     *      match=false,
     *      message= "genre.no_digits"
     * )
     */
    private $genre;

     /**
     * Cover.
     *
     * @var string $cover
     *
     * @ORM\Column(
     *     name="Cover",
     *     type="string",
     *     length=128,
     *     nullable=false,
     * )
     *
     * @Assert\File(mimeTypes={ "image/jpg", "image/jpeg", "image/png" },maxSize="20M")
     */
    private $cover;

     /**
     * Publisher.
     *
     * @var string $publisher
     *
     * @ORM\Column(
     *     name="publisher",
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
    private $publisher;

    /**
     * Year.
     *
     * @var int $year
     *
     * @ORM\Column(
     *     name="year",
     *     type="integer",
     *     length=128,
     *     nullable=false,
     * )
     * @Assert\Type("integer")
     */
    private $year;

     /**
     * Total number of copies
     *
     * @var int $numberOfCopies
     *
     * @ORM\Column(
     *     name="NumberOfCopies",
     *     type="integer",
     *     nullable=false,
     *     options={"unsigned"=true}
     * )
     * @Assert\Type("integer")
     * @Assert\GreaterThanOrEqual(0)
     */
    private $numberOfCopies;

    /**
     * Description.
     *
     * @var string $description
     *
     * @ORM\Column(
     *     name="description",
     *     type="string",
     *     length=1025,
     *     nullable=false,
     * )
     *
     * @Assert\Length(min="3")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="Reservation", mappedBy="book")
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity="Loan", mappedBy="book")
     */
    private $loans;

    /**
     * Book entity constructor.
     */
    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->loans = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set ISBN
     *
     * @param string $isbn
     *
     * @return Book
     */
    public function setISBN($isbn)
    {
        $this->isbn = $isbn;

        return $this;
    }

    /**
     * Get ISBN
     *
     * @return string
     */
    public function getISBN()
    {
        return $this->isbn;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Book
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set author
     *
     * @param string $author
     *
     * @return Book
     */
    public function setAuthor($author)
    {
        $this->author = $author;

        return $this;
    }

    /**
     * Get author
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set genre
     *
     * @param string $genre
     *
     * @return Book
     */
    public function setGenre($genre)
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get genre
     *
     * @return string
     */
    public function getGenre()
    {
        return $this->genre;
    }

    /**
     * Set cover
     *
     * @param string $cover
     *
     * @return Book
     */
    public function setCover($cover)
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * Get cover
     *
     * @return string
     */
    public function getCover()
    {
        return $this->cover;
    }

    /**
     * Set publisher
     *
     * @param string $publisher
     *
     * @return Book
     */
    public function setPublisher($publisher)
    {
        $this->publisher = $publisher;

        return $this;
    }

    /**
     * Get publisher
     *
     * @return string
     */
    public function getPublisher()
    {
        return $this->publisher;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return Book
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

     /**
     * Set number of copies
     *
     * @param integer $count
     *
     * @return Book
     */
    public function setNumberOfCopies($count)
    {
        $this->numberOfCopies = $count;

        return $this;
    }

    /**
     * Get number of copies
     *
     * @return integer
     */
    public function getNumberOfCopies()
    {
        return $this->numberOfCopies;
    }

     /**
     * Get list of reservations
     *
     * @return ArrayCollection
     */
    public function getReservations()
    {
        return $this->reservations;
    }

     /**
     * Get list of reservations
     *
     * @return ArrayCollection
     */
    public function getLoans()
    {
        return $this->loans;
    }

    /**
     * Get currently available count
     *
     * @return integer
     */
    public function getCurrentlyAvailable()
    {
        return $this->getNumberOfCopies() - $this->loans->count() - $this->reservations->count();
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Book
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
