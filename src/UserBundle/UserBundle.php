<?php
// src/UserBundle/UserBundle.php
namespace UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * User Bundle class.
 */
class UserBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
