<?php

namespace App\Form;

use App\Entity\Voting;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VotingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('likeArticle',  ChoiceType::class, [
                'value' => 'valeur1', // cochée par défaut
                'choices' => true,
                'expanded' => false,  // => boutons
                'label' => 'MonLabel'
])
            ->add('dislikeArticle')

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Voting::class,
        ]);
    }
}
