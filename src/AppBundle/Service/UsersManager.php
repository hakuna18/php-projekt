<?php

// src/AppBundle/Service/UsersManager.php
namespace AppBundle\Service;

use UserBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;

class UsersManager
{
    protected $entityManager = null;

    protected $fosUserManager = null;

    /**
     * BooksManager constructor.
     *
     * @param Doctrine\ORM\EntityManager $em Entity Manager
     */
    public function __construct(EntityManagerInterface $em, UserManagerInterface $fosUserManager)
    {
        $this->entityManager = $em;
        $this->fosUserManager = $fosUserManager;
    }

    // Looks for users with name/surname/email mathching given regex pattern.
    public function findUserByPattern($pattern) {
        $pattern = '*' . strtoupper(trim($pattern)) . '*';
        $users = $this->fosUserManager->findUsers();
        $result = array();
        foreach($users as $user) {
            if(preg_match($pattern, strtoupper($user->getName()))
            || preg_match($pattern, strtoupper($user->getSurname()))
            || preg_match($pattern, strtoupper($user->getEmail()))
            )
                array_push($result, $user);
        }

        $nonAdminUsers = array_filter($result, function ($user){
            return !in_array("ROLE_SUPER_ADMIN", $user->getRoles());
        });
        return $nonAdminUsers;
    }

    public function deleteUser($user) {
        foreach ($user->getReservations() as $reservation) {
            $this->$em->remove($reservation);
        } 
        $fosUserManager->deleteUser($user);
        $this->$em->flush();
    }
}