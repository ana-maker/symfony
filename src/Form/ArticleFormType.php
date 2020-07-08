<?php

namespace App\Form;

use App\Entity\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ArticleFormType
 * @package App\Form
 */
class ArticleFormType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void

    {
        $builder
            ->add('title', TextType::class, [
                'help' => 'Introduce here your title'
            ])
            ->add('content', TextType::class)
            ->add('publishedAt', null, [
                'widget' => 'single_text'
            ])
            ->add('author', TextType::class)
            ->add('content', TextareaType::class)
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false
            ]);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class
        ]);
    }


}