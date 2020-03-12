<?php

namespace App\Form;

use App\Entity\User;
use App\Form\ApplicationType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::Class, $this->getConfiguration("Prénom", "Votre Prénom..."))
            ->add('lastName', TextType::Class, $this->getConfiguration("Nom", "Votre Nom de famille..."))
            ->add('email', EmailType::Class, $this->getConfiguration("Email", "Votre adreese email..."))
            ->add('picture', UrlType::Class, $this->getConfiguration("Photo de votre profil", "URL de votre avatar..."))
            ->add('hash', PasswordType::Class, $this->getConfiguration("Mot de Passe", "Choissisez votre mot de passe..."))
            ->add('passwordConfirm', PasswordType::Class, $this->getConfiguration("Confirmation du mot de passe", "Veuiller confirmer votre mot de passe"))
            ->add('introduction', TextType::Class, $this->getConfiguration("Introduction", "Présentez-vous en quelques mots..."))
            ->add('description', TextareaType::Class, $this->getConfiguration("Descritpion détaillée", "C'est le moment de vous présenter en détails..."));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
