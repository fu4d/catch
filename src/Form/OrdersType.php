<?php

namespace App\Form;

use App\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrdersType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('order_id', IntegerType::class)
            ->add('order_datetime', DateTimeType::class)
            ->add('customer_id', IntegerType::class)
            ->add('customer_fname', TextType::class)
            ->add('customer_lname', TextType::class)
            ->add('customer_email', TextType::class)
            ->add('customer_phone', TextType::class)
            ->add('customer_street', TextareaType::class)
            ->add('customer_postcode', TextType::class)
            ->add('customer_suburb', TextType::class)
            ->add('customer_state', TextType::class)
            ->add('longitude', TextType::class)
            ->add('latitude', TextType::class)
            ->add('distinct_unit_count', IntegerType::class)
            ->add('total_units_count', IntegerType::class)
            ->add('subtotal', FloatType::class)
            ->add('discount', FloatType::class)
            ->add('total_order_value', FloatType::class)
            ->add('average_unit_price', FloatType::class)
            ->add('shipping_fee', FloatType::class)
            ->add('grand_total', FloatType::class)
            ->add('save', SubmitType::class,
                    ['label' => 'Save Order'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}
