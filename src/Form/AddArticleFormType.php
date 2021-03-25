<?php

namespace App\Form;

use App\Entity\Articles;
use App\Entity\Themes;
use App\Entity\ArticlesThemes;
use App\Entity\KeyWord;
use App\Entity\Pictures;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;



class AddArticleFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('description')
            // ->add('dateCreate')
            // ->add('dateUpdate')
            // ->add("pictures")
        //     ->add("name", EntityType::Class,[
        //     "label" => "Theme : ",
        //     "class" => Themes::Class,
        //     "choice_label" => "name",
        //     "expanded" => false,
        //     "multiple" => false,
        //     "required" => true,
        // ]);
            // ->add('keyWords', EntityType::Class,[
            //     "label" => "KeyWords : ",
            //     "class" => KeyWord::class,
            //     "choice_label" => "name",
            //     "expanded" => true,
            //     "multiple" => true,
            //     "required" => false,
            // ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Articles::class,
        ]);
    }
}
