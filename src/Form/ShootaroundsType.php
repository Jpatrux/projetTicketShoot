<?php

namespace App\Form;

use App\Entity\Shootarounds;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShootaroundsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('made', IntegerType::class, ['label' => 'Tirs réussis'])
            ->add('attempted', IntegerType::class, ['label' => 'Tirs tentés'])
            //->add('place', TextType::class, ['label' => 'Position sur le terrain'])
            //->add('percentage', IntegerType::class, ['label' => 'Pourcentage de réussite'])
            ->add('date', DateType::class, ['widget' => 'single_text'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shootarounds::class,
        ]);
    }
}
