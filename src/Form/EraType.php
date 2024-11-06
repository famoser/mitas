<?php

namespace App\Form;

use App\Entity\Era;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EraType extends AbstractType
{
    public const COPY_ERA_FIELD = 'copyEra';

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('name', TextType::class);
        $builder->add('deadlineAt', DateType::class, ['widget' => 'single_text', 'help' => 'help.deadline_at']);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            /** @var Era $era */
            $era = $event->getData();
            if ($era->getId()) {
                return;
            }

            $form = $event->getForm();
            $form->add(self::COPY_ERA_FIELD, EntityType::class, [
                // looks for choices from this entity
                'class' => Era::class,
                'choice_label' => 'name',
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    /* @var EntityRepository<Era> $er */
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.deadlineAt', 'DESC');
                },
                'mapped' => false,
                'required' => false,
            ]);
        });

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'entity_era',
            'data_class' => Era::class,
        ]);
        parent::configureOptions($resolver);
    }
}
