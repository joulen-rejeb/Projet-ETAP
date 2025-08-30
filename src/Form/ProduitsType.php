<?php

namespace App\Form;

use App\Entity\Produits;
use App\Entity\Emplacement;
use App\Entity\Familles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotNull;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nameProduct', TextType::class, [
                'label' => 'Nom du produit'
            ])
            ->add('photoProduct', FileType::class, [
                'label' => 'Photo du produit',
                'required' => false,
                'mapped' => false
            ])
            ->add('emplacement', EntityType::class, [
                'class' => Emplacement::class,
                'choice_label' => 'zone',
                'placeholder' => '--- Choisir un emplacement ---',
                'attr' => ['class' => 'form-control'],
                'disabled' => $options['emplacement_disabled'],
                'choices' => $options['emplacements'], // Utiliser les emplacements filtrés
            ])
           ->add('familles', EntityType::class, [
    'class' => Familles::class,
    'choice_label' => 'nameCategory',
    'placeholder' => '--- Choisir une catégorie ---',
    'required' => true,
    'constraints' => [
        new NotNull(['message' => 'Veuillez choisir une catégorie.']),
    ],
    'disabled' => $options['famille_disabled'], // ✅ ajouté
        'choices' => $options['familles'], // ⚡ Utiliser la liste injectée

])

            ->add('quantityInitial', IntegerType::class, [
                'label' => 'Quantité initiale',
                'required' => true,
                'attr' => ['class' => 'form-control'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
            'emplacement_disabled' => false,
            'famille_disabled' => false,
            'emplacements' => [], // Option pour les emplacements filtrés
            'familles' => [], // Option pour les familles filtrées
        ]);
    }
}