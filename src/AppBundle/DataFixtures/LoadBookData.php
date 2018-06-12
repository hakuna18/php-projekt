<?php
/**
 * Data fixtures for books.
 */

namespace AppBundle\DataFixtures;

use AppBundle\Entity\Book;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class LoadBookData.
 */
class LoadBookData extends Fixture
{
    /**
     * Load books.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            [
                'title' => 'Kolor magii',
                'author' => 'T. Prachett',
                'genre' => 'fantasy',
                'coverURI' => 'swiat-dysku-kolor-magii-terry-pratchett.jpg',
                'publisher' => 'Pr?szy?ski i S-ka',
                'year' => 1994,
                'totalAvailable' => 10
            ],
            [
                'title' => 'Harry Potter: Kamien filozoficzny',
                'author' => 'J.K.Rowling',
                'genre' => 'fantasy',
                'coverURI' => 'kamien-filozoficzny.jpg',
                'publisher' => 'Media Rodzina',
                'year' => 2016,
                'totalAvailable' => 3
            ],
            [
                'title' => 'Solaris',
                'author' => 'Sanislaw Lem',
                'genre' => 'Sci-Fi',
                'publisher' => 'Wydawnictwo Literackie',
                'year' => 2012,
                'coverURI' => 'solaris.jpg',
                'totalAvailable' => 1
            ],
        ];

        foreach ($data as $item) {
            $book = new Book();
            $book->setTitle($item['title']);
            $book->setAuthor($item['author']);
            $book->setGenre($item['genre']);
            $book->setCoverURI($item['coverURI']);
            $book->setPublisher($item['publisher']);
            $book->setYear($item['year']);
            $book->setTotalAvailable($item['totalAvailable']);
            $book->setCurrentlyAvailable($item['totalAvailable']);
     
            $manager->persist($book);
        }
        $manager->flush();
    }
}