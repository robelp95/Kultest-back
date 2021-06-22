<?php


namespace App\Form\Type;


use App\Form\Model\UserDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void{
        $builder
            ->add('address', TextType::class)
            ->add('brandName', TextType::class)
            ->add('base64Image', TextType::class)
            ->add('categoryId', IntegerType::class)
            ->add('deliveryCharge', TextType::class)
            ->add('description', TextType::class)
            ->add('email', TextType::class)
            ->add('minDelivery', TextType::class)
            ->add('name', TextType::class)
            ->add('open', IntegerType::class) //TODO handle as boolean
            ->add('opening', TextType::class)
            ->add('orderViaId', TextType::class)
            ->add('paymentInstructions', TextType::class)
            ->add('phoneNumber', IntegerType::class)
            ->add('username', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserDto::class,
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }

    public function getName(){
        return '';
    }
}