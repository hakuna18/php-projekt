<?php
/**
 * UsersManager.
 */
namespace AppBundle\Service;

use UserBundle\Entity\User;
use FOS\UserBundle\Model\UserManagerInterface;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;

/**
 * Class UsersManager
 */
class UsersManager
{
    /**
     * FOS User Manager
     */
    protected $fosUserManager = null;

    /**
     * BooksManager constructor.
     *
     * @param FOS\UserBundle\Model\UserManagerInterface $fosUserManager FOS User Manager
     */
    public function __construct(UserManagerInterface $fosUserManager)
    {
        $this->fosUserManager = $fosUserManager;
    }

    /**
     * Find by pattern
     *
     * Looks for users with name/surname/email mathching given regex pattern.
     *
     * @param string $pattern
     *
     * @param int    $page
     *
     * @return Pagerfanta\Pagerfanta
     */
    public function query($pattern, $page = 1)
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

        $paginator = new Pagerfanta(new ArrayAdapter($result));
        $paginator->setMaxPerPage(User::NUM_ITEMS);
        $paginator->setCurrentPage($page);

        return $paginator;
    }

    /**
     * Find users with given role
     *
     * @param string $role Role
     *
     * @return array
     */
    public function findUsersByRole($role)
    {
        $users = $this->fosUserManager->findUsers();

        return array_filter($users, function ($user) use ($role) {
            return $user->hasRole($role);
        });
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
        $this->fosUserManager->deleteUser($user);
    }
}
