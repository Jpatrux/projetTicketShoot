<?php

namespace App\Form;

use App\Entity\Positions;
use App\Entity\Teams;
use App\Entity\Users;
use App\Repository\PositionRepository;
use App\Repository\TeamRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, ['label' => "Nom d'utilisateur"])
            ->add('name', TextType::class, ['label' => 'Nom'])
            ->add('firstName', TextType::class, ['label' => 'Prénom'])
            ->add('number', IntegerType::class, ['label' => 'Numéro de Maillot'])
            ->add('UserRoles', ChoiceType::class,
                ['choices' => ['Entraineur' => 'ROLE_COACH',
                    'Joueur' => 'ROLE_PLAYER'],
                    'mapped' => false,
                    'label' => "Type d'utilisateur"])
            ->add('agreeTerms', CheckboxType::class, [
                'label' => "Conditions d'utilisation",
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('team', EntityType::class, [
                'label' => 'Equipe',
                'class' => Teams::class,
                'query_builder' => function (TeamRepository $teamRepository) {
                    return $teamRepository->createQueryBuilder('team');
                },])
            ->add('position', EntityType::class, [
                'label' => 'Poste',
                'class' => Positions::class,
                'query_builder' => function (PositionRepository $positionRepository) {
                    return $positionRepository->createQueryBuilder('position');
                },])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
