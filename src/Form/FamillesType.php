<?php
// src/Form/FamillesType.php
namespace App\Form;

use App\Entity\Familles;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class FamillesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
         ->add('nameCategory', TextType::class, [
    'label' => 'Nom de la catégorie',
])
->add('photoFamille', FileType::class, [
    'label' => 'Photo du Famille',
    'required' => false,   
    'mapped' => false
]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Familles::class,
        ]);
    }
}
