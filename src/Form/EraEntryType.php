<?php

namespace App\Form;

use App\Entity\EraEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EraEntryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('fullName', TextType::class);
        $builder->add('email', EmailType::class);

        $builder->add('workMode', TextType::class, ['required' => false]);
        $builder->add('team', TextType::class, ['required' => false]);
        $builder->add('generalAgreement', TextareaType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'entity_era_entry',
            'data_class' => EraEntry::class,
        ]);
        parent::configureOptions($resolver);
    }
}
