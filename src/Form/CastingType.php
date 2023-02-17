<?php

namespace App\Form;

use App\Entity\Casting;
use App\Entity\Movie;
use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CastingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('role', TextType::class, [
                'attr' => [
                    'placeholder' => 'Le nom du rôle ....'
                ]
            ])
            ->add('creditOrder', ChoiceType::class, [
                'label' => 'Quel rôle ?',
                'expanded' => true,
                'choices' => [
                    '1er' => 1,
                    '2ème' => 2,
                    '3ème' => 3,
                    '4ème' => 4,
                    '5ème' => 5,
                ]
            ])
            ->add('person', EntityType::class, [
                'label' => 'Quel acteur ?',
                'class' => Person::class,
                'choice_label' => 'getFullName',
                'query_builder' => function (PersonRepository $personRepository) {
                    return $personRepository->createQueryBuilder('p')
                        ->orderBy('p.firstname', 'ASC');
                }
            ])
            ->add('movie', EntityType::class, [
                'label' => 'Choisir le film',
                'class' => Movie::class,
                'choice_label' => 'title'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Casting::class,
        ]);
    }
}
