<?php
/**
 * ConfirmType.
 */
namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Confirmation form.
 */
class ConfirmType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Add submit button that confirms requested action.
        $builder->add('confirm', SubmitType::class, [
            'label' => 'action.confirm',
        ]);
    }
}
