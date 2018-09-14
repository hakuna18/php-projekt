<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Class RegistrationType.
 */
class RegistrationType extends AbstractType
{
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
