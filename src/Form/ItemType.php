<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Quest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('rarity')
            ->add('picture')
            ->add('quests', EntityType::class, [
                'class' => Quest::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
