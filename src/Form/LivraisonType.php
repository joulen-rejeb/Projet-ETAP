<?php


namespace App\Form;

use App\Entity\Livraison;
use App\Entity\Produits;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('product', EntityType::class, [
    'class' => Produits::class,
    'choice_label' => 'nameProduct',
    'placeholder' => 'Choisir un produit',
    'choice_attr' => function(Produits $prod) {
        $emplacement = $prod->getEmplacement();
        $famille = $prod->getFamilles();
        
        return [
            'data-zone' => $emplacement ? $emplacement->getZone() : 'Non spécifiée',
            'data-famille' => $famille ? $famille->getNameCategory() : 'Non spécifiée',
        ];
    },
    'attr' => ['class' => 'product-selector']
])

            ->add('quantity', IntegerType::class, [
                'attr' => ['min' => 1],
            ])
             ->add('entryDate', DateTimeType::class, [
        'widget' => 'single_text',   // pour un input type="datetime-local"
        'html5' => true,
        'data' => new \DateTime(),   // valeur par défaut = date et heure actuelles
        'attr' => ['readonly' => true] // rendre le champ non modifiable
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => Livraison::class]);
    }
}

