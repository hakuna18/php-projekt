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
                'isbn' => '1234567890123',
                'title' => 'Kolor magii',
                'author' => 'T. Prachett',
                'genre' => 'fantasy',
                'cover' => 'swiat-dysku-kolor-magii-terry-pratchett.jpg',
                'publisher' => 'Prószyński i S-ka',
                'year' => 1994,
                'numberOfCopies' => 10,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
            ],
            [
                'isbn' => '2234567890123',
                'title' => 'Harry Potter: Kamien filozoficzny',
                'author' => 'J.K.Rowling',
                'genre' => 'fantasy',
                'cover' => 'kamien-filozoficzny.jpg',
                'publisher' => 'Media Rodzina',
                'year' => 2016,
                'numberOfCopies' => 3,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'

            ],
            [
                'isbn' => '3234567890123',
                'title' => 'Solaris2',
                'author' => 'Sanislaw Lem',
                'genre' => 'Sci-Fi',
                'publisher' => 'Wydawnictwo Literackie',
                'year' => 2012,
                'cover' => 'solaris.jpg',
                'numberOfCopies' => 1,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'

            ],
            [
                'isbn' => '4234567890123',
                'title' => 'Solaris3',
                'author' => 'Sanislaw Lem',
                'genre' => 'Sci-Fi',
                'publisher' => 'Wydawnictwo Literackie',
                'year' => 2012,
                'cover' => 'solaris.jpg',
                'numberOfCopies' => 1,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'

            ],
            [
                'isbn' => '5234567890123',
                'title' => 'Solaris4',
                'author' => 'Sanislaw Lem',
                'genre' => 'Sci-Fi',
                'publisher' => 'Wydawnictwo Literackie',
                'year' => 2012,
                'cover' => 'solaris.jpg',
                'numberOfCopies' => 1,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'

            ],
            [
                'isbn' => '6234567890123',
                'title' => 'Solaris5',
                'author' => 'Sanislaw Lem',
                'genre' => 'Sci-Fi',
                'publisher' => 'Wydawnictwo Literackie',
                'year' => 2012,
                'cover' => 'solaris.jpg',
                'numberOfCopies' => 1,
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'

            ],
        ];

        foreach ($data as $item) {
            $book = new Book();
            $book->setTitle($item['title'])
                ->setISBN($item['isbn'])
                ->setAuthor($item['author'])
                ->setGenre($item['genre'])
                ->setCover($item['cover'])
                ->setPublisher($item['publisher'])
                ->setYear($item['year'])
                ->setNumberOfCopies($item['numberOfCopies'])
                ->setDescription($item['description']);
     
            $manager->persist($book);
        }
        $manager->flush();
    }
}