<?php
namespace Rss\RecommendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SimilarUrlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('similarUrl', 'url', 
            array(
                'label'  => '類似URL',
                'required' => false,
            )
        );
    }

    public function getName()
    {
        return 'similarUrl';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
                [
                    'validation_groups' => 'url',
                    'data_class' => 'Rss\RecommendBundle\Entity\SimilarUrl',
                    'csrf_protection' => true,
                    'csrf_field_name' => '_token',
                ]
        );
    }
    
}
