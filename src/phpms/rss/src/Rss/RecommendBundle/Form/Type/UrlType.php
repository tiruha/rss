<?php
namespace Rss\RecommendBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UrlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('url', 'url', array('label'  => 'URL'));
        $builder->add('synonym', 'collection',
            array(
                'type'         => new SynonymType(),
                'allow_add'    => true,
                'by_reference' => false,
            )
        );
    }

    public function getName()
    {
        return 'url';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
                [
                    'validation_groups' => 'url',
                    'data_class' => 'Rss\RecommendBundle\Entity\Url',
                    'csrf_protection' => true,
                    'csrf_field_name' => '_token',
                ]
        );
    }
    
}
