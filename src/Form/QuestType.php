<?php

namespace App\Form;

use App\Entity\Merchant;
use App\Entity\Quest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('map')
            ->add('objective')
            ->add('status')
            ->add('merchant', EntityType::class, [
                'class' => Merchant::class,
                'choice_label' => 'name',
            ])
            /*
            // Allow to get a drop-down list of Items
            ->add('items', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
            ])
             */
            // Use of const MAPS declared in /Entity/Quest.php
            ->add('map', ChoiceType::class, [
                // array_flip is here to secure the const in case where the const would be inversed
                'choices' => array_flip(Quest::MAPS), // Allow to have "Goblin Caves" => "goblin caves"
                'label' => 'Map',
                'placeholder' => 'Choose a map',
            ])
            ->add('questItems', CollectionType::class, [
                'entry_type' => QuestItemForm::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
                'label' => false,
                'attr' => ['class' => 'quest-items-collection'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quest::class,
        ]);
    }
}
