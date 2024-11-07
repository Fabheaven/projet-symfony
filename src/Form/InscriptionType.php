<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => ['placeholder' => 'Entrez votre e-mail'],
                'constraints' => [
                    new NotBlank(['message' => "L'email est obligatoire."]),
                    new Email(['message' => 'Veuillez entrer une adresse e-mail valide.']),
                ],
            ])
            ->add('pseudo', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['placeholder' => 'Entrez votre pseudo'],
                'constraints' => [
                    new NotBlank(['message' => 'Le pseudo est obligatoire.']),
                    new Length([
                        'min' => 3,
                        'max' => 50,
                        'minMessage' => 'Le pseudo doit contenir au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le pseudo ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe',
                'attr' => ['placeholder' => 'Entrez votre mot de passe', 'class' => 'password-field'],
                'constraints' => [
                    new NotBlank(['message' => 'Le mot de passe est obligatoire.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Le mot de passe doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirmer le mot de passe',
                'attr' => ['placeholder' => 'Confirmez votre mot de passe'],
                'mapped' => false,  // Ne pas mapper à l'entité User
                'constraints' => [
                    new NotBlank(['message' => 'La confirmation du mot de passe est obligatoire.']),
                ],
            ])
            
            ->add('submit', SubmitType::class, [
                'label' => 'Créer un compte',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
