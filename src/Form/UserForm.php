<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nickname')
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => array_flip(User::ROLES),
                'label' => 'Roles',
                'placeholder' => 'Choose a role',
                'multiple' => false, // don't forget this when property in entity is an array
                'expanded' => false, // drop-down list
                'mapped' => false, // set to false because we manage the field manually
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
