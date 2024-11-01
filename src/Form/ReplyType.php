<?php

namespace App\Form;

use App\Entity\EraEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReplyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('timeOffRequests', TextareaType::class, ['required' => false]);
        $builder->add('absences', TextareaType::class, ['required' => false]);
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
