<?php

namespace App\Form;

use App\Entity\Ad;
use App\Entity\Booking;
use App\Entity\User;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdminBookingType extends AbstractType {

    private $transformer;

    public function __construct(FrenchToDateTimeTransformer $transformer) {
        $this->transformer = $transformer;

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', TextType::Class)
            ->add('endDate', TextType::Class)
            ->add('comment')
            ->add('booker', EntityType::class, [
                'class' => User::Class,
                'choice_label' => function($user) {
                    return $user->getFirstName() . ' ' . strtoupper($user->getLastName());
                }])
            ->add('ad', EntityType::Class, [
                'class' => Ad::Class,
                'choice_label' => 'title'
            ])
        ;

        $builder->get('startDate')->addModelTransformer($this->transformer);
        $builder->get('endDate')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
