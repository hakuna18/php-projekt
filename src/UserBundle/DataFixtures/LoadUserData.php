<?php
/**
 * Data fixtures for users.
 */

namespace UserBundle\DataFixtures;

use UserBundle\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class LoadUserData.
 */
class LoadUserData extends Fixture
{
    private $userManager;

    private $encoder;

    public function __construct(UserManagerInterface $userManager, UserPasswordEncoderInterface $encoder)
    {
        $this->userManager = $userManager;
        $this->encoder = $encoder;
    }

    /**
     * Load users.
     *
     * @param \Doctrine\Common\Persistence\ObjectManager $manager Object manager
     */
    public function load(ObjectManager $manager)
    {
        $data = [
            [
                'username' => 'admin',
                'email' => 'admin@email.com',
                'password' => 'asdf',
                'role' => 'ROLE_ADMIN',
                'name' => 'Admin',
                'surname' => 'Adminowski'
            ],
            [
                'username' => 'user1',
                'email' => 'user1@email.com',
                'password' => 'asdf',
                'role' => 'ROLE_READER',
                'name' => 'Jan',
                'surname' => 'Nowak'
            ],
        ];

        foreach ($data as $userData) {
            $user = $this->userManager->createUser();
            $user->setUsername($userData['username']);
            $user->setEmail($userData['email']);
            $user->setPassword($this->encoder->encodePassword($user, $userData['password']));        
            $user->setRole($userData['role']);
            $user->setName($userData['name']);
            $user->setSurname($userData['surname']);
            
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
        }
    }
}
