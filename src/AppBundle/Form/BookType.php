<?php
/**
 * Book type.
 */
namespace AppBundle\Form;

use AppBundle\Entity\Book;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class BookType.
 */
class BookType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\Form\FormBuilderInterface $builder Form builder
     * @param array                                        $options Options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'ISBN',
            TextType::class,
            [
                'label' => 'book.isbn',
                'required' => true,
                'attr' => [
                    'min_length' => 13,
                    'max_length' => 13,
                ],
            ]
        )->add(
            'title',
            TextType::class,
            [
                'label' => 'book.title',
                'required' => true,
                'attr' => [
                    'max_length' => 128,
                ],
            ]
        )->add(
            'author',
            TextType::class,
            [
                'label' => 'book.author',
                'required' => true,
                'attr' => [
                    'max_length' => 128,
                ],
            ]
        )->add(
            'genre',
            TextType::class,
            [
                'label' => 'book.genre',
                'required' => true,
                'attr' => [
                    'max_length' => 128,
                ],
            ]
        )->add(
            'publisher',
            TextType::class,
            [
                'label' => 'book.publisher',
                'required' => true,
                'attr' => [
                    'max_length' => 128,
                ],
            ]
        )->add(
            'year',
            IntegerType::class,
            [
                'label' => 'book.year',
                'required' => true,
                'attr' => [
                    'max_length' => 128,
                    'min' => '0'
                ],
            ]
        )->add(
            'numberOfCopies',
            IntegerType::class,
            [
                'label' => 'book.number_of_copies',
                'required' => true,
                'attr' => [
                    'max_length' => 128,
                    'min' => '1'
                ],
            ]
        )->add(
            'description',
            TextType::class,
            [
                'label' => 'book.description',
                'required' => true
            ]
        )->add('cover',
            FileType::class, 
            [
                'label' => 'cover_file',
                'data_class' => null,
                'required' => false
            ]
        )->add('save',
            SubmitType::class, 
            [
                'label' => 'form.submit'
            ]
        );

        if (!$options['edit_mode']){
            $builder->addEventListener(FormEvents::POST_SUBMIT, function ($event) {
                $form = $event->getForm();
                if ( $form['cover']->getData() == NULL ){
                    $form->addError(new FormError($this->translator->trans('validation.upload_cover')));                  
                }
            });
        }
    }

    /**
     * {@inheritdoc}
     *
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver Resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Book::class,
                'edit_mode' => false
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return null|string Result
     */
    public function getBlockPrefix()
    {
        return 'book';
    }
}