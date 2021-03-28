<?php

namespace App\Form;

use App\Entity\Themes;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class AddThemeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        // ->add("name", EntityType::Class,[
        //     "label" => "Theme : ",
        //     "class" => Themes::Class,
        // //     "choice_label" => "name",
        // //     "expanded" => false,
        //     "multiple" => true,
        // //     "required" => true,
        // ]);
            ->add('name')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Themes::class,
        ]);
    }
}
