<?php

namespace App\Form;

use App\Entity\Doyenne;
use App\Entity\Section;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('code')
            ->add('doyenne', EntityType::class, [
                'class' => Doyenne::class,
                'choice_label' => 'nom',
                'attr' => ['class' => "form-select"]
            ])
            ->add('paroisse', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete' => "off",]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Section::class,
        ]);
    }
}
