<?php

namespace App\Form;

use App\Entity\Ingredient;
use App\Entity\Recette;
use App\Repository\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class RecetteType extends AbstractType
{

    private $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'minlength' => '2',
                    'maxlength' => '50'
                ],
                'label' => 'Nom de la recette',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Length(['min' => 2, 'max' => 50]),
                    new Assert\NotBlank()
                ]
            ])
            ->add('time', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'min' => 1,
                    'max' => 1440,
                ],
                'required' => false,
                'label' => 'Temps estimé (en minutes) pour réalisé',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(1441)
                ]
            ])
            ->add('numberPersonne', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'choices'  => $this->chooseLoop(1,50),
                'label' => 'Pour combien de personnes',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(51)
                ]
            ])
            ->add('difficulty', RangeType::class, [
                'attr' => [
                    'class' => 'form-range',
                    'min' => 1,
                    'max' => 5
                ],
                'label' => 'Difficulté de la recette',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\Positive(),
                    new Assert\LessThan(6)
                ]
            ])
            ->add('process', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Etapes de la recette',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('price', TextType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Prix de la recette',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            ->add('favorites', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check'
                ],
                'required' => false,
                'label' => 'Ajouter en favoris',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotNull(),
                ]
            ])
            ->add('isPublic', CheckboxType::class, [
                'attr' => [
                    'class' => 'form-check'
                ],
                'required' => false,
                'label' => 'Rendre la recette publique',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotNull(),
                ]
            ])
            // ->add('createdAt')
            // ->add('UpdatedAt')
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'query_builder' => function(IngredientRepository $r) {
                    return $r->createQueryBuilder('i')
                        ->where('i.user = :user')
                        ->setParameter('user', $this->token->getToken()->getUser())
                        ->orderBy('i.name', 'ASC');
                },
                'choice_label' => 'name',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Les ingrédients',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
            ])
            // ->add('ingredientList', TextareaType::class, [
            //     'attr' => [
            //         'class' => 'form-control'
            //     ],
            //     'label' => 'Liste des ingrédients',
            //     'label_attr' => [
            //         'class' => 'form-label mt-4'
            //     ],
            // ])

            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ],
                'label' => 'Créer ma recette'

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recette::class,
        ]);
    }


    /**
     * function to create choice type input
     *
     * @param integer $from
     * @param integer $to
     * @return array
     */
    public function chooseLoop(int $from, int $to): array
    {
        $arr = [];

        for ($i=$from; $i <= $to; $i++) {
            $arr[$i] = $i;
        }

        return $arr;
    }
}
