<?php

namespace App\Form;

use App\Entity\EraEntry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReplyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('vacationsInEra', CheckboxType::class, ['required' => false, 'help' => 'help.vacations_in_era']);
        $builder->add('timeOffRequests', TextareaType::class, ['required' => false, 'help' => 'help.time_off_requests']);
        $builder->add('absences', TextareaType::class, ['required' => false, 'help' => 'help.absences']);
        $builder->add('comments', TextareaType::class, ['required' => false, 'help' => 'help.comments']);
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
