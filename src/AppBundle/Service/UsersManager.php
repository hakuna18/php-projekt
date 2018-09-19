<?php
/**
 * UsersManager.
 */
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
    /**
     * Entity manager
     */
    protected $entityManager = null;

    /**
     * FOS User Manager
     */
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
     * @param string     $pattern
     *
     * @param array|null $roles
     *
     * @param int        $page
     *
     * @return Pagerfanta\Pagerfanta
     */
    public function query($pattern, $roles, $page = 1)
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

        // must have any role defined by "$roles"
        if ($roles) {
            $result = array_filter($result, function ($user) use ($roles) {
                foreach ($roles as $role) {
                    if ($user->hasRole($role)) {
                        return true;
                    }
                }

                return false;
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
