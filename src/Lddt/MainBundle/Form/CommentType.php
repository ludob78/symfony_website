<?php

namespace Lddt\MainBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->add('pseudo',TextType::class,array('label'=>'Votre pseudo','attr'=>array('class'=>'form-control')))
            ->add('content',TextareaType::class,array('label'=>'Votre commentaire','attr'=>array('class'=>'form-control')))
//            ->add('createdAt')
//            ->add('updatedAt');
//            ->add('draw')
            ->add('valider',SubmitType::class,array(
        'attr'=>array('class'=>'btn btn-primary pull-right','style'=>'margin-top:15px;')));

    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Lddt\MainBundle\Entity\Comment'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'lddt_mainbundle_comment';
    }


}
