<?php

namespace ML\PlatformBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use ML\PlatformBundle\Repository\CategoryRepository;


class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $pattern = 'D%';

        $builder
          ->add('date',       DateTimeType::class)
          ->add('title',      TextType::class)
          ->add('author',     TextType::class)
          ->add('content',    TextareaType::class)
          ->add('image',      ImageType::class) //va chercher le formulaire image dans Form/ImageType
          /*
       * Rappel :
       ** - 1er argument : nom du champ, ici « categories », car c'est le nom de l'attribut
       ** - 2e argument : type du champ, ici « CollectionType » qui est une liste de quelque chose
       ** - 3e argument : tableau d'options du champ
       */
          ->add('categories', EntityType::class, array(
            'class'  => 'MLPlatformBundle:Category', //permet de selectionner le formulaire CategoryType
            'choice_label'   => 'name', //autorise l'ajout d'une categorie
            'multiple'=> false, //autorise la suppression
            'mapped' => false,
            'query_builder' => function(CategoryRepository $repository) use($pattern) {
              return $repository->getLikeQueryBuilder($pattern);
              }
          ))
          ->add('save',       SubmitType::class);

        $builder->addEventListener(
          FormEvents::PRE_SET_DATA,
          function(FormEvent $event) {
            $advert = $event->getData();

            if (null === $advert) {
              return;
            }
          }
        );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'ML\PlatformBundle\Entity\Advert'
        ));
    }

}
