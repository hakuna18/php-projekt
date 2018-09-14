<?php

namespace AppBundle\Repository;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use AppBundle\Entity\Loan;

/**
 * LoanRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LoanRepository extends \Doctrine\ORM\EntityRepository
{
    /**
     *  Query by user's surname/email or book's ISBN.
     * 
     * @param string $pattern
     * 
     * @param int $page
     * 
     * @return Pagerfanta\Pagerfanta
     */
    public function query($pattern, $page = 1)
    {
        $pattern = '/'.strtoupper(trim($pattern)).'/';
        $result = array();
        // If regex valid
        if (@preg_match($pattern, null) !== false) {
            $loans = $this->findAll();
            foreach ($loans as $loan) {
                if (preg_match($pattern, strtoupper($loan->getUser()->getSurname()))
                || preg_match($pattern, strtoupper($loan->getUser()->getEmail()))
                || preg_match($pattern, strtoupper($loan->getBook()->getISBN()))
                ) {
                    array_push($result, $loan);
                }
            }
        }

        $paginator = new Pagerfanta(new ArrayAdapter($result));
        $paginator->setMaxPerPage(Loan::NUM_ITEMS);
        $paginator->setCurrentPage($page);
        
        return $paginator;
    }
}
