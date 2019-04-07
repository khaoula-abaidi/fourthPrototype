<?php

namespace App\Form;

use App\Entity\Decision;
use App\Entity\Document;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('doi')
         /*   ->add('decision',EntityType::class,[
                'class' => Decision::class,
                'placeholder' => 'Sélectionner la décision',
                'mapped' => false,
                'required'=>false
            ])
         */
         ->add('decision', EntityType::class,
             [
                 'class' => Decision::class,
                 'choice_label' => 'content',
                 'expanded' => false,//Affichage dans une liste déroulante si true sous forme de bouton radio
                 'multiple' => false,
               //  'data' =>$options['default']
             ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
       // $resolver->setRequired(['default']);
    }
}
