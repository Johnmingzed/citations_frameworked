<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Citation;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CitationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('auteur_id', EntityType::class, [
                'attr' => [
                    'class' => 'form-select'
                ],
                'class' => Auteur::class,
                'choice_label' => 'auteur',
                'label' => 'Auteur',
                'placeholder' => 'Anonyme',
                'empty_data' => null,
                'required' => false,
                'choices' => $options['auteur']

            ])
            ->add('citation', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '8'
                ],
                'label' => 'Biographie',
                'sanitize_html' => true,
                'required' => true
            ])
            ->add('explication', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => '8'
                ],
                'label' => 'Explication (facultatif)',
                'sanitize_html' => true,
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Citation::class,
            'auteur' => null
        ]);
    }
}
