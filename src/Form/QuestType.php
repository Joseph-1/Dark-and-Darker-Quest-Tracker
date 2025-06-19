<?php

namespace App\Form;

use App\Entity\Item;
use App\Entity\Merchant;
use App\Entity\Quest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
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
            // Allow to get a drop-down list of Items
            ->add('items', EntityType::class, [
                'class' => Item::class,
                'choice_label' => 'item_name',
                'multiple' => true,
                'expanded' => true,
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
