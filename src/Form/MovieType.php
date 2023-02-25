<?php

namespace App\Form;

use App\Entity\Genre;
use App\Entity\Movie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MovieType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('title', TextType::class, [
        'label' => 'Titre du film',
        'attr' => [
          'placeholder' => 'titre du film ici...'
        ]
      ])
      // ->add('rating')
      ->add('duration', IntegerType::class, [
        'label' => 'Durée du film ou de la série en minutes',
        'attr' => [
          'min' => 15,
          'max' => 300,
        ]
      ])
      // ->add('poster', UrlType::class, [
      //     'label' => 'Image du film',
      //     'attr' => [
      //         'placeholder' => 'URL de l\'image ici...',
      //     ]
      // ])
      ->add('type', ChoiceType::class, [
        'label' => false,
        'multiple' => false,
        'expanded' => true,
        'choices' => [
          'film' => 'film',
          'serie' => 'serie',
        ]
      ])
      ->add('releaseDate', DateType::class, [
        'label' => 'date de sortie',
        'widget' => 'single_text',
      ])
      ->add('summary', TextareaType::class, [
        'label' => 'Sommaire du film / serie'
      ])
      ->add('synopsis', TextareaType::class)
      ->add('country', CountryType::class, [
        'label' => 'Pays du film',
        'attr' => [
          'placeholder' => 'Indicatif du pays ex: FR ...',
        ]
      ])
      //->add('updatedAt')
      ->add('genres', EntityType::class, [
        'class' => Genre::class,
        // property to show
        'choice_label' => 'name',
        // Many to Many so multiple egual true otherwise error
        'multiple' => true,
        'expanded' => true,
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => Movie::class,
    ]);
  }
}
