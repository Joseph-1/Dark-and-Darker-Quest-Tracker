<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Quest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('rarity')
            ->add('picture')
            // Use of const RARITIES declared in /Entity/Item.php
            ->add('rarity', ChoiceType::class, [
                // array_flip is here to secure the const in case where the const would be inversed
                'choices' => array_flip(Item::RARITIES), // Allow to have "Junk" => "junk"
                'label' => 'Rarity',
                'placeholder' => 'Choose a rarity',
            ])
            /*
            ->add('quests', EntityType::class, [
                'class' => Quest::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            */
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
