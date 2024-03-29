<?php

namespace App\Form;

use App\Entity\Aspirant;
use App\Entity\Grade;
use App\Entity\Section;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AspirantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('matricule')
            ->add('nom')
            ->add('prenoms')
            ->add('sexe')
            ->add('contact')
            ->add('urgence')
            ->add('contactUrgence')
            ->add('montant')
            ->add('teeshirt')
            ->add('taille')
            ->add('montantTeeshirt')
            ->add('createdAt', null, [
                'widget' => 'single_text',
            ])
            ->add('updatedAt', null, [
                'widget' => 'single_text',
            ])
            ->add('grade', EntityType::class, [
                'class' => Grade::class,
                'choice_label' => 'id',
            ])
            ->add('section', EntityType::class, [
                'class' => Section::class,
                'choice_label' => 'id',
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
