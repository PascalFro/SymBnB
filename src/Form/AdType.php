<?php

namespace App\Form;

use App\Entity\Ad;
use App\Form\ImageType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdType extends AbstractType
{

    /**
     * Permet d'avoir la configuration de base d'un champ
     *
     * @param  string $label       [description]
     * @param  string $placeholder [description]
     * @param  array $Options      [description]
     * @return array               [description]
     */

    private function getConfiguration($label, $placeholder, $options =[]) {
        return array_merge([
                'label' => $label,
                'attr' => ['placeholder' => $placeholder]
            ], $options);
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                TextType::Class,
                $this->getConfiguration("titre", "Tapez un super titre pour votre annonce !")
            )
            ->add(
                'slug',
                TextType::Class,
                $this->getConfiguration("Adresse web", "Tapez l'adresse web (automatique)", [
                    'required' => false])
            )
             ->add(
                'introduction',
                TextType::Class,
                $this->getConfiguration("Introduction", "Donnez une description globale de votre annonce")
            )
             ->add(
                'content',
                TextareaType::Class,
                $this->getConfiguration("Description détaillée", "Donnez une description qui donne envie de venir !")
            )
            ->add(
                'coverImage',
                UrlType::Class,
                $this->getConfiguration("Url de l'image principale", "Adresse d'une image qui donne envie...")
            )
           ->add(
                'rooms',
                IntegerType::Class,
                $this->getConfiguration("Nombre de chambres", "Le nombre de chambres dsiponibles")
            )
            ->add(
                'price',
                MoneyType::Class,
                $this->getConfiguration("Prix par nuit", " Indiquez le prix souhaité pur une nuit")
            )
            ->add(
                'images',
                CollectionType::Class,
                [
                    'entry_type' => ImageType::Class,
                    'allow_add' => true,
                    'allow_delete' => true
                ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
