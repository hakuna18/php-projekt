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
            ],
            [
                'title' => 'Harry Potter: Kamien filozoficzny',
                'author' => 'J.K.Rowling',
                'genre' => 'fantasy',
            ],
            [
                'title' => 'Solaris',
                'author' => 'Sanislaw Lem',
                'genre' => 'Sci-Fi',
            ],
        ];

        foreach ($data as $item) {
            $book = new Book();
            $book->setTitle($item['title']);
            $book->setAuthor($item['author']);
            $book->setGenre($item['genre']);
     
            $manager->persist($book);
        }
        $manager->flush();
    }
}