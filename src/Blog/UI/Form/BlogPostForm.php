<?php

declare(strict_types=1);

namespace App\Blog\UI\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

final class BlogPostForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                    'required' => true,
                    'trim' => true,
                    'constraints' => [
                        new NotNull(),
                        new Length(['min' => 10, 'max' => 80]),
                    ],
                ]
            )
            ->add('content', TextareaType::class, [
                    'required' => true,
                    'attr' => ['rows' => 10],
                    'trim' => true,
                    'constraints' => [
                        new NotNull(),
                        new Length(['min' => 20]),
                    ],
                ]
            )
            ->add('image', FileType::class, [
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new NotNull(),
                    new Image(
                        [
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'image/jpeg',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid JPG image',
                        ]
                    ),
                ],
            ])
            ->add('add', SubmitType::class);

        $this->changeImageFieldIfUseInCli($builder);
        $this->transformFormValues($builder);
    }

    private function transformFormValues(FormBuilderInterface $builder): void
    {
        $builder
            ->get('title')->addModelTransformer(new CallbackTransformer(
                function ($title) {
                    return $title;
                },
                function ($title) {
                    if (!empty($title)) {
                        return strip_tags($title, ['ul', 'li', 'ol', 'p', 'strong']);
                    }

                    return $title;
                }
            ));

        $builder->get('content')->addModelTransformer(new CallbackTransformer(
            function ($content) {
                return $content;
            },
            function ($content) {
                if (!empty($content)) {
                    return strip_tags($content, ['ul', 'li', 'ol', 'p', 'strong']);
                }

                return $content;
            }
        ));
    }

    private function changeImageFieldIfUseInCli(FormBuilderInterface $builder): void
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (isset($data['imagePath']) && !isset($data['image'])) {
                $form->remove('image');
                $form->add('imagePath', TextType::class, [
                    'required' => true,
                    'constraints' => [
                        new NotNull(),
                        new Image(
                            [
                                'maxSize' => '1024k',
                                'mimeTypes' => [
                                    'image/jpeg',
                                ],
                                'mimeTypesMessage' => 'Please upload a valid JPG image',
                            ]
                        ),
                    ],
                ]);
            }
        });
    }
}
