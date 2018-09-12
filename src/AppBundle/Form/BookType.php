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
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\File;

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
                    'size' => 11
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
                    'size' => 30
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
                    'min' => '0',
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
                    'min' => '1',
                ],
            ]
        )->add(
            'description',
            TextareaType::class,
            [
                'label' => 'book.description',
                'attr' => [
                    'cols' => 40,
                    'rows' => 3
                ],
                'required' => true,
            ]
        )->add(
            'cover',
            FileType::class,
            [
                'label' => 'cover_file',
                // It is needed to add this work-around when editing a book: Due to HTML specification 'FileType' cannot be pre-filled
                // https://github.com/symfony/symfony/issues/10696#issuecomment-40263577
                // https://stackoverflow.com/a/16367121/2603886
                // so when editing: a) set data_class to null to avoid symfony error b) make this field not required
                'data_class' => $options['edit_mode']? null : File::class,   
                'required' => $options['edit_mode']? false : true,
            ]
        )->add(
            'save',
            SubmitType::class,
            [
                'label' => 'form.submit',
            ]
        );

        // Since the cover upload field is not required when editing, make sure the cover does not get reset to null
        // by manually reverting it to the previous cover
        if ($options['edit_mode']) {
            $book = $builder->getData();
            $cover_backup = $book->getCover();
            $builder->addEventListener(FormEvents::POST_SUBMIT, function ($event) use ($book, $cover_backup) {
                $form = $event->getForm();
                if (!$form->isValid() || $book->getCover() == null)
                    $book->setCover($cover_backup);
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
                'edit_mode' => false,
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
