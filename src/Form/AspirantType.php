<?php

namespace App\Form;

use App\Entity\Aspirant;
use App\Entity\Grade;
use App\Entity\Section;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AspirantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,['attr'=>['class' => 'form-control']])
            ->add('prenoms', TextType::class,['attr'=>['class' => 'form-control']])
            ->add('matricule', TextType::class,['attr'=>['class' => 'form-control', 'readonly' => true], 'required' => false])
            ->add('sexe', TextType::class,['attr'=>['class' => 'form-control', 'readonly' => true], 'required' => false])
            ->add('contact', TextType::class,['attr'=>['class' => 'form-control'], 'required' => false])
            ->add('urgence', TextType::class,['attr'=>['class' => 'form-control', 'readonly' => true], 'required' => false])
            ->add('contactUrgence', TextType::class,['attr'=>['class' => 'form-control'], 'required' => false])
            ->add('montant', TextType::class,['attr'=>['class' => 'form-control', 'readonly' => true], 'required' => false])
//            ->add('teeshirt')
            ->add('taille', TextType::class,['attr'=>['class' => 'form-control', 'readonly' => true, 'required'=>false]])
//            ->add('montantTeeshirt')
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('updatedAt', null, [
//                'widget' => 'single_text',
//            ])
            ->add('grade', EntityType::class, [
                'class' => Grade::class,
                'choice_label' => 'nom',
                'attr' => ["class" => 'form-select select2']
            ])
            ->add('section', EntityType::class, [
                'class' => Section::class,
                'choice_label' => 'paroisse',
                'attr' => ['class' => 'form-select select2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Aspirant::class,
        ]);
    }
}
