<?php


namespace App\Form\Type;


use App\Form\Model\MenuDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class MenuFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class)
            ->add('products', CollectionType::class, [
                'allow_add' => true,
                'allow_delete' => true,
                'entry_type' => ProductFormType::class
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MenuDto::class,
        ]);
    }

    public function getBlockPrefix(){
        return "";
    }
    public function getName(){
        return "";
    }



}