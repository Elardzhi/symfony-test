<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('dateOfBirth', TextType::class)
            ->add('status', TextType::class, ['disabled' => !$options['is_edit']])
            ->add('products', CollectionType::class, [
                'entry_type'    => ProductType::class,
                'by_reference' => false,
                'allow_add' => true,
                'disabled' => $options['is_edit']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class'      => Customer::class,
            'is_edit'         => false
        ]);
    }
}
