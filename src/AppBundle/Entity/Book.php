<?php
/**
 * Book entity.
 */
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Book.
 *
 * @ORM\Table(
 *     name="books"
 * )
 * @ORM\Entity(
 *     repositoryClass="AppBundle\Repository\BooksRepository"
 * )
 * @UniqueEntity(
 *     fields={"name"}
 * )
 */
class Book
{
    /**
     * Use constants to define configuration options that rarely change instead
     * of specifying them in app/config/config.yml.
     * See http://symfony.com/doc/current/best_practices/configuration.html#constants-vs-configuration-options
     */
    const NUM_ITEMS = 10;

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
    protected $id;

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
    protected $title;

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
     */
    protected $author;

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
     */
    protected $genre;

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
     * URI to the cover.
     *
     * @var string $coverURI
     *
     * @ORM\Column(
     *     name="CoverURI",
     *     type="string",
     *     length=128,
     *     nullable=false,
     * )
     */
    protected $coverURI;

    /**
     * Set cover URI
     *
     * @param string $coverURI
     *
     * @return Book
     */
    public function setCoverURI($uri)
    {
        $this->coverURI = $uri;

        return $this;
    }

    /**
     * Get cover URI
     *
     * @return string
     */
    public function getCoverURI()
    {
        return $this->coverURI;
    }

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
     */
    protected $publisher;

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
     * Year.
     *
     * @var integer $year
     *
     * @ORM\Column(
     *     name="year",
     *     type="integer",
     *     length=128,
     *     nullable=false,
     * )
     */
    protected $year;

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
     * Total available count
     *
     * @var integer $totalAvailable
     *
     * @ORM\Column(
     *     name="TotalAvailable",
     *     type="integer",
     *     nullable=false,
     *     options={"unsigned"=true}
     * )
     */
    protected $totalAvailable;

     /**
     * Set total available count
     *
     * @param integer $totalAvailable
     *
     * @return Book
     */
    public function setTotalAvailable($count)
    {
        $this->totalAvailable = $count;

        return $this;
    }

    /**
     * Get total available count
     *
     * @return integer
     */
    public function getTotalAvailable()
    {
        return $this->totalAvailable;
    }

     /**
     * Currently available count
     *
     * @var integer $currentlyAvailable
     *
     * @ORM\Column(
     *     name="CurrentlyAvailable",
     *     type="integer",
     *     nullable=false,
     *     options={"unsigned"=true}
     * )
     */
    protected $currentlyAvailable;

     /**
     * Set currently available count
     *
     * @param integer $totatAvailable
     *
     * @return Book
     */
    public function setCurrentlyAvailable($count)
    {
        $this->currentlyAvailable = $count;

        return $this;
    }

    /**
     * Get currently available count
     *
     * @return integer
     */
    public function getCurrentlyAvailable()
    {
        return $this->currentlyAvailable;
    }
}
