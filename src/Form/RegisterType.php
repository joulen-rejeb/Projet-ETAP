<?php

namespace App\Form;

use App\Entity\Utilisateurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email', EmailType::class)
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
            ])
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Responsable Consommables' => 'ROLE_CONSOMMABLES',
                    'Responsable Fournitures' => 'ROLE_FOURNITURES',
                    'Responsable des produits de nettoyage' => 'ROLE_NETTOYAGE',
                    'Responsable des fournitures de bureau' => 'ROLE_BUREAU',
                    'Responsable des consommables informatiques' => 'ROLE_INFORMATIQUE',
                    'Admin' => 'ROLE_ADMIN',
                ],
                'expanded' => true,
                'multiple' => false,
            ])
            ->add('lieu', ChoiceType::class, [
                'label' => 'Lieu',
                'choices' => [
                    'Mohamed V' => 'MOHAMED_V',
                    'Charguia' => 'CHARGUIA',
                    'Kheireddine' => 'KHEIREDDINE',
                ],
                'expanded' => true,
                'multiple' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
        ]);
    }
}