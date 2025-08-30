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

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username')
            ->add('email', EmailType::class)
            ->add('role', ChoiceType::class, [
                'label' => 'Rôle',
                'choices' => [
                    'Responsable Consommables' => 'ROLE_CONSOMMABLES',
                    'Responsable Fournitures' => 'ROLE_FOURNITURES',
                    'Responsable des produits de nettoyage' => 'ROLE_NETTOYAGE',
                    'Responsable des fournitures de bureau' => 'ROLE_BUREATIQUE',
                    'Responsable des consommables informatiques' => 'ROLE_INFORMATIQUE',
                    'Admin' => 'ROLE_ADMIN',
                    
                ],
                'expanded' => true,
                'multiple' => false,
            ]);

        // Ajoute le champ password uniquement si on n'est PAS en édition (is_edit == false)
        if (!$options['is_edit']) {
            $builder->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Utilisateurs::class,
            'is_edit' => false, // option personnalisée par défaut
        ]);

        // Déclaration explicite de l'option personnalisée is_edit (doit être bool)
        $resolver->setAllowedTypes('is_edit', 'bool');
    }
}
