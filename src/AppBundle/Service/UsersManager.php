<?php

// src/AppBundle/Service/UsersManager.php
namespace AppBundle\Service;

use UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Class UsersManager
 */
class UsersManager
{
    protected $entityManager = null;

    protected $fosUserManager = null;

    /**
     * BooksManager constructor.
     *
     * @param Doctrine\ORM\EntityManager                $em             Entity Manager
     *
     * @param FOS\UserBundle\Model\UserManagerInterface $fosUserManager FOS User Manager
     */
    public function __construct(EntityManagerInterface $em, UserManagerInterface $fosUserManager)
    {
        $this->entityManager = $em;
        $this->fosUserManager = $fosUserManager;
    }

    /**
     * Find by pattern
     *
     * Looks for users with name/surname/email mathching given regex pattern.
     *
     * @param string $pattern
     *
     * @param bol    $includeAdmins
     *
     * @param int    $page
     *
     * @return Pagerfanta\Pagerfanta
     */
    public function findByPattern($pattern, $includeAdmins = false, $page = 1)
    {
        $pattern = '/'.strtoupper(trim($pattern)).'/';
        $result = array();
        // If regex valid
        if (@preg_match($pattern, null)) {
            $users = $this->fosUserManager->findUsers();
            foreach ($users as $user) {
                if (preg_match($pattern, strtoupper($user->getName()))
                || preg_match($pattern, strtoupper($user->getSurname()))
                || preg_match($pattern, strtoupper($user->getEmail()))
                ) {
                    array_push($result, $user);
                }
            }
        }

        if (!$includeAdmins) {
            $result = array_filter($result, function ($user) {
                return !in_array("ROLE_SUPER_ADMIN", $user->getRoles());
            });
        }

        $paginator = new Pagerfanta(new ArrayAdapter($result));
        $paginator->setMaxPerPage(User::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * Get all users
     *
     * @return array
     */
    public function getAllUsers()
    {
        return $this->fosUserManager->findUsers();
    }

    /**
     * Update user
     *
     * @param UserBundle\Entity\User $user User
     */
    public function updateUser($user)
    {
        $this->fosUserManager->updateUser($user);
    }

    /**
     * Delete user
     *
     * @param UserBundle\Entity\User $user User
     */
    public function deleteUser($user)
    {
        foreach ($user->getReservations() as $reservation) {
            $this->entityManager->remove($reservation);
        }
        $this->fosUserManager->deleteUser($user);
        $this->entityManager->flush();
    }
}
