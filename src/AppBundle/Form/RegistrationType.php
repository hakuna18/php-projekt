<?php
/**
 * RegistrationType.
 */
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

/**
 * Class RegistrationType.
 */
class RegistrationType extends AbstractType
{
    /**
     * Authorization checker
     */
    private $authChecker;

    /**
     * BookType constructor
     *
     * @param Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface $authChecker
     */
    public function __construct(AuthorizationCheckerInterface $authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     * BuildForm
     *
     * @param Symfony\Component\Form\FormBuilderInterface $builder Form builder
     *
     * @param array                                       $options Options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            TextType::class,
            [
                'required' => true,
                'data' => '',
                'attr' => [
                    'max_length' => 128,
                ],
            ]
        )->add(
            'surname',
            TextType::class,
            [
                'required' => true,
                'data' => '',
                'attr' => [
                    'max_length' => 128,
                ],
            ]
        );
        if ($this->authChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add(
                'role',
                ChoiceType::class,
                [
                    'choices'  => [
                        'role.reader' => 'ROLE_READER',
                        'role.admin' => 'ROLE_ADMIN',
                    ],
                    'expanded' => true,
                    'required' => true,
                ]
            );
        }
    }

    /**
     * GetParent
     *
     * @return string
     */
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    /**
     * GetBlockPrefix
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
}
