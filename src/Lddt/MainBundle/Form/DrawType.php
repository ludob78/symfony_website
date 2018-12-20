<?php

namespace Lddt\MainBundle\Form;

use function Sodium\add;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DrawType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

            $builder->add('title',TextType::class,array('label'=>'Nom du dessin','attr'=>array('class'=>'form-control')));
            //Si l'instance ne renvoit pas un ID, il s'agit d'une création donc on affiche les champs ci-dessous
            if ($builder->getData()->getId()==false){
            $builder
                    //Formulaire imbriqué pour charger la class PicType > 1 form pour créer un dessin et 1 form imbriqué pour créer une image
                    ->add('pic',PicType::class) ;
            }
//                    ->add('drawPath',TextType::class,array('label'=>'Chemin du dessin','attr'=>array('class'=>'form-control')))
        /*$builder ->add('avatarPath',TextType::class,array('label'=>'Chemin de votre Avatar','attr'=>array('class'=>'form-control')))
                    ->add('authorName',TextType::class,array('label'=>'Votre nom','attr'=>array('class'=>'form-control')));*/

            $builder->add('category',EntityType::class,array('class'=>'Lddt\MainBundle\Entity\Category','choice_label'=>'name','label'=>'Catégorie','attr'=>array('class'=>'form-control')))
                ->add('color',EntityType::class,array('class'=>'Lddt\MainBundle\Entity\Color','label'=>'Couleur','multiple'=>true,'expanded'=>true))
//                ->add('tag',EntityType::class,array('class'=>'Lddt\MainBundle\Entity\Tag','choice_label'=>'name','label'=>'Tag','multiple'=>true,'expanded'=>true))
                ->add("tag",CollectionType::class,array('entry_type'=>TagType::class,'allow_add'=>true,'allow_delete'=>true))
                ->add('valider',SubmitType::class,array('attr'=>array('class'=>'btn btn-primary pull-right','style'=>'margin-top:15px;')));
        //            ->add('isOnline') //Dans le constructeur de la classe Draw
        //            ->add('createdAt')//Dans le constructeur de la classe Draw
        //            ->add('updatedAt')//Dans le constructeur de la classe Draw
        //                L'attribut choice_label permet de ne pas avoir la méthode __ToString dans l'entité DrawType
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lddt\MainBundle\Entity\Draw'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lddt_mainbundle_draw';
    }



}
