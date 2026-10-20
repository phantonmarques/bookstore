<?php

namespace App\Form;

use App\Entity\Assunto;
use App\Entity\Autor;
use App\Entity\Livro;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class LivroType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titulo', TextType::class, [
                'label' => 'Título',
                'required' => true,
                'empty_data' => '',
                'constraints' => [
                    new Assert\NotBlank(
                        message: 'O título é obrigatório.',
                    ),
                    new Assert\Length(
                        max: 40,
                        maxMessage: 'O título deve ter no máximo {{ limit }} caracteres.',
                    ),
                ],
            ])
            ->add('editora', TextType::class, [
                'label' => 'Editora',
                'required' => true,
                'empty_data' => '',
                'constraints' => [
                    new Assert\NotBlank(
                        message: 'A editora é obrigatória.',
                    ),
                    new Assert\Length(
                        max: 40,
                        maxMessage: 'A editora deve ter no máximo {{ limit }} caracteres.',
                    ),
                ],
            ])
            ->add('edicao', IntegerType::class, [
                'label' => 'Edição',
                'required' => true,
                'invalid_message' => 'Informe um número inteiro válido para a edição.',
                'constraints' => [
                    new Assert\NotBlank(
                        message: 'A edição é obrigatória.',
                    ),
                    new Assert\Range(
                        notInRangeMessage: 'A edição deve estar entre {{ min }} e {{ max }}.',
                        min: 1,
                        max: 2147483647,
                    ),
                ],
            ])
            ->add('preco', MoneyType::class, [
                'label' => 'Preço',
                'currency' => false,
                'input' => 'string',
                'scale' => 2,
                'html5' => false,
                'grouping' => true,
                'required' => true,
                'invalid_message' => 'Informe um preço válido.',
                'constraints' => [
                    new Assert\NotBlank(
                        message: 'O preço é obrigatório.',
                    ),
                    new Assert\Range(
                        notInRangeMessage: 'O preço deve estar entre R$ 0,01 e R$ 99.999.999,99.',
                        min: '0.01',
                        max: '99999999.99',
                    ),
                ],
            ])
            ->add('anoPublicacao', TextType::class, [
                'label' => 'Ano de publicação',
                'required' => true,
                'empty_data' => '',
                'attr' => [
                    'maxlength' => 4,
                    'inputmode' => 'numeric',
                ],
                'constraints' => [
                    new Assert\NotBlank(
                        message: 'O ano de publicação é obrigatório.',
                    ),
                    new Assert\Range(
                        notInRangeMessage: 'O ano de publicação deve estar entre {{ min }} e {{ max }}.',
                        min: 1900,
                        max: (int) date('Y'),
                    ),
                ],
            ])
            ->add('assuntos', EntityType::class, [
                'label' => 'Assuntos',
                'class' => Assunto::class,
                'choice_label' => static fn (Assunto $assunto): string => sprintf('%s (#%d)', $assunto->getDescricao(), $assunto->getId()),
                'multiple' => true,
                'by_reference' => false,
                'required' => true,
                'invalid_message' => 'Selecione assuntos válidos.',
                'constraints' => [
                    new Assert\Count(
                        min: 1,
                        minMessage: 'Selecione pelo menos um assunto.',
                    ),
                ],
            ])
            ->add('autores', EntityType::class, [
                'label' => 'Autores',
                'class' => Autor::class,
                'choice_label' => static fn (Autor $autor): string => sprintf('%s (#%d)', $autor->getNome(), $autor->getId()),
                'multiple' => true,
                'by_reference' => false,
                'required' => true,
                'invalid_message' => 'Selecione autores válidos.',
                'constraints' => [
                    new Assert\Count(
                        min: 1,
                        minMessage: 'Selecione pelo menos um autor.',
                    ),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livro::class,
            'extra_fields_message' => 'O formulário contém campos não permitidos.',
        ]);
    }
}
