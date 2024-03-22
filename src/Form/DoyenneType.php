<?php

namespace App\Form;

use App\Entity\Doyenne;
use App\Entity\Vicariat;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DoyenneType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('code')
            ->add('vicariat', EntityType::class, [
                'class' => Vicariat::class,
                'choice_label' => 'nom',
                'attr' => ['class' => 'form-select']
            ])
            ->add('nom', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete' => "off"]
            ])
//            ->add('slug')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Doyenne::class,
        ]);
    }
}
