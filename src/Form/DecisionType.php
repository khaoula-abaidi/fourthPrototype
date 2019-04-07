<?php

namespace App\Form;

use App\Entity\Decision;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DecisionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        /*    ->add('isTaken', CheckboxType::class,['label' => 'Depot ou non ?'])
            ->add('content',\Symfony\Component\Form\Extension\Core\Type\TextType::class,
                ['label'=> 'Taper le contenu de la décision'
                ])
        */
            ->add('deposit',ChoiceType::class,['label'=> 'Voulez-vous déposer ?',
                'choices'=>[
                    'Je veux déposer'=> 'oui',
                    'Je ne veux pas' => 'non',
                    'Je ne sais pas encore' => 'wait'
                ]])
        //    ->add('document',DocumentType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Decision::class,
        ]);
    }
}
