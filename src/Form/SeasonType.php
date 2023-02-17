<?php

namespace App\Form;

use App\Entity\Movie;
use App\Entity\Season;
use App\Repository\MovieRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SeasonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nbEpisode', NumberType::class)
            ->add('number', NumberType::class)
            ->add('movie', EntityType::class, [
                'class' => Movie::class,
                // we can put anonym functions or entity methods in 'choice_label'
                'choice_label' => 'title',
                'multiple' => false,
                'expanded' => false,
                'query_builder' => function(MovieRepository $movieRepository) {
                    return $movieRepository->createQueryBuilder('m')
                        ->where("m.type = 'serie'")
                        ->orderBy('m.title', 'ASC');
                }
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Season::class,
        ]);
    }
}
