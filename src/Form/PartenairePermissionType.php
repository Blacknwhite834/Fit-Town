<?php

namespace App\Form;

use App\Entity\PartenairePermission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartenairePermissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('is_members_read')
            ->add('is_members_write')
            ->add('is_members_add')
            ->add('is_members_products_add')
            ->add('is_members_payment_schedules_read')
            ->add('is_members_statistiques_read')
            ->add('is_members_subscription_read')
            ->add('is_payment_schedules_read')
            ->add('is_payment_schedules_write')
            ->add('is_payment_day_read')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PartenairePermission::class,
        ]);
    }
}
